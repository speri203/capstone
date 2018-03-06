<?php namespace NGAFID\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NGAFID\Http\Requests\Request;
use NGAFID\User;


class UploadRequest extends Request {

    /**
     * Determine if the user is authorized to perform this request.
     */
	public function authorize()
    {
        //verify if the user belongs to the fleet
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
            'aircraft'      => 'required',
            'n_number'      => 'required|min:1|max:30',
            'flight_data'   => 'required|mimes:csv,txt',
        ];
    }

    public function messages()
    {
        return[
            'flight_data.required'  => 'The file upload is required.',
            'flight_data.mimes'     => 'The file upload should be a csv type.'
        ];
    }

}
