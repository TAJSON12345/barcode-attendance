<?php
session_start();

if(isset($_SESSION["user_id"])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | Barcode Attendance System</title>
<link rel="icon" href="assets/images/favicon.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

    background:#0d6efd;

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

}

.login-card{

    width:420px;

    border:none;

    border-radius:15px;

    overflow:hidden;

    box-shadow:0 10px 25px rgba(0,0,0,.25);

}

.login-header{

    background:#003b8b;

    color:#fff;

    text-align:center;

    padding:25px;

}

.login-header img{

    width:70px;

    margin-bottom:10px;

}

.login-body{

    padding:30px;

    background:#fff;

}

.btn-login{

    width:100%;

}

.footer-text{

    font-size:13px;

    color:#777;

    text-align:center;

    margin-top:20px;

}

</style>

</head>

<body>

<div class="card login-card">

<div class="login-header">

<img
src="assets/images/logo.png"
alt="Federal Polytechnic Orogun Logo"
class="img-fluid mb-3"
style="width:90px;">

<h4>Federal Polytechnic Orogun</h4>

<p class="mb-0">Barcode Attendance Management System</p>

</div>

<div class="login-body">

<?php

if(isset($_GET["error"])){

echo '

<div class="alert alert-danger">

Invalid Username or Password.

</div>

';

}

?>

<form action="authenticate.php" method="POST">

<div class="mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Password

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-primary btn-login">

Login

</button>

</form>

<div class="footer-text">

<strong>Federal Polytechnic Orogun</strong><br>

Barcode Attendance Management System<br>

ND Computer Science Project<br>

© <?= date("Y") ?>

</div>

</div>

</div>

</body>

</html>