<?php $role = $_SESSION['role']; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-file-signature me-2"></i>ตรวจสอบรายการขอจัดตั้งชมรมใหม่
    </h4>
</div>

<div class="card-custom p-4">
    <?php if (empty($requests)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-folder-open fa-3x mb-3 text-secondary"></i>
            <p class="m-0 fw-bold">ไม่มีรายการคำเสนอขอจัดตั้งชมรมในระบบ</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">โลโก้</th>
                        <th>ชื่อชมรมที่เสนอขอ</th>
                        <th>อาจารย์ที่ปรึกษา</th>
                        <th>ผู้ยื่นคำขอจัดตั้ง</th>
                        <th>สถานะ Phase I</th>
                        <th class="text-center" style="width: 120px;">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $row): ?>
                    <tr>
                        <td>
                            <?php if (assetExists($row['club_logo'])): ?>
                                <img src="<?= asset($row['club_logo']) ?>" style="width:45px;height:45px;border-radius:50%;object-fit:cover;" alt="Logo">
                            <?php else: ?>
                                <div style="width:45px;height:45px;border-radius:50%;background:var(--surface-alt); display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-users text-muted small"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-primary-custom"><?= e($row['club_name']) ?></div>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;">
                                <?= e(mb_strimwidth($row['description'], 0, 100, '...')) ?>
                            </small>
                        </td>
                        <td>
                            <?php if ($row['advisor_name']): ?>
                                <span class="fw-semibold"><i class="fa-solid fa-user-tie text-primary-soft me-1"></i><?= e($row['advisor_name']) ?></span>
                            <?php else: ?>
                                <span class="text-muted small">ไม่ระบุ</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['proposer_name']): ?>
                                <div><?= e($row['proposer_name']) ?></div>
                                <small class="text-muted font-monospace"><?= e($row['proposer_student_id']) ?></small>
                            <?php else: ?>
                                <span class="text-muted small">ไม่ระบุ</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'approved'): ?>
                                <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>อนุมัติจัดตั้งแล้ว</span>
                            <?php elseif ($row['status'] === 'pending'): ?>
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner fa-spin me-1"></i>รอตรวจสอบเอกสาร</span>
                            <?php elseif ($row['status'] === 'correcting'): ?>
                                <span class="badge bg-info text-white"><i class="fa-solid fa-pen-to-square me-1"></i>ส่งกลับแก้ไขเอกสาร</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>ปฏิเสธจัดตั้ง</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= url('backoffice/clubs/requests/detail/' . (int)$row['id']) ?>" 
                               class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fa-solid fa-eye me-1"></i>ตรวจสอบ
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'backoffice/clubs/requests', $limit) ?>
    <?php endif; ?>
</div>
