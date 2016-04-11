<?php require_once("config.php"); ?>
<?php
    if(isset($_GET['add'])){

//        $_SESSION['product_' . $_GET['add']] += 1;
//
//        redirect('index.php');


        $query = query("SELECT * FROM products WHERE product_id = "  . escape_string($_GET['add']) ."");
        confirm($query);

        while($row = fetch_array($query)){
            if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]){
                $_SESSION['product_' . $_GET['add']] += 1;
                redirect("../public/checkout.php");
            }else{
                set_message("We have only " . $row['product_quantity'] . " " . $row['product_title'] . " " . "Available.");
                redirect("../public/checkout.php");
            }

        }
    }

    if(isset($_GET['remove'])){
        $_SESSION['product_' . $_GET['remove']]--;
        if($_SESSION['product_' . $_GET['remove']] < 1){
            unset($_SESSION['item_total']);
            unset($_SESSION['item_quantity']);
            redirect("../public/checkout.php");
        }else{
            redirect("../public/checkout.php");
        }
    }

if(isset($_GET['delete'])){
    $_SESSION['product_' . $_GET['delete']] = '0';
    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);
    redirect("../public/checkout.php");
}


function cart(){
    $total = 0;
    $item_quantity = 0;

    $item_name = 1;
    $item_number = 1;
    $amount = 1;
    $quantity = 1;

    foreach( $_SESSION as $name => $value ){
        if($value > 0){
            if(substr($name, 0, 8) == 'product_'){

                $length = strlen($name - 8);

                $id = substr($name, 8, $length);
                $query = query("SELECT * FROM products WHERE product_id = " . escape_string($id) . "");
                confirm($query);

                while( $row = fetch_array($query) ){

                    $sub = $row['product_price']*$value;
                    $item_quantity += $value;
                    $product = <<<DELIMETER
             <tr>
                <td>{$row['product_title']}</td>
                <td>&#36; {$row['product_price']}</td>
                <td>{$value}</td>
                <td>{$sub}</td>
                <td><a class='btn btn-warning' href="../system/cart.php?remove={$row['product_id']}"><span class='glyphicon glyphicon-minus'></span></a>
                <a class='btn btn-success' href="cart.php?add={$row['product_id']}"><span class='glyphicon
                glyphicon-plus'></span></a>
                <a class='btn btn-danger' href="../system/cart.php?delete={$row['product_id']}"><span class='glyphicon
                glyphicon-remove'></span></a></td>
            </tr>
            <input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}">
  <input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}">
  <input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
  <input type="hidden" name="quantity_{$quantity}" value="{$value}">
DELIMETER;

                    echo $product;

                    $item_name++;
                    $item_number++;
                    $amount++;
                    $quantity++;


                }

                $_SESSION['item_total'] = $total +=$sub;
                $_SESSION['item_quantity'] = $item_quantity;
            }
        }


    }



}

function show_paypal(){
    if(isset($_SESSION['item_quantity']) && $_SESSION['item_quantity'] >= 1){
        $paypal = <<<DELIMETER
<input type="image" name="upload"
           src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
           alt="PayPal - The safer, easier way to pay online">
DELIMETER;
        echo $paypal;
    }



}


