<?php
namespace NGAFID;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * NGAFID\User
 *
 * @property int                $id
 * @property string|null        $username
 * @property string             $password
 * @property string             $password_salt
 * @property string             $firstname
 * @property string             $lastname
 * @property int                $org_id
 * @property string|null        $user_type
 * @property bool               $access_level
 * @property string             $email
 * @property \Carbon\Carbon     $updated_at
 * @property \Carbon\Carbon     $created_at
 * @property string|null        $last_login
 * @property string             $confirmed
 * @property string|null        $active
 * @property string|null        $confirmation_token
 * @property string|null        $remember_token
 * @property-read \NGAFID\Fleet $fleet
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereConfirmationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereOrgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         wherePasswordSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\User
 *         whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Eloquent
    implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'password',
        'org_id',
        'active',
        'confirmed',
        'access_level',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'password_salt'];

    /*************************************************************************
     * Model Eloquent Relationships
     */

    public function fleet()
    {
        return $this->belongsTo('NGAFID\Fleet', 'org_id', 'id');
    }

    /*************************************************************************
     * Public Methods
     */

    public function isFleetAdministrator()
    {
        return $this->id === $this->fleet->administrator;
    }
}
