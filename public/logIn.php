<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | Log In</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
   <?php include_once '../templates/header.php'; ?>
   
    <main>
        <h2>Log In</h2>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="formSubmitted" value="set" style="display: none;">
            <label for="inpUsername">Username: </label>
            <input type="text" name="inpUsername" id="inpUsername" required>

            <br>

            <label for="password1">Password: </label>
            <input type="password" name="password" id="password" required>

            <br>

           
            <input type="submit" value="Log In">
        </form>

        <?php

            include_once '../src/utils/databaseHelper.php';

            function performLogIn(){
                $dbHelper = new DatabaseHelper();

                if(!isset($_POST["inpUsername"])){
                    echo "<p class='error'>Username not set.</p>";
                    return;
                }

                if(!isset($_POST["password"])){
                    echo "<p class='error'>Password not set.</p>";
                    return;
                }


                $username = $_POST["inpUsername"];

                if(!$dbHelper->checkIfUsernameExists($username)){
                    echo "<p class='error'>Invalid Credentials.</p>";
                    return;
                }

                $password = $_POST["password"];

                if(!$dbHelper->checkUserCredentials($username, $password)){
                    echo "<p class='error'>Invalid Credentials.</p>";
                    return;
                }
                
                $userType = $dbHelper->getUserType($username);

                if($userType == "N/a"){
                    echo "<p class='error'>Invalid Credentials.</p>";
                    return;
                }

                $_SESSION['LOGGED_IN_USER'] = $username;
                $_SESSION["LOGGED_IN_USER_IS_ADMIN"] = strtolower($userType) == 'admin';

                if($_SESSION["LOGGED_IN_USER_IS_ADMIN"]){
                    session_unset(); // makes sure the basket session var is cleared
                    $_SESSION['LOGGED_IN_USER'] = $username;
                    $_SESSION["LOGGED_IN_USER_IS_ADMIN"] = strtolower($userType) == 'admin';
                }

                header('Location: '."/");
            }
            

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["formSubmitted"])) {
                performLogIn();
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
