<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Ifrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConection();

$student = new Student(null,
    "Maria Clara",
    new \DateTimeImmutable('2016-07-19'));

$sqlInsert = "INSERT INTO students(name, birth_date) VALUES (:name,:birth_date);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $student->name());
$statement->bindValue(':birth_date',$student->birthDate()->format('Y-m-d'));

if ($statement->execute()){
    echo 'Aluno incluído';
}


