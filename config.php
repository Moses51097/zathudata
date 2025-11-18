<?php
// Load env values
$DATABASE_URL = getenv("DATABASE_URL");
$PAYCHANGU_PUBLIC_KEY = getenv("PAYCHANGU_PUBLIC_KEY");
$PAYCHANGU_SECRET_KEY = getenv("PAYCHANGU_SECRET_KEY");

if (!$DATABASE_URL) {
    die("DATABASE_URL not found in environment");
}

$db = parse_url($DATABASE_URL);

$host = $db["host"];
$port = $db["port"];
$user = $db["user"];
$pass = $db["pass"];
$dbname = ltrim($db["path"], "/");

$pdo = new PDO(
    "pgsql:host=$host;port=$port;dbname=$dbname",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
?>
