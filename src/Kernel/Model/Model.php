<?php

namespace Mvc\Framework\Kernel\Model;

use \PDO;

class Model
{
    private static ?Model $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        try {
            $this->pdo = new PDO("mysql:dbname=$db;host=$host:$port", $user, $pass);
            $this->pdo->exec("SET CHARACTER SET utf8");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo 'Connexion échouée avec la basse de donné : ' . $e->getMessage();
        }
    }

    public final static function getInstance(): Model
    {
        if (self::$instance === null) {
            self::$instance = new Model;
        }
        return self::$instance;
    }

    public final function get(string $table, int $id): array
    {
        $query = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_CLASS, 'Mvc\\Framework\\App\\Entity\\' . ucfirst($table));
    }

    public final function getAll(string $table): array
    {
        $query = $this->pdo->prepare("SELECT * FROM $table");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Mvc\\Framework\\App\\Entity\\' . ucfirst($table));
    }

    public final function create(string $table, array $data): void
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
        $query = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");
        $query->execute($data);
    }

    public final function update(string $table, int $id, array $data): void
    {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $query = $this->pdo->prepare("UPDATE $table SET $set WHERE id = :id");
        $query->execute(array_merge($data, ['id' => $id]));
    }

    public final function delete(string $table, int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        $query->execute(['id' => $id]);
    }

    public final function patch(string $table, int $id, array $data): void
    {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $query = $this->pdo->prepare("UPDATE $table SET $set WHERE id = :id");
        $query->execute(array_merge($data, ['id' => $id]));
    }



}
