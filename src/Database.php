<?php

declare(strict_types=1);

namespace NoteApp;

use NoteApp\Exception\ConfigException;
use NoteApp\Exception\NotFoundException;
use NoteApp\Exception\StorageException;
use PDO;
use PDOException;
use Throwable;

class Database
{
  private PDO $conn;
  
  public function __construct(array $config)
  {
    try {

      $this->validateConfiguration($config);
      $this->createConnection($config);

    } catch (PDOException $e) {
      throw new StorageException("Connection error");
    }

  }

  public function createNote(array $data): void
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

  public function searchNotes(
    string $phrase,
    string $sortBy, 
    string $sortOrder, 
    int $pageSize, 
    int $pageNumber
  ): array {
    try {
      $phrase = $this->conn->quote('%' . $phrase . '%');

      if(!in_array($sortBy, ['created','title'])) {
        $sortBy = 'title';
      }
      if(!in_array($sortOrder, ['asc','desc'])) {
        $sortOrder = 'desc';
      }
      
      $offset = ($pageNumber-1) * $pageSize;

      $query = "
        SELECT id, title, created 
        FROM notestable
        WHERE title LIKE ($phrase)
        ORDER BY $sortBy $sortOrder
        LIMIT $offset, $pageSize
      ";
      $result = $this->conn->query($query, PDO::FETCH_ASSOC); // drugi parametr określa w jakim formacie dane będą zwrócne
      return $result->fetchAll(); // tutaj też można określić format fetchowania
    
    } catch (Throwable $e) {
      throw new StorageException('Search notes fetch error',400,$e);
    }  
  }

  public function getSearchCount(string $phrase): int
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

  public function getNoteList(
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

      $query = "
        SELECT id, title, created 
        FROM notestable
        ORDER BY $sortBy $sortOrder
        LIMIT $offset, $pageSize
      ";
      $result = $this->conn->query($query, PDO::FETCH_ASSOC); // drugi parametr określa w jakim formacie dane będą zwrócne
      return $result->fetchAll(); // tutaj też można określić format fetchowania
    
    } catch (Throwable $e) {
      throw new StorageException('Note list fetch error',400,$e);
    }   
  }

  public function getNotesCount(): int 
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

  public function getNote(int $id): array
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

  public function editNote(int $id, array $data): void
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

  public function deleteNote(int $id): void
  {
    try {
      $query = "DELETE FROM notestable WHERE id = $id LIMIT 1";
      $this->conn->exec($query);
    } catch (Throwable $e) {
      throw new StorageException('Delete note fail', 400, $e);
    }
  }

  private function createConnection(array $config): void
  {
    $dsn = "mysql:dbname={$config['database']};host={$config['host']}";    
    $this->conn = new PDO(
      $dsn,
      $config['user'],
      $config['password'],
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //domyślne rzucanie błędów przez PDO
      ]
    );
  }

  private function validateConfiguration(array $config): void
  {
    if(empty($config['database'])
    || empty($config['host'])
    || empty($config['user'])
    || empty($config['password'])) {
      throw new ConfigException('Storage configuration data');
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
}