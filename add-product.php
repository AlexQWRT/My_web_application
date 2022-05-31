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
        input:invalid {
            box-shadow: 0.1vw 0.1vw 0.1vw red;
        }
    </style>
</head>
<body onload="properties_output()">
    <div class="body">
        <span class="head">Product Add</span>
        <div class="buttons">
            <button class="save" onclick="validate()">Save</button>
            <a href="index.php"><button class="cancel">Cancel</button></a>
        </div>
        <hr>
        <form action="/" method="post" id="product_form">
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
                    <td><input type="text" name="sku" class="value" id="sku" maxlength="12" pattern="^[a-zA-Z0-9]+$" required ></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name" class="value" id="name" maxlength="100" pattern="^[a-zA-Z0-9;,. -]+$" required ></td>
                </tr>
                <tr>
                    <td>Price ($):</td>
                    <td><input type="text" name="price" class="value" id="price" maxlength="8" pattern="\d+(\.\d{2})?" required ></td>
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
            if (!document.getElementById(field_name).validity.valid) {
                alert('Warning, "' + field_name + '" filed length should be less or equals 8 characters and contain numbers with 2 numbers after daught)!');
                return false;
            }
            if (document.getElementById(field_name).value == 0) {
                alert('"Warning,' + field_name + '" filed length should be above zero!');
                return false;
            }
            return true;
        }
        function validate() {
            if (!document.getElementById('sku').validity.valid) {
                alert('"SKU" filed length should be less or equals 12 characters and contain only English letters and numbers!');
                return false;
            }
            if (!document.getElementById('name').validity.valid) {
                alert('"Name" filed length should be less or equals 100 characters and contain only English letters, numbers, spaces and some special characters (;,.-)!');
                return false;
            }
            if (!validate_field('price')) {
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

        function create_property(property_name, field_name, message) {
            return '<tr><td>' + field_name + ':</td><td><input type="text" name="' + property_name + 
                   '" class="value" id="' + property_name + '" maxlength="8" pattern="\\d+(\\.\\d{2})?" required></td></tr>';
        }
        function create_message(message) {
            return '<tr><td></td><td class="hint">' + message + '</td></tr>';
        }
        function properties_output() {
            let where = document.getElementById('properties');
            switch(document.getElementById('productType').value) {
                case '1':
                    where.innerHTML = create_property('size', 'Size (MB)') +
                                      create_message('Please, provide size');
                    break;
                case '2':
                    where.innerHTML = create_property('weight', 'Weight (KG)') +
                                      create_message('Please, provide weight');
                    break;
                case '3':
                    where.innerHTML = create_property('height', 'Height (CM)') +
                                      create_property('width', 'Width (CM)') +
                                      create_property('length', 'Length (CM)') +
                                      create_message('Please, provide dimensions');
                    break;
            }
            return;
        }
        
    </script>
</html>