<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Code for user registration
if(isset($_POST['submit']))
{
    $name=$_POST['fullname'];
    $email=$_POST['emailid'];
    $contactno=$_POST['contactno'];
    $password=md5($_POST['password']);

    $query=mysqli_query($con,"insert into users(name,email,contactno,password) values('$name','$email','$contactno','$password')");
    if($query)
    {
        echo "<script>alert('You are successfully register');</script>";
    }
    else{
        echo "<script>alert('Not register something went wrong');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shopping Portal | Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/green.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/rateit.css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="assets/css/config.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <script type="text/javascript">
        function valid() {
            if (document.register.password.value != document.register.confirmpassword.value) {
                alert("Password and Confirm Password Field do not match  !!");
                document.register.confirmpassword.focus();
                return false;
            }
            return true;
        }
        function userAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data:'email='+$("#email").val(),
                type: "POST",
                success:function(data){
                    $("#user-availability-status1").html(data);
                    $("#loaderIcon").hide();
                },
                error:function (){}
            });
        }
    </script>
</head>
<body class="cnt-home">
<?php include('includes/top-header.php');?>
<?php include('includes/main-header.php');?>
<?php include('includes/menu-bar.php');?>

<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="home.html">Home</a></li>
                <li class='active'>Signup</li>
            </ul>
        </div>
    </div>
</div>

<div class="body-content outer-top-bd">
    <div class="container">
        <div class="sign-in-page inner-bottom-sm">
            <div class="row">
                <div class="col-md-6 col-sm-6 create-new-account">
                    <h4 class="checkout-subtitle">Create a new account</h4>
                    <form class="register-form outer-top-xs" role="form" method="post" name="register" onsubmit="return valid();">
                        <div class="form-group">
                            <label class="info-title">Full Name <span>*</span></label>
                            <input type="text" class="form-control unicase-form-control text-input" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label class="info-title">Email Address <span>*</span></label>
                            <input type="email" class="form-control unicase-form-control text-input" id="email" onBlur="userAvailability()" name="emailid" required>
                            <span id="user-availability-status1" style="font-size:12px;"></span>
                        </div>
                        <div class="form-group">
                            <label class="info-title">Contact No. <span>*</span></label>
                            <input type="text" class="form-control unicase-form-control text-input" name="contactno" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label class="info-title">Password <span>*</span></label>
                            <input type="password" class="form-control unicase-form-control text-input" name="password" required>
                        </div>
                        <div class="form-group">
                            <label class="info-title">Confirm Password <span>*</span></label>
                            <input type="password" class="form-control unicase-form-control text-input" name="confirmpassword" required>
                        </div>
                        <button type="submit" name="submit" class="btn-upper btn btn-primary checkout-page-button">Sign Up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>
