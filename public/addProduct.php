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
    <title>eCommerce Site | Add Product</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Third Party Package for rich text editing !-->
    <script
        src="https://cdn.tiny.cloud/1/smrhcplb6anb84fmg9s187qchjddjykvru2vfeiwkr6924kb/tinymce/7/tinymce.min.js"
        referrerpolicy="origin">
    </script>

    <script>
        tinymce.init({
            selector: '#productDescription'
        });
    </script>
</head>

<body>
    <?php include_once '../templates/header.php'; ?>

    <main>
        <h2>Add Product</h2>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <input type="text" name="formSubmitted" value="set" style="display: none;">
            <label for="inpProductName">Product Name: </label>
            <input type="text" name="inpProductName" id="inpProductName" required>

            <br>

            <label for="productDescription">Product Description: </label>
            <textarea id="productDescription" name="productDescription"></textarea>

            <br>

            <label for="inpPrice">Price (£): </label>
            <input type="number" name="inpPrice" id="inpPrice" min="0" max="10000" step="0.01" required>

            <br>

            <label for="imageFile">Image</label>
            <input type="file" name="imageFile" id="imageFile" accept="image/*" />

            <input type="submit" value="Add Product">
        </form>
    </main>


    <?php

include_once '../src/utils/databaseHelper.php';

    function handleAddProduct(){
        try{
            $target = "";
        if (empty($_FILES["imageFile"]["name"])) {
            // place holder image
            $target = "https://via.placeholder.com/300";
        }else{
            // Read the file's contents
            $target = $_FILES["imageFile"]["tmp_name"];
        }

        $fileContent = file_get_contents($target);

        // Convert the contents to a base64 string
        $base64String = base64_encode($fileContent);

        // prepend the base64 string with data URL scheme information
        $fileType = $_FILES["imageFile"]["type"];
        $base64String = 'data:' . $fileType . ';base64,' . $base64String;

        $productName = $_POST["inpProductName"];
        $pricePence = $_POST["inpPrice"] * 100;

        $productDesc = $_POST["productDescription"];

        if(strlen($productDesc) < 10){
            echo "<p class='error'>No product description provided.</p>";
            return;
        }

        $dbHelper = new DatabaseHelper();

        $dbHelper->addProduct($productName, $productDesc, $base64String, $pricePence);

        }catch(Exception $e){
            ($e); // early evaluate the exception var
            echo "<p class='error'>Something went wrong.</p>";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["formSubmitted"])) {
        handleAddProduct();
    }

    ?>

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
