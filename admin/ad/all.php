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
$sort = isset($_GET['sort']) && !empty($_GET['sort']) ? $_GET['sort'] : 'a.date_added DESC';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT a.*,a.id as ad_id, a.status as ad_status, u.*,c.`name` as categorey_name,p.name as parent_name FROM ads a "
        . "LEFT JOIN categories c ON a.category_id=c.id LEFT JOIN categories p ON p.id=c.parent_id "
        . "LEFT JOIN `user` u ON (a.`userId` = u.id) ORDER BY $sort LIMIT $start_from, $limit");
$query->execute();

$ads = $query->fetchAll();
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | All Ads";
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
                        <h2>View All Ads</h2>
                        <small>
                            <p>
                                <a href="/ad_site/admin">Admin</a> > <a href="all.php">All Ads</a>
                            </p>
                        </small>
                    </div>
                </div>
            </div>
            <div class="row">
                <form action="/ad_site/admin/ad/all.php" method="GET" id="filter">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <label>Sort By </label> &ensp;
                            <select name="sort" class="" onchange="$('#filter').submit()">
                                <option <?php echo isset($sort) && $sort == 'a.date_added DESC' ? 'selected' : '' ?>
                                    value="a.date_added DESC">Ad: Latest First</option>
                                <option <?php echo isset($sort) && $sort == 'a.date_added ASC' ? 'selected' : '' ?>
                                    value="a.date_added ASC">Ad: Oldest First</option>
                                <option <?php echo isset($sort) && $sort == 'a.price DESC' ? 'selected' : '' ?>
                                    value="a.price DESC">Price: High to Low</option>
                                <option <?php echo isset($sort) && $sort == 'a.price ASC' ? 'selected' : '' ?>
                                    value="a.price ASC">Price: Low to High</option>
                                <option <?php echo isset($sort) && $sort == 'a.status DESC' ? 'selected' : '' ?>
                                    value="a.status DESC">Ad: Not Active First</option>
                                <option <?php echo isset($sort) && $sort == 'a.status ASC' ? 'selected' : '' ?>
                                    value="a.status ASC">Ad: Active First</option>
                            </select>
                        </div>
                        <table class="table table-striped table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>User</td>
                                    <td>Title</td>
                                    <td>Categorey</td>
                                    <td>Price</td>
                                    <td>Status</td>
                                    <td>Date Added</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ads)) { ?>
                                <td colspan="8">No Records Found</td>
                            <?php } else { ?>
                                <?php foreach ($ads as $ad) { ?>
                                    <tr>
                                        <td><?php echo $ad['ad_id'] ?></td>
                                        <td><a href="#"><?php echo $ad['firstname'] . ' ' . $ad['lastname'] ?></a></td>
                                        <td><?php echo $ad['title'] ?></td>
                                        <td><?php echo $ad['parent_name'] . ' > ' . $ad['categorey_name'] ?></td>
                                        <td><?php echo number_format($ad['price'], 2) ?></td>
                                        <td><?php echo ($ad['ad_status'] == '0') ? 'Pending' : ($ad['ad_status'] == '2' ? 'Rejected' : 'Active') ?></td>
                                        <td><?php echo date("d-M-Y", strtotime($ad['date_added'])) ?></td>
                                        <td>
                                            <a href="/ad_site/admin/ad/view.php?ad_id=<?php echo $ad['ad_id'] ?>" type='button' class="btn btn-info btn-sm" title="View"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            <a href="/ad_site/admin/ad/edit.php?ad_id=<?php echo $ad['ad_id'] ?>" type='button' class="btn btn-warning btn-sm"  title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                            <a href="/ad_site/controller/admin/ad/delete_ad.php?ad_id=<?php echo $ad['ad_id'] ?>" type='button' class="btn btn-danger btn-sm deleteAd"  title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>

                        <div class="text-center">
                            <ul class='pagination'>
                                <?php
                                $sql = "SELECT COUNT(id) as count FROM ads";
                                $rs_result = $link->prepare($sql);
                                $rs_result->execute();
                                $row = $rs_result->fetch();
                                $total_records = $row['count'];
                                $total_pages = ceil($total_records / $limit);
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $a = $page == $i ? 'active' : '';
                                    echo "<li class='$a'><a href='/ad_site/admin/ad/all.php?sort=$sort&page=" . $i . "'>" . $i . "</a></li>";
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
    $('.deleteAd').click(function (e) {
        e.preventDefault();
        var $link = $(this);
        if (confirm("Are you Sure want to delete this Ad?")) {
            document.location.assign($link.attr('href'));
        }
    });
</script>

</html>