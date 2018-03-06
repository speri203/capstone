<?php
namespace NGAFID;

use Eloquent;

/**
 * NGAFID\Fleet
 *
 * @property int
 *           $id
 * @property string
 *           $name
 * @property string
 *           $address
 * @property string
 *           $city
 * @property string|null
 *           $state
 * @property string|null
 *           $zip_code
 * @property string
 *           $country
 * @property string|null
 *           $phone
 * @property string|null
 *           $fax
 * @property int|null
 *           $administrator
 * @property string|null
 *           $encrypt_data
 * @property \Carbon\Carbon|null
 *           $created_at
 * @property \Carbon\Carbon|null
 *           $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\NGAFID\FlightID[]
 *                $flights
 * @property-read \Illuminate\Database\Eloquent\Collection|\NGAFID\User[]
 *                $users
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         duration($fleetID)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereEncryptData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Fleet
 *         whereZipCode($value)
 * @mixin \Eloquent
 */
class Fleet extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'organization';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'phone',
        'fax',
        'encrypt_data',
    ];

    /*************************************************************************
     * Model Scopes
     */

    public function scopeDuration($query, $fleetID)
    {
        return $query->where('fleet_id', '=', $fleetID);
    }

    /*************************************************************************
     * Model Eloquent Relationships
     */

    public function users()
    {
        return $this->hasMany('NGAFID\User', 'org_id', 'id');
    }

    public function flights()
    {
        return $this->hasMany('NGAFID\FlightID');
    }

    public function crypto_key()
    {
        return $this->hasOne('NGAFID\CryptoSystem', 'fleet_id', 'id');
    }

    /*************************************************************************
     * Public Methods
     */

    public function wantsDataEncrypted()
    {
        return $this->encrypt_data === 'Y';
    }

    public function isUND()
    {
        return in_array($this->id, [1, 3]);
    }
}
