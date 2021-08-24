<?php

declare(strict_types=1);

namespace NoteApp\Model;

interface ModelInterface
{
  public function list(string $sortBy, string $sortOrder, int $pageSize, int $pageNumber): array;
  
  public function search(string $phrase, string $sortBy, string $sortOrder, int $pageSize, int $pageNumber): array;
  
  public function count(): int;

  public function searchCount(string $phrase): int;

  public function get(int $id): array;

  public function create(array $data): void;

  public function edit(int $id, array $data): void;

  public function delete(int $id): void;
}