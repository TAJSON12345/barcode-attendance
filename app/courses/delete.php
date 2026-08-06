<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Check if an ID was provided
if (!isset($_GET["id"])) {
    die("Course not specified.");
}
if (!canManageCourses()) {

    die("Access Denied");

}
$id = $_GET["id"];

/*
|--------------------------------------------------------------------------
| Check if the course has attendance records
|--------------------------------------------------------------------------
*/

$check = $conn->prepare("
SELECT COUNT(*)
FROM attendance
WHERE course_id = :id
");

$check->execute([
    ":id" => $id
]);

$total = $check->fetchColumn();

if ($total > 0) {

    header("Location:list.php?error=used");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Course
|--------------------------------------------------------------------------
*/

$delete = $conn->prepare("
DELETE FROM courses
WHERE id = :id
");

$delete->execute([
    ":id" => $id
]);

header("Location:list.php?deleted=1");
exit;

?>