<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}


if (isset($_SESSION['LOGGED_IN_USER'])) {
    include_once '../src/utils/databaseHelper.php';
    $dbHelper = new DatabaseHelper();

    $username = $_SESSION['LOGGED_IN_USER'];
    $isAdmin = $_SESSION["LOGGED_IN_USER_IS_ADMIN"];

    $userId = $dbHelper->getUserIdByUsername($username);
    $invoices = [];

    if ($isAdmin) {
        // Admin can view all invoices
        $invoices = $dbHelper->getAllTopLevelInvoices();
    } else {
        // Regular user can only view their own.
        $invoices = $dbHelper->getTopLevelInvoicesByUserId($userId);
    }


    $searchTextConst = "searchText";
    if (isset($_POST[$searchTextConst])) {
        $matchingUsername = array_filter($invoices, function ($invoice) {
            $searchTextConst = "searchText";

            $name = $invoice->getUsername();
            $name = strtolower($name);

            $searchText = $_POST[$searchTextConst];
            $searchText = strtolower($searchText);

            return str_contains($name, $searchText);
        });

        $matchingTimestamp = [];

        include_once '../src/utils/userHelper.php';
        $uHelper = new UserHelper();
        $searchText = $_POST[$searchTextConst];
        if ($uHelper->isValidTimestamp($searchText)) {
            $matchingTimestamp = array_filter($invoices, function ($invoice) {

                include_once '../src/utils/userHelper.php';
                $uHelper = new UserHelper();

                $searchTextConst = "searchText";

                $datetime = $invoice->getDateTimeCreated();
                $datetime = $uHelper->extractDate($datetime);

                $searchText = $_POST[$searchTextConst];
                $searchText = $uHelper->extractDate($searchText);

                return $searchText == $datetime;
            });
        }

        $invoices = array_merge($matchingUsername, $matchingTimestamp);
    }
} else {
    // Guests can only view invoices directly by URL
    header('Location: ' . "/");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | View Invoices</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include_once '../templates/header.php'; ?>

    <h2 style="text-align: center">Invoices</h2>

    <main class="invoice-container">
        <div class="search">
            <form action="viewInvoices.php" method="post">
                <input type="text" id="searchText" name="searchText" placeholder="Search...">
                <input type="submit" value="Search">
            </form>
            <form action="viewInvoices.php" method="post">
                <input type="submit" value="Clear">
            </form>
        </div>

        <?php foreach ($invoices as $invoice) : ?>
            <div class="invoice-card">
                <div class="invoice-image-wrapper">
                    <img src="<?php echo $invoice->getImage(); ?>" alt="Invoice Thumbnail" class="invoice-image">
                </div>
                <div class="invoice-created-date">
                    <h3>
                        <?php echo $invoice->getDateTimeCreated(); ?>
                    </h3>
                    <p class="invoice-value">£<?php echo $invoice->getInvoiceValuePence() / 100; ?></p>

                    <?php if ($isAdmin) : ?>
                        <p class="invoice-value"><?php echo $invoice->getUsername(); ?></p>
                    <?php endif; ?>

                    <form action="/viewInvoice.php?id=<?php echo $invoice->getId(); ?>" method="post">
                        <input type="submit" value="View Invoice">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <?php include_once '../templates/footer.php'; ?>
</body>

</html>