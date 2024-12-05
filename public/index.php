<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}


include_once '../src/utils/databaseHelper.php';
$dbHelper = new DatabaseHelper();

$searchTextConst = "searchText";

$products = $dbHelper->getAllProducts();

if (isset($_POST[$searchTextConst])) {
    $matchingProductNames = array_filter($products, function ($product) {
        $searchTextConst = "searchText";

        $name = $product->getName();
        $name = strtolower($name);

        $searchText = $_POST[$searchTextConst];
        $searchText = strtolower($searchText);

        return str_contains($name, $searchText);
    });

    $matchingProductDesc = array_filter($products, function ($product) {
        $searchTextConst = "searchText";

        $desc = $product->getDescription();
        $desc = strtolower($desc);

        $searchText = $_POST[$searchTextConst];
        $searchText = strtolower($searchText);

        return str_contains($desc, $searchText);
    });

    $products = array_merge($matchingProductNames, $matchingProductDesc);
}

// If not logged in, set up a guest user
if (!isset($_SESSION['LOGGED_IN_USER'])) {
    include_once '../src/utils/userHelper.php';
    $uHelper = new UserHelper();

    $uuid = $uHelper->generateUuid();
    $_SESSION['LOGGED_IN_USER'] = "guest-" . $uuid;
    $_SESSION["LOGGED_IN_USER_IS_ADMIN"] = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include_once '../templates/header.php'; ?>

    <main class="product-container">


    <div class="search">
    <form action="index.php" method="post">
        <input type="text" id="searchText" name="searchText" placeholder="Search...">
        <input type="submit" value="Search">
    </form>
    <form action="index.php" method="post">
        <input type="submit" value="Clear">
    </form>
</div>



        <?php foreach ($products as $product) : ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>" class="product-image">
                </div>
                <div class="product-info">
                    <h3>
                        <?php echo $product->getName(); ?>
                    </h3>
                    <p class="product-price">£<?php echo $product->getPricePence() / 100; ?></p>
                    <form action="/viewProduct.php?id=<?php echo $product->getId(); ?>" method="post">
                        <input type="submit" value="Select Product">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
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
