<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Repositories\BaseRepository;

/**
 * Class ConversationRepository
 * @package App\Repositories
 * @version June 2, 2020, 4:40 pm UTC
*/

class ConversationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'guest',
        'host',
        'status'
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
        return Conversation::class;
    }
}
