<?php

declare(strict_types=1);

namespace NoteApp;

require_once('Exception/StorageException.php');
require_once('Exception/ConfigException.php');

use NoteApp\Exception\ConfigException;
use NoteApp\Exception\StorageException;
use PDO;
use PDOException;
use Throwable;

class Database
{
  public function __construct(array $config)
  {
    try {

      $this->validateConfiguration($config);

      $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
    
      $connection = new PDO(
        $dsn,
        $config['user'],
        $config['password']
      );
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
}