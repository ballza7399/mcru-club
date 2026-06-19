<?php
session_start(); require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'student') { header("Location: index.php"); exit(); }
$user_id = $_SESSION['user_id']; $role = $_SESSION['role'];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); $status = $_GET['action'] == 'approve' ? 'approved' : 'rejected';
    $can_update = ($role == 'admin' || $conn->query("SELECT id FROM clubs WHERE id=(SELECT club_id FROM applications WHERE id=$id) AND president_id=$user_id")->num_rows > 0);
    if($can_update) $conn->query("UPDATE applications SET status = '$status' WHERE id = $id");
    header("Location: manage_apps.php"); exit();
}
$sql = "SELECT a.id, u.name, u.student_id, u.faculty, u.major, u.phone, c.club_name, a.status FROM applications a JOIN users u ON a.user_id = u.id JOIN clubs c ON a.club_id = c.id " . ($role == 'admin' ? "" : "WHERE c.president_id = $user_id ") . "ORDER BY a.id DESC";
$apps = $conn->query($sql);
?>
<!DOCTYPE html><html lang="th"><head><meta charset="UTF-8"><title>จัดการคำขอเข้าชมรม</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><link rel="stylesheet" href="style.css"></head><body>
<?php include 'navbar.php'; ?>
<div class="container mt-4 pb-5"><h4 class="text-primary-custom fw-bold mb-4">จัดการคำขอเข้าชมรม (สมาชิก)</h4>
    <div class="card-custom p-4"><div class="table-responsive"><table class="table align-middle text-nowrap"><thead class="table-light"><tr><th>รหัสนักศึกษา</th><th>ชื่อ-นามสกุล</th><th>คณะ/สาขา</th><th>เบอร์โทร</th><th>ชมรม</th><th>สถานะ</th><th>จัดการ</th></tr></thead><tbody>
        <?php while ($row = $apps->fetch_assoc()): ?>
        <tr><td class="fw-bold text-primary-custom"><?= htmlspecialchars($row['student_id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td><td><?= htmlspecialchars($row['faculty']) ?><br><small class="text-muted"><?= htmlspecialchars($row['major']) ?></small></td>
        <td><?= htmlspecialchars($row['phone']) ?></td><td><span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($row['club_name']) ?></span></td>
        <td><?php if($row['status'] == 'pending') echo '<span class="badge bg-warning text-dark">รอตรวจสอบ</span>'; else if($row['status'] == 'approved') echo '<span class="badge bg-success">อนุมัติแล้ว</span>'; else echo '<span class="badge bg-danger">ปฏิเสธ</span>'; ?></td>
        <td><?php if($row['status'] == 'pending'): ?><a href="manage_apps.php?action=approve&id=<?= $row['id'] ?>" class="btn btn-sm btn-success px-3">อนุมัติ</a> <a href="manage_apps.php?action=reject&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger px-3">ปฏิเสธ</a>
        <?php else: ?><span class="text-muted small">- ดำเนินการแล้ว -</span><?php endif; ?></td></tr>
        <?php endwhile; ?>
    </tbody></table></div></div>
</div></body></html>