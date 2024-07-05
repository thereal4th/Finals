<?php 
include ('functions.php');
include ('database_con.php');

if(!isset($_GET['product_id'])){
    echo '<html><body>';
    echo '<div id="countdown">Redirecting you to main page in <span id="time">5</span> seconds. Please access this with product id.</div>';
    echo '<script>
        var countdown = 5;
        var intervalId = setInterval(function() {
            countdown--;
            document.getElementById("time").textContent = countdown;
            if (countdown <= 0) {
                clearInterval(intervalId);
                location.href = "no_account.php";
            }
        }, 1000);
    </script>';
    echo '</body></html>';
    exit();
}

$ID = $_GET['product_id'];

    $specific_product = getProductbyId($ID);
    if(isset($_POST['add'])){
        $query ="INSERT INTO buyer_db (FILE, ITEM_DESC, PRICE, CATEGORY, GENDER, Ptype)
                SELECT FILE, ITEM_DESC, PRICE, CATEGORY, GENDER, Ptype
                FROM products
                WHERE ID = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $ID);
        mysqli_stmt_execute($stmt);

        // Handle success or error
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Product added to cart successfully!";
        } else {
            echo "Error adding product to cart.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Shopping System</title>
    <style>
        .control-images{
    width: 300px;
    height: 200px;
    border-radius: 10px 10px 0px 0px;
    }

    .images{
        justify-items: center;
    }
    </style>
</head>
<body>
<h2>Online Shopping System</h2>
<div class="container">
    <div class="display-items">
        <!-- Call our functions here -->
        <?php

        if ($specific_product) { // Check if product exists
            ?>
            <div class="images">
                <img src="<?php echo "Images/{$specific_product['FILE']}"; ?>" class="control-images">
            </div>
            <div class="item-description">
                <p class="title">
                    <?php echo $specific_product['ITEM_DESC']; ?>
                </p>
            </div>
            <div class="item-category">
                <p class="category"><?php echo $specific_product['CATEGORY']; ?></p>
            </div>
            <div class="item-gender">
                <p class="gender"><?php echo $specific_product['GENDER']; ?></p>
            </div>
            <div class="item-type">
                <p class="Ptype"><?php echo $specific_product['Ptype']; ?></p>
            </div>
            <div class="item-type">
                <p class="Price">$<?php echo $specific_product['PRICE']; ?></p>
            </div>
            <?php
        } else {
            echo "Product not found."; // Handle case when product doesn't exist
        }
        ?>
    </div>
    <div class="add-to-cart">
        <form method="POST">
            <input type="submit" name="add" value="Add to cart">
        </form>
    </div>
    <div class="display-addition-options">
        <?php
            include_once ('functions.php');
            $products = recommended($specific_product['CATEGORY']); 
        ?>
        <?php
                foreach($products as $product){
                    if($product['ID'] == $specific_product['ID']){
                        continue;
                    }
                    ?>
                    <div class = "item-grid">
                        <div class="images">
                            <img src="<?php echo "Images/{$product['FILE']}"?>" class="control-images">
                        </div>
                        <div class="item-details">
                            <p class="title">
                                <a href="product.php?product_id=<?php echo $product['ID']; ?>" target = "blank"><?php echo $product['ITEM_DESC']?></a>
                            </p>
                            <p class="category"><?php echo $product['CATEGORY']?></p> 
                            <p class="Ptype"><?php echo $product['Ptype']?></p> 
                            <p class="price">$<?php echo $product['PRICE']?></p>  
                        </div>
                    </div>  
                    <?php
                }
                ?>
            
    </div>
</div>
</body>
</html>