<?php
session_start();
require_once 'functions.php';
if(isset($_SESSION['user'])){
    redirect("../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <link href="../img/favicon.ico" rel="icon">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div id="BackToHome">
            <a href="../index.php"><i class="fa fa-home"></i>Back To Home</a>
        </div>
        <div class="wrap-login100">
            <form class="login100-form validate-form">
					<span class="login100-form-title p-b-26">
						Register
					</span>
                <span class="login100-form-title">
						<!--<i class="zmdi zmdi-font"></i>-->
                        <i class="fa fa-user"></i>
					</span>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="First name">
                    <span class="focus-input100" data-placeholder="First name"></span>
                </div>
                <p class="reg-error"></p>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="Last name">
                    <span class="focus-input100" data-placeholder="Last name"></span>
                </div>
                <p class="reg-error"></p>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="Username">
                    <span class="focus-input100" data-placeholder="Username"></span>
                </div>
                <p class="reg-error"></p>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="Email">
                    <span class="focus-input100" data-placeholder="Email"></span>
                </div>
                <p class="reg-error"></p>
                <div class="wrap-input100 validate-input">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" name="Password">
                    <span class="focus-input100" data-placeholder="Password"></span>
                </div>
                <p class="reg-error"></p>
                <div class="wrap-input100 validate-input">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" name="Retype password">
                    <span class="focus-input100" data-placeholder="Retype password"></span>
                </div>
                <p class="reg-error"></p>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn submitBtn">
                            Register
                        </button>
                    </div>
                    <p id="responseMsg"></p>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/daterangepicker/moment.min.js"></script>
<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="../vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="../assets/js/login.js"></script>
<script src="../assets/js/custom.js"></script>

</body>
</html>
