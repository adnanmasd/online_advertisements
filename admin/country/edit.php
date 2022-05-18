<?php
include '../../templates/admin/startSession.php';
include_once '../../model/db/db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
} else {
    $id = $_GET['id'];

    $db = new Db();
    $link = $db->connect();
    $q = "SELECT * FROM country c WHERE c.id = '$id'";
    $query = $link->query($q);
    $country = $query->fetch();
    if (empty($country)) {
        header("Location: " . "/ad_site/admin/404.php");
    }
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Edit Country";
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
                        <div class="pull-right">
                            <a href='/ad_site/controller/admin/country/delete.php?id=<?php echo $country['id'] ?>' class="btn btn-danger">
                                <i class="glyphicon glyphicon-remove-sign"></i> Delete</a>
                        </div>
                        <h2>Edit Country</h2>
                        <small><p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/country/view.php">All Countries</a> > <a href="/ad_site/admin/country/edit.php?id=<?php echo $country['id'] ?>">Edit Country</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="/ad_site/controller/admin/country/update.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <input type="hidden" value="<?php echo $country['id'] ?>" name="id">
                        <div class="form-group required">
                            <label>Country Name</label>
                            <input type="text" name="name" placeholder="Enter Name for Country" required class="form-control"
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255" value="<?php echo $country['name'] ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" class="btn btn-success form-buttons">
                            <input type="reset" value="Reset" class="btn btn-danger form-buttons">
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <?php require '../../templates/admin/footer.php'; ?>
        <?php require '../../templates/admin/js_scripts.php'; ?>
    </body>
</html>