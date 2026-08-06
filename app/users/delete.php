<?php

require_once "../includes/auth.php";
require_once "../config/db.php";

if (!canManageUsers()) {

    die("Access Denied");

}

if (!isset($_GET["id"])) {
    die("User not found.");
}

$id = (int)$_GET["id"];

/*
|--------------------------------------------------------------------------
| Don't allow deleting yourself
|--------------------------------------------------------------------------
*/

if ($id == $_SESSION["user_id"]) {

    header("Location:list.php?selfdelete=1");
    exit();

}

/*
|--------------------------------------------------------------------------
| Check user exists
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=:id
");

$stmt->execute([
    ":id"=>$id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user){
    die("User not found.");
}

/*
|--------------------------------------------------------------------------
| Prevent deleting the last Administrator
|--------------------------------------------------------------------------
*/

if($user["role"]=="Administrator"){

    $stmt = $conn->query("
    SELECT COUNT(*)
    FROM users
    WHERE role='Administrator'
    ");

    $admins = $stmt->fetchColumn();

    if($admins <= 1){

        header("Location:list.php?lastadmin=1");
        exit();

    }

}

/*
|--------------------------------------------------------------------------
| Delete user
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
DELETE FROM users
WHERE id=:id
");

$stmt->execute([
    ":id"=>$id
]);

header("Location:list.php?deleted=1");
exit();