<?php

namespace App\Repositories;

use App\Models\Rating;
use App\Repositories\BaseRepository;

/**
 * Class RatingRepository
 * @package App\Repositories
 * @version May 23, 2020, 3:13 pm UTC
*/

class RatingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'challenge_id',
        'comment',
        'rate'
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
        return Rating::class;
    }
}
