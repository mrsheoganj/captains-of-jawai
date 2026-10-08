<?php
session_start();
require_once __DIR__ . '/../../private/config/Database.php';
use App\Config\Database;

// Check auth
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$db = Database::getConnection();

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $enquiry_id = $_POST['enquiry_id'];
    $new_status = $_POST['status'];
    $notes = $_POST['staff_notes'] ?? '';
    
    $stmt = $db->prepare("UPDATE enquiries SET status = ?, staff_notes = ? WHERE id = ?");
    $stmt->execute([$new_status, $notes, $enquiry_id]);
    $success = "Enquiry #$enquiry_id updated successfully.";
}

// Fetch all enquiries
$stmt = $db->query("SELECT * FROM enquiries ORDER BY created_at DESC");
$enquiries = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Dashboard | Captains of Jawai</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav { display: flex; gap: 20px; margin-bottom: 30px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        .nav a { text-decoration: none; color: #4b5563; font-weight: bold; }
        .nav a.active { color: #D4AF37; }
        .nav a:hover { color: #b5952f; }
        h1 { margin-top: 0; color: #111; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-weight: 600; }
        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-new { background: #dbeafe; color: #1e3a8a; }
        .status-contacted { background: #fef08a; color: #854d0e; }
        .status-booked { background: #dcfce7; color: #166534; }
        .status-closed { background: #f3f4f6; color: #374151; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: #fff; width: 500px; margin: 100px auto; padding: 30px; border-radius: 8px; }
        textarea, select { width: 100%; padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #D4AF37; color: #fff; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; margin-top: 15px; font-weight: bold; }
        .close { float: right; cursor: pointer; font-size: 20px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav">
        <a href="../index.php">CMS (Text Editor)</a>
        <a href="index.php" class="active">CRM (Enquiries)</a>
        <a href="?logout=1" style="margin-left:auto; color: #991b1b;">Logout</a>
    </div>

    <h1>Enquiries CRM Pipeline</h1>
    <?php if (isset($success)) echo "<div style='background: #dcfce7; color: #166534; padding: 10px; margin-bottom: 20px;'>$success</div>"; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Dates</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($enquiries)): ?>
                <tr><td colspan="7">No enquiries found.</td></tr>
            <?php else: ?>
                <?php foreach ($enquiries as $eq): ?>
                <tr>
                    <td>#<?= $eq['id'] ?></td>
                    <td><?= date('M j, Y', strtotime($eq['created_at'])) ?></td>
                    <td><?= htmlspecialchars($eq['first_name'] . ' ' . $eq['last_name']) ?></td>
                    <td><?= htmlspecialchars($eq['email']) ?><br><?= htmlspecialchars($eq['phone']) ?></td>
                    <td><?= htmlspecialchars($eq['travel_dates']) ?> (<?= $eq['guests'] ?> Guests)</td>
                    <td><span class="status-badge status-<?= strtolower($eq['status']) ?>"><?= htmlspecialchars($eq['status']) ?></span></td>
                    <td><button onclick="openModal(<?= htmlspecialchars(json_encode($eq)) ?>)">Manage</button></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Manage Modal -->
<div id="manageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Manage Enquiry <span id="modalId"></span></h2>
        <form method="POST">
            <input type="hidden" name="enquiry_id" id="formEnquiryId">
            <label>Update Status:</label>
            <select name="status" id="formStatus">
                <option value="NEW">NEW</option>
                <option value="CONTACTED">CONTACTED</option>
                <option value="QUALIFIED">QUALIFIED</option>
                <option value="PROPOSAL_SENT">PROPOSAL SENT</option>
                <option value="BOOKED">BOOKED</option>
                <option value="CLOSED">CLOSED</option>
            </select>
            
            <label style="display:block; margin-top:20px;">Staff Notes (Internal):</label>
            <textarea name="staff_notes" id="formNotes" rows="5" placeholder="Add private notes regarding this booking..."></textarea>
            
            <button type="submit" name="update_status">Save Changes</button>
        </form>
    </div>
</div>

<script>
function openModal(data) {
    document.getElementById('manageModal').style.display = 'block';
    document.getElementById('modalId').innerText = '#' + data.id;
    document.getElementById('formEnquiryId').value = data.id;
    document.getElementById('formStatus').value = data.status;
    document.getElementById('formNotes').value = data.staff_notes || '';
}
function closeModal() {
    document.getElementById('manageModal').style.display = 'none';
}
</script>
</body>
</html>
