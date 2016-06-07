<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 16:13
 */

namespace Repository;


use Doctrine\DBAL\Connection;

class BaseRepository
{
    protected function getRows(Connection $db, $table, array $conditions = [], $limit = 0, $offset = 0, $orderBy = [])
    {
        if (!$orderBy) {
            $orderBy = ['id' => 'ASC'];
        }

        $queryBuilder = $db->createQueryBuilder();
        $queryBuilder
            ->select('t.*')
            ->from($table, 't')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('t.' . key($orderBy), current($orderBy));

        $parameters = [];
        foreach ($conditions as $key => $value) {
            $parameters[':' . $key] = $value;
            $where = $queryBuilder->expr()->eq('t.' . $key, ':' . $key);
            $queryBuilder->andWhere($where);
        }

        $queryBuilder->setParameters($parameters);
        $statement = $queryBuilder->execute();
        $data = $statement->fetchAll();

        return $data;
    }
}