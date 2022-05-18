<?php
include '../../templates/admin/startSession.php';
include_once '../../model/db/db.php';
if (!isset($_SESSION['admin_user'])) {
    header("Location: " . "/ad_site/admin/ad/login.php?return=" . ($_SERVER['REQUEST_URI']));
}

if (!isset($_GET['ad_id'])) {
    header("Location: " . "/ad_site/admin/404.php");
} else {
    $id = $_GET['ad_id'];
    $db = new Db();
    $link = $db->connect();

    $query = $link->query("SELECT a.*,c.id as child,p.id as parent FROM ads a LEFT JOIN "
            . "categories c ON c.id = a.category_id LEFT JOIN categories p ON p.id = c.parent_id "
            . "WHERE a.id='$id' AND a.`userId`='" . $_SESSION['user']['id'] . "'");

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
        $page_title = " | Edit Advertisement";
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
                        <h2>Edit Ad</h2>
                        <small><p>
                                <a href="/ad_site/admin">Admin</a> > <a href="all.php">All Ads</a> > 
                                <a href="/ad_site/admin/ad/edit.php?ad_id=<?php echo $_GET['id'] ?>">Edit Ad</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="/ad_site/controller/admin/ad/edit_ad.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <input type="hidden" name="ad_id" value="<?php echo $ad['id'] ?>">
                        <div class="form-group required">
                            <label>Categorey</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="parent" id="parent" class="form-control" onchange="populate_child()" required="">
                                        <?php
                                        include_once '../../scripts/parent_cat.php';
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="child" id="child" class="form-control" required="">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label>Title</label>
                            <input type="text" name="title" placeholder="Enter Title for the Ad" required class="form-control"
                                   value="<?php echo $ad['title'] ?>" data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Description</label>
                            <textarea type="text" name="description" placeholder="Enter Description" required class="form-control"
                                      data-parsley-trigger="keyup" data-parsley-minlength="10" rows="5" data-parsley-errors-container=".errorspandescription" 
                                      data-parsley-required-message="Please enter description."  
                                      data-parsley-pattern-message="Your description must be longer than (10) characters."><?php echo $ad['description'] ?></textarea>
                            <span class="errorspandescription"></span>
                        </div>
                        <div class="form-group required">
                            <label>Price</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rs.</span>
                                <input type="number" name="price"  placeholder="Enter price upto (2) decimals e.g. 9.99" required class="form-control" 
                                       step="0.01" value="<?php echo $ad['price'] ?>">
                                <span class="input-group-addon">PKR</span>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label>Condition</label><br/>
                            <p>
                                <input type="radio" name="condition" value="New" required="" <?php echo ($ad['condition'] === 'New') ? 'checked' : '' ?> />
                                New  &ensp;&ensp;
                                <input type="radio" name="condition" value="Used"  <?php echo ($ad['condition'] === 'Used') ? 'checked' : '' ?> /> 
                                Used &ensp;&ensp;
                                <input type="radio" name="condition" value="Refurbished"  <?php echo ($ad['condition'] === 'Refurbished') ? 'checked' : '' ?>/>
                                Refurbished &ensp;&ensp; 
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group required">
                                    <label>Image 1</label>
                                    <?php if (isset($ad['image1'])) { ?>
                                        <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image1'] ?>" class="img-responsive"/>
                                    <?php } else { ?>
                                        <input type="file" required="" name="image1"  data-parsley-filemaxmegabytes="2" 
                                               data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg">
                                           <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <br/>
                                <br/>
                                <div class="form-group ">
                                    <label>Change Image 1</label>
                                    <input type="file" name="image1" data-parsley-filemaxmegabytes="2" 
                                           data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Image 2</label>
                                    <?php if (isset($ad['image2'])) { ?>
                                        <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image2'] ?>" class="img-responsive"/>
                                    <?php } else { ?>
                                        <input type="file" name="image2" data-parsley-filemaxmegabytes="2" data-parsley-trigger="change" 
                                               data-parsley-filemimetypes="image/jpeg">
                                           <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <?php if (isset($ad['image2'])) { ?>
                                    <br/>
                                    <br/>
                                    <div class="form-group ">
                                        <label>Change Image 2</label>
                                        <input type="file" name="image2" data-parsley-filemaxmegabytes="2" 
                                               data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Image 3</label>
                                    <?php if (isset($ad['image3'])) { ?>
                                        <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image3'] ?>" class="img-responsive"/>
                                    <?php } else { ?>
                                        <input type="file" name="image3" data-parsley-filemaxmegabytes="2" data-parsley-trigger="change" 
                                               data-parsley-filemimetypes="image/jpeg"> 
                                           <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <?php if (isset($ad['image3'])) { ?>
                                    <br/>
                                    <br/>
                                    <div class="form-group ">
                                        <label>Change Image 3</label>
                                        <input type="file" name="image3" data-parsley-filemaxmegabytes="2" 
                                               data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Image 4</label>
                                    <?php if (isset($ad['image4'])) { ?>
                                        <img src="/ad_site/controller/view_image.php?path=<?php echo $ad['image4'] ?>" class="img-responsive"/>
                                    <?php } else { ?>
                                        <input type="file" name="image4" data-parsley-filemaxmegabytes="2" data-parsley-trigger="change" 
                                               data-parsley-filemimetypes="image/jpeg"> 
                                           <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <?php if (isset($ad['image4'])) { ?>
                                    <br/>
                                    <br/>
                                    <div class="form-group ">
                                        <label>Change Image 4</label>
                                        <input type="file" name="image4" data-parsley-filemaxmegabytes="2" 
                                               data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg" />
                                    </div>
                                <?php } ?>
                            </div>
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
        <script>
            var app = app || {};

        // Utils
            (function ($, app) {
                'use strict';

                app.utils = {};

                app.utils.formDataSuppoerted = (function () {
                    return !!('FormData' in window);
                }());

            }(jQuery, app));

        // Parsley validators
            (function ($, app) {
                'use strict';

                window.Parsley
                        .addValidator('filemaxmegabytes', {
                            requirementType: 'string',
                            validateString: function (value, requirement, parsleyInstance) {

                                if (!app.utils.formDataSuppoerted) {
                                    return true;
                                }

                                var file = parsleyInstance.$element[0].files;
                                var maxBytes = requirement * 1048576;

                                if (file.length == 0) {
                                    return true;
                                }

                                return file.length === 1 && file[0].size <= maxBytes;

                            },
                            messages: {
                                en: 'File is to big'
                            }
                        })
                        .addValidator('filemimetypes', {
                            requirementType: 'string',
                            validateString: function (value, requirement, parsleyInstance) {

                                if (!app.utils.formDataSuppoerted) {
                                    return true;
                                }

                                var file = parsleyInstance.$element[0].files;

                                if (file.length == 0) {
                                    return true;
                                }

                                var allowedMimeTypes = requirement.replace(/\s/g, "").split(',');
                                return allowedMimeTypes.indexOf(file[0].type) !== -1;

                            },
                            messages: {
                                en: 'Only jpg or jpeg are allowed'
                            }
                        });

            }(jQuery, app));


        </script>

        <script type="text/javascript">
            function populate_child() {
                $.ajax({
                    type: 'POST',
                    url: '../../scripts/child_cat.php',
                    data: 'parent_id=' + $('#parent').val(),
                    success: function (html) {
                        $('#child').html(html);
                        if ($('#parent').val() == <?php echo $ad['parent'] ?>)
                            $('#child').val(<?php echo $ad['child'] ?>);
                    }
                });
            }

            $(document).ready(function () {
                $('#parent').val(<?php echo $ad['parent'] ?>);
                populate_child();
            });
        </script>
    </body>
</html>