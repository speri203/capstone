<?php namespace NGAFID\Services;

use NGAFID\User;
use NGAFID\Fleet;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			//'username' => 'required|max:75|unique:user',
            'fleet'     => 'required|min:3|max:35|unique:organization,name',
			'email'     => 'required|email|max:50|unique:user,email',
            'firstname' => 'required|min:3|max:75',
            'lastname'  => 'required|min:3|max:75',
            'password'  => 'required|confirmed|min:6|max:12',
            'address'   => 'max:100',
            'city'      => 'max:45',
            'country'   => 'max:45',
            'state'     => 'max:45',
            'zip_code'  => 'regex:/^\d{5}(?:[-\s]\d{4})?$/',//'numeric|digits:5',
            'phone'     => 'regex:/^([0-9\s\-\+\(\)]*)$/',  //'numeric|digits_between:10,12',
            'fax'       => 'regex:/^([0-9\s\-\+\(\)]*)$/',  //'numeric|digits_between:10,12',
            'consent'   => 'required',
            'hp_filter' => 'honeypot',
            'hp_time'   => 'required|honeytime:5'
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
        //create fleet info then associate the fleet ID with the user

        $fleet = Fleet::create([
            'name'      => $data['fleet'],
            'address'   => $data['address'],
            'city'      => $data['city'],
            'country'   => $data['country'],
            'state'     => $data['state'],
            'zip_code'  => $data['zip_code'],
            'phone'     => $data['phone'],
            'fax'       => $data['fax']
        ]);

		return User::create([
			'username'      => $data['email'],
            'firstname'     => $data['firstname'],
            'lastname'      => $data['lastname'],
			'email'         => $data['email'],
            'user_type'     => 'N',
			'password'      => bcrypt($data['password']),
            'org_id'        => $fleet->id,
            'access_level'  => 1,
            'active'        => 'Y',
		]);
	}

}
