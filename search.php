<?php
include '/templates/startSession.php';
include_once '/model/db/db.php';

$keyword = isset($_GET['keyword']) && !empty($_GET['keyword']) ? $_GET['keyword'] : null;
$category = isset($_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : null;
$city = isset($_GET['city']) && !empty($_GET['city']) ? $_GET['city'] : null;
$country = isset($_GET['country']) && !empty($_GET['country']) ? $_GET['country'] : null;
$from = isset($_GET['from']) && !empty($_GET['from']) ? $_GET['from'] : null;
$to = isset($_GET['to']) && !empty($_GET['to']) ? $_GET['to'] : null;
$sort = isset($_GET['sort']) && !empty($_GET['sort']) ? $_GET['sort'] : 'a.date_added DESC';

$limit = 15;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;

$db = new Db();
$link = $db->connect();

$q = "SELECT a.*,u.city,u.country,c.id as cat_id,c.parent_id FROM ads a LEFT JOIN `user` u ON u.id=a.`userId` "
        . "LEFT JOIN categories c ON c.id = a.category_id  WHERE a.status = '1' ";
$p = "SELECT COUNT(a.id) as count FROM ads a LEFT JOIN categories c ON c.id = a.category_id LEFT JOIN `user` u ON u.id=a.`userId` WHERE a.status = '1' ";
if (isset($keyword)) {
    $q .= "AND a.title LIKE '%" . $keyword . "%' ";
    $p .= "AND a.title LIKE '%" . $keyword . "%' ";
}
if (isset($category)) {
    $q .= "AND (a.category_id = '" . $category . "' OR c.parent_id = '" . $category . "') ";
    $p .= "AND (a.category_id = '" . $category . "' OR c.parent_id = '" . $category . "') ";
}
if (isset($city)) {
    $q .= "AND u.city = '" . $city . "' ";
    $p .= "AND u.city = '" . $city . "' ";
}
if (isset($country)) {
    $q .= "AND u.country = '" . $country . "' ";
    $p .= "AND u.country = '" . $country . "' ";
}
if (isset($from)) {
    $q .= "AND a.price >= '" . $from . "' ";
    $p .= "AND a.price >= '" . $from . "' ";
}
if (isset($to)) {
    $q .= "AND a.price <= '" . $to . "' ";
    $p .= "AND a.price <= '" . $to . "' ";
}
$q .= " ORDER BY $sort LIMIT $start_from, $limit";
$query = $link->query($q);
$ads = $query->fetchAll();

$pagination = $link->query($p);
$pages = $pagination->fetch();

$min = 0;
$r = $link->query("SELECT max(a.price) as max FROM ads a WHERE a.status = '1'")->fetch();
$max = $r['max'] ? $r['max'] : 1000;
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | " . $keyword . " - Search Result";
        require '/templates/head.php';
        ?>
        <style>
            .ad{
                min-height: 200px;
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
                    <div class="page-header">
                        <h2>Search Results</h2>
                        <small><p>
                                <a href="/ad_site">Home</a> > <a href="search.php">Search Results</a>
                            </p></small>
                    </div>
                    <form action="/ad_site/search.php" method="GET" id="filter">
                        <div class="col-sm-3">
                            <div class="">
                                <div class="form-group">
                                    <label>Search For:</label>
                                    <input id="searchKeyword" class="form-control" name="keyword"
                                           placeholder="Enter something to search ..." 
                                           value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="amount">Price:</label>
                                    <input type="text" id="amount" readonly style="border:0; color:#000; margin: 10px auto">
                                    <input type="hidden" id="from" name="from">
                                    <input type="hidden" id="to" name="to" >
                                    <div id="slider-range"></div>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category" class="form-control">
                                        <?php include './scripts/search_cat.php'; ?>
                                        <?php
                                        foreach ($options as $option) {
                                            echo $option;
                                        }
                                        ?>                                  
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="country" id="country" class="form-control" onchange="populate_cities()">
                                        <?php include './scripts/country.php'; ?>
                                        <option value="">- All Countries -</option>
                                        <?php
                                        foreach ($countries as $c) {
                                            $selected = isset($_GET['country']) && $_GET['country'] == $c['id'] ? 'selected' : '';
                                            echo "<option $s value='" . $c['id'] . "'>" . $c['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city" id="city" class="form-control">
                                        <option value="">- All Cities -</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success pull-right">Apply</button>
                                <a href="search.php?keyword=<?php echo $keyword ?>" class="btn btn-danger pull-left">Reset</a>

                            </div>
                        </div>
                        <div class="col-sm-9">
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
                                </select>
                            </div>
                            <br/>
                            <br/>
                            <?php if (!empty($ads)) { ?>
                                <?php foreach ($ads as $key => $ad) { ?>
                                    <div class="row ad">
                                        <div class="col-sm-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <span class="pull-left h4"><?php echo $ad['title'] ?></span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body">
                                                    <img class="img-responsive pull-left thumbnail" style="margin: 0 10px" 
                                                         src="/ad_site/controller/view_image.php?path=<?php echo $ad['image1'] ?>&w=100&h=100" alt="...">
                                                    <p><?php echo substr($ad['description'], 0, 300) . (strlen($ad['description']) > 300 ? '...' : '') ?></p>
                                                </div>
                                                <div class="panel-footer">
                                                    <span class="pull-left"><?php echo 'Rs. ' . $ad['price'] ?></span>
                                                    <a href="ad.php?id=<?php echo $ad['id'] ?>" class="btn btn-primary btn-xs pull-right" role="button">Show More</a>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                <?php } ?>
                                <div class="text-center">
                                    <ul class='pagination'>
                                        <?php
                                        $total_records = $pages['count'];
                                        $total_pages = ceil($total_records / $limit);
                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            $a = $page == $i ? 'active' : '';
                                            $filters = "";
                                            if (isset($keyword)) {
                                                $filters .= "&keyword=$keyword";
                                            }
                                            if (isset($from)) {
                                                $filters .= "&from=$from";
                                            }
                                            if (isset($to)) {
                                                $filters .= "&to=$to";
                                            }
                                            if (isset($category)) {
                                                $filters .= "&category=$category";
                                            }
                                            if (isset($country)) {
                                                $filters .= "&country=$country";
                                            }
                                            if (isset($city)) {
                                                $filters .= "&city=$city";
                                            }
                                            if (isset($sort)) {
                                                $filters .= "&sort=$sort";
                                            }
                                            echo "<li class='$a'><a href='/ad_site/search.php?" . $filters . "&page=" . $i . "'>" . $i . "</a></li>";
                                        };
                                        ?>
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-info">No Result Found !</div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>


<?php require '/templates/footer.php'; ?>
<?php require '/templates/js_scripts.php'; ?>
<script>
    $(function () {
        $("#slider-range").slider({
            range: true,
            step: 0.01,
            min: <?php echo $min ?>,
            max: <?php echo $max ?>,
            values: [<?php echo isset($_GET['from']) ? $_GET['from'] : $min ?>, <?php echo isset($_GET['to']) ? $_GET['to'] : $max ?>],
            slide: function (event, ui) {
                $("#amount").val("Rs. " + ui.values[0] + " - Rs. " + ui.values[1]);
                $("#from").val(ui.values[0]);
                $("#to").val(ui.values[1]);
            }
        });
        $("#amount").val("Rs. " + $("#slider-range").slider("values", 0) +
                " - Rs. " + $("#slider-range").slider("values", 1));
        $("#from").val($("#slider-range").slider("values", 0));
        $("#to").val($("#slider-range").slider("values", 1));
    });
</script>
<script type="text/javascript">
    function populate_cities() {
        $.ajax({
            type: 'POST',
            url: 'scripts/city.php',
            data: 'country_id=' + $('#country').val(),
            success: function (html) {
                $('#city').html(html);
                $('#city').val(<?php echo isset($_GET['city']) ? $_GET['city'] : '' ?>);
            }
        });
    }

    $(document).ready(function () {
        $('#country').val(<?php echo isset($_GET['country']) ? $_GET['country'] : '' ?>);
        if ($('#country').val())
            populate_cities();
    });
</script>

<script type="text/javascript">
    $(function () {
        $("#searchKeyword").autocomplete({
            source: 'scripts/autocomplete.php',
            minLength: 2,
            focus: function (event, ui) {
                $("#searchKeyword").val(ui.item.keyword);
                return false;
            },
            select: function (event, ui) {
                $("#searchKeyword").val(ui.item.keyword);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                    .data("item.keyword", item)
                    .append("<a>" + item.keyword + "</a>")
                    .appendTo(ul);
        };
    });
</script>

<script>
    $('#filter').submit(function () {
        $(':input', this).each(function () {
            this.disabled = !($(this).val());
        });
    });
</script>


</html>