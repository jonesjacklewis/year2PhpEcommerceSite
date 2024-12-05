<?php
include_once '../src/config/config.php';
include_once '../src/models/product.php';
include_once '../src/models/productWithQuantity.php';
include_once '../src/models/userContactDetails.php';
include_once '../src/models/topLevelInvoice.php';
include_once '../src/models/roleType.php';

/**
 *
 * Contains a number of methods for database manipulation.
 *
 */
class DatabaseHelper{

    // Instance variable
    private $conn;

    /**
     * The constructor for the database helper.
     * It sets up a database connection.
     * It then creates the necessary tables, and adds the default data.
     */
    public function __construct(){
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->createTables();
        $this->setUpDefaultData();
    }

    /**
     * The destructor for the database helper.
     * It closes the database connection.
     */
    public function __destruct(){
        $this->conn->close();
    }

    /**
     * Checks if a username is in the database.
     *
     * @param string $username - A username to check the existence of.
     *
     * @return boolean - True if the username exists, false otherwise.
     */
    public function checkIfUsernameExists($username) {
        $query = "
        SELECT
        Username
        FROM Users
        WHERE Username = ?;
        ";
    
        // Execute the query with the provided username
        $results = $this->conn->execute_query($query, [$username]);

        if (!$results) {
            // Username does not exists
            return false;
        }

        // Username exist
        return $results->num_rows > 0;
        
    }

    /**
     * Checks if the user's credentials are valid.
     *
     * @param string $username - A username to check.
     * @param string $password - A password to check.
     *
     * @return boolean - True if the credentials are valid, else False.
     */
    public function checkUserCredentials($username, $password) {
        $query = "
        SELECT
        HashedPassword
        FROM Users
        WHERE Username = ?
        LIMIT 1;
        ";
    
        $results = $this->conn->execute_query($query, [$username]);

        if (!$results) {
            return false;
        }

        $hashedPassword = "incorrect";

        foreach($results as $row){
            $hashedPassword = $row["HashedPassword"];
        }

        return password_verify($password, $hashedPassword);
    }
    
    /**
     * Creates a new user.
     *
     * @param string $username - The user's username.
     * @param string $hashedPassword - The user's password, hashed.
     * @param string $roleType - The type of user role to assign. Defaults to "User" if not specified.
     *
     */
    public function createUser($username, $hashedPassword, $roleType = "User"){
        $roleId = $this->getRoleIdByName($roleType);

        $query = "
        INSERT IGNORE
        INTO Users
        (Username, HashedPassword, UserTypeId)
        VALUES
        (?, ?, ?);
        ";

        // Execute the query with the provided parameters
        $this->conn->execute_query($query, [$username, $hashedPassword, $roleId]);
    }

    /**
     * Gets the user type of a user by their username
     *
     * @param string $username - A username to check.
     *
     * @return string - The user's type. Defaults to "N/a" if the username is invalid.
     */
    public function getUserType($username){
        $query = "
        SELECT
        UserTypeId
        FROM Users
        WHERE Username = ?
        LIMIT 1;
        ";

        $results = $this->conn->execute_query($query, [$username]);

        if(!$results){
            return "N/a";
        }

        $userTypeId = -1;

        foreach($results as $row){
            $userTypeId = $row["UserTypeId"];
        }

        if($userTypeId == -1){
            return "N/a";
        }

        $query = "
        SELECT
        UserTypeName
        FROM UserTypes
        WHERE UserTypeId = ?
        LIMIT 1;";

        $results = $this->conn->execute_query($query, [$userTypeId]);

        if(!$results){
            return "N/a";
        }

        $userTypeName = "N/a";

        foreach($results as $row){
            $userTypeName = $row["UserTypeName"];
        }

        return $userTypeName;
    }

