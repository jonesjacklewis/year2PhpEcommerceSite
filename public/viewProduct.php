<?php
$LOCATION = 'Location: ';

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

if (isset($_SESSION['LOGGED_IN_USER'])) {
    $userId = $_SESSION['LOGGED_IN_USER'];
    $isAdmin = $_SESSION["LOGGED_IN_USER_IS_ADMIN"];
}else{
    $userId = "N/a";
    $isAdmin = false;
}


include_once '../src/utils/databaseHelper.php';
$dbHelper = new DatabaseHelper();

if (!isset($_GET['id'])) {
    // if no product id redirect to home
    header($LOCATION . "/");
}

$productId = $_GET['id'];

$product = $dbHelper->getProductById($productId);

if ($product == "N/a") {
    // if the product isn't valid, redirect to home
    header($LOCATION . "/");
}

if(isset($_POST['itemId'])){
    $itemIdSub = $_POST['itemId'];
    $quantity = $_POST["quantity"];

    if(isset($_SESSION['BASKET'])){
        $basket = $_SESSION['BASKET'];

        $basket[$itemIdSub] = $quantity;

        $_SESSION['BASKET'] = $basket;
    }else{
        $basket = [
            $itemIdSub => $quantity
        ];
    }

    $_SESSION['BASKET'] = $basket;
    header($LOCATION . "/");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Site | <?php echo $product->getName(); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include_once '../templates/header.php'; ?>


    <main class="view-product-wrapper">
        <div class='row'>
            <div class='column'>
                <div class='left-column'>
                    <img
                    src="<?php echo $product->getImage(); ?>"
                    alt="<?php echo $product->getName(); ?>" />
                </div>
            </div>
            <div class='column'>
                <div class='right-column'>
                    <h2><?php echo $product->getName(); ?></h2>

                    <div>
                        <?php echo $product->getDescription(); ?>
                    </div>

                    <p class="bigText">
                        £<?php echo $product->getPricePence() / 100; ?>
                    </p>

                    <?php if (!$isAdmin && $userId != "N/a") : ?>
                        <form method="post" action="/viewProduct.php?id=<?php echo $product->getId();?>">
                            <input
                            type="text"
                            name="itemId"
                            id="itemId"
                            style="visibility:hidden;"
                            value="<?php echo $product->getId(); ?>">

                            <br>

                            <label for="quantity">Quantity: </label>
                            <input type="number" name="quantity" id="quantity" value="1" min="0" max="10"> <br>

                            <input type="submit" value="Add to Basket">
                        </form>
                    <?php else : ?>
                        <form method="post" action="/viewProduct.php?id=<?php echo $product->getId();?>">
                            <!-- Admin's can view the product, but not add to basket. -->
                            <input type="submit" value="Add to Basket" disabled>
                        </form>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </main>


    <?php include_once '../templates/footer.php'; ?>
</body>

</html>
