<?php
    if(!isset($_GET['ID'])){
        echo '<html><body>';
        echo '<div id="countdown">Redirecting you to main page in <span id="time">5</span> seconds. Please access this with product id.</div>';
        echo '<script>
            var countdown = 5;
            var intervalId = setInterval(function() {
                countdown--;
                document.getElementById("time").textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(intervalId);
                    location.href = "seller.php";
                }
            }, 1000);
        </script>';
        echo '</body></html>';
        exit();
    }
    $ID = $_GET['ID'];

    //must have checking for this
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ID = $_POST['ID'];
        $item_desc = $_POST['item_desc'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $gender = $_POST['Gender']; // Changed from 'gender' to 'Gender'
        $Ptype = $_POST['type']; // Changed from 'Ptype' to 'type'
        $more_desc = $_POST['more_desc'];
        include ('functions.php');
        updateQuery($ID, $item_desc, $price, $category, $gender, $Ptype, $more_desc);

        header('Location: seller.php');
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
        include ('functions.php');
        $specific_product = updateProductbyId($ID);

        if ($specific_product) { // Check if product exists
            ?>
            <form method="post">
                <input type="hidden" name="ID" value="<?php echo $specific_product['ID']; ?>">
                <div class="images">
                    <img src="<?php echo "Images/{$specific_product['FILE']}"; ?>" class="control-images">
                </div>
                <div class="item-description">
                    <label for="item_desc">Item Description:</label><br>
                    <textarea id="item_desc" name="item_desc"><?php echo $specific_product['ITEM_DESC']; ?></textarea>
                </div>
                <div class="item-price">
                    <label for="item_price">Price:</label><br>
                    <input type="text" id="price" name="price" value="<?php echo $specific_product['PRICE']; ?>">
                </div>
                <div class="drop-down">
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="Apparels" <?php if ($specific_product['CATEGORY'] == 'Apparels') echo 'selected'; ?>>Apparels</option>
                    <option value="Accessory" <?php if ($specific_product['CATEGORY'] == 'Accessory') echo 'selected'; ?>>Accessory</option>
                </select>
                <label for="Gender">Gender:</label>
                    <select name="Gender" id="Gender">
                        <option value="male" <?php if ($specific_product['GENDER'] == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($specific_product['GENDER'] == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                    <label for="type">Type:</label>
                    <select name="type" id="type">
                        <!-- Initially empty; options will be added dynamically -->
                    </select>
                </div>
                <div class="more_desc">
                    <p><?php echo $specific_product['MORE_DESC']; ?></p>
                    <label for="more_desc">More Description:</label><br>
                    <textarea id="more_desc" name="more_desc"><?php echo $specific_product['MORE_DESC']; ?></textarea>
                </div>
                <input type="submit" value="Update Product">
            </form>
            <?php
        } else {
            echo "Product not found."; // Handle case when product doesn't exist
        }
        ?>
        <div class = "Home">
        <a href="seller.php"><h3>Main</h3></a>
        </div>
    </div>
</div>
<script>
            const categoryDropdown = document.getElementById('category');
            const typeDropdown = document.getElementById('type');
            const genderDropdown = document.getElementById('Gender');

            // Define clothing types for each category and gender
            const Categories = {
                Apparels: {
                    male: ['Brief', 'Jackets', 'Jeans', 'T-shirt'],
                    female: ['Blouse', 'Dress', 'Maxi Dresses', 'Cardigans']
                },
                Accessory: {
                    male: ['Belts', 'Sunglasses', 'Watch'],
                    female: ['Belts', 'Necklace', 'Sunglasses']
                },
            };

            // Function to update type options based on selected category and gender
            function updateTypeOptions() {
                const selectedCategory = categoryDropdown.value;
                const selectedGender = genderDropdown.value;
                typeDropdown.innerHTML = ''; // Clear existing options

                // Add options based on selected category and gender
                if (selectedCategory === 'Watch') {
                    Categories.Watch[selectedGender].forEach(type => {
                        const option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        typeDropdown.appendChild(option);
                    });
                } else {
                    Categories[selectedCategory][selectedGender].forEach(type => {
                        const option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        typeDropdown.appendChild(option);
                    });
                }
            }

            // Initial update based on default selections
            updateTypeOptions();

            // Listen for changes in category and gender selections using jQuery
            $(document).ready(function() {
                $('#category, #Gender').on('change', updateTypeOptions);
            });

        </script>
</body>
</html>
