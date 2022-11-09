<?php

namespace Blog;

use PDO;

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
        $statement = $this->connection->prepare('SELECT * FROM post WHERE url_key = :url_key');
        $statement->execute([ // передача запросу для юрлкей
           'url_key' => $urlKey
        ]);

        $result = $statement->fetchAll(); // масив результатів

        return array_shift($result);
    }
}