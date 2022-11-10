<?php

namespace Blog;

use PDO;
use PDOException;
use InvalidArgumentException;

class Database
{
    private PDO $connection;

 public function __construct(string $dsn, string $username = null, string $password = '123321')
 {
     try { // конект БД, написання повідомлення про ерор
         $this->connection = new PDO($dsn, $username, $password);
         $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
     } catch (PDOException $exception) {
         throw new InvalidArgumentException($exception->getMessage());
     }

 }

 public function getConnection(): PDO
 {
     return $this->connection;
 }
}
