<?php
/**
 * @var array $apps
 * @var array $club
 * @var int $currentPage
 * @var int $totalPages
 * @var int $limit
 */
$clubIdQuery = '?club_id=' . (int)$club['id'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-user-plus text-warning me-2"></i>จัดการผู้สมัครเข้าชมรม</h4>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <?php if (empty($apps)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-folder-open fs-1 mb-3 opacity-25"></i>
            <p class="m-0">ยังไม่มีผู้ยื่นขอสมัครเข้าชมรมนี้</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>คณะ / สาขาวิชา</th>
                        <th>ติดต่อ</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($apps as $row): ?>
                        <tr>
                            <td><strong><?= e($row['student_id']) ?></strong></td>
                            <td><?= e($row['name']) ?></td>
                            <td>
                                <small><?= e($row['faculty']) ?></small><br>
                                <small class="text-muted"><?= e($row['major']) ?></small>
                            </td>
                            <td>
                                <small><i class="fa-regular fa-envelope me-1"></i><?= e($row['email']) ?></small><br>
                                <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?= e($row['phone']) ?></small>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'approved'): ?>
                                    <span class="badge bg-success">อนุมัติแล้ว</span>
                                <?php elseif ($row['status'] === 'rejected'): ?>
                                    <span class="badge bg-danger">ปฏิเสธแล้ว</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">รอพิจารณา</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($row['status'] === 'pending'): ?>
                                    <a href="<?= url('cluboffice/applications/approve/' . (int)$row['id']) . $clubIdQuery ?>" 
                                       class="btn btn-sm btn-success text-white me-1"
                                       data-confirm="ยืนยันรับนักศึกษารายนี้เข้าชมรม? เมื่ออนุมัติแล้วจะเพิ่มชื่อเข้าสมาชิกชมรมโดยอัตโนมัติ"
                                       data-confirm-title="อนุมัติคำขอสมัครเข้าชมรม"
                                       data-confirm-icon="question"
                                       data-confirm-color="#198754"
                                       data-confirm-btn="อนุมัติ">
                                        <i class="fa-solid fa-check me-1"></i>อนุมัติ
                                    </a>
                                    <a href="<?= url('cluboffice/applications/reject/' . (int)$row['id']) . $clubIdQuery ?>" 
                                       class="btn btn-sm btn-danger text-white"
                                       data-confirm="ยืนยันปฏิเสธคำขอสมัครเข้าร่วมชมรมนี้?"
                                       data-confirm-title="ปฏิเสธคำขอสมัครเข้าชมรม"
                                       data-confirm-icon="warning"
                                       data-confirm-color="#dc3545"
                                       data-confirm-btn="ปฏิเสธ">
                                        <i class="fa-solid fa-xmark me-1"></i>ปฏิเสธ
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">ดำเนินการแล้ว</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'cluboffice/applications', $limit) ?>
    <?php endif; ?>
</div>
