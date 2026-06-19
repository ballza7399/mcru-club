<?php
session_start(); require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') {
    $user_id = $_SESSION['user_id']; $club_id = intval($_POST['club_id']);
    // เช็คว่าเต็มหรือยัง
    $club_data = $conn->query("SELECT max_members, (SELECT COUNT(*) FROM applications WHERE club_id = $club_id AND status='approved') as current_members FROM clubs WHERE id = $club_id")->fetch_assoc();
    if($club_data['current_members'] >= $club_data['max_members']) {
        echo "<script>alert('ชมรมนี้สมาชิกเต็มแล้ว'); window.location.href='club_detail.php?id=$club_id';</script>"; exit();
    }
    $stmt = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND club_id = ?");
    $stmt->bind_param("ii", $user_id, $club_id); $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO applications (user_id, club_id, status) VALUES (?, ?, 'pending')");
        $insert->bind_param("ii", $user_id, $club_id); $insert->execute();
        echo "<script>alert('สมัครสำเร็จ รอการอนุมัติ'); window.location.href='club_detail.php?id=$club_id';</script>";
    }
}
?>