<?php
namespace NGAFID\Http\Middleware;

use Auth;
use Closure;
use DB;
use NGAFID\CryptoSystem;
use URL;

class ToggleEncryption
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isFleetAdministrator()) {
            $enrolledInEncryption = Auth::user()->fleet->wantsDataEncrypted();
            $secretKey = $request->input('secretKey');
            $session = $request->session();

            $toggle = $request->input('toggle') === 'F'
                      || $session->get('toggleEnc') === 'F'
                ? 'F'
                : 'T';

            if ($request->input('toggle') === 'T') {
                $session->forget('encrSK');
                $session->forget('toggleEnc');
                $session->forget('toggle');
                $session->save();

                flash()->success(
                    '<strong>Encryption On!</strong> You are now viewing your encrypted flight data.'
                );

                return redirect(URL::previous());
            }

            if (strpos($request->path(), 'decrypt') !== false
                && $enrolledInEncryption) {
                if ( !str_contains(URL::previous(), 'decrypt')) {
                    $session->put('prevURL', $session->previousUrl());
                }

                $session->keep(['flash_notification.level', 'flash_notification.message']);

                if ($toggle === 'F' && count($secretKey) > 0) {
                    $salt = getenv('STATIC_SALT');
                    $hashedKey = md5($salt . $secretKey);
                    $userKey = CryptoSystem::where(
                        'fleet_id',
                        '=',
                        Auth::user()->org_id
                    )
                        ->pluck(
                            DB::raw("AES_DECRYPT(user_key, '{$hashedKey}')")
                        );

                    if (strpos($userKey, '-----BEGIN PRIVATE KEY-----')
                        !== false) {
                        $session->put(
                            'encrSK',
                            gzcompress(base64_encode($userKey), 9)
                        );
                        $session->put('toggleEnc', 'F');
                        $session->save();

                        flash()->success(
                            '<strong>Encryption Off! </strong> You are now viewing your decrypted flight data.'
                        );

                        return redirect($session->get('prevURL'));
                    } else {
                        flash()->error('<strong>Whoops!</strong> Incorrect secret key.');

                        return redirect()->back();
                    }
                } else {
                    $session->put('toggleEnc', 'T');
                }

                $session->save();
            } elseif ($request->is('flights/*/edit')) {
                // Check if data is decrypted before editing
                if ($enrolledInEncryption && $toggle !== 'F'
                    && count($secretKey) <= 0) {
                    $session->setPreviousUrl($request->url());

                    flash()->warning(
                        'Please disable the encryption before editing your flight data.'
                    );

                    return redirect('decrypt?toggle=F');
                }
            }
        }

        return $next($request);
    }
}
