<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'student') { header("Location: index.php"); exit(); }

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// การบันทึก/แก้ไข ข้อมูลชมรม
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $club_name = $_POST['club_name'];
    $description = $_POST['description'];
    $max_members = intval($_POST['max_members']);
    
    // จัดการอัปโหลดไฟล์
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    $logo_path = ""; $qr_path = "";
    
    if(isset($_FILES["logo"]) && $_FILES["logo"]["error"] == 0) {
        $logo_path = $target_dir . time() . "_logo_" . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_path);
    }
    if(isset($_FILES["qr_code"]) && $_FILES["qr_code"]["error"] == 0) {
        $qr_path = $target_dir . time() . "_qr_" . basename($_FILES["qr_code"]["name"]);
        move_uploaded_file($_FILES["qr_code"]["tmp_name"], $qr_path);
    }

    if ($action == 'add' && $role == 'admin') {
        $stmt = $conn->prepare("INSERT INTO clubs (club_name, description, max_members, club_logo, qr_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $club_name, $description, $max_members, $logo_path, $qr_path);
        $stmt->execute();
        echo "<script>alert('เพิ่มชมรมสำเร็จ'); window.location.href='manage_clubs.php';</script>";
    } 
    elseif ($action == 'edit') {
        $club_id = intval($_POST['club_id']);
        
        // เช็คสิทธิ์การแก้ไข (ถ้าเป็นประธาน ต้องเป็นชมรมของตัวเอง)
        $can_edit = false;
        if($role == 'admin') $can_edit = true;
        else {
            $chk = $conn->query("SELECT id FROM clubs WHERE id=$club_id AND president_id=$user_id")->num_rows;
            if($chk > 0) $can_edit = true;
        }

        if($can_edit) {
            $update_sql = "UPDATE clubs SET club_name=?, description=?, max_members=?";
            $params = [$club_name, $description, $max_members];
            $types = "ssi";
            
            if($logo_path != "") { $update_sql .= ", club_logo=?"; $params[] = $logo_path; $types .= "s"; }
            if($qr_path != "") { $update_sql .= ", qr_code=?"; $params[] = $qr_path; $types .= "s"; }
            
            // แอดมินสามารถเปลี่ยนประธานได้
            if($role == 'admin') {
                $pres_student_id = $_POST['pres_student_id'];
                if(!empty($pres_student_id)) {
                    $u = $conn->query("SELECT id FROM users WHERE student_id='$pres_student_id'")->fetch_assoc();
                    if($u) {
                        $pid = $u['id'];
                        $update_sql .= ", president_id=?"; $params[] = $pid; $types .= "i";
                        $conn->query("UPDATE users SET role='president' WHERE id=$pid AND role='student'");
                    }
                } else {
                     $update_sql .= ", president_id=NULL";
                }
            }

            $update_sql .= " WHERE id=?";
            $params[] = $club_id;
            $types .= "i";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            echo "<script>alert('อัปเดตข้อมูลชมรมสำเร็จ'); window.location.href='manage_clubs.php';</script>";
        }
    }
}

// ลบชมรม (Admin Only)
if(isset($_GET['delete']) && $role == 'admin') {
    $del_id = intval($_GET['delete']);
    $conn->query("DELETE FROM applications WHERE club_id=$del_id");
    $conn->query("DELETE FROM clubs WHERE id=$del_id");
    header("Location: manage_clubs.php");
    exit();
}

// ดึงข้อมูลชมรม
if($role == 'admin') {
    $sql = "SELECT c.*, u.student_id as pres_id, u.name as pres_name FROM clubs c LEFT JOIN users u ON c.president_id = u.id";
} else {
    $sql = "SELECT c.*, u.student_id as pres_id, u.name as pres_name FROM clubs c LEFT JOIN users u ON c.president_id = u.id WHERE c.president_id = $user_id";
}
$clubs = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลชมรม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary-custom fw-bold">จัดการข้อมูลชมรม</h4>
        <?php if($role == 'admin'): ?>
            <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#addClubModal">+ สร้างชมรมใหม่</button>
        <?php endif; ?>
    </div>

    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr><th>โลโก้</th><th>ชื่อชมรม</th><th>ประธาน</th><th>รับสูงสุด</th><th>จัดการ</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $modals_html = ''; // สร้างตัวแปรสำหรับเก็บ HTML ของ Modal แก้ไข
                    while($row = $clubs->fetch_assoc()): 
                    ?>
                    <tr>
                        <td>
                            <?php if(!empty($row['club_logo']) && file_exists($row['club_logo'])): ?>
                                <img src="<?= $row['club_logo'] ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                            <?php else: ?>
                                <div style="width:40px; height:40px; border-radius:50%; background:#eee;"></div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-primary-custom"><?= htmlspecialchars($row['club_name']) ?></td>
                        <td><?= $row['pres_name'] ? $row['pres_name'].'<br><small class="text-muted">('.$row['pres_id'].')</small>' : '<span class="badge bg-danger">ยังไม่ระบุ</span>' ?></td>
                        <td><?= $row['max_members'] ?> คน</td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">แก้ไข</button>
                            <?php if($role == 'admin'): ?>
                                <a href="manage_clubs.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบชมรมและข้อมูลการสมัครทั้งหมดของชมรมนี้?')">ลบ</a>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php 
                    // เก็บโค้ด Modal ของแต่ละแถวไว้ในตัวแปร ไม่ให้แสดงผลในตาราง
                    ob_start(); 
                    ?>
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">แก้ไขข้อมูลชมรม</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="club_id" value="<?= $row['id'] ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">ชื่อชมรม</label>
                                            <input type="text" name="club_name" class="form-control" value="<?= htmlspecialchars($row['club_name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">รายละเอียด</label>
                                            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($row['description']) ?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">จำกัดจำนวนสมาชิก (คน)</label>
                                                <input type="number" name="max_members" class="form-control" value="<?= $row['max_members'] ?>" required>
                                            </div>
                                            <?php if($role == 'admin'): ?>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-danger">รหัสนักศึกษาประธานชมรม</label>
                                                <input type="text" name="pres_student_id" class="form-control" value="<?= $row['pres_id'] ?>" placeholder="เว้นว่างถ้าไม่มี">
                                                <small class="text-muted">ระบบจะอัปเดตยศให้รหัสนี้เป็นประธานอัตโนมัติ</small>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row border-top pt-3 mt-2">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">อัปโหลดโลโก้ใหม่ (ถ้าต้องการเปลี่ยน)</label>
                                                <input type="file" name="logo" class="form-control" accept="image/*">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">อัปโหลด QR Code ใหม่</label>
                                                <input type="file" name="qr_code" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $modals_html .= ob_get_clean(); // นำ HTML ของ Modal ไปต่อท้ายในตัวแปร
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $modals_html ?>

<?php if($role == 'admin'): ?>
<div class="modal fade" id="addClubModal" tabindex="-1" aria-labelledby="addClubModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold" id="addClubModalLabel">สร้างชมรมใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อชมรม</label>
                        <input type="text" name="club_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">จำกัดจำนวนสมาชิก (คน)</label>
                        <input type="number" name="max_members" class="form-control" value="50" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">โลโก้ชมรม</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">QR Code</label>
                            <input type="file" name="qr_code" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">สร้างชมรม</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

</body>
</html>