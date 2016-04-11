<?php
/**
 * Created by PhpStorm.
 * User: Tamal
 * Date: 2/16/16
 * Time: 1:36 PM
 */

$uploads_directory = "uploads";

/*Helper Function*/
function set_message($msg){
    if(!empty($msg)){
        $_SESSION['message'] = $msg;
    }else{
       $msg = "";
    }
}

function display_message(){
    if($_SESSION['message']){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location){
    header("Location: $location ");
}

function query($sql){
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED " . mysqli_error($connection));
    }
}

function escape_string($string){
    global $connection;
    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result){
    return mysqli_fetch_array($result);
}





/* get products */

function get_products(){
    $query = query("SELECT * FROM products WHERE product_quantity >=1");
    confirm($query);

    while( $row = fetch_array($query) ){
        $display_img = display_image($row['product_image']);
        $product = <<<DELIMETER
<div class="col-sm-4 col-lg-4 col-md-4 product">
                    <div class="thumbnail">
                        <a href="item.php?id={$row['product_id']}"><img src="../system/{$display_img}" alt=""></a>
                        <div class="caption">
                            <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                            <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                            </h4>
                            <p>{$row['product_description']}</p>
                            <a class="btn btn-primary" href="../system/cart.php?add={$row['product_id']}">Add To
                            Cart</a>
                        </div>

                    </div>
                </div>

DELIMETER;

        echo $product;

    }


}

/*Get Categories*/

function get_categories(){
    $query = query("SELECT * FROM categories");

    confirm($query);

    while( $row = fetch_array($query) ){
        $category_links = <<<DELIMETER
<a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;
        echo $category_links;
    }
}


function get_products_in_cat_page(){
    $query = query("SELECT * FROM products WHERE product_category_id = ". escape_string($_GET['id']) ." WHERE product_quantity >=1");
    confirm($query);

    while( $row = fetch_array($query) ){
        $product_img = display_image($row['product_image']);
        $product_in_cat = <<<DELIMETER
<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../system/{$product_img}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>{$row['product_desc_short']}.</p>
                        <p>
                            <a href="../system/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMETER;

        echo $product_in_cat;

    }


}

function get_products_in_shop_page(){
    $query = query("SELECT * FROM products");
    confirm($query);

    while( $row = fetch_array($query) ){
        $product_img = display_image($row['product_image']);
        $product_in_cat = <<<DELIMETER
<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../system/{$product_img}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>{$row['product_desc_short']}.</p>
                        <p>
                            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMETER;

        echo $product_in_cat;

    }

}

function login_user(){
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = query("SELECT * FROM users WHERE username = '{$username}' AND password='{$password}'");
        confirm($query);

        if(mysqli_num_rows($query) == 0){
            set_message('Your Username or Password is not correct');
            redirect('login.php');
        }else{
            $_SESSION['username'] = $username;
            set_message('Welcome to Admin Panel' .$username);
            redirect('admin');
        }

    }



}

function send_message(){
    if(isset($_POST['submit'])){
        $to = "tamal@crypticbd.com";
        $form_name = $_POST['name'];
        $form_email = $_POST['email'];
        $form_subject = $_POST['subject'];
        $form_message = $_POST['message'];

        $headers = "From: {$from_name} {$email}";

        $result = mail($to,$form_subject,$form_message,$headers);

        if(!$result){
            set_message("Sorry we could not send your message");
            redirect('contact.php');
        }else{
            set_message("Your Message has been sent.");
            redirect('contact.php');
        }

    }
}

function last_id(){
    global $connection;

    return mysqli_insert_id($connection);

}

/* Backend Functions */

function display_image($picture){
    global $uploads_directory;
    return $uploads_directory . DS . $picture;
}

