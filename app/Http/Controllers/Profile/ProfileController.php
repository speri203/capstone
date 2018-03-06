<?php
namespace NGAFID\Http\Controllers\Profile;

use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use NGAFID\Commands\EncryptFlightDataCommand;
use NGAFID\CryptoSystem;
use NGAFID\Fleet;
use NGAFID\Http\Controllers\Controller;
use NGAFID\Http\Requests\ChangePasswordRequest;
use NGAFID\Http\Requests\CryptosystemRequest;
use NGAFID\Http\Requests\ProfileRequest;
use NGAFID\User;
use Queue;
use Request;
use Response;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $fleetInfo = Auth::user()->fleet;

        $userData = [
            'firstname'  => Auth::user()->firstname,
            'lastname'   => Auth::user()->lastname,
            'type'       => Auth::user()->user_type === 'G'
                ? 'GAARD'
                : 'NGAFID',
            'username'   => Auth::user()->email,
            'fleetInfo'  => $fleetInfo,
        ];

        return view('profile.profile')->with('data', $userData);
    }

    public function show()
    {
        return redirect('profile');
    }

    public function edit($fleetID)
    {
    }

    public function update($id, ProfileRequest $profileRequest)
    {
        $formfields = $profileRequest->all();

        $userData = [
            'firstname' => $formfields['firstname'],
            'lastname'  => $formfields['lastname'],
        ];

        $fleetData = [
            'address'  => $formfields['address'],
            'city'     => $formfields['city'],
            'country'  => $formfields['country'],
            'state'    => $formfields['state'],
            'zip_code' => $formfields['zip_code'],
            'phone'    => $formfields['phone'],
            'fax'      => $formfields['fax'],
        ];

        Fleet::find($id)
            ->update($fleetData);

        if (Auth::user()
            ->update($userData)) {
            flash()->success('Your profile has been successfully updated!');
        }

        return redirect('profile');
    }

    public function password()
    {
        return view('profile.password');
    }

    public function changePassword(ChangePasswordRequest $passwordRequest)
    {
        $password = $passwordRequest->all();
        $password = ['password' => Hash::make($password['password'])];

        if (Auth::user()
            ->update($password)) {
            flash()->success('Your password has been successfully changed!');
        }

        return redirect('profile');
    }

    public function confirm($token)
    {
        $user = User::where('confirmation_token', $token)
            ->first();
        if ($user) {
            $user->confirmation_token = '';
            $user->confirmed = 'yes';
            $user->active = 'Y';
            $user->save();

            flash()->success(
                'Your account has been successfully activated! Please change your password.'
            );
            Auth::loginUsingId($user->id);

            return redirect('profile/password');
        }

        flash()->error('There was a problem activating your account.');

        return redirect('auth/register');
    }

    public function initCryptoSystem()
    {
        if (Auth::check() && Auth::user()->isFleetAdministrator()) {
            return view('profile.cryptosystem');
        }

        return redirect('home');
    }

    public function generateKeys(CryptoSystemRequest $cryptoRequest)
    {
        $fleetID = Auth::user()->org_id;
        $request = $cryptoRequest->all();
        $plaintextKey = $request['secretKey'];
        $salt = getenv('STATIC_SALT');
        $hashedKey = md5($salt . $plaintextKey);

        // Check if record exists for fleet in the key table
        $keyExists = CryptoSystem::where('fleet_id', '=', $fleetID)
            ->pluck(DB::raw('COUNT(*)'));

        if ($keyExists === 0) {
            // Generate the keys and insert into the key table

            // Configuration settings for the key
            $config = [
                'digest_alg'       => 'sha512',
                'private_key_bits' => '4096',
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ];

            //Create the private and public key
            $keyPairs = openssl_pkey_new($config);

            // Extract the private key
            openssl_pkey_export($keyPairs, $privateKey);

            // Extract the public key into $publicKey
            $publicKey = openssl_pkey_get_details($keyPairs)['key'];

            $data = [
                'fleet_id'   => $fleetID,
                'user_id'    => Auth::user()->id,
                'user_key'   => DB::raw(
                    "AES_ENCRYPT('{$privateKey}', '{$hashedKey}')"
                ),
                'ngafid_key' => DB::raw("ENCODE('{$publicKey}', '{$salt}')"),
                'created_at' => Carbon::now()
                    ->toDateTimeString(),
            ];

            if (DB::table('fdmdm.asymmetric_key_log')
                ->insert($data)) {
                // Set encrypted to true
                $fleetInfo = Fleet::find($fleetID);
                $fleetInfo->encrypt_data = DB::raw("'Y'");
                $fleetInfo->save();

                flash()->success('Encryption is now enabled.');

                // Retroactively encrypt the user's data with a queued job
                Queue::pushOn(
                    'encryptionQueue',
                    new EncryptFlightDataCommand($fleetID)
                );

                return redirect('profile');
            }
        }

        flash()->error('You have previously created a key for this account.');

        return view('errors.403');
    }

    public function decrypt(Request $request)
    {
        $enrolledInEncryption = Auth::user()->fleet->wantsDataEncrypted();

        if ($enrolledInEncryption) {
            return view(
                'profile.decrypt',
                ['toggle' => Request::input('toggle')]
            );
        }

        return view('errors.403');
    }
}
