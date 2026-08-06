<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
if (!canTakeAttendance()) {

    die("Access Denied");

}
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request");
}

$barcode = trim($_POST["barcode"]);

/*
|--------------------------------------------------------------------------
| Find the Student
|--------------------------------------------------------------------------
*/

$sql = "
SELECT *
FROM students
WHERE barcode = :barcode
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ":barcode" => $barcode
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student){
    die("Student Not Found.");
}

/*
|--------------------------------------------------------------------------
| Prevent Duplicate Attendance
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM attendance
WHERE student_id = :student_id
AND attendance_date = CURRENT_DATE
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ":student_id" => $student["id"]
]);

if($stmt->fetch()){

    die("Attendance already recorded today.");

}

/*
|--------------------------------------------------------------------------
| Save Attendance
|--------------------------------------------------------------------------
*/

$sql = "
INSERT INTO attendance
(student_id, course_id)

VALUES
(:student_id, :course_id)
";

$stmt = $conn->prepare($sql);

$stmt->execute([

    ":student_id" => $student["id"],

    ":course_id" => $student["course_id"]

]);

header("Location: history.php");

exit;