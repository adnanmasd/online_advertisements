<?php
include '/templates/startSession.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . "/ad_site/login.php?return=" . ($_SERVER['REQUEST_URI']));
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Post A New Advertisement";
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
                        <h2>Post A new Ad</h2>
                        <small><p>
                                <a href="/ad_site">Home</a> > <a href="post_ad.php">Post New Ad</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="controller/new_ad.php" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <div class="form-group required">
                            <label>Categorey</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="parent" id="parent" class="form-control" onchange="populate_child()" required="">
                                        <?php
                                        include_once 'scripts/parent_cat.php';
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
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Description</label>
                            <textarea type="text" name="description" placeholder="Enter Description" required class="form-control"
                                      data-parsley-trigger="keyup" data-parsley-minlength="10" rows="5" data-parsley-errors-container=".errorspandescription" 
                                      data-parsley-required-message="Please enter description."  
                                      data-parsley-pattern-message="Your description must be longer than (10) characters."></textarea>
                            <span class="errorspandescription"></span>
                        </div>
                        <div class="form-group required">
                            <label>Price</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rs.</span>
                                <input type="number" name="price"  placeholder="Enter price upto (2) decimals e.g. 9.99" required class="form-control" 
                                       step="0.01">
                                <span class="input-group-addon">PKR</span>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label>Condition</label><br/>
                            <p>
                                <input type="radio" name="condition" value="New" required="" /> New  &ensp;&ensp;
                                <input type="radio" name="condition" value="Used" /> Used &ensp;&ensp;
                                <input type="radio" name="condition" value="Refurbished" /> Refurbished &ensp;&ensp; 
                            </p>
                        </div>
                        <div class="form-group required">
                            <label>Image 1</label>
                            <input type="file" required="" name="image1"  data-parsley-filemaxmegabytes="2" 
                                   data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg">
                        </div>
                        <div class="form-group">
                            <label>Image 2</label>
                            <input type="file" required="" name="image2" data-parsley-filemaxmegabytes="2" data-parsley-trigger="change" 
                                   data-parsley-filemimetypes="image/jpeg">
                        </div>
                        <div class="form-group">
                            <label>Image 3</label>
                            <input type="file" required="" name="image3" data-parsley-filemaxmegabytes="2" data-parsley-trigger="change" 
                                   data-parsley-filemimetypes="image/jpeg">
                        </div>
                        <div class="form-group">
                            <label>Image 4</label>
                            <input type="file" required="" name="image4" data-parsley-filemaxmegabytes="2" data-parsley-trigger="change" 
                                   data-parsley-filemimetypes="image/jpeg">
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Submit" class="btn btn-success form-buttons">
                            <input type="reset" value="Reset" class="btn btn-danger form-buttons">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- end of .container --> 
</body>
</html>


<?php require '/templates/footer.php'; ?>
<?php require '/templates/js_scripts.php'; ?>
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
            url: './scripts/child_cat.php',
            data: 'parent_id=' + $('#parent').val(),
            success: function (html) {
                $('#child').html(html);
            }
        });
    }
</script>
</html>