function display_order(){
    $query = query("SELECT * FROM orders");
    confirm($query);

    while($row=fetch_array($query)){
        $orders = <<<DELIMETER
<tr>
                        <td>{$row['order_id']}</td>
                        <td>{$row['order_amount']}</td>

                        <td>{$row['order_transection']}</td>
                        <td>{$row['order_currency']}</td>
                        <td>{$row['order_status']}</td>
                        <td><a href="../../system/templates/back/delete_order.php?id={$row['order_id']}" class="btn btn-danger"><span class="glyphicon plyphicon-remove">X</span></a></td>
                    </tr>
DELIMETER;
        echo $orders;
    }
}

function show_product_cat($product_category_id){
    $category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}'");

    confirm($category_query);
    while($cat_row = fetch_array($category_query)){
        return $cat_row['cat_title'];
    }

}

function display_product(){
    $query = query("SELECT * FROM products");
    confirm($query);

    while($row=fetch_array($query)){
        $category = show_product_cat($row['product_category_id']);

        $display_img = display_image($row['product_image']);
        $products = <<<DELIMETER
<tr>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_title']}</td>
                        <td><a href="index.php?edit_product&id={$row['product_id']}"><img width='100' src="../../system/{$display_img}"></a></td>

                        <td>{$category}</td>
                        <td>{$row['product_price']}</td>
                        <td>{$row['product_quantity']}</td>
                        <td><a href="../../system/templates/back/delete_product.php?id={$row['product_id']}" class="btn btn-danger"><span class="glyphicon plyphicon-remove">X</span></a></td>
                    </tr>
DELIMETER;
        echo $products;
    }
}



function add_products(){
    if(isset($_POST['publish'])){
        $product_title = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $product_short_desc = escape_string($_POST['product_desc_short']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_image = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);

        move_uploaded_file($image_temp_location , UPLOAD_DIRECTORY . DS . $product_image);

        $product_insert_query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, product_desc_short,
product_quantity, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}',
'{$product_description}', '{$product_short_desc}', '{$product_quantity}', '{$product_image}')");
        $last_id = last_id();
        confirm($product_insert_query);

        set_message("New Product with id ($last_id) was Added");
    }
}

function show_categories_all_product_page(){
    $query = query("SELECT * FROM categories");

    confirm($query);

    while( $row = fetch_array($query) ){
        $show_cat = <<<DELIMETER
<option value="{$row['cat_id']}">{$row['cat_title']}</option>
DELIMETER;
        echo $show_cat;
    }
}


function update_products(){
    if(isset($_POST['update'])){
        $product_title = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $product_short_desc = escape_string($_POST['product_desc_short']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_image = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);

        if(empty($product_image)){
            $get_pic = query("SELECT product_image FROM products WHERE product_id =" .escape_string($_GET['id']). "");
    confirm($get_pic);
            while($pic = fetch_array($get_pic)){
                $product_image = $pic['product_image'];
            }
        }

        move_uploaded_file($image_temp_location , UPLOAD_DIRECTORY . DS . $product_image);

        $product_update_query = "UPDATE products SET ";
        $product_update_query .= "product_title = '{$product_title}', ";
        $product_update_query .= "product_category_id = '{$product_category_id}', ";
        $product_update_query .= "product_price = '{$product_price}', ";
        $product_update_query .= "product_description = '{$product_description}', ";
        $product_update_query .= "product_desc_short = '{$product_short_desc}', ";
        $product_update_query .= "product_quantity = '{$product_quantity}', ";
        $product_update_query .= "product_image = '{$product_image}' ";
        $product_update_query .= "WHERE product_id = " . escape_string($_GET['id']) ;
        confirm($product_update_query);

        $send_update_query = query($product_update_query);

        set_message("Product has been updated!");
        redirect("index.php?products");
    }
}


function show_categories_in_admin(){

    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = fetch_array($query)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        $cat = <<<DELIMETER
<tr>
                    <td>{$cat_id}</td>
                    <td>{$cat_title}</td>
                    <td><a class="btn btn-danger" href="../../system/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
                    <td></td>
                </tr>
DELIMETER;
        echo $cat;

    }

}

