<?php 

    function getProducts($int){
        include('database_con.php');
        $query = ("SELECT * FROM products ORDER BY rand() LIMIT $int");
        $result = mysqli_query($con, $query);

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if(empty($data)){
            return "Your Shop is ready, Please add Items!";
        }
        return $data;
    }

    function recommended($category){
        include('database_con.php');
        $query = ("SELECT * FROM products where CATEGORY = ?");
        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 's', $category);
            mysqli_stmt_execute($stmt);
    
            $result = mysqli_stmt_get_result($stmt);
            while ($row = $result->fetch_assoc()) {
                // Access the data in the row here
                $data[] = $row;
            }
        }
        return $data;
    }

    function getProductbyId($ID){
        include('database_con.php');
        $query = ("SELECT * FROM products WHERE ID = ?");
        $stmt = mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt, $query);

        //Bind the item desc as a parameter
        mysqli_stmt_bind_param($stmt, 'i', $ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $specific_product = mysqli_fetch_assoc($result);
        return $specific_product;
    }

    function updateProductbyId($ID){
        include('database_con.php');
        $query = ("SELECT * FROM products WHERE ID = ?");
        $stmt = mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt, $query);

        //Bind the item desc as a parameter
        mysqli_stmt_bind_param($stmt, 'i', $ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $specific_product = mysqli_fetch_assoc($result);
        return $specific_product;
    }

    function updateQuery($ID, $item_desc, $price, $category, $gender, $Ptype, $more_desc) {
        include('database_con.php');
    
        $query = "UPDATE products SET ITEM_DESC = ?, PRICE = ?, CATEGORY = ?, GENDER = ?, Ptype = ?, MORE_DESC = ? WHERE ID = ?";
        $stmt = mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt, $query);  
        mysqli_stmt_bind_param($stmt, 'ssssssi', $item_desc, $price, $category, $gender, $Ptype, $more_desc, $ID);   
        mysqli_stmt_execute($stmt);
    }

    function deleteItembyId($ID) {
        include('database_con.php');
        $query = ("DELETE FROM products WHERE ID = ?");
        $stmt = mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt, $query);

        mysqli_stmt_bind_param($stmt, 'i', $ID);
        mysqli_stmt_execute($stmt);
    }

    //Functions for Categories link
    function getProductsByTypeAndGender($type, $gender){
        include('database_con.php');
        $query = "SELECT * FROM products WHERE Ptype = ? AND GENDER = ?";
        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ss', $type, $gender);
            mysqli_stmt_execute($stmt);
    
            $result = mysqli_stmt_get_result($stmt);
            $data = array(); // Initialize $data as an empty array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    //not used yet
    function getProductsbyGender($gender){
        include('database_con.php');
        $query = "SELECT * FROM products WHERE GENDER = ?";
        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 's',  $gender);
            mysqli_stmt_execute($stmt);
    
            $result = mysqli_stmt_get_result($stmt);
            $data = array(); // Initialize $data as an empty array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    function getAddedToCart($USER_ID){
        include('database_con.php');
        //buyer_db = cart database
        $query = "SELECT * FROM buyer_db WHERE USER_ID = ?";
        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, $query)){
            mysqli_stmt_bind_param($stmt, 'i', $USER_ID);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $data = array();

            while ($row = $result->fetch_assoc()){
                $data[] = $row;
            }
        }
        return $data;
    }

    /* this one is cool uses JOIN method
    function getAddedToCart($USER_ID){
        include('database_con.php');
    
        // SQL query with JOIN operation
        $query = "SELECT * FROM buyer_db 
                  JOIN user_db ON buyer_db.USER_ID = user_db.USER_ID
                  WHERE buyer_db.USER_ID = ?";
    
        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, $query)){
            // Bind the USER_ID to the placeholder in the query
            mysqli_stmt_bind_param($stmt, 's', $USER_ID);
            mysqli_stmt_execute($stmt);
    
            $result = mysqli_stmt_get_result($stmt);
            $data = array();
    
            while ($row = $result->fetch_assoc()){
                $data[] = $row;
            }
        }
        return $data;
    } */
?>
