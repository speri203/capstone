<?php namespace NGAFID\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NGAFID\Http\Requests\Request;


class ChangePasswordRequest extends Request {

    /**
     * Determine if the user is authorized to perform this request.
     */
	public function authorize()
    {
        //verify if the user is logged in
        if ( ! \Auth::check() )
        {
            return false;
        }
        return true;
    }

    public function rules()
    {
        return [
            'password'                  => 'required|min:6|max:12|confirmed',
            'password_confirmation'     => 'required|min:6|max:12',
        ];
    }

}
