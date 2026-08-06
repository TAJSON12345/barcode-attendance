<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
if (!canManageStudents()) {

    die("Access Denied");

}
if (!isset($_GET["id"])) {
    die("Student ID not specified.");
}

$id = $_GET["id"];

// Delete the student
$stmt = $conn->prepare("
DELETE FROM students
WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);

header("Location: list.php?deleted=1");
exit;