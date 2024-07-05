<?php
include ('functions.php');

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

$type = urldecode($_GET['type']); // Get the product type from the URL
$gender = urldecode($_GET['gender']); // Get the gender from the URL

if(isset($_POST['add'])){
    include_once('database_con.php');
    //retrieve the ID 
    $ID = $_POST['product_id'];

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
    <title>Buyer Side Category</title>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="item-details">
    <?php 
    $products = getProductsByTypeAndGender($type, $gender);
    if(!empty($products)) {
        foreach($products as $product){
            ?>
            <div class = "item-grid">
                <div class="images">
                <a href="product.php?product_id=<?php echo $product['ID'];?>"><img src="<?php echo "Images/{$product['FILE']}"?>" class="control-images"></a>
                </div>
                <div class="item-details">
                    <p class="title">
                        <a href="product.php?product_id=<?php echo $product['ID']; ?>" target = "blank"><?php echo $product['ITEM_DESC']?></a>
                    </p>
                    <p class="price">$<?php echo $product['PRICE']?></p>
                    <p class="category"><?php echo $product['CATEGORY']?></p>
                    <p class="gender"><?php echo $product['GENDER']?></p>
                    <p class="Ptype"><?php echo $product['Ptype']?></p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['ID']; ?>">
                        <input type="submit" name="add" value="Add to CART" onclick="return confirm('Are you sure you want to add <?php echo $product['ITEM_DESC']?> $<?php echo $product['PRICE']?> this item to your cart?');">
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        echo "There is not Item for this category - ". $type;
    }
    ?>
    </div>
    <div class="categories">
    <div class = "Home">
        <a href="seller.php"><h3>Main</h3></a>
    </div>
        <a href=""></a>
    <div class="apparels">
        <h4>Apparels</h4>
        <div class="male">
            <h5>Male</h5>
            <a href="category.php?type=Brief&gender=male"><p>Brief</p></a>
            <a href="category.php?type=Jackets&gender=male"><p>Jackets</p></a>
            <a href="category.php?type=Jeans&gender=male"><p>Jeans</p></a>
            <a href="category.php?type=T-Shirt&gender=male"><p>T-shirt</p></a>
        </div>
        <div class="female">
            <h5>Female</h5>
            <a href="category.php?type=Blouse&gender=female"><p>Blouse</p></a> 
            <a href="category.php?type=Dress&gender=female"><p>Dress</p></a> 
            <a href="category.php?type=<?php echo urlencode('Maxi Dresses'); ?>&gender=female"><p>Maxi Dresses</p></a> 
            <a href="category.php?type=Cardigans&gender=female"><p>Cardigans</p></a> 
        </div>
    </div>
    <div class="accessory">
    <h4>Accessories</h4>
        <div class="male">
            <h5>Male</h5>
            <a href="category.php?type=Belts&gender=male"><p>Belts</p></a>
            <a href="category.php?type=Sunglasses&gender=male"><p>Sunglasses</p></a>
            <a href="category.php?type=Watch&gender=male"><p>Watch</p></a>
        </div>
        <div class="female">
            <h5>Female</h5>
            <a href="category.php?type=Belts&gender=female"><p>Belts</p></a> 
            <a href="category.php?type=Necklace&gender=female"><p>Necklace</p></a>
            <a href="category.php?type=Sunglasses&gender=female"><p>Sunglasses</p></a>
        </div>
    </div>
    </div>
    </body>
</html>