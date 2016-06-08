<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:43
 */

namespace Entity;

class Subject
{
    /**
     * Group id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Group name.
     *
     * @var string
     */
    protected $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}