<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/db.php";
if (!canViewReports()) {

    die("Access Denied");

}
use Dompdf\Dompdf;
use Dompdf\Options;

$course = $_GET['course'] ?? "";
$date   = $_GET['date'] ?? "";

$courseTitle = "All Courses";

if (!empty($course)) {
    $stmtCourse = $conn->prepare("SELECT course_title FROM courses WHERE id = ?");
    $stmtCourse->execute([$course]);
    $courseTitle = $stmtCourse->fetchColumn() ?: "Unknown Course";
}

$sql = "
SELECT
    students.student_id,
    students.fullname,
    courses.course_title,
    attendance.attendance_date,
    attendance.attendance_time
FROM attendance
JOIN students
    ON attendance.student_id = students.id
JOIN courses
    ON attendance.course_id = courses.id
WHERE 1=1
";

$params = [];

if (!empty($course)) {
    $sql .= " AND attendance.course_id = :course";
    $params[':course'] = $course;
}

if (!empty($date)) {
    $sql .= " AND attendance.attendance_date = :date";
    $params[':date'] = $date;
}

$sql .= "
ORDER BY
attendance.attendance_date DESC,
attendance.attendance_time DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Build HTML
|--------------------------------------------------------------------------
*/

$html = '

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<style>

body{
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size:12px;
    margin:20px;
}

h2,h3,h4{
    text-align:center;
    margin:3px;
}

.info{
    margin-top:20px;
    margin-bottom:20px;
}

.info p{
    margin:4px 0;
    font-size:13px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table,th,td{
    border:1px solid #000;
}

th{
    background:#343a40;
    color:white;
    padding:8px;
    text-align:center;
}

td{
    padding:6px;
}

.footer{
    margin-top:20px;
    font-size:12px;
}

</style>

</head>

<body>

<h2>Federal Polytechnic Orogun</h2>

<h3>Barcode Attendance Management System</h3>

<h4>Attendance Report</h4>

<div class="info">

<p><strong>Course:</strong> '.$courseTitle.'</p>

<p><strong>Date:</strong> '.($date=="" ? "All Dates" : $date).'</p>

</div>

<table>

<tr>

<th>S/N</th>
<th>Student ID</th>
<th>Student Name</th>
<th>Course</th>
<th>Date</th>
<th>Time</th>

</tr>

';

$i = 1;

foreach($records as $row){

$html .= '

<tr>

<td align="center">'.$i++.'</td>

<td>'.htmlspecialchars($row["student_id"]).'</td>

<td>'.htmlspecialchars($row["fullname"]).'</td>

<td>'.htmlspecialchars($row["course_title"]).'</td>

<td align="center">'.$row["attendance_date"].'</td>

<td align="center">'.$row["attendance_time"].'</td>

</tr>

';

}

if(count($records)==0){

$html .= '

<tr>

<td colspan="6" align="center">

No attendance records found.

</td>

</tr>

';

}

$html .= '

</table>

<div class="footer">

<p><strong>Total Records:</strong> '.count($records).'</p>

<p><strong>Generated On:</strong> '.date("d F Y h:i:s A").'</p>

</div>

</body>

</html>

';
/*
|--------------------------------------------------------------------------
| Generate PDF
|--------------------------------------------------------------------------
*/

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

/*
|--------------------------------------------------------------------------
| Page Number
|--------------------------------------------------------------------------
*/

$canvas = $dompdf->getCanvas();

$font = $dompdf->getFontMetrics()->getFont("Helvetica", "normal");

$canvas->page_text(
    700,
    565,
    "Page {PAGE_NUM} of {PAGE_COUNT}",
    $font,
    10
);

/*
|--------------------------------------------------------------------------
| Download
|--------------------------------------------------------------------------
*/

$filename = "Attendance_Report_" . date("Y-m-d_H-i-s") . ".pdf";

$dompdf->stream($filename, [
    "Attachment" => true
]);

exit;