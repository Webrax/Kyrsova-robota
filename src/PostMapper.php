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

    public function getList(int $page = 1, int $limit = 3, string $direction = 'ASC'): ?array //  сортування і вивід постів за датою (часом)
    {
        if (!in_array($direction, ['DESC', 'ASC'])) {
            throw new Exception('The direction is not supported.');
        }

        $start = ($page - 1) * $limit; // логіка правильного сортування, -1 тому що старт іде з нульової позиції
        $statement = $this->connection->prepare(
            'SELECT * FROM post ORDER BY published_date ' . $direction .
            ' LIMIT ' . $start . ',' . $limit
        );

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getTotalCount(): int // вертає ціле число (кіл-ть сторінок)
    {
        $statement = $this->connection->prepare('SELECT count(post_id) as total FROM post');
        $statement->execute();

        return (int) ($statement->fetchColumn() ?? 0);
        // fetchcolumn це з бібліотеки PDO
        // ?? значить: якщо результат є, то він виводиться, якщо немає, тоді 0
    }
}