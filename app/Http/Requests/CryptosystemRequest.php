<?php
namespace NGAFID\Http\Requests;

use Auth;

class CryptosystemRequest extends Request
{
    /**
     * Determine if the user is authorized to perform this request.
     */
    public function authorize()
    {
        // Verify if the user is logged in
        if ( !Auth::check()) {
            return view('auth.login');
        }

        $fleet = Auth::user()->fleet;
        $isFleetAdmin = Auth::user()
            ->isFleetAdministrator();
        $shouldEncrypt = Auth::user()->fleet->wantsDataEncrypted();

        if ( !$isFleetAdmin) {
            // Only admins can create encryption keys
            return view('errors.403');
        } elseif ($shouldEncrypt) {
            // The admin has already created the encryption keys, so do not grant access to this page again
            return view('errors.403');
        }

        return true;
    }

    public function rules()
    {
        return [
            'secretKey' => [
                'required',
                'min:10',
                'max:32',
                'confirmed',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