    /**
     * Adds a new type of product.
     *
     * @param string $productName - The name of the product.
     * @param string $productDescription - The product's description.
     * @param string $productImage - The product's image as a base64 string.
     * @param int $productPrice - The product's price in pence.
     *
     */
    public function addProduct($productName, $productDescription, $productImage, $productPrice){
        $query = "
        INSERT IGNORE
        INTO Products
        (ProductName, ProductDescription, ProductImageBase64, ProductPricePence)
        VALUES
        (?, ?, ?, ?);
        ";

        // Execute the query with the provided parameters
        $this->conn->execute_query($query, [$productName, $productDescription, $productImage, $productPrice]);
    }

    /**
     * Gets all the products in the database.
     *
     * @return Product[] - An array containing all the products in the database.
     */
    public function getAllProducts(){
        $products = [];

        $query = "
        SELECT
        ProductId, ProductName, ProductDescription, ProductImageBase64, ProductPricePence
        FROM Products;
        ";

        $results = $this->conn->execute_query($query);

        if(!$results){
            return $products;
        }

        foreach($results as $row){
            $id = $row["ProductId"];
            $name = $row["ProductName"];
            $description = $row["ProductDescription"];
            $price = $row["ProductPricePence"];
            $image = $row["ProductImageBase64"];

            $product = new Product($id, $name, $description, $price, $image);

            array_push($products, $product);
        }

        return $products;
    }

    /**
     * Gets a product by its ID.
     *
     * @param int $productId - The ID of the product.
     *
     * @return Product - The product specified by the ID. Defaults to "N/a".
     */
    public function getProductById($productId){

        $query = "
        SELECT
        ProductId, ProductName, ProductDescription, ProductImageBase64, ProductPricePence
        FROM Products
        WHERE ProductId = ?;
        ";

        $results = $this->conn->execute_query($query, [$productId]);

        if(!$results){
            return "N/a";
        }

        foreach($results as $row){
            $id = $row["ProductId"];
            $name = $row["ProductName"];
            $description = $row["ProductDescription"];
            $price = $row["ProductPricePence"];
            $image = $row["ProductImageBase64"];

            return new Product($id, $name, $description, $price, $image);

        }

        return "N/a";
    }

    /**
     * Gets a product by its ID.
     *
     * @param int $productId - The ID of the product.
     * @param int $quantity - The quantity for that product.
     *
     * @return Product - The product specified by the ID with a specific quantity. Defaults to "N/a".
     */
    public function getProductWithQuantityById($productId, $quantity){
        $query = "
        SELECT
        ProductId, ProductName, ProductDescription, ProductImageBase64, ProductPricePence
        FROM Products
        WHERE ProductId = ?;
        ";

        $results = $this->conn->execute_query($query, [$productId]);

        if(!$results){
            return "N/a";
        }

        foreach($results as $row){
            $id = $row["ProductId"];
            $name = $row["ProductName"];
            $description = $row["ProductDescription"];
            $price = $row["ProductPricePence"];
            $image = $row["ProductImageBase64"];

            return new ProductWithQuantity($id, $name, $description, $price, $image, $quantity);

        }

        return "N/a";
    }

    /**
     * Gets the ID for a specific user.
     *
     * @param string $username - The username to find the id of.
     *
     * @return int|string - The user's ID. Defaults to "N/a".
     */
    public function getUserIdByUsername($username){
        $query = "
        SELECT
        UserId
        FROM Users
        WHERE Username = ?
        LIMIT 1;
        ";

        $results = $this->conn->execute_query($query, [$username]);

        if(!$results){
            return "N/a";
        }

        foreach($results as $row){
            return $row["UserId"];
        }

        return "N/a";
    }

    /**
     * Adds contact details for a user.
     *
     * @param int $userId - The ID that the contact details should relate to.
     * @param string $addressLine1 - The first address line for the contact details.
     * @param string $addressLine2 - The second address line for the contact details. Nullable.
     * @param string $townCity - The Town or city for the contact details.
     * @param string $county - The county for the contact details.
     * @param string $postcode - The postcode for the contact details.
     * @param string $phoneNumber - The phoneNumber for the contact details. Nullable.
     * @param string $email - The email for the contact details.
     *
     */
    public function addUserContactDetails(
        $userId,
        $addressLine1,
        $addressLine2,
        $townCity,
        $county,
        $postcode,
        $phoneNumber,
        $email
    ){
        if(strlen($addressLine2) == 0){
            $addressLine2 = null;
        }

        if(strlen($phoneNumber) == 0){
            $phoneNumber = null;
        }


        $query = "
        INSERT INTO
        UserContactDetails
        (UserId, AddressLine1, AddressLine2, TownCity, County, Postcode, PhoneNumber, Email)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?);
        ";

