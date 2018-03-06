<?php namespace NGAFID\Http\Controllers;

use NGAFID\Dropbox;
use NGAFID\Http\Requests;
use NGAFID\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

use Mail;
use Storage;

class DropboxAuthController extends Controller {

    private $api_client;

    public function __construct(Dropbox $dropbox) {
        $this->middleware('auth');

        $this->api_client = $dropbox->api();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $client_id = env('DROPBOX_APP_KEY');

        $url = "https://www.dropbox.com/1/oauth2/authorize?response_type=code&client_id={$client_id}";

        return view('dropbox_auth/index', [
            'dbx_url' => $url,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Authenticatable $user) {
        $auth_code = $request->get('auth_code');
        $user_id = $user->id;
        $org_id = $user->org_id;
        $org_name = $user->fleet->name;

        // url query param structure:
        // https://www.dropbox.com/developers/documentation/http/documentation
        $data = [
            'code' => $auth_code,
            'client_id' => env('DROPBOX_APP_KEY'),
            'client_secret' => env('DROPBOX_APP_SECRET'),
            'grant_type' => 'authorization_code',
        ];

        // use Guzzle client for synchronous request,
        // checkout below for basic usage:
        // docs.guzzlephp.org/en/5.3/quickstart.html#post-requests
        $response = $this->api_client->post(
            '/1/oauth2/token',  // url relative to the base_uri defined in Dropbox class
            ['body' => $data]   // data to pass through POST request
        );

        $response_body = $response->json();
        $access_token = $response_body['access_token'];

        $filename = "user{$user_id}_org{$org_id}_{$org_name}.txt";

        Storage::put($filename, $access_token);

        flash()->success('Dropbox authentication was successful!');

	$emailData = [
		'fullname' => "{$user->firstname} {$user->lastname}",
		'user_email' => $user->email,
		'user_id' => $user_id,
		'org_id' => $org_id,
		'org_name' => $org_name,
		'filename' => $filename,
	];

	Mail::send('emails.dbx_auth_success', $emailData, function ($message) {
		$message->to('kelton.karboviak@und.edu', 'Kelton Karboviak')->subject('New Successful Dropbox Authorization!');
	});

        return redirect()->back();
    }

}
