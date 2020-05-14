<?php

namespace App\Repositories;

use App\Models\Challenge;
use App\Repositories\BaseRepository;

/**
 * Class ChallengeRepository
 * @package App\Repositories
 * @version May 14, 2020, 10:56 am UTC
*/

class ChallengeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'user_id',
        'title',
        'description',
        'difficulty',
        'lat',
        'lng',
        'time'
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
