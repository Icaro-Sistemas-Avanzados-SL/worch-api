<?php

namespace App\Repositories;

use App\Models\Challenge;
use App\Repositories\BaseRepository;

/**
 * Class ChallengeRepository
 * @package App\Repositories
 * @version May 23, 2020, 3:09 pm UTC
*/

class ChallengeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'user_id',
        'parent_id',
        'title',
        'description',
        'difficulty',
        'time',
        'address',
        'slug'
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
        return Challenge::class;
    }
}
