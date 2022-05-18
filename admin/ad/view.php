<?php
include '../../templates/startSession.php';
include_once '../../model/db/db.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
}

if (!isset($_GET['ad_id'])) {
    header("Location: " . "/ad_site/admin/404.php");
} else {
    $id = $_GET['ad_id'];

    $db = new Db();
    $link = $db->connect();
    $q = "SELECT a.*,a.id as ad_id,u.firstname,u.lastname,u.email,u.mobile_number,c.id as child_id,c.`name` as child_name ,p.id as parent_id, p.`name` as parent_name, "
            . "city.`name` as city_name, country.`name` as country_name "
            . "FROM ads a LEFT JOIN `user` u ON u.id = a.`userId` LEFT JOIN city ON city.id = u.city "
            . "LEFT JOIN country ON country.`id` = u.country LEFT JOIN "
            . "categories c ON c.id = a.category_id LEFT JOIN categories p ON p.id = c.parent_id "
            . "WHERE a.id='$id'";
    $query = $link->query($q);
    $ad = $query->fetch();
    if (empty($ad)) {
        header("Location: " . "/ad_site/admin/404.php");
    }
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | " . $ad['title'];
        require '../../templates/admin/head.php';
        ?>
        <style>
            .adImage{
                margin: 10px 15px;
                display: inline
            }
        </style>
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
                            <a href='/ad_site/admin/ad/edit.php?ad_id=<?php echo $ad['ad_id'] ?>' class="btn btn-info">
                                <i class="glyphicon glyphicon-edit"></i> Edit</a>
                            <?php if ($ad['status'] == '0' || $ad['status'] == '2') { ?>
                                <a href='/ad_site/controller/admin/ad/approve_ad.php?ad_id=<?php echo $ad['ad_id'] ?>' class="btn btn-success">
                                    <i class="glyphicon glyphicon-thumbs-up"></i> Approve</a>
                            <?php } else { ?>    
                                <a href='/ad_site/controller/admin/ad/disapprove_ad.php?ad_id=<?php echo $ad['ad_id'] ?>' class="btn btn-warning">
                                    <i class="glyphicon glyphicon-thumbs-down"></i> Disapprove</a>
                            <?php } ?>
                            <a href='/ad_site/controller/admin/ad/delete_ad.php?ad_id=<?php echo $ad['ad_id'] ?>' class="btn btn-danger">
                                <i class="glyphicon glyphicon-remove-sign"></i> Delete</a>
                        </span>
                        <h2><?php echo $ad['title'] ?></h2>
                        <small><p>
                                <a href="#">Home</a> > 
                                <a href="#"><?php echo $ad['parent_name'] ?></a> > 
                                <a href="#"><?php echo $ad['child_name'] ?></a> > 
                                <a href="#"><?php echo $ad['title'] ?></a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <?php if (isset($ad['image1'])) { ?>
                        <a href="<?php echo $ad['image1']; ?>" target="_blank">
                            <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image1'] ?>&w=250&h=250" class="img-responsive adImage"/>
                        </a>
                    <?php } ?>
                    <?php if (isset($ad['image2'])) { ?>
                        <a href="<?php echo $ad['image2']; ?>" target="_blank">
                            <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image2'] ?>&w=250&h=250" class="img-responsive adImage"/>
                        </a>
                    <?php } ?>
                    <?php if (isset($ad['image3'])) { ?>
                        <a href="<?php echo $ad['image3']; ?>" target="_blank">
                            <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image3'] ?>&w=250&h=250" class="img-responsive adImage"/>
                        </a>
                    <?php } ?>
                    <?php if (isset($ad['image4'])) { ?>
                        <a href="<?php echo $ad['image4']; ?>" target="_blank">
                            <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image4'] ?>&w=250&h=250" class="img-responsive adImage"/>
                        </a>
                    <?php } ?>
                </div>
                <div class="col-xs-12"><br/>
                    <table class="table-bordered table table-responsive">
                        <col width="20%">
                        <col width="80%">
                        <tr>
                            <td>Price</td>
                            <td><?php echo $ad['price'] . ' PKR' ?></td>
                        </tr>
                        <tr>
                            <td>Condition</td>
                            <td><?php echo $ad['condition'] ?></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><?php echo nl2br($ad['description']) ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person Name</td>
                            <td><?php echo $ad['firstname'] . ' ' . $ad['lastname'] ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person Number</td>
                            <td><?php echo $ad['mobile_number'] ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person Email</td>
                            <td><?php echo $ad['email'] ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person Place</td>
                            <td><?php echo $ad['city_name'] . ', ' . $ad['country_name'] ?></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </body>
</html>


<?php require '../../templates/admin/footer.php'; ?>
<?php require '../../templates/admin/js_scripts.php'; ?>

</html>