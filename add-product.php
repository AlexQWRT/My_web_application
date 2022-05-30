<?php
    $host = 'sql310.epizy.com';
    $user = 'epiz_31815620';
    $password = 'y8j2vgsp';
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            padding: 0;
            margin: 0;
            color: #6e5227;
            font-size: 1vw;
            background-color: #fff3e0;
        }
        div.body {
            width: 80%;
            min-height: 100vh;
            margin-left: 10%;
            margin-right: 10%;
            background-color: #f5d99a;

        }
        span.head {
            font-size: 3vw;
            margin-left: 1vw;
        }
        button {
            font-family: 'Ubuntu', sans-serif;
            height: 2.4vw;
            border: 0.15vw solid #6e5227;
            border-radius: 0.7vw;
            display: inline-block;
            padding: 0.1vw 0.5vw; 
            text-decoration: none; 
            color: #6e5227;
            background-color: #fff3e0;
            font-size: 1.8vw;
            cursor: pointer;
        }
        div.buttons {
            position: absolute;
            right: 11%;
            top: 0.7vw;
        }
        hr {
            width: 96%;
            height: 0.1vw;
            margin-left: 2%;
            margin-right: 2%;
            margin-top: 0.3vw;
            margin-bottom: 0.7vw;
            border: none; 
            background-color: #6e5227;
            color: #6e5227;
        }
        table {
            width:40%;
            margin-left: 25%;
            margin-right: 35%;
            margin-bottom: 0;
        }
        td {
            text-align: end;
            width: 50%;
        }
        td.hint {
            text-align: center;
        }
        input.value, select.value {
            background-color: #fff3e0;
            border: 0.15vw solid #6e5227;
            border-radius: 0.3vw;
            box-sizing: border-box;
            width: 100%;
            font-family: 'Ubuntu', sans-serif;
            font-size: 1vw;
            margin: 0;
        }
        select.value {
            margin: 0;
            cursor: pointer;
        }
        option {
            font-size: 0.9vw;
        }
    </style>
</head>
<body onload="properties_output()">
    <div class="body">
        <span class="head">Product Add</span>
        <div class="buttons">
            <a href="index.php"><button class="cancel">Cancel</button></a>
            <button class="save" onclick="validate()">Save</button>
        </div>
        <hr>
        <form action="index.php" method="post" id="product_form">
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
                    <td><input type="text" name="sku" class="value" id="sku" required ></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name" class="value" id="name" required ></td>
                </tr>
                <tr>
                    <td>Price ($):</td>
                    <td><input type="text" name="price" class="value" id="price" required ></td>
                </tr>
                <tr>
                    <td>Type switcher:</td>
                    <td>
                        <select name="category" class="value" id="productType" required onchange="properties_output()">
                            <?php
                                $result = mysqli_query($connection, 'SELECT * FROM `categories`;');
                                while( $array = mysqli_fetch_assoc($result) ) {
                                    echo '<option value="' . $array['category_id'] . '" id="' . $array['category_name'] . '"';
                                    echo '>' . $array['category_name'] . '</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <table id="properties">
            </table>
            <?php
                mysqli_close($connection);
            ?>
        </form>
    </div>
</body>
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
            if (document.getElementById(field_name).value >= 10000000) {
                alert('Warning, ' + field_name + ' should be less than 10000000!');
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
                alert('SKU should be shorter than 13 characters!');
                return false;
            }
            if (document.getElementById('name').value.trim().length < 1) {
                alert('Name should not be empty!');
                return false;
            }
            if (document.getElementById('name').value.trim().length >= 100) {
                alert('Name should be shorter than 100 characters!');
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
            if (document.getElementById('price').value >= 10000000) {
                alert('Price should be less than 10000000!');
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
                    if (!validate_field('height') || !validate_field('width') || !validate_field('length')) {
                        return false;
                    }
                    break;
            }
            document.getElementById('product_form').submit();
        }
        function properties_output() {
            let where = document.getElementById('properties');
            switch(document.getElementById('productType').value) {
                case '1':
                    where.innerHTML = '<tr><td>Size (MB):</td><td><input type="text" name="size" class="value" id="size" required></td></tr>' +
                    '<tr><td></td><td class="hint">Please, provide size</td></tr>';
                    break;
                case '2':
                    where.innerHTML = '<tr><td>Weight (KG):</td><td><input type="text" name="weight" class="value" id="weight" required></td></tr>' +
                    '<tr><td></td><td class="hint">Please, provide weight</td></tr>';
                    break;
                case '3':
                    where.innerHTML = '<tr><td>Height (CM):</td><td><input type="text" name="height" class="value" id="height" required></td></tr>' +
                    '<tr><td>Width (CM):</td><td><input type="text" name="width" class="value" id="width" required></td></tr>' +
                    '<tr><td>Length (CM):</td><td><input type="text" name="length" class="value" id="length" required></td></tr>' +
                    '<tr><td></td><td class="hint">Please, provide dimensions</td></tr>';
                    break;
            }
            return;
        }
        
    </script>
</html>

