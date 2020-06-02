<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Conversation",
 *      required={"guest", "host", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="guest",
 *          description="guest",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="host",
 *          description="host",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="boolean"
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
class Conversation extends Model
{
    use SoftDeletes;

    public $table = 'conversations';

    protected $with = ['guestUser', 'hostUser', 'messages'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'guest',
        'host',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'guest' => 'integer',
        'host' => 'integer',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'guest' => 'required',
        'host' => 'required',
        'status' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function guestUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'guest')->without('conversations');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function hostUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'host')->without('conversations');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'conversation_id')->without('conversation');
    }

    public function scopeUser($query, $user){
        if($user) {
            $query->where('guest', $user)->orWhere('host', $user);
        }
    }
}
