<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\BaseRepository;

/**
 * Class MessageRepository
 * @package App\Repositories
 * @version June 2, 2020, 4:41 pm UTC
*/

class MessageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'message',
        'is_seen',
        'deleted_from_sender',
        'deleted_from_receiver',
        'user_id',
        'conversation_id'
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
        return Message::class;
    }
}
