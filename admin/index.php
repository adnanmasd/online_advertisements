<?php
include '../templates/admin/startSession.php';
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Dashboard";
        require '../templates/admin/head.php';
        ?>
        <style>
            .thumbnail > i {
                font-size: 3em;
            }
        </style>
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
                        <h1 class="text-center">Ad Site Administrator Panel
                            <p class="lead">A web portal for managing content on Ad Site.</p>
                        </h1>
                        <?php if (isset($_SESSION['admin_user'])) { ?>
                            <p class="lead">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="thumbnail text-center">
                                        <i class="glyphicon glyphicon-thumbs-up"></i>
                                        <div class="caption">
                                            <a href="/ad_site/admin/ad/pending.php">Approve Pending Ads</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="thumbnail text-center">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                        <div class="caption">
                                            <a href="/ad_site/admin/ad/all.php">View All Ads</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="thumbnail text-center">
                                        <i class="glyphicon glyphicon-user"></i>
                                        <div class="caption">
                                            <a href="/ad_site/admin/user/view.php">View All Users</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="thumbnail text-center">
                                        <i class="glyphicon glyphicon-list"></i>
                                        <div class="caption">
                                            <a href="/ad_site/admin/category/view.php">View All Categories</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="thumbnail text-center">
                                        <i class="glyphicon glyphicon-flag"></i>
                                        <div class="caption">
                                            <a href="/ad_site/admin/country/view.php">View All Countries</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="thumbnail text-center">
                                        <i class="glyphicon glyphicon-list-alt"></i>
                                        <div class="caption">
                                            <a href="/ad_site/admin/city/view.php">View All Cities</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="text-center">
                                Developed By: Adnan Masood<br/> 
                                VU-ID: BC130400421<br/>
                                Group ID: F1602947DC
                            </div>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


<?php require '../templates/admin/js_scripts.php'; ?>

</html>