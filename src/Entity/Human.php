<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:44
 */

namespace Entity;

class Human
{
    /**
     * Group id.
     *
     * @var integer
     */
    protected $id;

    /**
     * First name.
     *
     * @var string
     */
    protected $firstname;

    /**
     * Last name.
     *
     * @var string
     */
    protected $lastname;

    /**
     * Pather name.
     *
     * @var string
     */
    protected $pathername;

    /**
     * Group.
     *
     * @var Group
     */
    protected $group;

    /**
     * Type.
     *
     * @var string
     */
    protected $type;

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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPathername()
    {
        return $this->pathername;
    }

    /**
     * @param string $pathername
     */
    public function setPathername($pathername)
    {
        $this->pathername = $pathername;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function getFullname()
    {
        return $this->getLastname() . ' ' .
            $this->getFirstname() . ' ' .
            $this->getPathername();
    }
}
