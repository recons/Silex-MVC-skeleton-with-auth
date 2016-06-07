<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:45
 */

namespace Repository;

use Doctrine\DBAL\Connection;
use Entity\Human;

class HumanRepository extends BaseRepository implements RepositoryInterface
{
    private $db;

    private $groupRepository;
    
    public function __construct(Connection $db, $groupRepository)
    {
        $this->db = $db;
        $this->groupRepository = $groupRepository;
    }
    
    public function find($id)
    {
        $data = $this->db->fetchAssoc('SELECT * FROM people WHERE id = ?', [$id]);
        return !empty($data) ? $this->buildHuman($data) : false;
    }

    public function save($human)
    {
        $data = array(
            'first_name' => $human->getFirstname(),
            'last_name' => $human->getLastname(),
            'pather_name' => $human->getPathername(),
            'group_id' => $human->getGroup()->getId(),
            'type' => $human->getType(),
        );

        if ($human->getId()) {
            $this->db->update('people', $data, ['id' => $human->getId()]);
        } else {
            $this->db->insert('people', $data);
            $id = $this->db->lastInsertId();
            $human->setId($id);
        }
    }

    public function delete($id)
    {
        $this->db->delete('people', ['id' => $id]);
    }

    public function findAll($limit = 0, $offset = 0, $orderBy = [])
    {
        return $this->getPeople([], $limit, $offset, $orderBy);
    }

    public function findAllTeachers()
    {
        $data = $this->db->fetchAll('SELECT * FROM teachers');

        $people = [];
        foreach ($data as $row) {
            $id = $row['ID'];
            $people[$id] = $this->buildHuman($row);
        }

        return $people;
    }

    public function findAllStudents()
    {
        return $this->getPeople(['type' => 's'], 0, 0, []);
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @return array
     */
    protected function getPeople(array $conditions = [], $limit = 0, $offset = 0, $orderBy = [])
    {
        $data = $this->getRows($this->db, 'people', $conditions, $limit, $offset, $orderBy);

        $people = [];
        foreach ($data as $row) {
            $id = $row['ID'];
            $people[$id] = $this->buildHuman($row);
        }
        
        return $people;
    }
    
    protected function buildHuman($data)
    {
        $group = $this->groupRepository->find($data['GROUP_ID']);

        $human = new Human();
        $human->setId($data['ID']);
        $human->setFirstname($data['FIRST_NAME']);
        $human->setLastname($data['LAST_NAME']);
        $human->setPathername($data['PATHER_NAME']);
        $human->setGroup($group);
        $human->setType($data['TYPE']);

        return $human;
    }
}