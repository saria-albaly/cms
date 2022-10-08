<?php

namespace App\Repositories;

use App\Models\article;
use App\Repositories\BaseRepository;

/**
 * Class smsRepository
 * @package App\Repositories
 * @version January 13, 2022, 9:19 am UTC
*/

class articleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'content'
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
        return article::class;
    }
}
