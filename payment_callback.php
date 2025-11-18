<?php
require "config.php";

$data = json_decode(file_get_contents("php://input"), true);

$tx_ref = $data["tx_ref"] ?? "";
$status = $data["status"] ?? "";

if (!$tx_ref) die("Invalid callback");

if ($status === "success") {
    $stmt = $pdo->prepare("UPDATE transactions SET status='successful' WHERE tx_ref=?");
    $stmt->execute([$tx_ref]);
}

echo "OK";
?>
