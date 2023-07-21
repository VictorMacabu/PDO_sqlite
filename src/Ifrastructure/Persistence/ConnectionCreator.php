<?php

namespace Alura\Pdo\Ifrastructure\Persistence;

use PDO;
class ConnectionCreator
{
    public static function createConection(): PDO
    {
        $databasePath = __DIR__ . '/../../../banco.sqlite';

        return new PDO('sqlite:' . $databasePath);
    }
}