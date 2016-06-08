<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:45
 */

namespace Repository;


use Doctrine\DBAL\Connection;
use Entity\Group;

class GroupRepository extends BaseRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    
    public function find($id)
    {
        $data = $this->db->fetchAssoc('SELECT * FROM groups WHERE id = ?', [$id]);
        return !empty($data) ? $this->buildGroup($data) : false;
    }

    public function save($group)
    {
        $data = array(
            'name' => $group->getName(),
        );
        if ($group->getId()) {
            $this->db->update('groups', $data, ['id' => $group->getId()]);
        } else {
            $this->db->insert('groups', $data);
            $id = $this->db->lastInsertId();
            $group->setId($id);
        }
    }

    public function delete($id)
    {
        $this->db->delete('groups', ['id' => $id]);
    }

    public function findAll($limit = 0, $offset = 0, $orderBy = [])
    {
        return $this->getGroups([], $limit, $offset, $orderBy);
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @return array
     */
    protected function getGroups(array $conditions = [], $limit = 0, $offset = 0, $orderBy = [])
    {
        $data = $this->getRows($this->db, 'groups', $conditions, $limit, $offset, $orderBy);

        $groups = [];
        foreach ($data as $row) {
            $id = $row['ID'];
            $groups[$id] = $this->buildGroup($row);
        }
        
        return $groups;
    }
    
    protected function buildGroup($data)
    {
        $group = new Group();
        $group->setId($data['ID']);
        $group->setName($data['NAME']);

        return $group;
    }

    public function getStatByYear()
    {
        $data = $this->db->fetchAll('SELECT * FROM average_marks_by_year order by year asc');

        $stat = [];
        foreach ($data as $row) {
            $year = $row['YEAR'];
            $avg = $row['AVG'];
            $stat[$year] = $avg;
        }

        return $stat;
    }
}