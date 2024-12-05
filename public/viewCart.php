<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

// Constant for the Location header
$LOCATION = 'Location: ';

include_once '../src/utils/databaseHelper.php';
$dbHelper = new DatabaseHelper();

// Allows the user to remove contact details
if (isset($_POST["contactDetailToDelete"])) {
    $contactDetailToDelete = $_POST["contactDetailToDelete"];

    $dbHelper->deleteContactDetailByContactDetailId($contactDetailToDelete);
}

if (isset($_SESSION['BASKET'])) {
    $username = $_SESSION['LOGGED_IN_USER'];
    $basket = $_SESSION['BASKET'];

    if (isset($_POST["contactDetailToUseForInvoice"]) && isset($_SESSION['LOGGED_IN_USER'])) {
        $contactDetailId = $_POST["contactDetailToUseForInvoice"];
        $username = $_SESSION['LOGGED_IN_USER'];

        $userId = $dbHelper->getUserIdByUsername($username);

        $productsWithQuantities = [];
        $invoiceValuePence = 0;

        foreach (array_keys($basket) as $productId) {
            $quantity = $basket[$productId];

            $productWithQuantity = $dbHelper->getProductWithQuantityById($productId, $quantity);

            if ($productWithQuantity != "N/a") { // Only adds valid products
                array_push($productsWithQuantities, $productWithQuantity);
            }
        }

        foreach($productsWithQuantities as $product){
            // Calculates the price
            $invoiceValuePence += $product->getQuantity() * $product->getPricePence();
        }

        // Creates the invoice
        $dbHelper->createInvoice($invoiceValuePence, $contactDetailId);

        $invoiceId = $dbHelper->getIdForLatestInvoiceByUserId($userId);

        // Adds items to the invoice
        foreach($productsWithQuantities as $product){
            $dbHelper->addInvoiceItem($invoiceId, $product->getId(), $product->getQuantity());
        }

        unset($_SESSION["BASKET"]);
        header($LOCATION . "/viewInvoice.php?id=$invoiceId");

    }

    if (isset($_POST['idToRemove'])) {
        unset($basket[$_POST['idToRemove']]); // clear the item from the basket
        $_SESSION['BASKET'] = $basket;
    }

    $productsWithQuantities = [];



    foreach (array_keys($basket) as $productId) {
        $quantity = $basket[$productId];

        $productWithQuantity = $dbHelper->getProductWithQuantityById($productId, $quantity);

        if ($productWithQuantity != "N/a") {
            array_push($productsWithQuantities, $productWithQuantity);
        }
    }

    // Redirects if there are no valid products
    if (sizeof($productsWithQuantities) == 0) {
        header($LOCATION. "/");
    }


    $contactDetails = [];

    if (isset($_SESSION['LOGGED_IN_USER'])) {
        $username = $_SESSION['LOGGED_IN_USER'];

        $userId = $dbHelper->getUserIdByUsername($username);

        $contactDetails = $dbHelper->getUserContactDetailsByUserId($userId);
    }
} else {
    // if no basket session variable, redirects to home.
    // shouldn't be possible but just in case.
    header($LOCATION. "/");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | View Cart</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include_once '../templates/header.php'; ?>



    <main class="view-product-wrapper">
        <div class='row'>
            <div class='column'>
                <div class='left-column'>
                    <?php foreach ($productsWithQuantities as $product) : ?>
                        <div class="basket-card">
                            <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>">
                            <div class="item-info">
                                <div class="item-name"><?php echo $product->getName(); ?></div>
                                <div class="item-price">Price: £<?php echo $product->getPricePence() / 100; ?></div>
                                <div class="item-quantity">Quantity: <?php echo $product->getQuantity(); ?></div>
                                <div class="item-price">
                                    Subtotal: £<?php
                                    echo ($product->getPricePence() * $product->getQuantity()) / 100;
                                    ?>
                                </div>
                            </div>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input
                                type="text"
                                name="idToRemove"
                                id="idToRemove"
                                value="<?php echo $product->getId(); ?>"
                                style="visibility:hidden;display:none;"
                                >
                                <button class="remove-button">Remove</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class='column'>
                <div class='right-column'>

                    <p>Order Total: £
                        <span style="font-weight:bold;">
                    <?php
                        $total = 0;
                        foreach ($productsWithQuantities as $product) {
                            $total += $product->getPricePence() * $product->getQuantity();
                        }
                        
                        $total /= 100;
                        
                        echo $total;?>
                        </span>
                    </p>

                    <?php if (sizeof($contactDetails) == 0 && str_starts_with($username, "guest")) : ?>
                        <p>You are currently a guest, and no contact details are found. Please add one.</p>
                        <form method="post" action="/addContactDetails.php">
                            <input type="submit" value="Add Contact Details">
                        </form>
                        <?php elseif (sizeof($contactDetails) == 0) : ?>
                        <p>No contact details are found, please add one.</p>
                        <form method="post" action="/addContactDetails.php">
                            <input type="submit" value="Add Contact Details">
                        </form>
                    <?php else : ?>
                        <p>
                            <?php
                            echo sizeof($contactDetails) == 1 ? "This is" : "These are";
                            ?> the contact detail
                            <?php
                            echo sizeof($contactDetails) == 1 ? "" : "s";
                            ?> we have on file:
                        </p>

                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <select name="selectedContactDetails" id="selectedContactDetails">
                                <?php foreach ($contactDetails as $contactDetail) : ?>
                                    <option value="<?php echo $contactDetail->getId(); ?>">
                                        <?php echo $contactDetail; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="submit" value="Select">
                        </form>

                        <?php if (isset($_POST['selectedContactDetails'])) : ?>

                            <?php
                           
                           
                            $contactDetail = array_values(array_filter($contactDetails, function ($cd) {
                                return $cd->getId() == $_POST['selectedContactDetails'];
                            }))[0];
                            ?>

                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input
                                type="text"
                                name="contactDetailToDelete"
                                id="contactDetailToDelete"
                                style="display: none;"
                                value="<?php echo $contactDetail->getId(); ?>">

                                <input type="submit" value="Delete Contact Detail">
                            </form>


                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input
                                type="text"
                                name="selectedContactDetails"
                                id="selectedContactDetails"
                                style="display: none;"
                                value="<?php echo $contactDetail->getId(); ?>">

                                <input
                                type="text"
                                name="contactDetailToUse"
                                id="contactDetailToUse"
                                style="display: none;"
                                value="<?php echo $contactDetail->getId(); ?>">

                                <input type="submit" value="Use Contact Detail">
                            </form>

                            <?php if (isset($_POST['contactDetailToUse'])) : ?>

                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input
                                    type="text"
                                    name="contactDetailToUseForInvoice"
                                    id="contactDetailToUseForInvoice"
                                    style="display: none;"
                                    value="<?php echo $contactDetail->getId(); ?>">

                                    <input type="submit" value="Generate Invoice">
                                </form>

                            <?php endif; ?>



                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
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