        $this->conn->execute_query($query, [
            $userId,
            $addressLine1,
            $addressLine2,
            $townCity,
            $county,
            $postcode,
            $phoneNumber,
            $email
        ]);

    }

    /**
     * Gets all the contact details for a user.
     *
     * @param int $userId - The ID that the contact details should relate to.
     *
     * @return UseContactDetails[] - An array containing all the contact details for the user.
     */
    public function getUserContactDetailsByUserId($userId){
        $query = "
        SELECT
        *
        FROM UserContactDetails
        WHERE UserId = ?;
        ";

        $results = $this->conn->execute_query($query, [$userId]);

        if(!$results){
            return "N/a";
        }

        $contactDetails = [];

        foreach($results as $row){
           $userContactDetailsId = $row["UserContactDetailsId"];
           $addressLine1 = $row["AddressLine1"];
           $addressLine2 = $row["AddressLine2"];
           $townCity = $row["TownCity"];
           $county = $row["County"];
           $postcode = $row["Postcode"];
           $phoneNumber = $row["PhoneNumber"];
           $email = $row["Email"];

           $contactDetail = new UserContactDetails(
            $userContactDetailsId,
            $addressLine1,
            $addressLine2,
            $townCity,
            $county,
            $postcode,
            $phoneNumber,
            $email
            );

           array_push($contactDetails, $contactDetail);
        }

        return $contactDetails;
    }


    /**
     * Deletes a given contact detail.
     *
     * @param int $id - The ID of the contact detail to be deleted.
     *
     */
    public function deleteContactDetailByContactDetailId($id){
        $query = "
        DELETE FROM
        UserContactDetails
        WHERE UserContactDetailsId = ?;
        ";

        $this->conn->execute_query($query, [$id]);
    }

    /**
     * Creates an entry for an invoice.
     *
     * @param int $invoiceValuePence - The total value of the invoice, in pence.
     * @param int $userContactDetailsId - The ID of the contact detail to be used.
     *
     */
    public function createInvoice($invoiceValuePence, $userContactDetailsId){
        $query = "
        INSERT INTO
        Invoices
        (InvoiceValuePence, UserContactDetailsId)
        VALUES
        (?, ?);
        ";

        $this->conn->execute_query($query, [$invoiceValuePence, $userContactDetailsId]);
    }

    /**
     * Gets the ID of the latest invoice created for a user.
     *
     * @param int $userId - The ID for the user.
     *
     * @return int|string - The ID of the latest invoice. Defaults to "N/a".
     */
    public function getIdForLatestInvoiceByUserId($userId){
        $query = "
        SELECT
        I.InvoiceId AS LatestInvoiceId
        FROM
        Invoices I
        JOIN
        UserContactDetails UCD
        ON
        I.UserContactDetailsId = UCD.UserContactDetailsId
        WHERE
        UCD.UserId = ?
        ORDER BY I.DateTimeCreated
        DESC LIMIT 1;
        ";
    
        $results = $this->conn->execute_query($query, [$userId]);

        if(!$results){
            return "N/a";
        }

        foreach($results as $row){
           return $row["LatestInvoiceId"];
        }

        return "N/a";

    }

    /**
     * Adds an item to the invoice.
     *
     * @param int $invoiceId - The ID of the invoice to add to.
     * @param int $productId - The ID for the product to add.
     * @param int $quantity - The quantity of the product to add.
     *
     */
    public function addInvoiceItem($invoiceId, $productId, $quantity){
        $query = "
        INSERT INTO
        InvoiceDetails
        (InvoiceId, ProductId, Quantity)
        VALUES
        (?, ?, ?);
        ";

        $this->conn->execute_query($query, [$invoiceId, $productId, $quantity]);
    }

    /**
     * Generates a list of TopLevelInvoice used for invoice previews for a specific user.
     *
     * @param int $userId - The user id to get the invoice previews for.
     *
     * @return TopLevelInvoice[] - An array of the invoice previews.
     */
    public function getTopLevelInvoicesByUserId($userId){
        $query = "
        SELECT
        I.InvoiceId, I.InvoiceValuePence, I.DateTimeCreated, P.ProductImageBase64, U.Username
        FROM Invoices I
        JOIN UserContactDetails UCD ON I.UserContactDetailsId = UCD.UserContactDetailsId
        JOIN Users U ON UCD.UserId = U.UserId
        JOIN InvoiceDetails ID ON I.InvoiceId = ID.InvoiceId
        JOIN Products P ON ID.ProductId = P.ProductId
        JOIN (
            SELECT
            ID.InvoiceId, MAX(P.ProductPricePence) AS MaxPrice
            FROM InvoiceDetails ID
            INNER JOIN
            Products P ON ID.ProductId = P.ProductId
            GROUP BY ID.InvoiceId
        ) AS MaxPrices
        ON ID.InvoiceId = MaxPrices.InvoiceId
        AND P.ProductPricePence = MaxPrices.MaxPrice
        WHERE U.UserId= ?
        GROUP BY I.InvoiceId, P.ProductId
        ORDER BY I.InvoiceId, P.ProductId DESC;
        ";

        $results = $this->conn->execute_query($query, [$userId]);

        if(!$results){
            return [];
        }

        $invoices = [];

        foreach($results as $row){
           $invoiceId = $row['InvoiceId'];
           $invoiceValuePence = $row["InvoiceValuePence"];
           $dateTimeCreated = $row["DateTimeCreated"];
           $productImageBase64 = $row["ProductImageBase64"];
           $username = $row["Username"];

           array_push($invoices, new TopLevelInvoice(
            $invoiceId,
            $invoiceValuePence,
            $dateTimeCreated,
            $productImageBase64,
            $username
            ));
        }

        return $invoices;
    }

    /**
     * Generates a list of TopLevelInvoice used for invoice previews.
     *
     * @return TopLevelInvoice[] - An array of the invoice previews.
     */
    public function getAllTopLevelInvoices(){
        $query = "
        SELECT
        I.InvoiceId, I.InvoiceValuePence, I.DateTimeCreated, P.ProductImageBase64, U.Username
        FROM Invoices I
        JOIN UserContactDetails UCD
        ON I.UserContactDetailsId = UCD.UserContactDetailsId
        JOIN Users U
        ON UCD.UserId = U.UserId
        JOIN InvoiceDetails ID
        ON I.InvoiceId = ID.InvoiceId
        JOIN Products P
        ON ID.ProductId = P.ProductId
        JOIN (
            SELECT
            ID.InvoiceId, MAX(P.ProductPricePence) AS MaxPrice
            FROM InvoiceDetails ID
            INNER JOIN
            Products P
            ON ID.ProductId = P.ProductId
            GROUP BY ID.InvoiceId
        ) AS MaxPrices
        ON ID.InvoiceId = MaxPrices.InvoiceId
        AND P.ProductPricePence = MaxPrices.MaxPrice
        GROUP BY I.InvoiceId, P.ProductId
        ORDER BY I.InvoiceId, P.ProductId DESC
        ";

        $results = $this->conn->execute_query($query, []);

        if(!$results){
            return [];
        }

        $invoices = [];

        foreach($results as $row){
           $invoiceId = $row['InvoiceId'];
           $invoiceValuePence = $row["InvoiceValuePence"];
           $dateTimeCreated = $row["DateTimeCreated"];
           $productImageBase64 = $row["ProductImageBase64"];
           $username = $row["Username"];

           array_push($invoices, new TopLevelInvoice(
            $invoiceId,
            $invoiceValuePence,
            $dateTimeCreated,
            $productImageBase64,
            $username
            ));
        }

        return $invoices;
    }

    /**
     * Gets a specific TopLevelInvoice by invoice id.
     *
     * @param int $invoiceId - The ID of the invoice to build the preview from.
     *
     * @return TopLevelInvoice|string - The specific top level invoice. Defaults to "N/a"
     */
    public function getTopLevelInvoiceByInvoiceId($invoiceId){
        $query = "
        SELECT
        I.InvoiceId, I.InvoiceValuePence, I.DateTimeCreated, P.ProductImageBase64, U.Username
        FROM Invoices I
        JOIN UserContactDetails UCD
        ON I.UserContactDetailsId = UCD.UserContactDetailsId
        JOIN Users U
        ON UCD.UserId = U.UserId
        JOIN InvoiceDetails ID
        ON I.InvoiceId = ID.InvoiceId
        JOIN Products P
        ON ID.ProductId = P.ProductId
        JOIN (
            SELECT
            ID.InvoiceId, MAX(P.ProductPricePence) AS MaxPrice
            FROM InvoiceDetails ID
            INNER JOIN
            Products P
            ON ID.ProductId = P.ProductId
            GROUP BY ID.InvoiceId
        ) AS MaxPrices
        ON ID.InvoiceId = MaxPrices.InvoiceId
        AND P.ProductPricePence = MaxPrices.MaxPrice
        WHERE I.InvoiceId = ?
        GROUP BY I.InvoiceId, P.ProductId
        ORDER BY I.InvoiceId, P.ProductId DESC
        ";

        $results = $this->conn->execute_query($query, [$invoiceId]);

        if(!$results){
            return "N/a";
        }


        foreach($results as $row){
           $invoiceId = $row['InvoiceId'];
           $invoiceValuePence = $row["InvoiceValuePence"];
           $dateTimeCreated = $row["DateTimeCreated"];
           $productImageBase64 = $row["ProductImageBase64"];
           $username = $row["Username"];

           return new TopLevelInvoice($invoiceId, $invoiceValuePence, $dateTimeCreated, $productImageBase64, $username);
        }

        return "N/a";
    }

    /**
     * Gets all the products and respective quantities for a specific invoice.
     *
     * @param int $invoiceId - The id of the invoice to get the product details for.
     *
     * @return ProductWithQuantity[] - An array of the products with the quantities.
     */
    public function getProductAndQuantityByInvoiceId($invoiceId){
       
        $query = "
        SELECT DISTINCT
        P.ProductId, P.ProductName, P.ProductDescription, P.ProductImageBase64, P.ProductPricePence, ID.Quantity
        FROM Products P
        JOIN InvoiceDetails ID
        ON P.ProductId = ID.ProductId
        WHERE ID.InvoiceId = ?
        ORDER BY P.ProductPricePence DESC;
        ";

        $results = $this->conn->execute_query($query, [$invoiceId]);

        if(!$results){
            return [];
        }

        $products = [];

        foreach($results as $row){
            $id = $row["ProductId"];
            $name = $row["ProductName"];
            $description = $row["ProductDescription"];
            $price = $row["ProductPricePence"];
            $image = $row["ProductImageBase64"];
            $quantity = $row["Quantity"];

           array_push($products, new ProductWithQuantity($id, $name, $description, $price, $image, $quantity));
        }

        return $products;

    }

    /**
     * Gets the contact details for a specific invoice.
     *
     * @param int $invoiceId - The id of the invoice to get the contact details for.
     *
     * @return UserContactDetails|string - The contact details for that invoice. Defaults to "N/a".
     */
    public function getUserContactDetailsByInvoiceId($invoiceId){
        $query = "
        SELECT
        UCD.UserContactDetailsId,
        UCD.AddressLine1,
        UCD.AddressLine2,
        UCD.TownCity,
        UCD.County,
        UCD.Postcode,
        UCD.PhoneNumber,
        UCD.Email
        FROM UserContactDetails UCD
        JOIN Invoices I
        ON UCD.UserContactDetailsId = I.UserContactDetailsId
        WHERE I.InvoiceId = ?
        LIMIT 1;
        ";

        $results = $this->conn->execute_query($query, [$invoiceId]);

        if(!$results){
            return "N/a";
        }

        foreach($results as $row){
           $userContactDetailsId = $row["UserContactDetailsId"];
           $addressLine1 = $row["AddressLine1"];
           $addressLine2 = $row["AddressLine2"];
           $townCity = $row["TownCity"];
           $county = $row["County"];
           $postcode = $row["Postcode"];
           $phoneNumber = $row["PhoneNumber"];
           $email = $row["Email"];

           return new UserContactDetails(
            $userContactDetailsId,
            $addressLine1,
            $addressLine2,
            $townCity,
            $county,
            $postcode,
            $phoneNumber,
            $email
            );
        }

        return "N/a";
    }

    /**
     * Checks if a specific user has invoices.
     *
     * @param string $username - The username to check.
     *
     * @return boolean - True if the user has invoices, else False.
     */
    public function checkIfUsernameHasInvoices($username) {
        $query = "
        SELECT
        I.InvoiceId
        FROM Invoices I
        JOIN UserContactDetails UCD
        ON I.UserContactDetailsId = UCD.UserContactDetailsId
        JOIN Users U
        ON UCD.UserId = U.UserId
        WHERE U.Username = ?;
        ";
    
        // Execute the query with the provided username
        $results = $this->conn->execute_query($query, [$username]);

        if (!$results) {
            // Has no invoices
            return false;
        }

        // Has invoices
        return $results->num_rows > 0;
        
    }

    /**
     * Gets the ID of a user role by the role name.
     *
     * @return RoleType[] - A list of all the role types.
     */
    public function getUserRoleTypes(){
        $query = "
        SELECT
        UserTypeId, UserTypeName
        FROM UserTypes;
        ";

        $results = $this->conn->execute_query($query, []);

        if(!$results){
            return [];
        }

        $roleTypes = [];

        foreach($results as $row){
            $id = $row["UserTypeId"];
            $name = $row["UserTypeName"];

            array_push($roleTypes, new RoleType($id, $name));
        }

        return $roleTypes;
    }

    /**
     * Creates the database tables
     */
    private function createTables(){
        // Read the file src\database\create_table.sql
        $sql = file_get_contents("../src/database/create_table.sql");
    
        // Execute the multi query
        if ($this->conn->multi_query($sql)) {
            do {
                // Store first result set if any
                if ($result = $this->conn->store_result()) {
                    while ($row = $result->fetch_row()) {
                        ($row); // evaluated to nothingness, to remove unused variable
                    }
                    $result->free();
                }
                // Check if there are more result sets and move to the next one
            } while ($this->conn->more_results() && $this->conn->next_result());
        }
    }

    /**
     * Sets up three default user types: Admin; User; and Guest.
     */
    private function setUpDefaultUserTypes(){
        $queries = [
            "INSERT IGNORE INTO UserTypes (UserTypeName) VALUES ('Admin');",
            "INSERT IGNORE INTO UserTypes (UserTypeName) VALUES ('User');",
            "INSERT IGNORE INTO UserTypes (UserTypeName) VALUES ('Guest');"
        ];

        foreach ($queries as &$query) {
            $this->conn->query($query);
        }
    }

    /**
     * Creates a default admin user.
     */
    private function setUpDefaultAdminUser(){
        $username = "DefaultAdmin";
        $password = "HelloWorld123!";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $roleType = "Admin";

        $this->createUser($username, $hashedPassword, $roleType);
    }

    /**
     * Sets up the websites default data.
     */
    private function setUpDefaultData(){
        $this->setUpDefaultUserTypes();
        $this->setUpDefaultAdminUser();
    }

    /**
     * Gets the ID of a user role by the role name.
     *
     * @param string $roleName - The name of the role.
     *
     * @return int - The ID of the role.
     */
    private function getRoleIdByName($roleName){
        $query = "
        SELECT
        UserTypeId
        FROM UserTypes
        WHERE UserTypeName = ?
        LIMIT 1;
        ";

        $result = $this->conn->execute_query($query, [$roleName]);

        foreach($result as $row){
            return $row["UserTypeId"];
        }
    }

}
