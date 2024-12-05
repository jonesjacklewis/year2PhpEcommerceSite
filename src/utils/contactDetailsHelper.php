<?php

/**
 *
 * Contains a number of validation methods for contact details.
 *
 */
class ContactDetailsHelper{

    /**
     * Uses a regex to validate a UK postcode
     *
     * @param string $postcode - A postcode to validate.
     *
     * @return boolean - True if the postcode is valid, false otherwise.
     */
    public function postcodeIsValid($postcode) {

        $upper = strtoupper($postcode);

        // https://en.wikipedia.org/wiki/Postcodes_in_the_United_Kingdom#Validation

        $postcodeRegex = '/^
        (
            ([A-Z]{1,2}\d[A-Z0-9]?|ASCN|STHL|TDCU|BBND|[BFS]IQQ|PCRN|TKCA)\s?\d[A-Z]{2}|
            BFPO\s?\d{1,4}|
            (KY\d|MSR|VG|AI)\-?\d{4}|
            [A-Z]{2}\s?\d{2}|
            GE\s?CX|
            GIR\s?0A{2}|
            SAN\s?TA1
        )$
        /x';

        if (preg_match($postcodeRegex, $upper)) {
            return true;
        }
        return false;
    }

    /**
     * Validates a UK phone number
     *
     * @param string $telephone - A telephone number to validate.
     *
     * @return boolean - True if the telephone number is valid, false otherwise.
     */
    public function telephoneIsValid($telephone){
        if(!preg_match('/^\+?[0-9 ]+$/', $telephone)){
            return false;
        }

        return strlen($telephone) < 13 && strlen($telephone) >= 3;
    }

    /**
     * Validates an email address.
     *
     * @param string $email - An email address to validate.
     *
     * @return boolean - True if the email is valid, false otherwise.
     */
    public function emailIsValid($email){
        // email must contain at least one full-stop.
        $containsPeriod = strpos($email, ".");

        if(!$containsPeriod){
            return false;
        }

        // email must contain at least one at sign.
        $containsAt = strpos($email, "@");

        if(!$containsAt){
            return false;
        }

        return true;
    }

}
