<?php
namespace App\Repositories;

use App\Interfaces\TodoRepositoryInterface;
use App\Models\Todo;
use Illuminate\Support\Collection;

class TodoRepository implements TodoRepositoryInterface
{
    protected Todo $model;



    public function __construct(Todo $model)
    {
        $this->model = $model;
    }



    public function getAll(): array
    {
        return $this->model->latest()->get()->toArray();
    }



    public function create(array $data): array
    {
        if (!isset($data['title']) || empty(trim($data['title']))) {
            throw new \InvalidArgumentException('Title is required');
        }

        return $this->model->create([
            'title' => trim($data['title'])
        ])->toArray();
    }



    public function delete(int $id): bool
    {
        $todo = $this->model->find($id);
        
        if (!$todo) {
            return false; 
        }
        return $todo->delete();
    }
    
}