<?php

declare(strict_types=1);

namespace NoteApp\Model;

use NoteApp\Exception\ConfigException;
use NoteApp\Exception\NotFoundException;
use NoteApp\Exception\StorageException;
use PDO;
use PDOException;

abstract class AbstractModel 
{
  protected PDO $conn;
  
  public function __construct(array $config)
  {
    try {

      $this->validateConfiguration($config);
      $this->createConnection($config);

    } catch (PDOException $e) {
      throw new StorageException("Connection error");
    }

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
}