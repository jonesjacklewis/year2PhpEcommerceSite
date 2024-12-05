<?php

/**
 * Represents a product in an e-commerce website.
 *
 * Contains the following properties, accessed via getters and setters
 * - int $id: The unique identifier of the product.
 * - string $name: The name of the product.
 * - string $description: The description of the product.
 * - int $pricePence: The price of the product in pence.
 * - string $image: The image of the product expressed as a base64 string.
 */
class Product{
    // Instance Variables
    private $id;
    private $name;
    private $description;
    private $pricePence;
    private $image;

    /**
     * Constructs a new Product object.
     *
     * @param int $id - The unique identifier of the product.
     * @param string $name - The name of the product.
     * @param string $description - The description of the product.
     * @param int $pricePence - The price of the product in pence.
     * @param string $image - The image of the product expressed as a base64 string.
     *
     */
    public function __construct($id, $name, $description, $pricePence, $image) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->pricePence = $pricePence;
        $this->image = $image;
    }

    /**
     * Getter for the id property of the Product object.
     *
     * @return int - The unique identifier of the product.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter for the id property of the Product object.
     *
     * @param int $id - The unique identifier of the product.
     *
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Getter for the name property of the Product object.
     *
     * @return string - The name of the product.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Setter for the name property of the Product object.
     *
     * @param string $name - The name of the product.
     *
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Getter for the description property of the Product object.
     *
     * @return string - The description of the product.
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Setter for the description property of the Product object.
     *
     * @param string $description - The description of the product.
     *
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Getter for the price pence property of the Product object.
     *
     * @return int - The price of the product in pence.
     */
    public function getPricePence() {
        return $this->pricePence;
    }

    /**
     * Setter for the price pence property of the Product object.
     *
     * @param int $pricePence - The price of the product in pence.
     *
     */
    public function setPricePence($pricePence) {
        $this->pricePence = $pricePence;
    }

    /**
     * Getter for the image property of the Product object.
     *
     * @return string - The product's image as a base64 string.
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Setter for the image property of the Product object.
     *
     * @param string $image - The image of the product expressed as a base64 string.
     *
     */
    public function setImage($image){
        $this->image = $image;
    }
}
