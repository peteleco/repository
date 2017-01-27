<?php namespace Peteleco\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Peteleco\QueryFilter\BaseQueryFilter;

/**
 * Interface IRepository
 *
 * @package App\Repositories\Contracts
 */
interface IBaseRepository
{

    /**
     * Return model that will be user as reference in this
     * repository
     *
     * @return string
     */
    public function modelReference();

    /**
     * Implement QueryFilter directly from repository
     *
     * @param Builder     $query
     * @param BaseQueryFilter $filter
     */
    public function queryFilter(Builder $query, BaseQueryFilter $filter);


}
