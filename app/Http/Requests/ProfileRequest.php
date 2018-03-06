<?php namespace NGAFID\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NGAFID\Http\Requests\Request;
use NGAFID\User;


class ProfileRequest extends Request {

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
        //this method should also verify if the user belongs to the fleet (which may be redundant until user/roles are established)

        return true; //temporarily set to true if logged in
    }

    public function rules()
    {
        return [
            'firstname'                 => 'required|min:3|max:75',
            'lastname'                  => 'required|min:3|max:75',
            'address'                   => 'max:100',
            'city'                      => 'max:45',
            'country'                   => 'max:45',
            'state'                     => 'max:45',
            'zip_code'                  => 'regex:/^\d{5}(?:[-\s]\d{4})?$/',//'numeric|digits:5',
            'phone'                     => 'regex:/^([0-9\s\-\+\(\)]*)$/',//'numeric|digits_between:10,12',
            'fax'                       => 'regex:/^([0-9\s\-\+\(\)]*)$/',//'numeric|digits_between:10,12',
        ];
    }

    public function messages()
    {
        return[
            //'phone'  => 'Invalid phone number',
            'fax'    => 'Invalid fax number'
        ];
    }

}
