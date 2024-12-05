<?php

/**
 *
 * Contains a number of validation and utility methods for user manipulation.
 *
 */
class UserHelper{

    /**
     * Validates a given username.
     *
     * @param string $username - A username to validate.
     *
     * @return boolean - True if the username is valid, False otherwise.
     */
    public function usernameIsValid($username) {
        // Check if the username only contains letters, numbers, and underscores
        if (preg_match('/^\w+$/', $username)) {
            return true;
        }
    
        // If it contains anything else, return false
        return false;
    }

    /**
     * Validates a given password.
     *
     * @param string $password - A password to validate.
     *
     * @return boolean - True if the password is valid, False otherwise.
     */
    public function passwordIsValid($password) {
        $passwordLength = strlen($password);

        // must be longer than 8
        if($passwordLength < 8){
            return false;
        }

        // must contain at least one digit
        if (!preg_match('/\d/', $password)) {
            return false;
        }

        // must be multi-case
        $passwordLower = strtolower($password);
        $passwordUpper = strtoupper($password);

        if(
            $password == $passwordLower || $password == $passwordUpper
        ){
            return false;
        }
        
        // no spaces
        if(str_contains($password, " ")){
            return false;
        }

        // must contain at least one special character
        $allowed_specials = [
            "!",
            "£",
            "$",
            "%",
            "+"
        ];

        foreach ($allowed_specials as &$char) {
            if(str_contains($password, $char)){
                return true;
            }
        }

        return false;
    }

    /**
     * Generates a unique UUID.
     *
     * @return string - A unique UUID.
     */
    public function generateUuid() {
        // Source: https://www.uuidgenerator.net/dev-corner/php
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = random_bytes(16);
        assert(strlen($data) == 16);
    
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Verifies if a given string can be converted to a timestamp
     *
     * @param string $timestamp - A timestamp to validate.
     * 
     * @return bool - True if $timestamp is valid, else false.
     */
    public function isValidTimestamp($timestamp) {
        // Regular expression to find and extract date in the format YYYY-MM-DD
        $pattern = '/(\d{4}-\d{2}-\d{2})/';

        // Check if the pattern is found in the string
        if (preg_match($pattern, $timestamp, $matches)) {
            // Extract the date from matches array
            $datePart = $matches[1];

            // Validate the extracted date
            $dateParts = explode('-', $datePart);
            if (checkdate((int)$dateParts[1], (int)$dateParts[2], (int)$dateParts[0])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extracts the date part in the format YYYY-MM-DD from a given string.
     *
     * @param string $timestamp - A string from which to extract the date.
     * 
     * @return string|null - The extracted date in YYYY-MM-DD format, or null if no date is found.
     */
    public function extractDate($timestamp) {
        // Regular expression to find and extract date in the format YYYY-MM-DD
        $pattern = '/(\d{4}-\d{2}-\d{2})/';

        // Use preg_match to search for the pattern in the string and capture the date
        if (preg_match($pattern, $timestamp, $matches)) {
            // Return the first captured group which is the date
            return $matches[1];
        }

        // Return null if no date is found
        return null;
    }
}
