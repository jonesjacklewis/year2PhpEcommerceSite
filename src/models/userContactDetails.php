<?php

/**
 * Represents a user's contact details in an e-commerce website.
 *
 * Contains the following properties, accessed via getters and setters
    * - int $id: The unique identifier of the UserContactDetails.
    * - string $addressLine1: The first address line for the contact details.
    * - string $addressLine2: The second address line for the contact details. Nullable.
    * - string $townCity: The Town or city for the contact details.
    * - string $county: The county for the contact details.
    * - string $postcode: The postcode for the contact details.
    * - string $phoneNumber: The phoneNumber for the contact details. Nullable.
    * - string $email: The email for the contact details.
 */
class UserContactDetails{
    // instance variables
    private $id;
    private $addressLine1;
    private $addressLine2;
    private $townCity;
    private $county;
    private $postcode;
    private $phoneNumber;
    private $email;

    /**
     * Constructs a new UserContactDetails object.
     *
     * @param int $id - The unique identifier of the UserContactDetails.
     * @param string $addressLine1 - The first address line for the contact details.
     * @param string $addressLine2 - The second address line for the contact details. Nullable.
     * @param string $townCity - The Town or city for the contact details.
     * @param string $county - The county for the contact details.
     * @param string $postcode - The postcode for the contact details.
     * @param string $phoneNumber - The phoneNumber for the contact details. Nullable.
     * @param string $email - The email for the contact details.
     *
     */
    public function __construct(
        $id,
        $addressLine1,
        $addressLine2,
        $townCity,
        $county,
        $postcode,
        $phoneNumber,
        $email
    ) {
        $this->id = $id;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->townCity = $townCity;
        $this->county = $county;
        $this->postcode = $postcode;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }

    /**
     * Returns a string representation of the UserContactDetails.
     *
     *  @return string The string representation of the UserContactDetails, formatted as
     * "addressLine1, addressLine2 (if exists), townCity, county, postcode, phoneNumber (if exists), email".
     */
    public function __toString(){
        // should always have the first line of the address
        $stringParts = [$this->addressLine1];

        // if it has an addressLine2
        if($this->addressLine2 != null){
            array_push($stringParts, $this->addressLine2);
        }

        // other mandatory address parts
        array_push($stringParts, $this->townCity, $this->county, $this->postcode);

        // if it has a phoneNumber
        if($this->phoneNumber != null){
            array_push($stringParts, $this->phoneNumber);
        }

        // finally the email
        array_push($stringParts, $this->email);

        // parts comma separated
        return implode(', ', $stringParts);

    }

    /**
     * Getter for the id property of the UserContactDetails object.
     *
     * @return int - The unique identifier of the UserContactDetails.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter for the id property of the UserContactDetails object.
     *
     * @param int $id - The unique identifier of the UserContactDetails.
     *
     */
    public function setId($id) {
        return $this->id = $id;
    }

    /**
     * Getter for the addressLine1 property of the UserContactDetails object.
     *
     * @return string - The first address line for the contact details.
     */
    public function getAddressLine1() {
        return $this->addressLine1;
    }

    /**
     * Setter for the addressLine1 property of the UserContactDetails object.
     *
     * @param string $addressLine1 - The first address line for the contact details.
     */
    public function setAddressLine1($addressLine1) {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * Getter for the addressLine2 property of the UserContactDetails object. Nullable.
     *
     * @return string|null - The second address line for the contact details, or null if it does not exist.
     */
    public function getAddressLine2() {
        return $this->addressLine2;
    }

    /**
     * Setter for the addressLine2 property of the UserContactDetails object. Nullable.
     *
     * @param string|null $addressLine2 - The second address line for the contact details, or null.
     */
    public function setAddressLine2($addressLine2) {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * Getter for the townCity property of the UserContactDetails object.
     *
     * @return string - The town or city for the contact details.
     */
    public function getTownCity(){
        return $this->townCity;
    }

    /**
     * Setter for the townCity property of the UserContactDetails object.
     *
     * @param string $townCity - The town or city for the contact details.
     */
    public function setTownCity($townCity){
        return $this->townCity = $townCity;
    }

    /**
     * Getter for the county property of the UserContactDetails object.
     *
     * @return string - The county for the contact details.
     */
    public function getCounty(){
        return $this->county;
    }

    /**
     * Setter for the county property of the UserContactDetails object.
     *
     * @param string $county - The county for the contact details.
     */
    public function setCounty($county){
        $this->county = $county;
    }

    /**
     * Getter for the postcode property of the UserContactDetails object.
     *
     * @return string - The postcode for the contact details.
     */
    public function getPostcode(){
        return $this->postcode;
    }

    /**
     * Setter for the postcode property of the UserContactDetails object.
     *
     * @param string $postcode - The postcode for the contact details.
     */
    public function setPostcode($postcode){
        $this->postcode = $postcode;
    }

    /**
     * Getter for the phoneNumber property of the UserContactDetails object. Nullable.
     *
     * @return string|null - The phone number for the contact details, or null if it does not exist.
     */
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }

    /**
     * Setter for the phoneNumber property of the UserContactDetails object. Nullable.
     *
     * @param string|null $phoneNumber - The phone number for the contact details, or null.
     */
    public function setPhoneNumber($phoneNumber){
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Getter for the email property of the UserContactDetails object.
     *
     * @return string - The email for the contact details.
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Setter for the email property of the UserContactDetails object.
     *
     * @param string $email - The email for the contact details.
     */
    public function setEmail($email){
        $this->email = $email;
    }
}
