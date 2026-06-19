<?php
session_start(); require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$sql = "SELECT c.*, (SELECT COUNT(*) FROM applications WHERE club_id = c.id AND status='approved') as current_members FROM clubs c";
$clubs = $conn->query($sql);
?>
<!DOCTYPE html><html lang="th"><head><meta charset="UTF-8"><title>หน้าหลักชมรม</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'navbar.php'; ?>
<div class="container pb-5 mt-4">
    <h4 class="text-primary-custom fw-bold mb-4">รายชื่อชมรมที่เปิดรับสมัคร</h4>
    <div class="row g-4">
        <?php while ($row = $clubs->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100 text-center d-flex flex-column">
                <div class="club-banner"></div>
                <div class="px-3 pb-4 d-flex flex-column flex-grow-1 align-items-center">
                    <?php if(!empty($row['club_logo']) && file_exists($row['club_logo'])): ?>
                        <img src="<?= htmlspecialchars($row['club_logo']) ?>" class="club-logo-thumb" alt="Logo">
                    <?php else: ?><div class="club-logo-thumb bg-light d-flex align-items-center justify-content-center text-muted">No Image</div><?php endif; ?>
                    <h5 class="text-primary-custom fw-bold mt-3 mb-1"><?= htmlspecialchars($row['club_name']) ?></h5>
                    <div class="mb-2"><span class="badge bg-info text-dark rounded-pill">สมาชิก: <?= $row['current_members'] ?> / <?= $row['max_members'] ?></span></div>
                    <p class="text-muted small flex-grow-1 mb-4"><?= mb_substr(htmlspecialchars($row['description']), 0, 90) . '...' ?></p>
                    <a href="club_detail.php?id=<?= $row['id'] ?>" class="btn-outline-custom w-100 py-2">รายละเอียด / สมัคร</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div></body></html>