<!-- Default Bootstrap Navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <b><a class="navbar-brand" href="/ad_site">Ad Site</a></b>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <form name="search" id="search" class="navbar-form pull-left" action="./search.php" method="GET" style="display: inline-block">
                    <div class="form-group">
                        <div class="input-group">
                            <input id="searchInput" class="form-control" style="width: 35em" name="keyword" placeholder="Enter something to search ..." value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </span>
                        </div>
                    </div>
                </form>
                <?php if (!isset($_SESSION['user'])) { ?>
                    <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'register') !== false) ? 'active' : '' ?>">
                        <a href="./register.php">Register</a>
                    </li>

                    <li>
                        <a onclick="$('#login').modal('show');">Login</a>
                    </li>
                <?php } else { ?>
                    <li class="dropdown">
                        <a href="/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hello, <?php echo $_SESSION['user']['firstname'] ?>  <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="./post_ad.php">Post A New Ad</a></li>
                            <li><a href="./my_ads.php">View My Ads</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="./edit_profile.php">Edit My Profile</a></li>
                            <li><a href="./controller/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php } ?>

            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<?php if (!isset($_SESSION['user']) && (strpos($_SERVER['REQUEST_URI'], 'login') === false)) { ?>
    <!-- Login Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <form style="form-horizontal" method="POST" action="./controller/login.php?return=<?php echo $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>