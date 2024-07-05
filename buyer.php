<?php 
    session_start();
    if (!isset($_SESSION['username'])) {
        echo '<html><body>';
        echo '<div id="countdown">5</div>';
        echo '<div id="message">Please login with a valid account first.</div>';
        echo '<script>
            var countdownElement = document.getElementById("countdown");
            var messageElement = document.getElementById("message");
            var countdown = 5;
            var intervalId = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(intervalId);
                    window.location.href = "no_account.php";
                }
            }, 1000);
        </script>';
        echo '</body></html>';
        exit;
    }

    if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to login page
    header('Location: login.php');
    exit;
    }

    include ('database_con.php');
    if(isset($_POST['add'])){
        //retrieve the ID 
        $ID = $_POST['product_id'];
        $USER_ID = $_SESSION['USER_ID'];

        $query ="INSERT INTO buyer_db (FILE, ITEM_DESC, PRICE, CATEGORY, GENDER, Ptype, USER_ID)
                SELECT FILE, ITEM_DESC, PRICE, CATEGORY, GENDER, Ptype, ?
                FROM products
                WHERE ID = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ii',$USER_ID, $ID);
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
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class = "header">
    <div style = "width: 900px;">
        <p style = "margin-left: 75px; font-size: 25px;margin-top: 30px;color: #465a27; font-weight: bold;font-family: Times;">Online Shopping System<p>
    </div>
    <div style = "width: 300px">
        <a href="cart.php"><button class = "shop-button">CART</button></a>
        <form method="post">
            <button name="logout" class="shop-button" onclick="return confirm('Are you sure you want to logout?');">LOGOUT</button>
        </form>
    </div>
</div>
<div class = "subheader">
    <div style = "padding-right: 600px; display: inline-block;">
        <p class = "subheading" style = "display: inline-block;">Buyer <?php echo $_SESSION['username'];?></p>
    </div>  
    <!-- Trigger the modal with a button -->
</div>
<div style = "height: 1px; background-color: black;margin-left:230px;width:949.6px;margin-bottom:30px;"></div>
<div class="display-items"> 
        <?php  include ('functions.php');
         $products = getProducts(20)
         ?>
        <!-- call our functions here -->
         <?php
            foreach($products as $product){
                ?>
                <div class = "item-grid">
                    <div class="images">
                        <a href="buyer_product.php?product_id=<?php echo $product['ID']; ?>" target="_blank"><img src="<?php echo "Images/{$product['FILE']}"?>" class="control-images"></a>
                    </div>
                    <div class="item-details">
                        <p class="title">
                            <a href="buyer_product.php?product_id=<?php echo $product['ID']; ?>" target = "blank"><?php echo $product['ITEM_DESC']?></a>
                        </p>
                        <p class="category">Category: <?php echo $product['CATEGORY']?></p>
                        <p class="gender">For: <?php echo $product['GENDER']?></p>
                        <p class="Ptype">Product type: <?php echo $product['Ptype']?></p>
                        <p class="price">$<?php echo $product['PRICE']?></p>
                        <div class="add-to-cart">
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['ID']; ?>">
                            <input type="submit" name="add" value="Add to cart" onclick="return confirm('Are you sure you want to add <?php echo $product['ITEM_DESC']?> $<?php echo $product['PRICE']?> to your cart?');">
                        </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
</div>
<div class="sidebar-category">
<div class="apparels">
        <h4>Apparels</h4>
        <div class="male">
            <h4>Male</h4>
            <a href="buyer_category.php"><p>Brief</p></a>
            <a href="buyer_category.php?type=Jackets&gender=male"><p>Jackets</p></a>
            <a href="buyer_category.php?type=Jeans&gender=male"><p>Jeans</p></a>
            <a href="buyer_category.php?type=T-Shirt&gender=male"><p>T-shirt</p></a>
        </div>
        <div class="female">
            <h4>Female</h4>
            <a href="buyer_category.php?type=Blouse&gender=female"><p>Blouse</p></a> 
            <a href="buyer_category.php?type=Dress&gender=female"><p>Dress</p></a> 
            <a href="buyer_category.php?type=<?php echo urlencode('Maxi Dresses'); ?>&gender=female"><p>Maxi Dresses</p></a> 
            <a href="buyer_category.php?type=Cardigans&gender=female"><p>Cardigans</p></a> 
        </div>
    </div>
    <div class="accessory">
    <h4>Accessories</h4>
        <div class="male">
            <h4>Male</h4>
            <a href="buyer_category.php?type=Belts&gender=male"><p>Belts</p></a>
            <a href="buyer_category.php?type=Sunglasses&gender=male"><p>Sunglasses</p></a>
            <a href="buyer_category.php?type=Watch&gender=male"><p>Watch</p></a>
        </div>
        <div class="female">
            <h4>Female</h4>
            <a href="buyer_category.php?type=Belts&gender=female"><p>Belts</p></a> 
            <a href="buyer_category.php?type=Necklace&gender=female"><p>Necklace</p></a>
            <a href="buyer_category.php?type=Sunglasses&gender=female"><p>Sunglasses</p></a>
        </div>
</div>
</div>
    </body>
</html>