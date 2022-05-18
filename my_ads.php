<?php
include '/templates/startSession.php';
require_once '/model/db/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
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

$query = $link->prepare("SELECT a.*,c.`name` as categorey_name,p.name as parent_name FROM ads a LEFT JOIN categories c ON a.category_id=c.id "
        . "LEFT JOIN categories p ON p.id=c.parent_id WHERE a.`userId` = " . $_SESSION['user']['id'] . " ORDER BY $sort LIMIT $start_from, $limit");
$query->execute();

$ads = $query->fetchAll();
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | My Ads";
        require '/templates/head.php';
        ?>
    </head>
    <body>
        <?php require '/templates/nav.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php include '/templates/messages.php'; ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>My Ads</h2>
                        <small><p>
                                <a href="/ad_site">Home</a> > <a href="my_ads.php">My Ads</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form action="/ad_site/my_ads.php" method="GET" id="filter">
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
                    </form>
                    <table class="table table-striped table-responsive table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Title</td>
                                <td>Categorey</td>
                                <td>Price</td>
                                <td>Condition</td>
                                <td>Date Added</td>
                                <td>Status</td>
                                <td>Date Approved</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ads)) { ?>
                            <td colspan="9">No Records Found</td>
                        <?php } else { ?>
                            <?php foreach ($ads as $ad) { ?>
                                <tr>
                                    <td><?php echo $ad['id'] ?></td>
                                    <td><?php echo $ad['title'] ?></td>
                                    <td><?php echo $ad['parent_name'] . ' > ' . $ad['categorey_name'] ?></td>
                                    <td><?php echo number_format($ad['price'], 2) ?></td>
                                    <td><?php echo $ad['condition'] ?></td>
                                    <td><?php echo date("d-M-Y", strtotime($ad['date_added'])) ?></td>
                                    <td><?php echo $ad['status'] == 1 ? 'Active' : ($ad['status'] == 2 ? 'Rejected' : 'Not Active') ?></td>
                                    <td><?php echo $ad['date_approved'] ? $ad['date_approved'] : 'Not Approved' ?></td>
                                    <td>
                                        <a href="ad.php?id=<?php echo $ad['id'] ?>" type='button' class="btn btn-info btn-sm" title="View"><i class="glyphicon glyphicon-eye-open"></i></a>
                                        <a href="edit_ad.php?id=<?php echo $ad['id'] ?>" type='button' class="btn btn-success btn-sm"  title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
                                        <a href="/ad_site/controller/delete_ad.php?ad_id=<?php echo $ad['id'] ?>" type='button' class="btn btn-danger btn-sm deleteAd"  title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>

                    </table>
                    <div class="text-center">
                        <ul class='pagination'>
                            <?php
                            $sql = "SELECT COUNT(a.id) as count FROM ads a WHERE a.`userId` = '" . $_SESSION['user']['id'] . "'";
                            $rs_result = $link->prepare($sql);
                            $rs_result->execute();
                            $row = $rs_result->fetch();
                            $total_records = $row['count'];
                            $total_pages = ceil($total_records / $limit);
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $a = $page == $i ? 'active' : '';
                                echo "<li class='$a'><a href='/ad_site/my_ads.php?sort=" . str_replace(' ','+',$sort) . "&page=" . $i . "'>" . $i . "</a></li>";
                            };
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end of .container --> 
</body>
</html>


<?php require '/templates/footer.php'; ?>
<?php require '/templates/js_scripts.php'; ?>

<script>
    $('.deleteAd').click(function (e) {
        e.preventDefault();
        var $link = $(this);
        if (confirm("Are you Sure want to delete this Ad? This action in not undo able!")) {
            document.location.assign($link.attr('href'));
        }
    });
</script>

</html>