function add_category(){
    if(isset($_POST['add_category'])){
        $cat_title = escape_string($_POST['cat_title']);

        if(empty($cat_title) || $cat_title == " ") {
            echo "<p class='bg-danger'>This can not be empty</p>";
        }else{
            $insert_cat = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
            confirm($insert_cat);

            set_message("Category Created!");
        }

        }
}

function show_users_in_admin(){

    $query = query("SELECT * FROM users");
    confirm($query);

    while($row = fetch_array($query)){
        $user_id = $row['user_id'];
        $user_name = $row['username'];
        $user_email = $row['email'];


        $cat = <<<DELIMETER
<tr>
                    <td>{$user_id}</td>
                    <td>{$user_name}</td>
                    <td>{$user_email}</td>
                    <td><a class="btn btn-danger" href="../../system/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
                    <td></td>
                </tr>
DELIMETER;
        echo $cat;

    }

}

function add_user() {


    if(isset($_POST['add_user'])) {


        $username   = escape_string($_POST['username']);
        $email      = escape_string($_POST['email']);
        $password   = escape_string($_POST['password']);
// $user_photo = escape_string($_FILES['file']['name']);
// $photo_temp = escape_string($_FILES['file']['tmp_name']);


// move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);


        $query = query("INSERT INTO users(username,email,password) VALUES('{$username}','{$email}','{$password}')");
        confirm($query);

        set_message("USER CREATED");

        redirect("index.php?users");



    }



}

function display_reports(){


    $query = query(" SELECT * FROM reports");
    confirm($query);

    while($row = fetch_array($query)) {


        $report = <<<DELIMETER

        <tr>
             <td>{$row['report_id']}</td>
            <td>{$row['product_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_title']}
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="../../system/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

        echo $report;


    }





}


//Slides Functions
function add_slides(){
    if(isset($_POST['add_slide'])){
        $slide_title = $_POST['slide_title'];
        $slide_image = $_FILES['file']['name'];
        $slide_image_loc = $_FILES['file']['tmp_name'];

        if(empty($slide_title) || empty($slide_image)){
            echo "<p class='bg-danger'>This field cannot be empty</p>";
        }else{
            move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);

            $slide_query = query("INSERT INTO slides(slide_title, slide_image) VALUES('{$slide_title}',
            '{$slide_image}')");
            confirm($slide_query);
            set_message("Slide Added");
            redirect("index.php?slides");
        }
    }
}

function get_current_slide_in_admin(){
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);
    while($row= fetch_array($query)){
        $slide_image = display_image($row['slide_image']);
        $slides_active_admin = <<<DELIMETER

                <img class="img-responsive" src="../../system/{$slide_image}" alt="">

DELIMETER;
        echo $slides_active_admin;
    }
}

function get_active(){
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);

    while($row= fetch_array($query)){
        $slide_image = display_image($row['slide_image']);
        $slide_active = <<<DELIMETER
        <div class="item active">
            <img class="slide-image" src="../system/{$slide_image}" alt="">
        </div>
DELIMETER;
        echo $slide_active;
    }
}

function get_slides(){
    $query = query("SELECT * FROM slides");
    confirm($query);

    while($row= fetch_array($query)){
        $slide_image = display_image($row['slide_image']);
        $slides = <<<DELIMETER
            <div class="item">
                <img class="slide-image" src="../system/{$slide_image}" alt="">
            </div>
DELIMETER;
    echo $slides;
    }
}

function get_slide_thumbnails(){
    $query = query("SELECT * FROM slides ORDER BY slide_id ASC");
    confirm($query);
    while($row= fetch_array($query)) {
        $slide_image = display_image($row['slide_image']);
        $slides_thumb_admin = <<<DELIMETER

              <div class="col-xs-6 col-md-3 image_container">
        <a href="index.php?delete_slide_id={$row['slide_id']}">
            <img class="img-responsive slide_image" src="../../system/{$slide_image}" alt="">
        </a>

        <div class="caption">
            <p>{$row['slide_title']}</p>
        </div>
    </div>

DELIMETER;
        echo $slides_thumb_admin;
    }
}

