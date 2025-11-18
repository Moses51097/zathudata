<?php
require "config.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$data = json_decode(file_get_contents("php://input"), true);

$amount = floatval($data["amount"] ?? 0);
$user_id = intval($data["user_id"] ?? 0);

if ($amount < 50) {
    echo json_encode(["status" => "error", "message" => "Minimum deposit is 50 MWK"]);
    exit;
}

$tx_ref = "ZATHU_" . time() . "_" . $user_id;

$stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, tx_ref, status) VALUES (?, 'deposit', ?, ?, 'pending')");
$stmt->execute([$user_id, $amount, $tx_ref]);

$payload = [
    "amount" => $amount,
    "currency" => "MWK",
    "tx_ref" => $tx_ref,
    "callback_url" => "https://YOUR_BACKEND/payment_callback.php",
    "return_url" => "https://zathutrade.gt.tc/dashboard.php",
];

$curl = curl_init("https://api.paychangu.com/payment");
curl_setopt_array($curl, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $PAYCHANGU_SECRET_KEY,
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response;
?>
