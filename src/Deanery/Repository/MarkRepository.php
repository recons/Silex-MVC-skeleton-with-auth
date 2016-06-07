<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 02.06.16
 * Time: 18:52
 */

namespace Repository;

use Doctrine\DBAL\Connection;
use Entity\Mark;

class MarkRepository extends BaseRepository implements RepositoryInterface
{
    private $db;
    private $humanRepository;
    private $subjectRepository;

    public function __construct(Connection $db, $humanRepository, $subjectRepository)
    {
        $this->db = $db;
        $this->humanRepository = $humanRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function find($id)
    {
        $data = $this->db->fetchAssoc('SELECT * FROM marks WHERE id = ?', [$id]);
        return !empty($data) ? $this->buildMark($data) : false;
    }

    public function save($mark)
    {
        $data = array(
            'student_id' => $mark->getStudent()->getId(),
            'subject_id' => $mark->getSubject()->getId(),
            'teacher_id' => $mark->getTeacher()->getId(),
            'value' => $mark->getValue()
        );

        if ($mark->getId()) {
            $this->db->update('marks', $data, ['id' => $mark->getId()]);
        } else {
            $this->db->insert('marks', $data);
            $id = $this->db->lastInsertId();
            $mark->setId($id);
        }
    }

    public function delete($id)
    {
        $this->db->delete('marks', ['id' => $id]);
    }

    public function findAll($limit = 0, $offset = 0, $orderBy = [])
    {
        return $this->getMarks([], $limit, $offset, $orderBy);
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @return array
     */
    protected function getMarks(array $conditions = [], $limit = 0, $offset = 0, $orderBy = [])
    {
        $data = $this->getRows($this->db, 'marks', $conditions, $limit, $offset, $orderBy);

        $marks = [];
        foreach ($data as $row) {
            $id = $row['ID'];
            $marks[$id] = $this->buildMark($row);
        }

        return $marks;
    }

    protected function buildMark($data)
    {
        $student = $this->humanRepository->find($data['STUDENT_ID']);
        $teacher = $this->humanRepository->find($data['TEACHER_ID']);
        $subject = $this->subjectRepository->find($data['SUBJECT_ID']);

        $mark = new Mark();
        $mark->setId($data['ID']);
        $mark->setStudent($student);
        $mark->setSubject($subject);
        $mark->setTeacher($teacher);
        $mark->setValue($data['VALUE']);

        return $mark;
    }
}