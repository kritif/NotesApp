<?php

declare(strict_types=1);

namespace NoteApp\Model;

use NoteApp\Exception\NotFoundException;
use NoteApp\Exception\StorageException;
use PDO;
use Throwable;

class NoteModel extends AbstractModel implements ModelInterface
{
  public function search(
    string $phrase,
    string $sortBy, 
    string $sortOrder, 
    int $pageSize, 
    int $pageNumber
  ): array {
    return $this->findBy($phrase, $sortBy, $sortOrder, $pageSize, $pageNumber);
  }

  public function list(
    string $sortBy, 
    string $sortOrder, 
    int $pageSize, 
    int $pageNumber
  ): array {
    return $this->findBy(null, $sortBy, $sortOrder, $pageSize, $pageNumber); 
  }

  public function searchCount(string $phrase): int
  {
    try {
      $phrase = $this->conn->quote('%' . $phrase . '%');
      $query = "SELECT count(*) AS cn FROM notestable WHERE title LIKE $phrase";
      $result = $this->conn->query($query, PDO::FETCH_ASSOC);

      if($result == false) {
        throw new StorageException('Notes count fetch error',400);
      };
      return (int) $result->fetch()['cn']; 
    
    } catch (Throwable $e) {
      throw new StorageException('Notes count fetch error',400,$e);
    }
  }

  public function count(): int 
  {
    try {
      $query = "SELECT count(*) AS cn FROM notestable";
      $result = $this->conn->query($query, PDO::FETCH_ASSOC);

      if($result == false) {
        throw new StorageException('Notes count fetch error',400);
      };
      return (int) $result->fetch()['cn']; 
    
    } catch (Throwable $e) {
      throw new StorageException('Notes count fetch error',400,$e);
    }   
  }

  public function get(int $id): array
  {
    try {
      $query = "SELECT id, title, description, created FROM notestable WHERE id = $id";
      $result = $this->conn->query($query, PDO::FETCH_ASSOC);
      $note = $result->fetch();
      
    
    } catch (Throwable $e) {
      throw new StorageException('Signle note fetch error',400,$e);
    } 
    if(!$note) {
      throw new NotFoundException("Note with id: $id not found");
      exit('Note found exception');
    }
    return $note;
  }

  public function create(array $data): void
  {
    try {
      $this->validateNoteData($data);

      $title = $this->conn->quote($data['title']);
      $description = $this->conn->quote($data['description']);
      $created = $this->conn->quote(date('Y-m-d H:i:s'));

      $query = "
        INSERT INTO notestable(title, description, created) 
        VALUES($title, $description, $created)
      ";

      $this->conn->exec($query);
    } catch (StorageException $e) {
      throw new StorageException($e->getMessage());
    } catch (Throwable $e) {
      throw new StorageException('Note create failed.',400,$e);
    }    
  }

  public function edit(int $id, array $data): void
  {
    try {
      $title = $this->conn->quote($data['title']);
      $description = $this->conn->quote($data['description']);

      $query = "
      UPDATE notestable SET title = $title, description=$description
      WHERE id = $id";

      $this->conn->exec($query);

    } catch (Throwable $e) {
      throw new StorageException('Edit note fail', 400, $e);
    }
  }

  public function delete(int $id): void
  {
    try {
      $query = "DELETE FROM notestable WHERE id = $id LIMIT 1";
      $this->conn->exec($query);
    } catch (Throwable $e) {
      throw new StorageException('Delete note fail', 400, $e);
    }
  }

  private function validateNoteData(array $data): void
  {
    if(empty($data['title'])) {
      throw new StorageException('Note data not valid.');
    }

    if(strlen($data['title']) < 3 || strlen($data['title']) > 150) {
      throw new StorageException('Note data not valid.');
    }
  }

  private function findBy(
    ?string $phrase,
    string $sortBy, 
    string $sortOrder, 
    int $pageSize, 
    int $pageNumber
  ): array {
    try {
      
      if(!in_array($sortBy, ['created','title'])) {
        $sortBy = 'title';
      }
      if(!in_array($sortOrder, ['asc','desc'])) {
        $sortOrder = 'desc';
      }
      
      $offset = ($pageNumber-1) * $pageSize;
      $wherePart='';
      if($phrase) {
        $phrase = $this->conn->quote('%' . $phrase . '%');
        $wherePart = "WHERE title LIKE ($phrase)";
      }

      $query = "
        SELECT id, title, created 
        FROM notestable
        $wherePart
        ORDER BY $sortBy $sortOrder
        LIMIT $offset, $pageSize
      ";
      $result = $this->conn->query($query, PDO::FETCH_ASSOC); // drugi parametr określa w jakim formacie dane będą zwrócne
      return $result->fetchAll(); // tutaj też można określić format fetchowania
    
    } catch (Throwable $e) {
      throw new StorageException('Notes fetch error',400,$e);
    }  
  }

}