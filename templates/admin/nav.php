<!-- Default Bootstrap Navbar -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <b><a class="navbar-brand" href="/ad_site/home.php" target="_blank" title="Go Back to Site Front">Ad Site</a>
                <a class="navbar-brand" href="/ad_site/admin/index.php">| ADMIN</a></b>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (!isset($_SESSION['admin_user'])) { ?>
                    <li>
                        <a onclick="$('#login').modal('show');">Login</a>
                    </li>
                <?php } else { ?>
                    <li><a href="/ad_site/admin/ad/pending.php">Pending Ads</a></li>
                    <li><a href="/ad_site/admin/ad/all.php">View All Ads</a></li>
                    <li><a href="/ad_site/admin/user/view.php">View All Users</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Content Management <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/ad_site/admin/category/create.php">Add New Category</a></li>
                            <li><a href="/ad_site/admin/category/view.php">View All Categories</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/ad_site/admin/country/create.php">Add New Country</a></li>
                            <li><a href="/ad_site/admin/country/view.php">View All Countries</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/ad_site/admin/city/create.php">Add New City</a></li>
                            <li><a href="/ad_site/admin/city/view.php">View All City</a></li>
                        </ul>
                    </li>
                    <li><a href="/ad_site/controller/admin/logout.php">Logout</a></li>
                <?php } ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<?php if (!isset($_SESSION['admin_user']) && (strpos($_SERVER['REQUEST_URI'], 'login') === false)) { ?>
    <!-- Login Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Administrator Login</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <form style="form-horizontal" method="POST" action="../controller/admin/login.php?return=<?php echo $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Useremail (admin@adsite.com)</label>
                                    <input type="email" required name="email" value="admin@adsite.com" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Password (admin)</label>
                                    <input type="password" required name="password" value="admin" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Submit" class="btn btn-success form-buttons">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>