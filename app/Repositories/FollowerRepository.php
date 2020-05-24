<?php

namespace App\Repositories;

use App\Models\Follower;
use App\Repositories\BaseRepository;

/**
 * Class FollowerRepository
 * @package App\Repositories
 * @version May 23, 2020, 3:05 pm UTC
*/

class FollowerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'follower_id',
        'followed_id'
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
        return Follower::class;
    }
}
