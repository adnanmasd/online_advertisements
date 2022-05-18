<?php
include '../../templates/admin/startSession.php';
include_once '../../model/db/db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
} else {
    $id = $_GET['id'];

    $db = new Db();
    $link = $db->connect();
    $q = "SELECT c.id AS base_id, c.name AS base_name, c.parent_id AS base_parent, c.inherit AS inherit FROM categories c WHERE c.id = '$id'";
    $query = $link->query($q);
    $category = $query->fetch();
    if (empty($category)) {
        header("Location: " . "/ad_site/admin/404.php");
    }
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Edit Category";
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
                            <a href='/ad_site/controller/admin/category/delete.php?id=<?php echo $category['base_id'] ?>' class="btn btn-danger">
                                <i class="glyphicon glyphicon-remove-sign"></i> Delete</a>
                        </div>
                        <h2>Edit Category</h2>
                        <small><p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/category/view.php">All Categories</a> > <a href="/ad_site/admin/category/edit.php">Edit Category</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="/ad_site/controller/admin/category/update.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <input type="hidden" value="<?php echo $category['base_id'] ?>" name="id">
                        <div class="form-group required">
                            <label>Category Name</label>
                            <input type="text" name="name" placeholder="Enter Name for Category" required class="form-control"
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255" value="<?php echo $category['base_name'] ?>">
                        </div>
                        <div class="form-group required">
                            <label>Is this category Child?</label><br/>
                            <p>
                                <input type="checkbox" name="inherit" value="Y" onchange="populate_parent()" <?php echo ($category['inherit'] == 'Y' ? 'checked' : '') ?> />  &ensp;&ensp;
                            </p>
                        </div>
                        <div id='parent_cat' class="form-group required">
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
</html>


<?php require '../../templates/admin/footer.php'; ?>
<?php require '../../templates/admin/js_scripts.php'; ?>
<script>
    function populate_parent() {
        $.ajax({
            type: 'POST',
            url: '../../scripts/parent_cat.php',
            success: function (html) {
                $('#parent_cat').html(
                        "<label>Parent Category</label>\n\
                         <select name='parent_id' required class='form-control'>\n\
                        " + html);
                $('select[name=\'parent_id\']').val(<?php echo $category['base_parent'] ?>);
            }
        });
    }


</script>
<?php if ($category['inherit'] == 'Y') { ?>
    <script>
        $(document).ready(function () {
            populate_parent();
        });
    </script>
<?php } ?>

</html>