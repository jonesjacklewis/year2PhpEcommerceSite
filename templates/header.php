<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

// we will treat a logged out user, when the username = "N/a"

$username = "N/a";
$showInvoicesButton = false;

if (isset($_SESSION['LOGGED_IN_USER'])) {
    $username = $_SESSION['LOGGED_IN_USER'];

    $isAdmin = $_SESSION["LOGGED_IN_USER_IS_ADMIN"];


    $basketCount = 0;

    if (isset($_SESSION['BASKET'])) {
        $basket = $_SESSION['BASKET'];

        $basketCount = sizeof($basket);
    }

    include_once '../src/utils/databaseHelper.php';
    $dbHelper = new DatabaseHelper();

    if(!$isAdmin){
        $showInvoicesButton = $dbHelper->checkIfUsernameHasInvoices($username);
    }else{
        $showInvoicesButton = true;
    }
}

?>


<header>
    <h1>eCommerce Site</h1>
    <form method="post" action="index.php"><button type="submit">Home</button></form>
    <?php if ($username == "N/a" || str_starts_with($username, "guest")) : ?>
        <form method="post" action="logIn.php"><button type="submit">Log In</button></form>
        <form method="post" action="createUser.php"><button type="submit">Create User</button></form>
    <?php elseif ($username != "N/a" && !str_starts_with($username, "guest")) : ?>
        <form method="post" action="logOut.php"><button type="submit">Log Out</button></form>

        <?php if ($showInvoicesButton) : ?>
            <form method="post" action="viewInvoices.php"><button type="submit">View Invoices</button></form>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ($username != "N/a" && !$isAdmin) : ?>
        <form method="post" action="addContactDetails.php">
            <button type="submit">Add Contact Details</button>
        </form>
        <?php if ($basketCount > 0) : ?>
            <form method="post" action="viewCart.php">
                <button type="submit">View Cart (<?php echo $basketCount ?>)</button>
            </form>
        <?php else : ?>
            <form method="post" action="viewCart.php"><button type="submit" disabled>View Cart</button></form>
        <?php endif; ?>
    <?php elseif ($username != "N/a" && $isAdmin) : ?>
        <form method="post" action="addProduct.php"><button type="submit">Add Product</button></form>
        <form method="post" action="createUser.php"><button type="submit">Create User</button></form>
    <?php endif; ?>

</header>
