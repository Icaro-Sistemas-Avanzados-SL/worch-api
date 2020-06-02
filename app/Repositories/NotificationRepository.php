<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\BaseRepository;

/**
 * Class NotificationRepository
 * @package App\Repositories
 * @version June 2, 2020, 4:40 pm UTC
*/

class NotificationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'message',
        'read',
        'notification_user_id',
        'notificated_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }
}
