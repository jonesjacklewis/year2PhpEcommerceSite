<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

if (isset($_SESSION['LOGGED_IN_USER'])) {
    $userId = $_SESSION['LOGGED_IN_USER'];
    $isAdmin = $_SESSION["LOGGED_IN_USER_IS_ADMIN"];
}else{
    $userId = "guest";
    $isAdmin = false;
}


include_once '../src/utils/databaseHelper.php';
$dbHelper = new DatabaseHelper();

if (!isset($_GET['id'])) {
    header('Location: ' . "/");
}

$invoiceId = $_GET['id'];

$productsWithQuantities = $dbHelper->getProductAndQuantityByInvoiceId($invoiceId);

$topLevelInvoice = $dbHelper->getTopLevelInvoiceByInvoiceId($invoiceId);

$invoiceContactDetails = $dbHelper->getUserContactDetailsByInvoiceId($invoiceId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | View Invoice</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>


<body>
    <?php include_once '../templates/header.php'; ?>

    <h2 style="text-align: center">Invoice Details</h2>

    <main class="view-product-wrapper">

        <div class='row'>

            <div class='column'>

                <div class='left-column'>
                    <h3>Product Overview</h3>
                    <?php foreach ($productsWithQuantities as $product) : ?>
                        <div class="basket-card">
                            <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>">
                            <div class="item-info">
                                <div class="item-name"><?php echo $product->getName(); ?></div>
                                <div class="item-price">Price: £<?php echo $product->getPricePence() / 100; ?></div>
                                <div class="item-quantity">Quantity: <?php echo $product->getQuantity(); ?></div>
                                <div class="item-price">
                                    Subtotal: £
                                    <?php echo ($product->getPricePence() * $product->getQuantity()) / 100; ?>
                                </div>
                            </div>
                            <form action="/viewProduct.php?id=<?php echo $product->getId(); ?>" method="post">
                                <input type="submit" value="View Details">
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <div class='column'>
                <div class='right-column'>
                    <h3>Invoice Details</h3>

                    <?php if ($isAdmin) : ?>
                        <p>
                            <span style="font-weight:bold">
                            Invoice User:
                            </span>
                        <?php
                        echo $topLevelInvoice->getUsername();
                        ?>
                        </p>
                    <?php endif; ?>
                    <p>
                        <span style="font-weight:bold">
                        Invoice Date:
                        </span>
                        <?php
                        echo $topLevelInvoice->getDateTimeCreated();
                        ?>
                    </p>
                    <p>
                        <span style="font-weight:bold">
                        Invoice Total:
                        </span> £
                        <?php
                        echo $topLevelInvoice->getInvoiceValuePence() / 100;
                        ?>
                    </p>

                    <h4>Invoice Contact Details</h4>

                    <div class="product-card">
                        <div class="product-info">
                            <h5>
                                <?php echo $invoiceContactDetails->getAddressLine1() ?>
                            </h5>
                            <?php if($invoiceContactDetails->getAddressLine2() != null): ?>
                            <p>
                                <span style="font-weight:bold">
                                Address Line 2:
                                </span>
                                <?php
                                echo $invoiceContactDetails->getAddressLine2();
                                ?>
                            </p>
                            <?php endif;?>
                            <p>
                                <span style="font-weight:bold">
                                Town/City:
                                </span>
                                <?php echo $invoiceContactDetails->getTownCity();
                                ?>
                            </p>
                            <p>
                                <span style="font-weight:bold">
                                County:
                                </span>
                                <?php
                                echo $invoiceContactDetails->getCounty();
                                ?>
                            </p>
                            <p>
                                <span style="font-weight:bold">
                                Postcode:
                                </span>
                                <?php
                                echo $invoiceContactDetails->getPostcode();
                                ?>
                            </p>
                            <?php if($invoiceContactDetails->getPhoneNumber() != null): ?>
                            <p>
                                <span style="font-weight:bold">
                                Phone Number:
                                </span>
                                <?php
                                echo $invoiceContactDetails->getPhoneNumber();
                                ?>
                            </p>
                            <?php endif;?>
                            <p>
                                <span style="font-weight:bold">
                                Email:
                                </span>
                                <?php echo $invoiceContactDetails->getEmail();
                                ?>
                            </p>
                            
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
