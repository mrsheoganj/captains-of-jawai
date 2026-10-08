<?php
// /api/enquiries/index.php
header('Content-Type: application/json');
require_once '../../../private/config/Database.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

try {
    $db = Database::getConnection();

    // In a real application, CSRF token validation would occur here.

    // Sanitize input
    $full_name = htmlspecialchars(strip_tags($_POST['full_name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags($_POST['phone'] ?? ''));
    $travel_dates = htmlspecialchars(strip_tags($_POST['travel_dates'] ?? ''));
    $adults_count = (int)($_POST['adults_count'] ?? 2);
    $children_count = (int)($_POST['children_count'] ?? 0);
    $notes = htmlspecialchars(strip_tags($_POST['notes'] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($full_name) || empty($phone)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data.']);
        exit;
    }

    $enquiry_code = 'ENQ-' . strtoupper(substr(uniqid(), -6));

    $stmt = $db->prepare("
        INSERT INTO enquiries 
        (enquiry_code, full_name, email, phone, travel_dates, adults_count, children_count, notes) 
        VALUES 
        (:enquiry_code, :full_name, :email, :phone, :travel_dates, :adults_count, :children_count, :notes)
    ");

    $stmt->execute([
        ':enquiry_code' => $enquiry_code,
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':travel_dates' => $travel_dates,
        ':adults_count' => $adults_count,
        ':children_count' => $children_count,
        ':notes' => $notes
    ]);

    // Send an email via PHPMailer (assuming it's installed via composer or included)
    // require_once '../../../vendor/autoload.php';
    // $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    // try {
    //     $mail->isSMTP();
    //     $mail->Host       = 'smtp.example.com';
    //     $mail->SMTPAuth   = true;
    //     $mail->Username   = 'user@example.com';
    //     $mail->Password   = 'secret';
    //     $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    //     $mail->Port       = 465;
    //     $mail->setFrom('no-reply@captainsofjawai.com', 'Captains of Jawai');
    //     $mail->addAddress('admin@captainsofjawai.com', 'Admin');
    //     $mail->isHTML(true);
    //     $mail->Subject = "New Expedition Inquiry: $enquiry_code";
    //     $mail->Body    = "A new inquiry was received from $full_name ($email).";
    //     $mail->send();
    // } catch (Exception $e) {
    //     // Log email failure, but don't stop the client response
    // }

    echo json_encode(['success' => true, 'enquiry_code' => $enquiry_code, 'message' => 'Your inquiry has been received. Our team will contact you shortly.']);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred.']);
}
