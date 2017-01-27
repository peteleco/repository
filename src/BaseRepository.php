<?php namespace Peteleco\Repository;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Peteleco\QueryFilter\BaseQueryFilter;

/**
 * Class Repository
 *
 * @package App\Repositories
 */
abstract class BaseRepository
{

    /**
     * @var Model
     */
    protected $model;
    /**
     * @var CacheRepository
     */
    protected $cache;
    /**
     * In minutes
     *
     * @var array
     */
    protected $cacheTime = [
        'fast'    => 1,
        'quick'   => 5,
        'regular' => 10,
        'long'    => 60,
        'halfDay' => 720
    ];
    /**
     * @var App
     */
    protected $app;

    /**
     * Repository constructor.
     *
     * @param App   $app
     * @param Cache $cache
     */
    public function __construct(App $app, Cache $cache)
    {
        $this->app   = $app;
        $this->cache = $cache;
        if (! $this->modelReference()) {
            $this->setModel($this->modelInstance($this->modelReference()));
        }

        $this->setUp();
    }

    /**
     * If this repository has a model reference,
     * should be override
     * Default has no model reference
     *
     * @return bool
     */
    public function modelReference()
    {
        return false;
    }

    /**
     * @param Model $model
     *
     * @return Repository
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Return model instance
     *
     * @param string $model
     * @param array  $params
     *
     * @return Model
     * @throws RepositoryException
     */
    public function modelInstance($model, $params = [])
    {
        $model = $this->app->make($model, $params);
        if (! $model instanceof Model) {
            throw new RepositoryException("Class {$model} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    /**
     * Setup function
     */
    public function setUp()
    {

    }

    /**
     * Implement QueryFilter directly from repository
     *
     * $this->queryFilter($query = $this->model->query(), $filter = app(ResourceFilter::class));
     * $filter->model{NAME}(${model});
     * $filter->only{Something}();
     *
     * @param Builder         $query
     * @param BaseQueryFilter $filter
     */
    public function queryFilter(Builder $query, BaseQueryFilter $filter)
    {
        $filter->setBuilder($query);
        // Remove request fields passed through instance
        $filter->resetRequest();
    }
}
