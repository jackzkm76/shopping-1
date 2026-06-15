<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Code for User login
if(isset($_POST['login']))
{
   $email=$_POST['email'];
   $password=md5($_POST['password']);
   $query=mysqli_query($con,"SELECT * FROM users WHERE email='$email' and password='$password'");
   $num=mysqli_fetch_array($query);
   if($num>0)
   {
       $extra="my-cart.php";
       $_SESSION['login']=$_POST['email'];
       $_SESSION['id']=$num['id'];
       $_SESSION['username']=$num['name'];
       $uip=$_SERVER['REMOTE_ADDR'];
       $status=1;
       $log=mysqli_query($con,"insert into userlog(userEmail,userip,status) values('".$_SESSION['login']."','$uip','$status')");
       $host=$_SERVER['HTTP_HOST'];
       $uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
       header("location:http://$host$uri/$extra");
       exit();
   }
   else
   {
       $extra="login.php";
       $email=$_POST['email'];
       $uip=$_SERVER['REMOTE_ADDR'];
       $status=0;
       $log=mysqli_query($con,"insert into userlog(userEmail,userip,status) values('$email','$uip','$status')");
       $host  = $_SERVER['HTTP_HOST'];
       $uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
       header("location:http://$host$uri/$extra");
       $_SESSION['errmsg']="Invalid email id or Password";
       exit();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shopping Portal | Signin</title>
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
                <li class='active'>Login</li>
            </ul>
        </div>
    </div>
</div>

<div class="body-content outer-top-bd">
    <div class="container">
        <div class="sign-in-page inner-bottom-sm">
            <div class="row">
                <div class="col-md-6 col-sm-6 sign-in">
                    <h4 class="">Sign In</h4>
                    <p class="">Welcome to your account.</p>
                    <form class="register-form outer-top-xs" method="post">
                        <span style="color:red;"><?php echo htmlentities($_SESSION['errmsg']); echo htmlentities($_SESSION['errmsg']=""); ?></span>
                        <div class="form-group">
                            <label class="info-title">Email Address <span>*</span></label>
                            <input type="email" name="email" class="form-control unicase-form-control text-input" required>
                        </div>
                        <div class="form-group">
                            <label class="info-title">Password <span>*</span></label>
                            <input type="password" name="password" class="form-control unicase-form-control text-input" required>
                        </div>
                        <div class="radio outer-xs">
                            <a href="forgot-password.php" class="forgot-password pull-right">Forgot your Password?</a>
                        </div>
                        <button type="submit" name="login" class="btn-upper btn btn-primary checkout-page-button">Login</button>
                    </form>
					 <div class="switch-login">
                        <a href="signup.php" class="or-login">Or signup</a>
                    </div>
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

