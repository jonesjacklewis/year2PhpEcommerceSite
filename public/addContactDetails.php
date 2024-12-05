<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

if (isset($_SESSION['LOGGED_IN_USER'])) {
    $username = $_SESSION['LOGGED_IN_USER']; // gets the username from the session
}else{ // shouldn't be possible, but just in case
    header("Location: /");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | Add Contact Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
   <?php include_once '../templates/header.php'; ?>
   
    <main>
        <h2>Add Contact Details</h2>

        <!--
            PHP_SELF will target the current page
        -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!--
                Hidden field makes it more difficult to brute force the form.
            -->
            <input type="text" name="formSubmitted" value="set" style="display: none;">

            <label for="inpAddressLine1" class="required">Address Line 1: </label>
            <input type="text" name="inpAddressLine1" id="inpAddressLine1" required>

            <br>

            <label for="inpAddressLine2">Address Line 2: </label>
            <input type="text" name="inpAddressLine2" id="inpAddressLine2">

            <br>

            <label for="inpTownCity" class="required">Town/City: </label>
            <input type="text" name="inpTownCity" id="inpTownCity" required>

            <br>

            <label for="inpCounty" class="required">County: </label>
            <input type="text" name="inpCounty" id="inpCounty" required>

            <br>

            <label for="inpPostcode" class="required">Postcode: </label>
            <input type="text" name="inpPostcode" id="inpPostcode" required>

            <br>

            <label for="inpTelephone">Telephone: </label>
            <input type="tel" name="inpTelephone" id="inpTelephone">

            <br>

            <label for="inpEmail" class="required">Email: </label>
            <input type="email" name="inpEmail" id="inpEmail" required>

            <br>
            

            <input type="submit" value="Add Contact Details">
        </form>

        <?php

            // Include class files
            include_once '../src/utils/databaseHelper.php';
            include_once '../src/utils/contactDetailsHelper.php';

            function performAddContactDetails($username){
                // Create db instance
                $dbHelper = new DatabaseHelper();
                // Create ContactDetails instance
                $cdHelper = new ContactDetailsHelper();

                // Get Address Line 1

                $addressLine1 = "";

                if(isset($_POST["inpAddressLine1"])){
                    $addressLine1 = $_POST["inpAddressLine1"];
                }

                // Validate it
                if(strlen($addressLine1) == 0){
                    echo "<p class='error'>Address Line 1 not set.</p>";
                    return;
                }

                // Get Address Line 2
                $addressLine2 = "";

                if(isset($_POST["inpAddressLine2"])){
                    $addressLine2 = $_POST["inpAddressLine2"];
                }

                // Get Town/City
                $townCity = "";

                if(isset($_POST["inpTownCity"])){
                    $townCity = $_POST["inpTownCity"];
                }

                // Validate it
                if(strlen($townCity) == 0){
                    echo "<p class='error'>Town/City not set.</p>";
                    return;
                }

                // Get County
                $county = "";

                if(isset($_POST["inpCounty"])){
                    $county = $_POST["inpCounty"];
                }

                // Validate it
                if(strlen($county) == 0){
                    echo "<p class='error'>County not set.</p>";
                    return;
                }

                // Get Postcode
                $postcode = "";

                if(isset($_POST["inpPostcode"])){
                    $postcode = $_POST["inpPostcode"];
                }

                // Validate it on length
                if(strlen($postcode) == 0){
                    echo "<p class='error'>Postcode not set.</p>";
                    return;
                }

                // Validate on form
                if(!$cdHelper->postcodeIsValid($postcode)){
                    echo "<p class='error'>Invalid Postcode.</p>";
                    return;
                }

                // Get Telephone
                $telephone = "";

                if(isset($_POST["inpTelephone"])){
                    $telephone = $_POST["inpTelephone"];
                }

                echo $telephone;

                // validate it on form
                if($telephone != "" && !$cdHelper->telephoneIsValid($telephone)){
                    echo "<p class='error'>Invalid Telephone number.</p>";
                    return;
                }

                 // Get email
                 $email = "";

                 if(isset($_POST["inpEmail"])){
                     $email = $_POST["inpEmail"];
                 }
 
                 // Validate it on length
                 if(strlen($email) == 0){
                     echo "<p class='error'>Email not set.</p>";
                     return;
                 }
 
                 // Validate on form
                 if(!$cdHelper->emailIsValid($email)){
                     echo "<p class='error'>Invalid Email.</p>";
                     return;
                 }

                 // Get User Id
                 $userId = $dbHelper->getUserIdByUsername($username);

                 // validate id
                 if($userId == "N/a"){
                    echo "<p class='error'>Error getting user information.</p>";
                     return;
                 }

                 // Add Contact Details
                 $dbHelper->addUserContactDetails(
                    $userId,
                    $addressLine1,
                    $addressLine2,
                    $townCity,
                    $county,
                    $postcode,
                    $telephone,
                    $email
                );
                
                // Redirect the index page
                header('Location: '."/");
            }
            

            // On a post request, when the form has been complete
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["formSubmitted"])) {

                // for guest users
                if(str_starts_with($username, "guest")){
                    include_once '../src/utils/databaseHelper.php';
                    include_once '../src/utils/userHelper.php';

                    $dbHelper = new DatabaseHelper();
                    $uHelper = new UserHelper();

                    // randomly generate a password
                    $password = $uHelper->generateUuid();

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // create the db entry for the guest user
                    $dbHelper->createUser($username, $hashedPassword, "Guest");
                }
                
                // call the method
                performAddContactDetails($username);
                unset($_POST["formSubmitted"]);
            }
        ?>

    </main>

   <?php include_once '../templates/footer.php'; ?>

   <script>
    // stops form resubmissions, not needed as such but helps improve user experience
    // https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlrs
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>

</html>
