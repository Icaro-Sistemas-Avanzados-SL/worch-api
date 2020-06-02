<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Message",
 *      required={"message", "is_seen", "deleted_from_sender", "deleted_from_receiver", "user_id", "conversation_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="message",
 *          description="message",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_seen",
 *          description="is_seen",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="deleted_from_sender",
 *          description="deleted_from_sender",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="deleted_from_receiver",
 *          description="deleted_from_receiver",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="conversation_id",
 *          description="conversation_id",
 *          type="integer",
 *          format="int32"
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
class Message extends Model
{
    use SoftDeletes;

    public $table = 'messages';

    protected $with = ['conversation', 'user'];
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'message',
        'is_seen',
        'deleted_from_sender',
        'deleted_from_receiver',
        'user_id',
        'conversation_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'message' => 'string',
        'is_seen' => 'boolean',
        'deleted_from_sender' => 'boolean',
        'deleted_from_receiver' => 'boolean',
        'user_id' => 'integer',
        'conversation_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'message' => 'required',
        'user_id' => 'required',
        'conversation_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function conversation()
    {
        return $this->belongsTo(\App\Models\Conversation::class, 'conversation_id')->without('messages');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id')->without('messages');
    }
}
