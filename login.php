<?php
include '/templates/startSession.php';

if (isset($_SESSION['user'])) {
    header("Location: " . "/ad_site/home.php");
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Login Page";
        require 'templates/head.php';
        ?>
    </head>
    <body>
        <?php require '/templates/nav.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php include 'templates/messages.php'; ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h2>Please Login to Continue</h2>
                        <form style="form-horizontal" method="POST" action="controller/login.php?return=<?php echo $_GET['return'] ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Useremail</label>
                                <input type="email" required name="email" value="" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" required name="password" value="" class="form-control" />
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Submit" class="btn btn-success form-buttons">
                                <input type="reset" value="Reset" class="btn btn-danger form-buttons">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- end of .container --> 
    </body>
</html>


<?php require '/templates/footer.php'; ?>
<?php require '/templates/js_scripts.php'; ?>

</html>