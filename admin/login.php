<?php
include '../templates/admin/startSession.php';

if (isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/admin/index.php");
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Login Page";
        require '../templates/admin/head.php';
        ?>
    </head>
    <body>
        <?php require '../templates/admin/nav.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php include '../templates/admin/messages.php'; ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h2>Please Login to Continue</h2>
                        <form style="form-horizontal" method="POST" action="/ad_site/controller/admin/login.php?return=<?php echo $_GET['return'] ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Useremail (admin@adsite.com)</label>
                                <input type="email" required name="email" value="" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Password (admin)</label>
                                <input type="password" required name="password" value="" class="form-control" />
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Submit" class="btn btn-success form-buttons">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- end of .container --> 
    </body>
</html>


<?php require '../templates/admin/footer.php'; ?>
<?php require '../templates/admin/js_scripts.php'; ?>

</html>