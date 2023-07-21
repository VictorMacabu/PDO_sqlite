<?php

use Alura\Pdo\Ifrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConection();

$preparedStatement = $pdo->prepare('DELETE FROM students WHERE id = ?;');
$preparedStatement->bindValue(1,6, PDO::PARAM_INT);

$preparedStatement->execute();