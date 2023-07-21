<?php

namespace Alura\Pdo\Ifrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Ifrastructure\Persistence\ConnectionCreator;
use Exception;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private \PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionCreator::createConection();
    }

    public function allStudents(): array
    {
        $stmt = $this->connection->query('SELECT * FROM students;');

        return $this->hydrateStudentList($stmt);
    }

    public function studentBirthAt(\DateTimeInterface $birthDate): array
    {
        $stmt = $this->connection->query('SELECT * FROM students WHERE birth_date = ?;');
        $stmt->bindValue(1, $birthDate->format('Y-m-d'));
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }

    private function hydrateStudentList(\PDOStatement $stmt): array
    {
        $studentDataList = $stmt->fetchAll(fetch_style: PDO::FETCH_ASSOC);
        $studentList = [];

        foreach ($studentDataList as $studentData) {
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

        return $studentList;
    }
    public function save(Student $student): bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }
        return $this->update($student);
    }
    private function insert(Student $student): bool
    {
        $sqlInsert = "INSERT INTO students(name, birth_date) VALUES (:name,:birth_date);";
        $stmt = $this->connection->prepare($sqlInsert);
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

        if ($stmt->execute()){
            $student->defineId($this->connection->lastInsertId());
        }

        return $stmt;
    }
    private function update(Student $student): bool
    {
        $stmt = $this->connection->prepare('UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;');
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $stmt->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function remove(Student $student): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM students WHERE id = ?;');
        $stmt->bindValue(1,$student->id(), PDO::PARAM_INT);

        return $stmt->execute();
    }
}