<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Notification",
 *      required={"message", "read", "notification_user_id", "notificated_id"},
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
 *          property="read",
 *          description="read",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="notification_user_id",
 *          description="notification_user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="notificated_id",
 *          description="notificated_id",
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
class Notification extends Model
{
    use SoftDeletes;

    public $table = 'notifications';

    protected $with = ['notificated', 'notificationUser'];
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'message',
        'read',
        'notification_user_id',
        'notificated_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'message' => 'string',
        'read' => 'boolean',
        'notification_user_id' => 'integer',
        'notificated_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'message' => 'required',
        'read' => 'required',
        'notification_user_id' => 'required',
        'notificated_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function notificated()
    {
        return $this->belongsTo(\App\Models\User::class, 'notificated_id')->without('notifications');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function notificationUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'notification_user_id')->without('notifications');
    }
}
