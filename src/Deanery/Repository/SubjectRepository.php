<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:45
 */

namespace Repository;


use Doctrine\DBAL\Connection;
use Entity\Subject;

class SubjectRepository extends BaseRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    
    public function find($id)
    {
        $data = $this->db->fetchAssoc('SELECT * FROM subjects WHERE id = ?', [$id]);
        return !empty($data) ? $this->buildSubject($data) : false;
    }

    public function save($subject)
    {
        $data = array(
            'name' => $subject->getName(),
        );
        if ($subject->getId()) {
            $this->db->update('subjects', $data, ['id' => $subject->getId()]);
        } else {
            $this->db->insert('subjects', $data);
            $id = $this->db->lastInsertId();
            $subject->setId($id);
        }
    }

    public function delete($id)
    {
        $this->db->delete('subjects', ['id' => $id]);
    }

    public function findAll($limit = 0, $offset = 0, $orderBy = [])
    {
        return $this->getSubjects([], $limit, $offset, $orderBy);
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @return array
     */
    protected function getSubjects(array $conditions = [], $limit = 0, $offset = 0, $orderBy = [])
    {
        $data = $this->getRows($this->db, 'subjects', $conditions, $limit, $offset, $orderBy);

        $subjects = [];
        foreach ($data as $row) {
            $id = $row['ID'];
            $subjects[$id] = $this->buildSubject($row);
        }
        
        return $subjects;
    }
    
    protected function buildSubject($data)
    {
        $subject = new Subject();
        $subject->setId($data['ID']);
        $subject->setName($data['NAME']);

        return $subject;
    }
}