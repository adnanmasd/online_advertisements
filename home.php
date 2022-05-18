<?php
include '/templates/startSession.php';
include_once '/model/db/db.php';

$db = new Db();
$link = $db->connect();

$query = $link->query("SELECT `id`,`name` FROM categories");

$categories = $query->fetchAll();
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Home Page";
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
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h1 class="text-center">Ad Site
                            <p class="lead">A web portal for posting and viewing Advertisements online.</p>
                        </h1>
                        <p class="lead">
                            <br/>
                            Use the searchbar above to search for different ads posted on this website. You can further 
                            refine your search results by using filters on the search result page. To start with, you can also use the follwing links:
                            <br/>
                            <br/>
                            <?php foreach ($categories as $cat) { ?>
                                <a href="search.php?category=<?php echo $cat['id'] ?>" style="margin: auto 5px"><?php echo $cat['name']?></a>
                            <?php } ?>
                            <br/>
                            <br/>
                            <?php if (!isset($_SESSION['user'])) { ?>
                                If you want to post your ad, please <a href="register.php">Register Here</a>.
                                <br/>
                                <br/>
                                If you are already registered and want to manage your posted ads, Please 
                                <a href="#" onclick="$('#login').modal('show');">Login Here</a>.
                            <?php } else { ?>
                                Perform several management tasks by clicking on the dropdown menu on top right corner of the window.
                            <?php } ?>
                        <hr/>
                        <div class="text-center">
                            Developed By: Adnan Masood<br/> 
                            VU-ID: BC130400421<br/>
                            Group ID: F1602947DC
                        </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


<?php require '/templates/js_scripts.php'; ?>

</html>