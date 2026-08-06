<?php

$host = getenv("DB_HOST") ?: "postgres";
$port = getenv("DB_PORT") ?: "5432";
$dbname = getenv("DB_NAME") ?: "attendance_db";
$username = getenv("DB_USER") ?: "postgres";
$password = getenv("DB_PASSWORD") ?: "admin123";

try {

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    $conn = new PDO($dsn, $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Database Connection Failed: " . $e->getMessage());

}