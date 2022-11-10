<?php

declare(strict_types=1);

namespace Blog;

use PDO;

class LatestPosts
{
    /**
     * @var // PDO
     */
    private PDO $connection; // визначає простий та узгоджений інтерфейс для доступу до баз даних у PHP

    /**
     * LatestPosts constructor.
     * @param // PDO $connection
     */
    public function __construct(PDO $connection) // будова конструкції PDO
    {
        $this->connection = $connection;
    }

    /**
     * @param // int $limit
     * @return // array|null
     */
    public function get(int $limit): ?array // макс кіл-ть постів, яка виводиться на 1 сторінці
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM post ORDER BY published_date DESC LIMIT ' . $limit
        );

        $statement->execute();

        return $statement->fetchAll();
    }
}