<?php
include '/templates/startSession.php';

if (isset($_SESSION['user'])) {
    header("Location: " . "/ad_site");
}
?>
<!doctype html>
<html>
    <head>
        <?php
        $page_title = " | Register Page";
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
                        <h2>Registration</h2>
                        <small><p>
                                <a href="/ad_site">Home</a> > <a href="register.php">Register</a>
                            </p></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form action="controller/register.php?return=<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data" class="form-horizontal"  data-parsley-validate="">
                        <div class="form-group required">
                            <label>First Name</label>
                            <input type="text" name="firstname" placeholder="Enter First Name" required class="form-control"
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Last Name</label>
                            <input type="text" name="lastname" placeholder="Enter Last Name" required class="form-control"
                                   data-parsley-trigger="keyup" data-parsley-minlength="1" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Email</label>
                            <input type="email" name="email"  placeholder="Enter email" required class="form-control">
                        </div>
                        <div class="form-group required">
                            <label>Password</label>
                            <input type="password" name="password"  placeholder="Enter password" required class="form-control"
                                   data-parsley-minlength="6" data-parsley-maxlength="60" data-parsley-errors-container=".errorspannewpassinput" 
                                   data-parsley-required-message="Please enter your new password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z]).*" 
                                   data-parsley-pattern-message="Your password must contain at least (1) lowercase and (1) uppercase letter.">
                            <span class="errorspannewpassinput"></span>
                        </div>
                        <div class="form-group required">
                            <label>Mobile Number</label>
                            <input type="tel" name="mobile"  placeholder="Enter Mobile Number" required class="form-control">
                        </div>
                        <div class="form-group required">
                            <label>Address</label>
                            <input type="text" name="address" required  placeholder="Enter Street Address" class="form-control" data-parsley-maxlength="255">
                        </div>
                        <div class="form-group required">
                            <label>Country</label>
                            <select name="country" id="country" class="form-control" onchange="populate_cities()" required="">
                                <option value="">- Select One -</option>
                                <?php
                                include_once './scripts/country.php';
                                foreach ($countries as $country) {
                                    echo "<option value='" . $country['id'] . "'>" . $country['name'] . "</option>";
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
<script type="text/javascript">
    function populate_cities() {
        $.ajax({
            type: 'POST',
            url: './scripts/city.php',
            data: 'country_id=' + $('#country').val(),
            success: function (html) {
                $('#city').html(html);
            }
        });
    }
</script>
</html>