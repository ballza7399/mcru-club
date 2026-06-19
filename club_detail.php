<?php
session_start(); require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$club_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT c.*, u.name as pres_name, (SELECT COUNT(*) FROM applications WHERE club_id = c.id AND status='approved') as current_members FROM clubs c LEFT JOIN users u ON c.president_id = u.id WHERE c.id = ?");
$stmt->bind_param("i", $club_id); $stmt->execute(); $club = $stmt->get_result()->fetch_assoc();
if(!$club) { echo "ไม่พบข้อมูล"; exit; }
$app_status = null;
if ($_SESSION['role'] === 'student') {
    $chk = $conn->prepare("SELECT status FROM applications WHERE user_id = ? AND club_id = ?");
    $chk->bind_param("ii", $user_id, $club_id); $chk->execute();
    if ($row = $chk->get_result()->fetch_assoc()) $app_status = $row['status'];
}
$is_full = ($club['current_members'] >= $club['max_members']);
?>
<!DOCTYPE html><html lang="th"><head><meta charset="UTF-8"><title><?= htmlspecialchars($club['club_name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><link rel="stylesheet" href="style.css"></head><body>
<?php include 'navbar.php'; ?>
<div class="container pb-5 mt-4"><div class="row justify-content-center"><div class="col-md-8 text-center card-custom p-5">
    <?php if(!empty($club['club_logo']) && file_exists($club['club_logo'])): ?><img src="<?= htmlspecialchars($club['club_logo']) ?>" class="mb-3 rounded-circle" style="width: 120px; height:120px; object-fit:cover; border: 4px solid var(--accent-gold);"><?php endif; ?>
    <h2 class="text-primary-custom fw-bold mb-2"><?= htmlspecialchars($club['club_name']) ?></h2>
    <p class="text-muted fw-bold mb-4">ประธานชมรม: <?= $club['pres_name'] ? htmlspecialchars($club['pres_name']) : '<span class="text-danger">ยังไม่ระบุ</span>' ?></p>
    <div class="d-flex justify-content-center mb-4"><div class="border rounded px-4 py-2 bg-light"><span class="text-secondary fw-bold">สถานะรับสมาชิก: </span><span class="<?= $is_full ? 'text-danger' : 'text-success' ?> fs-5 fw-bold ms-2"><?= $club['current_members'] ?> / <?= $club['max_members'] ?></span></div></div>
    <p class="text-dark mb-4 text-start bg-light p-4 rounded-3"><?= nl2br(htmlspecialchars($club['description'])) ?></p>
    <hr class="my-4"><h5 class="fw-bold mb-3">สแกน QR Code เข้ากลุ่ม</h5>
    <?php if (!empty($club['qr_code']) && file_exists($club['qr_code'])): ?><img src="<?= htmlspecialchars($club['qr_code']) ?>" class="img-fluid mb-4 border rounded p-2" style="max-width: 200px;"><?php else: ?><p class="text-muted small border p-3 bg-light rounded d-inline-block">ยังไม่มี QR Code</p><?php endif; ?>
    <div class="mt-4">
        <?php if ($_SESSION['role'] === 'student'): ?>
            <?php if ($app_status === null): ?>
                <?php if ($is_full): ?><div class="alert alert-danger py-3 fw-bold rounded-3">❌ ชมรมนี้สมาชิกเต็มแล้ว ไม่สามารถสมัครได้</div>
                <?php else: ?><form action="apply.php" method="POST"><input type="hidden" name="club_id" value="<?= $club['id'] ?>"><button type="submit" class="btn-primary-custom w-100 py-3 fs-5">สมัครเข้าชมรมนี้</button></form><?php endif; ?>
            <?php elseif ($app_status === 'pending'): ?><div class="alert alert-warning py-3 fw-bold rounded-3">⏳ คุณได้ส่งคำขอแล้ว รอการอนุมัติ</div>
            <?php elseif ($app_status === 'approved'): ?><div class="alert alert-success py-3 fw-bold rounded-3">✅ คุณเป็นสมาชิกชมรมนี้แล้ว</div>
            <?php else: ?><div class="alert alert-danger py-3 fw-bold rounded-3">❌ คำขอสมัครถูกปฏิเสธ</div><?php endif; ?>
        <?php else: ?><div class="alert alert-info py-2 rounded-3 small">สิทธิ์ผู้ดูแล/ประธาน ไม่สามารถสมัครชมรมได้</div><?php endif; ?>
    </div>
</div></div></div></body></html>