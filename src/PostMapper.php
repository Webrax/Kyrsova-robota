<?php

namespace Blog;

use PDO; // універсальний спосіб роботи з БД

class PostMapper /*загрузка і вигрузка контенту з БД*/
{
    /**
     * @var // PDO
     */
    private PDO $connection;

    /**
     * PostMapper constructor
     * @param // PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param // string $urlKey
     * @return // array|null
     */
    /*публічний клас, який на вхід отримує аргумент юрлкей, а на вихід масив*/
    public function getByUrlKey(string $urlKey): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM post WHERE url_key = :url_key'); // запрос контенту з БД
        $statement->execute([ // передача запросу для юрлкей
           'url_key' => $urlKey
        ]);

        $result = $statement->fetchAll(); // масив результатів

        return array_shift($result);
    }

    public function getList(string $direction): ?array //  сортування і вивід постів за датою (часом)
    {
        if (!in_array($direction, ['DESC', 'ASC'])) {
            throw new Exception('The direction is not supported.');
        }
        $statement = $this->connection->prepare('SELECT * FROM post ORDER BY published_date ' . $direction);

        $statement->execute();

        return $statement->fetchAll();
    }
}