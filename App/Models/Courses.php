<?php
namespace Models;

use Services\Hydratation;

class Courses
{
    private $course_id;
    private $class_id;
    private $course_date;
    private $course_startTime;
    private $course_endTime;
    private $course_randomCode;
    
    
    use Hydratation;


    /**
     * Get the value of course_id
     */ 
    public function getCourse_id()
    {
        return $this->course_id;
    }

    /**
     * Set the value of course_id
     *
     * @return  self
     */ 
    public function setCourse_id($course_id)
    {
        $this->course_id = $course_id;

        return $this;
    }

    /**
     * Get the value of class_id
     */ 
    public function getClass_id()
    {
        return $this->class_id;
    }

    /**
     * Set the value of class_id
     *
     * @return  self
     */ 
    public function setClass_id($class_id)
    {
        $this->class_id = $class_id;

        return $this;
    }

    /**
     * Get the value of course_date
     */ 
    public function getCourse_date()
    {
        return $this->course_date;
    }

    /**
     * Set the value of course_date
     *
     * @return  self
     */ 
    public function setCourse_date($course_date)
    {
        $this->course_date = $course_date;

        return $this;
    }

    /**
     * Get the value of course_startTime
     */ 
    public function getCourse_startTime()
    {
        return $this->course_startTime;
    }

    /**
     * Set the value of course_startTime
     *
     * @return  self
     */ 
    public function setCourse_startTime($course_startTime)
    {
        $this->course_startTime = $course_startTime;

        return $this;
    }

    /**
     * Get the value of course_endTime
     */ 
    public function getCourse_endTime()
    {
        return $this->course_endTime;
    }

    /**
     * Set the value of course_endTime
     *
     * @return  self
     */ 
    public function setCourse_endTime($course_endTime)
    {
        $this->course_endTime = $course_endTime;

        return $this;
    }

    /**
     * Get the value of course_randomCode
     */ 
    public function getCourse_randomCode()
    {
        return $this->course_randomCode;
    }

    /**
     * Set the value of course_randomCode
     *
     * @return  self
     */ 
    public function setCourse_randomCode($course_randomCode)
    {
        $this->course_randomCode = $course_randomCode;

        return $this;
    }
}