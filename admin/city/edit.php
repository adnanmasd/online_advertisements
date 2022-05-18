<?php
include '../../templates/admin/startSession.php';
include_once '../../model/db/db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
} else {
    $id = $_GET['id'];

    $db = new Db();
    $link = $db->connect();
    $q = "SELECT * FROM city c WHERE c.id = '$id'";
    $query = $link->query($q);
    $city = $query->fetch();
    if (empty($city)) {
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
                            <a href='/ad_site/controller/admin/city/delete.php?id=<?php echo $city['id'] ?>' class="btn btn-danger">
                                <i class="glyphicon glyphicon-remove-sign"></i> Delete</a>
                        </div>
                        <h2>Edit City</h2>
                        <small><p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/city/view.php">All Countries</a> > <a href="/ad_site/admin/city/edit.php?id=<?php echo $city['id'] ?>">Edit City</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="/ad_site/controller/admin/city/update.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <input type="hidden" value="<?php echo $city['id'] ?>" name="id">
                        <div class="form-group required">
                            <label>City Name</label>
                            <input type="text" name="name" placeholder="Enter Name for City" required class="form-control" value="<?php echo $city['name'] ?>"
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Country</label>
                            <select name="country" class="form-control" required>
                                <option value="">- Select One -</option>
                                <?php
                                include '../../scripts/country.php';
                                foreach ($countries as $c) {
                                    $s = isset($city['country']) && $city['country'] == $c['id'] ? 'selected' : '';
                                    echo "<option $s value='" . $c['id'] . "'>" . $c['name'] . "</option>";
                                }
                                ?>
                            </select>
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