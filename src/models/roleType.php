<?php

/**
 * Represents a user role-type in an e-commerce website.
 *
 * Contains the following properties, accessed via getters and setters
 * - int $id: The unique identifier of the role type.
 * - string $name: The name of the role type.
 * - boolean $isGuest: Whether the role type is a guest. Not Settable.
 */
class RoleType{
    // instance variables
    private $id;
    private $name;
    private $isGuest;

    /**
     * Constructs a new RoleType object.
     *
     * @param int $id: The unique identifier of the role type.
     * @param string $name - The name of the role type.
     *
     */
    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
        $this->isGuest = strtolower($name) == 'guest';
    }

    /**
     * Getter for the id property of the RoleType object.
     *
     * @return int - The unique identifier of the role type.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter for the id property of the RoleType object.
     *
     * @param int $id - The unique identifier of the role type.
     *
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Getter for the name property of the RoleType object.
     *
     * @return string - The name of the role type.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Setter for the name property of the RoleType object.
     *
     * @param string $name - The name of the role type.
     *
     */
    public function setName($name) {
        $this->name = $name;
        $this->isGuest = strtolower($name) == 'guest';
    }

    /**
     * Getter for the isGuest property of the RoleType object.
     *
     * @return boolean - True if RoleType is guest else false.
     */
    public function getIsGuest() {
        return $this->isGuest;
    }
}
