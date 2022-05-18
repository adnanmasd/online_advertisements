<?php
include '../../templates/admin/startSession.php';
require_once '../../model/db/db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/admin/login.php?return=" . ($_SERVER['REQUEST_URI']));
}

$limit = 15;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;
$sort = isset($_GET['sort']) && !empty($_GET['sort']) ? $_GET['sort'] : 'u.date_registered DESC';


$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT * FROM `user` u ORDER BY $sort LIMIT $start_from, $limit;");
$query->execute();

$users = $query->fetchAll();
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | All Users";
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
                        <span class="pull-right">
                            <a href='#' class="btn btn-info">
                                <i class="glyphicon glyphicon-pencil"></i> Create</a>
                        </span>
                        <h2>View All Categories</h2>
                        <small>
                            <p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/user/view.php">All Users</a>
                            </p>
                        </small>
                    </div>
                </div>
            </div>
            <div class="row">
                <form action="/ad_site/admin/user/view.php" method="GET" id="filter">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <label>Sort By </label> &ensp;
                            <select name="sort" class="" onchange="$('#filter').submit()">
                                <option <?php echo isset($sort) && $sort == 'u.date_registered DESC' ? 'selected' : '' ?>
                                    value="u.date_registered DESC">Date: Newest Registered First</option>
                                <option <?php echo isset($sort) && $sort == 'u.date_registered ASC' ? 'selected' : '' ?>
                                    value="u.date_registered ASC">Date: Oldest Registered First</option>
                                <option <?php echo isset($sort) && $sort == 'u.firstname ASC' ? 'selected' : '' ?>
                                    value="u.firstname ASC">Firstname: A-Z</option>
                                <option <?php echo isset($sort) && $sort == 'u.firstname DESC' ? 'selected' : '' ?>
                                    value="u.firstname DESC">Firstname: Z-A</option>
                                <option <?php echo isset($sort) && $sort == 'u.lastname ASC' ? 'selected' : '' ?>
                                    value="u.lastname ASC">Lastname: A-Z</option>
                                <option <?php echo isset($sort) && $sort == 'u.lastname DESC' ? 'selected' : '' ?>
                                    value="u.lastname DESC">Lastname: Z-A</option>
                            </select>
                        </div>
                        <table class="table table-striped table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>First Name</td>
                                    <td>Last Name</td>
                                    <td>Email</td>
                                    <td>Contact No.</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)) { ?>
                                <td colspan="6">No Records Found</td>
                            <?php } else { ?>
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td><?php echo $user['id'] ?></td>
                                        <td><?php echo $user['firstname'] ?></td>
                                        <td><?php echo $user['lastname'] ?></td>
                                        <td><?php echo $user['email'] ?></td>
                                        <td><?php echo $user['mobile_number'] ?></td>
                                        <td>
                                            <a href="/ad_site/admin/user/edit.php?id=<?php echo $user['id'] ?>" type='button' class="btn btn-info btn-sm"  title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                            <?php if ($user['status']) { ?>
                                                <a href="/ad_site/controller/admin/user/disable.php?id=<?php echo $user['id'] ?>" type='button' class="btn btn-warning btn-sm"  title="Disable"><i class="glyphicon glyphicon-ban-circle"></i></a>
                                            <?php } else { ?>
                                                <a href="/ad_site/controller/admin/user/enable.php?id=<?php echo $user['id'] ?>" type='button' class="btn btn-success btn-sm"  title="Enable"><i class="glyphicon glyphicon-ok"></i></a>
                                            <?php } ?>
                                            <a href="/ad_site/controller/admin/user/delete.php?id=<?php echo $user['id'] ?>" type='button' class="btn btn-danger btn-sm delete"  title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <ul class='pagination'>
                                <?php
                                $sql = "SELECT COUNT(id) as count FROM `user`";
                                $rs_result = $link->prepare($sql);
                                $rs_result->execute();
                                $row = $rs_result->fetch();
                                $total_records = $row['count'];
                                $total_pages = ceil($total_records / $limit);
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $a = $page == $i ? 'active' : '';
                                    echo "<li class='$a'><a href='/ad_site/admin/user/view.php?sort=$sort&page=" . $i . "'>" . $i . "</a></li>";
                                };
                                ?>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php require '../../templates/admin/footer.php'; ?>
        <?php require '../../templates/admin/js_scripts.php'; ?>

        <script>
            $('.delete').click(function (e) {
                e.preventDefault();
                var $link = $(this);
                if (confirm("Are you Sure want to delete this User?")) {
                    document.location.assign($link.attr('href'));
                }
            });
        </script>
    </body>
</html>