<?php

class ReallySimpleIssueTracker_Priority {
    private $id;
    private $name;
    private $description;

    public function __construct($id, $name, $description = '') {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Constructs and returns the default status types
     * @static
     * @return array ReallySimpleIssueTracker_Priority
     */
    public static function getDefaultPriorities(){

        $urgent = new self('urgent',__('Urgent',ReallySimpleIssueTracker::HANDLE),'');
        $high = new self('high',__('High',ReallySimpleIssueTracker::HANDLE),'');
        $medium = new self('medium',__('Medium',ReallySimpleIssueTracker::HANDLE),'');
        $low = new self('low',__('Low',ReallySimpleIssueTracker::HANDLE),'');

        return array($urgent, $high, $medium, $low);
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param $id
     * @return bool|ReallySimpleIssueTracker_Priority
     */
    public function getPriorityById($id) {
        if($id == $this->id)
            return $this;
        else
            return false;
    }
}