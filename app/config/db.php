<?php

// Database server (the Docker service name)
$host = "postgres";

// Database port inside the Docker network
$port = "5432";

// Database name
$dbname = "attendance_db";

// PostgreSQL username
$username = "postgres";

// PostgreSQL password
$password = "admin123";

try {

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    $conn = new PDO($dsn, $username, $password);

    // Show errors if something goes wrong
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Database Connection Failed: " . $e->getMessage());

}

?>