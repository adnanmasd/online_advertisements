<?php
include '/templates/startSession.php';
include_once '/model/db/db.php';

if (!isset($_GET['id'])) {
    header("Location: " . "/ad_site/404.php");
} else {
    $id = $_GET['id'];
    $db = new Db();
    $link = $db->connect();

    $query = $link->query("SELECT a.*,u.firstname,u.lastname,u.email,u.mobile_number,c.id as child_id,c.`name` as child_name ,p.id as parent_id, p.`name` as parent_name, "
            . "city.`name` as city_name, country.`name` as country_name "
            . "FROM ads a LEFT JOIN `user` u ON u.id = a.`userId` LEFT JOIN city ON city.id = u.city "
            . "LEFT JOIN country ON country.`id` = u.country LEFT JOIN "
            . "categories c ON c.id = a.category_id LEFT JOIN categories p ON p.id = c.parent_id "
            . "WHERE a.id='$id'");

    $ad = $query->fetch();

    if (empty($ad)) {
        header("Location: " . "/ad_site/404.php");
    } else {
        $userLoggedIn = isset($_SESSION['user']) ? $_SESSION['user']['id'] : false;
        $adActive = $ad['status'] == '1';
        $userOwnerOfAd = ($userLoggedIn && ($ad['userId'] == $_SESSION['user']['id']));
        if (!$adActive && !$userOwnerOfAd)
            header("Location: " . "/ad_site/404.php");
    }
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | " . $ad['title'];
        require '/templates/head.php';
        ?>
        <style>
            .adImage{
                margin: 10px 15px;
                display: inline
            }
        </style>
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

                    <?php if (!$adActive && $userOwnerOfAd) { ?>
                        <div class="alert alert-danger"> This Ad is not active and will not be shown in search results. Only the owner of the Ad will be able to view this Ad.</div>
                    <?php } ?>
                    <div class="page-header">
                        <h2><?php echo $ad['title'] ?></h2>
                        <small><p>
                                <a href="/ad_site">Home</a> > 
                                <a href="/ad_site/search.php?category=<?php echo $ad['parent_id'] ?>"><?php echo $ad['parent_name'] ?></a> > 
                                <a href="/ad_site/search.php?category=<?php echo $ad['child_id'] ?>"><?php echo $ad['child_name'] ?></a> > 
                                <a href="ad.php?id=<?php echo $ad['id'] ?>"><?php echo $ad['title'] ?></a>
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


<?php require '/templates/footer.php'; ?>
<?php require '/templates/js_scripts.php'; ?>

</html>