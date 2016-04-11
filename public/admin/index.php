<?php
    require_once("../../system/config.php");

    include(TEMPLATE_BACK . "/header.php");
?>
<?php
    if(!isset($_SESSION['username'])){
        redirect("../../public");
    }
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dashboard <small>Statistics Overview</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <?php
                    if($_SERVER['REQUEST_URI'] == "/ecommerce/public/admin/" || $_SERVER['REQUEST_URI'] == "/ecommerce/public/admin/index.php"){
                        include(TEMPLATE_BACK . "/admin_content.php");
                    }

                    if(isset($_GET['orders'])){
                        include(TEMPLATE_BACK . "/orders.php");
                    }
                    if(isset($_GET['categories'])){
                        include(TEMPLATE_BACK . "/categories.php");
                    }
                    if(isset($_GET['products'])){
                        include(TEMPLATE_BACK . "/products.php");
                    }
                    if(isset($_GET['add_product'])){
                        include(TEMPLATE_BACK . "/add_product.php");
                    }
                    if(isset($_GET['users'])){
                        include(TEMPLATE_BACK . "/users.php");
                    }
                    if(isset($_GET['edit_product'])){
                        include(TEMPLATE_BACK . "/edit_product.php");
                    }
                    if(isset($_GET['add_user'])){
                        include(TEMPLATE_BACK . "/add_user.php");
                    }
                    if(isset($_GET['edit_user'])){
                        include(TEMPLATE_BACK . "/edit_user.php");
                    }
                    if(isset($_GET['reports'])){
                            include(TEMPLATE_BACK . "/reports.php");
                        }
                    if(isset($_GET['slides'])){
                        include(TEMPLATE_BACK . "/slides.php");
                    }
                    if(isset($_GET['delete_slide_id'])){
                        include(TEMPLATE_BACK . "/delete_slide.php");
                    }
                    ?>

            </div>
            <!-- /.container-fluid -->
            <?php include(TEMPLATE_BACK . "/footer.php"); ?>
