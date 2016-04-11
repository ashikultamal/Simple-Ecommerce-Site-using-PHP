<?php
/**
 * Created by PhpStorm.
 * User: Tamal
 * Date: 4/6/16
 * Time: 7:15 PM
**/
 require_once("../../config.php");

if(isset($_GET['id'])){
    $query = query("DELETE FROM products WHERE product_id = " . escape_string($_GET['id']) . " ");
    confirm($query);
    set_message("Product deleted successfully!");
    redirect("../../../public/admin/index.php?products");
}else{
    redirect("../../../public/admin/index.php?products");
}