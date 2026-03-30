<?php
header("Content-Type: application/json");

/* =========================
   DATABASE CONNECTION
========================= */
$host = "localhost";
$user = "root";
$password = "";
$database = "dj";

$conn = new mysqli($host, $user, $password, $database);

// Check DB connection
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit();
}

/* =========================
   READ JSON INPUT
========================= */
$raw = file_get_contents("php://input");
$input = json_decode($raw, true);

/* =========================
   VALIDATE DATA
========================= */
if (!isset($input['temperature']) || !isset($input['humidity'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing temperature or humidity"
    ]);
    exit();
}

$temp = floatval($input['temperature']);
$hum  = floatval($input['humidity']);

/* =========================
   INSERT INTO DATABASE
========================= */
$stmt = $conn->prepare("INSERT INTO sensor_data (temperature, humidity) VALUES (?, ?)");
$stmt->bind_param("dd", $temp, $hum);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Data inserted successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database insert failed"
    ]);
}

/* =========================
   CLEANUP
========================= */
$stmt->close();
$conn->close();
?>