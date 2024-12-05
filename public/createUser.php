<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

if (isset($_SESSION['LOGGED_IN_USER'])) {
    include_once '../src/utils/databaseHelper.php';
    $dbHelper = new DatabaseHelper();

    $username = $_SESSION['LOGGED_IN_USER'];
    $isAdmin = $_SESSION["LOGGED_IN_USER_IS_ADMIN"];

    $roleTypes = $dbHelper->getUserRoleTypes();

    $roleTypes = array_filter($roleTypes, function ($roleType) {
        // where roleType->getIsGuest() = false
        return !$roleType->getIsGuest();
    });
} else {
    $isAdmin = false;
    $roleTypes = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include_once '../templates/header.php'; ?>

    <main>
        <h2>Create User</h2>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="formSubmitted" value="set" style="display: none;">
            <label for="inpUsername">Username: </label>
            <input type="text" name="inpUsername" id="inpUsername" required>

            <br>

            <label for="password1">Password: </label>
            <input type="password" name="password1" id="password1" required>

            <br>

            <label for="password2">Confirm Password: </label>
            <input type="password" name="password2" id="password2" required>

            <br>

            <!-- Allows admins to create another admin user -->
            <?php if ($isAdmin) : ?>
                <label for="inpRoleType">Role Type: </label>

                <select name="inpRoleType" id="inpRoleType">
                    <?php foreach ($roleTypes as $roleType) : ?>
                        <option value="<?php echo $roleType->getName(); ?>">
                            <?php echo $roleType->getName(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <br>

            <?php endif; ?>

            <input type="submit" value="Create User">
        </form>

        <?php

        include_once '../src/utils/databaseHelper.php';
        include_once '../src/utils/userHelper.php';

        function performCreateUser()
        {
            $dbHelper = new DatabaseHelper();
            $uHelper = new UserHelper();

            if (!isset($_POST["inpUsername"])) {
                echo "<p class='error'>Username not set.</p>";
                return;
            }

            if (!isset($_POST["password1"])) {
                echo "<p class='error'>Password not set.</p>";
                return;
            }

            if (!isset($_POST["password2"])) {
                echo "<p class='error'>Password confirm not set.</p>";
                return;
            }

            $username = $_POST["inpUsername"];

            if (!$uHelper->usernameIsValid($username)) {
                echo "<p class='error'>Username is not valid.</p>";
                return;
            }

            // As there is special logic for guest users
            // Do not allow the creation of users that start with guest
            if (str_starts_with($username, "guest")) {
                echo "<p class='error'>Username cannot be prepended with the string 'guest'.</p>";
                return;
            }

            $password = $_POST["password1"];
            $passwordConfirm = $_POST["password2"];

            // passwords should match
            if ($password != $passwordConfirm) {
                echo "<p class='error'>Passwords do not match.</p>";
                return;
            }

            if (!$uHelper->passwordIsValid($password)) {
                echo "<p class='error'>Password is Invalid. Must be 8 or more characters including mix cased letters, numbers, and one of !£$%+</p>";
                return;
            }

            if ($dbHelper->checkIfUsernameExists($username)) {
                echo "<p class='error'>Username already exists.</p>";
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // If the role type option is selected
            if (isset($_POST["inpRoleType"])) {
                $roleType = $_POST["inpRoleType"];
                $dbHelper->createUser($username, $hashedPassword, $roleType);
            } else {
                $dbHelper->createUser($username, $hashedPassword);
            }



            header('Location: ' . "/login.php");
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["formSubmitted"])) {
            performCreateUser();
            unset($_POST["formSubmitted"]);
        }
        ?>

    </main>

    <?php include_once '../templates/footer.php'; ?>

    <script>
        // stops form resubmissions, not needed as such but helps improve user experience
        // https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlrs
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>
