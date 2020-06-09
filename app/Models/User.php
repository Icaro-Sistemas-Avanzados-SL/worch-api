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

    protected $with = ['followeds'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'bio',
        'phone',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function challenges()
    {
        return $this->hasMany(\App\Models\Challenge::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function favourites()
    {
        return $this->hasMany(\App\Models\Favourite::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function followers()
    {
        return $this->hasMany(\App\Models\Follower::class, 'followed_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function followeds()
    {
        return $this->hasMany(\App\Models\Follower::class, 'follower_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class, 'user_id');
    }
}
