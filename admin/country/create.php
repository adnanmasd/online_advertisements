<?php
include '../../templates/admin/startSession.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Create A New Country";
        require '../../templates/admin/head.php';
        ?>
    </head>
    <body>
        <?php require '../../templates/admin/nav.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php include '../../templates/admin/messages.php'; ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create A New Country</h2>
                        <small><p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/country/view.php">All Countries</a> > <a href="/ad_site/admin/country/create.php">Create a New Country</a> 
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="/ad_site/controller/admin/country/create.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <div class="form-group required">
                            <label>Country Name</label>
                            <input type="text" name="name" placeholder="Enter Name for Country" required class="form-control"
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" class="btn btn-success form-buttons">
                            <input type="reset" value="Reset" class="btn btn-danger form-buttons">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>


    <?php require '../../templates/admin/footer.php'; ?>
    <?php require '../../templates/admin/js_scripts.php'; ?>
</html>