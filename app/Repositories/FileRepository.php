<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\BaseRepository;

/**
 * Class FileRepository
 * @package App\Repositories
 * @version May 14, 2020, 10:58 am UTC
*/

class FileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'challenge_id',
        'category_id',
        'url'
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
        return File::class;
    }
}
