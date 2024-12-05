<?php

/**
 * Represents a product with a given quantity in an e-commerce website.
 *
 * It extends from its Parent Class `Product`
 *
 * Contains the following properties, accessed via getters and setters
 * - int $id: The unique identifier of the product.
 * - string $name: The name of the product.
 * - string $description: The description of the product.
 * - int $pricePence: The price of the product in pence.
 * - string $image: The image of the product expressed as a base64 string.
 * - int $quantity: The quantity of the product.
 */
class ProductWithQuantity extends Product {
    // Instance Variables
    private $quantity;

    /**
     * Constructs a new ProductWithQuantity object.
     *
     * @param int $id - The unique identifier of the product.
     * @param string $name - The name of the product.
     * @param string $description - The description of the product.
     * @param int $pricePence - The price of the product in pence.
     * @param string $image - The image of the product expressed as a base64 string.
     * @param int $quantity - The quantity of the product.
     *
     */
    public function __construct($id, $name, $description, $pricePence, $image, $quantity) {
        // Call the parent constructor to set the inherited properties
        parent::__construct($id, $name, $description, $pricePence, $image);
        
        // Set the new property specific to this subclass
        $this->quantity = $quantity;
    }

    /**
     * Getter for the quantity property of the ProductWithQuantity object.
     *
     * @return int - The quantity of the product.
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Setter for the quantity property of the ProductWithQuantity object.
     *
     * @param int $quantity - The quantity of the product.
     *
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}
