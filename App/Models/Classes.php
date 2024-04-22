<?php
namespace Models;

use Services\Hydratation;

class Classes
{
    private $classId;
    private $className;
    private $classStartDate;
    private $classEndDate;
    private $placesAvailable;

    use Hydratation;


    /**
     * Get the value of classId
     */ 
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * Set the value of classId
     *
     * @return  self
     */ 
    public function setClassId($classId)
    {
        $this->classId = $classId;

        return $this;
    }

    /**
     * Get the value of className
     */ 
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set the value of className
     *
     * @return  self
     */ 
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the value of classStartDate
     */ 
    public function getClassStartDate()
    {
        return $this->classStartDate;
    }

    /**
     * Set the value of classStartDate
     *
     * @return  self
     */ 
    public function setClassStartDate($classStartDate)
    {
        $this->classStartDate = $classStartDate;

        return $this;
    }

    /**
     * Get the value of classEndDate
     */ 
    public function getClassEndDate()
    {
        return $this->classEndDate;
    }

    /**
     * Set the value of classEndDate
     *
     * @return  self
     */ 
    public function setClassEndDate($classEndDate)
    {
        $this->classEndDate = $classEndDate;

        return $this;
    }

   

    /**
     * Get the value of placesAvailable
     */ 
    public function getPlacesAvailable()
    {
        return $this->placesAvailable;
    }

    /**
     * Set the value of placesAvailable
     *
     * @return  self
     */ 
    public function setPlacesAvailable($placesAvailable)
    {
        $this->placesAvailable = $placesAvailable;

        return $this;
    }
}