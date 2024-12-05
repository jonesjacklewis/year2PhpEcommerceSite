<?php

/**
 * Represents a top-level invoice for an e-commerce website.
 * This is used to show the invoice previews.
 *
 * Contains the following properties, accessed via getters and setters
* int $id: The unique identifier of the invoice.
* int $invoiceValuePence: The total value of the invoice in pence.
* datetime $dateTimeCreated: The creation timestamp of the invoice.
* string $image: The preview image of the invoice as a base64 string.
*                        The most expensive product is used for the image.
* string $username: The username of the user the invoice belongs to.
 */
class TopLevelInvoice{
    // instant variables
    private $id;
    private $invoiceValuePence;
    private $dateTimeCreated;
    private $image;
    private $username;

    /**
     * Constructs a new TopLevelInvoice object.
     *
     * @param int $id - The unique identifier of the invoice.
     * @param int $invoiceValuePence - The total value of the invoice in pence.
     * @param datetime $dateTimeCreated - The creation timestamp of the invoice.
     * @param string $image - The preview image of the invoice as a base64 string.
     *                        The most expensive product is used for the image.
     * @param string $username - The username of the user the invoice belongs to.
     *
     */
    public function __construct($id, $invoiceValuePence, $dateTimeCreated, $image, $username){
        $this->id = $id;
        $this->invoiceValuePence = $invoiceValuePence;
        $this->dateTimeCreated = $dateTimeCreated;
        $this->image = $image;
        $this->username = $username;
    }

    /**
     * Getter for the id property of the invoice object.
     *
     * @return int - The unique identifier of the invoice.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter for the id property of the invoice object.
     *
     * @param int $id - The unique identifier of the invoice.
     *
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Getter for the invoice value pence property of the invoice object.
     *
     * @return int - The value of the invoice in pence.
     */
    public function getInvoiceValuePence() {
        return $this->invoiceValuePence;
    }

    /**
     * Setter for the invoice value pence property of the invoice object.
     *
     * @param int $invoiceValuePence - The value of the invoice in pence.
     *
     */
    public function setInvoiceValuePence($invoiceValuePence) {
        $this->invoiceValuePence = $invoiceValuePence;
    }

    /**
     * Getter for the datetime property of the invoice object.
     *
     * @return datetime - The datetime the invoice was created.
     */
    public function getDateTimeCreated() {
        return $this->dateTimeCreated;
    }

    /**
     * Setter for the datetime property of the invoice object.
     *
     * @param datetime $dateTimeCreated - The datetime of the invoice.
     *
     */
    public function setDateTimeCreated($dateTimeCreated) {
        $this->dateTimeCreated = $dateTimeCreated;
    }

     /**
     * Getter for the image property of the invoice object.
     *
     * @return string - The invoice's preview image as a base64 string.
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Setter for the image property of the invoice object.
     *
     * @param string $image - The image of the invoice expressed as a base64 string.
     *
     */
    public function setImage($image){
        $this->image = $image;
    }

    /**
     * Getter for the username property of the invoice object.
     *
     * @return string - The username of the user that the invoice belongs to.
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Setter for the username property of the invoice object.
     *
     * @param string $username - The username of the user that the invoice belongs to.
     *
     */
    public function setUsername($username){
        $this->username = $username;
    }
}
