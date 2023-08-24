<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    abstract public function model(): string;

    public function resolveModel() : Model {
        return app()->make($this->model());
    }
    
    public function getAll(): Collection
    {
        return $this->model->get();
    }

    public function getWhereIn(array $ids, string $columns = 'id'): Collection
    {
        return $this->model->whereIn($columns, $ids)->get();
    }
    
    public function getOne(int $id): Model
    {
        $model = $this->model->find($id);
        if (!$model) {
            throw new ModelNotFoundException();
        }
        return $model;
    }
    
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }
    
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model->refresh();
    }
    
    public function delete(Model $model): void
    {
        $model->delete();
    }
}