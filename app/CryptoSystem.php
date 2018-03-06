<?php namespace NGAFID;

use Eloquent;

/**
 * NGAFID\CryptoSystem
 *
 * @property int                 $fleet_id
 * @property int|null            $user_id
 * @property mixed|null          $user_key
 * @property mixed|null          $ngafid_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\CryptoSystem
 *         whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\CryptoSystem
 *         whereFleetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\CryptoSystem
 *         whereNgafidKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\CryptoSystem
 *         whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\CryptoSystem
 *         whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\CryptoSystem
 *         whereUserKey($value)
 * @mixin \Eloquent
 */
class CryptoSystem extends Eloquent
{
    // I did not define any relationships in this model because they may change in the future
    // when integrating fleet gaard and other enhancements.

    protected $table = 'fdmdm.asymmetric_key_log';

    protected $fillable = ['fleet_id', 'user_id', 'user_key', 'ngafid_key'];
}
