<?php
/**
 * Created by PhpStorm.
 * User: Tamal
 * Date: 4/6/16
 * Time: 7:15 PM
**/
 require_once("../../config.php");

if(isset($_GET['id'])){
    $query = query("DELETE FROM users WHERE user_id = " . escape_string($_GET['id']) . " ");
    confirm($query);
    set_message("User deleted successfully!");
    redirect("../../../public/admin/index.php?users");
}else{
    redirect("../../../public/admin/index.php?users");
}