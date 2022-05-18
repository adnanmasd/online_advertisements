<?php
include '../../templates/admin/startSession.php';
include_once '../../model/db/db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
} else {
    $id = $_GET['id'];

    $db = new Db();
    $link = $db->connect();
    $q = "SELECT * FROM `user` u WHERE u.id='" . $id . "'";
    $query = $link->query($q);
    $user = $query->fetch();
    if (empty($user)) {
        header("Location: " . "/ad_site/admin/404.php");
    }
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Edit User";
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
                            <a href='/ad_site/controller/admin/user/delete.php?id=<?php echo $user['id'] ?>' class="btn btn-danger">
                                <i class="glyphicon glyphicon-remove-sign"></i> Delete</a>
                            <?php if ($user['status']) { ?>
                                <a href="/ad_site/controller/admin/user/disable.php?id=<?php echo $user['id'] ?>" type='button' class="btn btn-default" title="Disable"><i class="glyphicon glyphicon-ban-circle"></i> Disable</a>
                            <?php } else { ?>
                                <a href="/ad_site/controller/admin/user/enable.php?id=<?php echo $user['id'] ?>" type='button' class="btn btn-default" title="Enable"><i class="glyphicon glyphicon-check"></i> Enable</a>
                            <?php } ?>
                        </div>
                        <h2>Edit User</h2>
                        <small><p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/user/view.php">All User</a> > <a href="/ad_site/admin/user/edit.php">Edit User</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form action="/ad_site/controller/admin/user/update.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                        <div class="form-group required">
                            <label>First Name</label>
                            <input type="text" name="firstname" placeholder="Enter First Name" required class="form-control"
                                   value="<?php echo $user['firstname'] ?>" data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Last Name</label>
                            <input type="text" name="lastname" placeholder="Enter Last Name" required class="form-control"
                                   value="<?php echo $user['lastname'] ?>" data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Email</label>
                            <input type="email" name="email"  placeholder="Enter email" required class="form-control" value="<?php echo $user['email'] ?>">
                        </div>
                        <div class="form-group required">
                            <label>Mobile Number</label>
                            <input type="tel" name="mobile"  placeholder="Enter Mobile Number" required class="form-control" value="<?php echo $user['mobile_number'] ?>">
                        </div>
                        <div class="form-group required">
                            <label>Address</label>
                            <input type="text" name="address" required  placeholder="Enter Street Address" class="form-control" data-parsley-maxlength="255" value="<?php echo $user['address'] ?>">
                        </div>
                        <div class="form-group required">
                            <label>Country</label>
                            <select name="country" id="country" class="form-control" onchange="populate_cities()" required="">
                                <option value="">- Select One -</option>
                                <?php
                                include_once '../../scripts/country.php';
                                foreach ($countries as $country) {
                                    $s = $user['country'] == $country['id'] ? 'selected' : '';
                                    echo "<option $s value='" . $country['id'] . "'>" . $country['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group required">
                            <label>City</label>
                            <select name="city" id="city" class="form-control"  required="">
                                <option value="">- Select One -</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save" class="btn btn-success form-buttons">
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <?php require '../../templates/admin/footer.php'; ?>
        <?php require '../../templates/admin/js_scripts.php'; ?>
        <script type="text/javascript">
            function populate_cities() {
                $.ajax({
                    type: 'POST',
                    url: '../../scripts/city.php',
                    data: 'country_id=' + $('#country').val(),
                    success: function (html) {
                        $('#city').html(html);
                        if ($('#country').val() == <?php echo $user['country'] ?>)
                            $('#city').val(<?php echo $user['city'] ?>);
                    }
                });
            }

            $(document).ready(function () {
                $('#country').val(<?php echo $user['country'] ?>);
                populate_cities();
            });
        </script>
    </body>
</html>