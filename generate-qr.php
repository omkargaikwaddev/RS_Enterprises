<?php
require_once __DIR__ . '/vendor/autoload.php'; // You'll need to install the QR code library
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;

$amount = $_GET['amount'] ?? 0;
$merchantUpi = "your-upi@bank"; // Replace with your actual UPI ID
$merchantName = "Riddhi Siddhi";

// Create UPI payment URL
$upiUrl = "upi://pay?pa={$merchantUpi}&pn={$merchantName}&am={$amount}&cu=INR";

// Create QR code
$qrCode = QrCode::create($upiUrl)
    ->setSize(300)
    ->setMargin(10)
    ->setForegroundColor(new Color(45, 51, 107)) // Your theme color #2D336B
    ->setBackgroundColor(new Color(255, 255, 255));

$writer = new PngWriter();
$result = $writer->write($qrCode);

header('Content-Type: ' . $result->getMimeType());
echo $result->getString(); 