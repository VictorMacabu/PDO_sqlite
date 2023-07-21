<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Ifrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConection();

$statement = $pdo->query('SELECT * FROM students;');

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);

$studentList = [];

foreach ($studentDataList as $studentData){
    try {
        $studentList[] = new Student(
            $studentData['id'],
            $studentData['name'],
            new \DateTimeImmutable($studentData['birth_date'])
        );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

var_dump($studentList);