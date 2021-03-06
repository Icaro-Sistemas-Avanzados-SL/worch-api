<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email_verified_at",
 *          description="email_verified_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $table = 'users';

    protected $with = ['followeds', 'followers', 'notificationsReceived'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $hidden = ['password'];

    public $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'bio',
        'phone',
        'height',
        'weight',
        'birthdate',
        'gender',
        'instagram',
        'facebook',
        'avatar'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'bio' => 'string',
        'phone' => 'string',
        'weight' => 'integer',
        'height' => 'integer',
        'birthdate' => 'date',
        'gender' => 'string',
        'instagram' => 'string',
        'facebook' => 'string',
        'avatar' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $updateRules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function challenges()
    {
        return $this->hasMany(\App\Models\Challenge::class, 'user_id')->without('user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function favourites()
    {
        return $this->hasMany(\App\Models\Favourite::class, 'user_id')->without('user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function notificationsReceived()
    {
        return $this->hasMany(\App\Models\Notification::class, 'notificated_id')->without('notificated', 'notificationUser');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function followers()
    {
        return $this->hasMany(\App\Models\Follower::class, 'followed_id')->without('followed', 'follower');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function followeds()
    {
        return $this->hasMany(\App\Models\Follower::class, 'follower_id')->without('follower', 'followed');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class, 'user_id')->without('user');
    }


    public $withCount = ['challenges', 'followeds', 'followers'];

    public function getAvgRatingAttribute ()
    {
        $ratings = Rating::whereHas('challenge', function ($q) {
            $q->where('user_id', $this->id);
        });

        return $ratings->avg('rate');

    }

}
