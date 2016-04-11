<?php add_category(); ?>
<div id="page-wrapper">

    <div class="container-fluid">


        <h1 class="page-header">
            Product Categories

        </h1>

        <h4><?php display_message(); ?></h4>

        <div class="col-md-4">

            <form action="" method="post">

                <div class="form-group">
                    <label for="category-title">Title</label>
                    <input type="text" class="form-control" name="cat_title">
                </div>

                <div class="form-group">

                    <input type="submit" name="add_category" class="btn btn-primary" value="Add Category">
                </div>


            </form>


        </div>


        <div class="col-md-8">

            <table class="table">
                <thead>

                <tr>
                    <th>id</th>
                    <th>Title</th>
                </tr>
                </thead>


                <tbody>
                    <?php show_categories_in_admin(); ?>
                </tbody>

            </table>

        </div>


    </div>
    <!-- /.container-fluid -->

