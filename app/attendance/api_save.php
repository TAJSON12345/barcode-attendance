<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

header("Content-Type: application/json");

$barcode = $_POST["barcode"] ?? "";
$department = $_POST["department"] ?? "";
$course_id = $_POST["course_id"] ?? "";

if ($barcode == "" || $department == "" || $course_id == "") {

    echo json_encode([
        "status"=>"error",
        "message"=>"Missing data."
    ]);

    exit;
}

$stmt = $conn->prepare("
SELECT *
FROM students
WHERE barcode = :barcode
");

$stmt->execute([
    ":barcode"=>$barcode
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$student){

    echo json_encode([
        "status"=>"error",
        "message"=>"Student not found."
    ]);

    exit;
}

if($student["department"] != $department){

    echo json_encode([
        "status"=>"error",
        "message"=>"Wrong Department."
    ]);

    exit;
}

$check = $conn->prepare("
SELECT id
FROM attendance
WHERE student_id=:student
AND course_id=:course
AND attendance_date=CURRENT_DATE
");

$check->execute([
    ":student"=>$student["id"],
    ":course"=>$course_id
]);

if($check->rowCount()>0){

    echo json_encode([
        "status"=>"duplicate",
        "message"=>"Attendance already taken.",
        "student"=>$student["fullname"]
    ]);

    exit;
}

$save = $conn->prepare("
INSERT INTO attendance
(student_id,course_id)
VALUES
(:student,:course)
");

$save->execute([
    ":student"=>$student["id"],
    ":course"=>$course_id
]);

echo json_encode([

    "status"=>"success",

    "message"=>"Attendance Recorded",

    "student"=>$student["fullname"],

    "student_id"=>$student["student_id"],

    "photo"=>$student["photo"]

]);