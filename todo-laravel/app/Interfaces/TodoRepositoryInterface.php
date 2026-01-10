<?php
namespace App\Interfaces;


interface TodoRepositoryInterface
{
    public function getAll(): array;

    public function create(array $data): array;

    public function delete(int $id): bool;
}