<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use function foo\func;

/**
 * @SWG\Definition(
 *      definition="Challenge",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="category_id",
 *          description="category_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="parent_id",
 *          description="parent_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="difficulty",
 *          description="difficulty",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="lat",
 *          description="lat",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="lng",
 *          description="lng",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="time",
 *          description="time",
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
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      )
 * )
 */
class Challenge extends Model
{
    use SoftDeletes;

    public $table = 'challenges';

    protected $with = ['category', 'files', 'parent', 'user'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'category_id',
        'user_id',
        'parent_id',
        'title',
        'description',
        'difficulty',
        'lat',
        'lng',
        'time',
        'address',
        'slug'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'difficulty' => 'integer',
        'lat' => 'float',
        'lng' => 'float',
        'time' => 'string',
        'address' => 'string',
        'slug' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required',
        'user_id' => 'required',
        'title' => 'required',
        'description' => 'required',
        'difficulty' => 'required',
        'lat' => 'required',
        'lng' => 'required'
    ];

    public $appends = ['rate'];


    public function getRateAttribute()
    {

        return $this->ratings->avg('rate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id')->without('challenges');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id')->without('challenges');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function favourites()
    {
        return $this->hasMany(\App\Models\Favourite::class, 'challenge_id')->without('challenge');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function files()
    {
        return $this->hasMany(\App\Models\File::class, 'challenge_id')->without('challenge');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class, 'challenge_id')->without('challenge');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Challenge', 'parent_id')->without('children');
    }

    public function children() {
        return $this->hasMany('App\Models\Challenge', 'parent_id')->without('parent'); //get all subs. NOT RECURSIVE
    }

    public function scopeFollowed($query, $user){
        if($user) {
            $query->whereHas('user', function($q) use($user){
                $q->whereHas('followers', function ($query) use($user) {
                    $query->where('follower_id', $user);
                });
            });
        }
    }

    public function scopeNear($query, $latitude, $longitude)
    {
        if($latitude) {
            $distance = 100;
            return $query->whereRaw("ST_Distance_Sphere(point(lng, lat),point(?, ?)) * .000621371192 < ?",
                [
                    $longitude,
                    $latitude,
                    $distance
                ]);
        }
    }
}
