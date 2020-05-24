<?php

namespace App\Repositories;

use App\Models\Favourite;
use App\Repositories\BaseRepository;

/**
 * Class FavouritesRepository
 * @package App\Repositories
 * @version May 23, 2020, 3:07 pm UTC
*/

class FavouritesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'challenge_id'
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
        return Favourite::class;
    }
}
