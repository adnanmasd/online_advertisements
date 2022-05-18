<?php
include '/templates/startSession.php';
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | 404 Not Found";
        require '/templates/head.php';
        ?>
    </head>
    <body>
        <?php require '/templates/nav.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php include '/templates/messages.php'; ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="jumbotron">
                        <h1>404 - Not Found</h1>
                        <p class="lead">Oops! The page you are looking for is not found.</p>
                    </div>
                </div>
            </div>
        </div> <!-- end of .container --> 
    </body>
</html>


<?php require '/templates/footer.php'; ?>
<?php require '/templates/js_scripts.php'; ?>

</html>