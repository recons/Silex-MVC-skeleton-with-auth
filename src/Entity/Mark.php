<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 15:43
 */

namespace Entity;

class Mark
{
    /**
     * Group id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Student.
     *
     * @var Human
     */
    protected $student;

    /**
     * Subject.
     *
     * @var Subject
     */
    protected $subject;

    /**
     * Teacher.
     *
     * @var Human
     */
    protected $teacher;

    /**
     * Value.
     *
     * @var float
     */
    protected $value;

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
     * @return Human
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param Human $student
     */
    public function setStudent(Human $student)
    {
        $this->student = $student;
    }

    /**
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param Subject $subject
     */
    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return Human
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param Human $teacher
     */
    public function setTeacher(Human $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


}
