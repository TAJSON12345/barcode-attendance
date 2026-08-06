<?php

session_start();

require_once "config/db.php";

$username = trim($_POST["username"]);
$password = $_POST["password"];

$sql = "
SELECT *
FROM users
WHERE username = :username
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ":username"=>$username
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user && password_verify($password,$user["password"])){

    $_SESSION["user_id"] = $user["id"];

    $_SESSION["fullname"] = $user["fullname"];

    $_SESSION["role"] = $user["role"];

    header("Location: index.php");

    exit();

}

header("Location: login.php?error=1");

exit();