<?php
/*
    $host = 'sql310.epizy.com';
    $user = 'epiz_31815620';
    $password = 'y8j2vgsp';
    $database = 'epiz_31815620_shop';*/

    //connecting to MySQL
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'epiz_31815620_shop';
    $connection = mysqli_connect($host, $user, $password, $database);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Add</title>
    <style>

    </style>
    <script
			  src="https://code.jquery.com/jquery-3.6.0.js"
			  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
			  crossorigin="anonymous"></script>
    <script>
        
        function validate_field(field_name) {
            if (!isFinite(document.getElementById(field_name).value)) {
                alert('Warning, ' + field_name + ' should be numeric!');
                return false;
            }
            if (document.getElementById(field_name).value <= 0) {
                alert('Warning, ' + field_name + ' should be above zero!');
                return false;
            }
            return true;
        }
        function validate() {
            if (document.getElementById('sku').value.trim().length < 1) {
                alert('SKU should not be empty!');
                return false;
            }
            if (document.getElementById('sku').value.trim().length > 12) {
                alert('SKU should be shorter than 12 characters!');
                return false;
            }
            if (document.getElementById('name').value.trim().length < 1) {
                alert('Name should not be empty!');
                return false;
            }
            if (document.getElementById('name').value.trim().length > 255) {
                alert('Name should be shorter than 255 characters!');
                return false;
            }
            if (!isFinite(document.getElementById('price').value)) {
                alert('Price should be numeric!');
                return false;
            }
            if (document.getElementById('price').value <= 0) {
                alert('Price should be above zero!');
                return false;
            }
            switch(document.getElementById('productType').value) {
                case '1':
                    if (!validate_field('size')) {
                        return false;
                    }
                    break;
                case '2':
                    if (!validate_field('weight')) {
                        return false;
                    }
                    break;
                case '3':
                    if (validate_field('height') || validate_field('width') || validate_field('length')) {
                        return false;
                    }
                    break;
            }
        }
        function properties_output() {
            /*if (document.getElementById('properties') != null) {
                document.getElementById('properties').remove();
            }
            let where = document.getElementById('insert_after_this');
            let table = document.createElement('table');
            table.setAttribute('id', 'properties');*/
            let where = document.getElementById('properties');
            switch(document.getElementById('productType').value) {
                case '1':
                    where.innerHTML = '<tr><td>Size (MB):</td><td><input type="text" name="size" id="size" required></td></tr>';
                    break;
                case '2':
                    where.innerHTML = '<tr><td>Weight (KG):</td><td><input type="text" name="weight" id="weight" required></td></tr>';
                    break;
                case '3':
                    where.innerHTML = '<tr><td>Height (CM):</td><td><input type="text" name="height" id="height" required></td></tr>' +
                    '<tr><td>Width (CM):</td><td><input type="text" name="width" id="width" required></td></tr>' +
                    '<tr><td>Length (CM):</td><td><input type="text" name="length" id="length" required></td></tr>';
                    break;
            }
            return;
        }
        
    </script>
</head>
<body>
    <h1>Product Add</h1>
    <a href="index.php"><button class="cancel">CANCEL</button></a>
    <button class="save" form="product_form" onclick="validate()">Save</button>
    <form action="index.php" method="post" id="product_form">
        <hr color='black'>
        <?php  
            if( !$connection ) {
                echo 'Failed to connect to database!<br>';
                echo mysqli_connect_error();
                exit();
            }
        ?>
        <table>
            <tr>
                <td>SKU:</td>
                <td><input type="text" name="sku" id="sku" required ></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" id="name" required ></td>
            </tr>
            <tr>
                <td>Price ($):</td>
                <td><input type="text" name="price" id="price" required ></td>
            </tr>
            <tr>
                <td>Type switcher:</td>
                <td>
                    <select name="category" id="productType" required onchange="properties_output()">
                        <?php
                            $result = mysqli_query($connection, 'SELECT * FROM `categories`;');
                            while( $array = mysqli_fetch_assoc($result) ) {
                                echo '<option value="' . $array['category_id'] . '" id="' . $array['category_name'] . '"';
                                if (isset($_POST['category']) and $_POST['category'] == $array['category_id']) echo ' selected';
                                echo '>' . $array['category_name'] . '</option>';
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <table id="properties">
            <tr>
                <td>Size (MB):</td>
                <td>
                    <input type="text" name="size" id="size" required>
                </td>
            </tr>
        </table>
        <?php
            mysqli_close($connection);
        ?>
    </form>
</body>
</html>

