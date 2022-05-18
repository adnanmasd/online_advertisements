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
$sort = isset($_GET['sort']) && !empty($_GET['sort']) ? $_GET['sort'] : 'p.name ASC,c.name ASC';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT c.id AS base_id, p.id AS parent_id, c.name AS base_name, p.name AS parent_name FROM categories c "
        . "LEFT JOIN categories p ON p.id = c.parent_id ORDER BY $sort LIMIT $start_from, $limit;");
$query->execute();

$categories = $query->fetchAll();
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | All Categories";
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
                            <a href='/ad_site/admin/category/create.php' class="btn btn-info">
                                <i class="glyphicon glyphicon-pencil"></i> Create</a>
                        </span>
                        <h2>View All Categories</h2>
                        <small>
                            <p>
                                <a href="/ad_site/admin">Admin</a> > <a href="/ad_site/admin/category/view.php">All Categories</a>
                            </p>
                        </small>
                    </div>
                </div>
            </div>
            <div class="row">
                <form action="/ad_site/admin/category/view.php" method="GET" id="filter">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <label>Sort By </label> &ensp;
                            <select name="sort" class="" onchange="$('#filter').submit()">
                                <option <?php echo isset($sort) && $sort == 'p.name ASC,c.name ASC' ? 'selected' : '' ?>
                                    value="p.name ASC,c.name ASC">Alphabetic: A-Z</option>
                                <option <?php echo isset($sort) && $sort == 'p.name DESC,c.name DESC' ? 'selected' : '' ?>
                                    value="p.name DESC,c.name DESC">Alphabetic: Z-A</option>
                            </select>
                        </div>
                        <table class="table table-striped table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Name</td>
                                    <td>Parent</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)) { ?>
                                <td colspan="4">No Records Found</td>
                            <?php } else { ?>
                                <?php foreach ($categories as $category) { ?>
                                    <tr>
                                        <td><?php echo $category['base_id'] ?></td>
                                        <td><?php echo $category['base_name'] ?></td>
                                        <td><?php echo $category['parent_name'] ?></td>
                                        <td>
                                            <a href="/ad_site/admin/category/edit.php?id=<?php echo $category['base_id'] ?>" type='button' class="btn btn-warning btn-sm"  title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                            <a href="/ad_site/controller/admin/category/delete.php?id=<?php echo $category['base_id'] ?>" type='button' class="btn btn-danger btn-sm delete"  title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            </tbody>

                        </table>
                        <div class="text-center">
                            <ul class='pagination'>
                                <?php
                                $sql = "SELECT COUNT(id) as count FROM categories";
                                $rs_result = $link->prepare($sql);
                                $rs_result->execute();
                                $row = $rs_result->fetch();
                                $total_records = $row['count'];
                                $total_pages = ceil($total_records / $limit);
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $a = $page == $i ? 'active' : '';
                                    echo "<li class='$a'><a href='/ad_site/admin/category/view.php?sort=$sort&page=" . $i . "'>" . $i . "</a></li>";
                                };
                                ?>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end of .container --> 
</body>
</html>


<?php require '../../templates/admin/footer.php'; ?>
<?php require '../../templates/admin/js_scripts.php'; ?>

<script>
    $('.delete').click(function (e) {
        e.preventDefault();
        var $link = $(this);
        if (confirm("Are you Sure want to delete this Category?")) {
            document.location.assign($link.attr('href'));
        }
    });
</script>

</html>