<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:46
 */

namespace Repository;


interface RepositoryInterface
{
    public function find($id);
    public function save($entity);
    public function delete($id);
}