<?php $role = $_SESSION['role']; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-layer-group me-2"></i>จัดการข้อมูลการจัดตั้งชมรม</h4>
    <?php if ($role === 'admin' || $role === 'staff'): ?>
        <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#addClubModal">
            <i class="fa-solid fa-plus me-1"></i> สร้างชมรมใหม่
        </button>
    <?php endif; ?>
</div>

<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>โลโก้</th>
                    <th>ชื่อชมรม</th>
                    <th>ประธานชมรม</th>
                    <th>สถานะจัดตั้ง (Phase I)</th>
                    <th>ตรวจสอบสมาชิก (Phase II)</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clubs as $row): ?>
                <tr>
                    <td>
                        <?php if (assetExists($row['club_logo'])): ?>
                            <img src="<?= asset($row['club_logo']) ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;" alt="">
                        <?php else: ?>
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--surface-alt);"></div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold text-primary-custom"><?= e($row['club_name']) ?></td>
                    <td>
                        <?php if ($row['pres_name']): ?>
                            <?= e($row['pres_name']) ?><br>
                            <small class="text-muted"><?= e($row['pres_id']) ?></small>
                        <?php else: ?>
                            <span class="status-badge status-badge--neutral">ยังไม่ระบุ</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'approved'): ?>
                            <span class="badge bg-success">อนุมัติจัดตั้งแล้ว</span>
                        <?php elseif ($row['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">รอตรวจสอบเอกสาร</span>
                        <?php elseif ($row['status'] === 'correcting'): ?>
                            <span class="badge bg-info text-white">ส่งกลับแก้ไขเอกสาร</span>
                        <?php else: ?>
                            <span class="badge bg-danger">ปฏิเสธจัดตั้ง</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] !== 'approved'): ?>
                            <span class="text-muted small">-</span>
                        <?php else: ?>
                            <?php if ($row['member_verification_status'] === 'approved'): ?>
                                <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>อนุมัติผ่าน</span>
                            <?php elseif ($row['member_verification_status'] === 'pending'): ?>
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner fa-spin me-1"></i>รอตรวจ (<?= (int)$row['member_count'] ?> คน)</span>
                            <?php elseif ($row['member_verification_status'] === 'correcting'): ?>
                                <span class="badge bg-info text-white"><i class="fa-solid fa-pen-to-square me-1"></i>ส่งกลับแก้ไข</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">ยังไม่ส่งรายชื่อ (<?= (int)$row['member_count'] ?> คน)</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <!-- View Proposal Details Button -->
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewProposalModal<?= (int) $row['id'] ?>">
                                <i class="fa-solid fa-file-invoice me-1"></i>ดูใบเสนอขอ
                            </button>

                            <?php if ($row['status'] === 'pending' && ($role === 'admin' || $role === 'staff')): ?>
                                <a href="<?= url('backoffice/clubs/approve/' . (int) $row['id']) ?>" 
                                   class="btn btn-sm btn-success text-white"
                                   data-confirm="ยืนยันการอนุมัติจัดตั้งชมรมนี้ในระยะแรก?">
                                    <i class="fa-solid fa-circle-check me-1"></i>อนุมัติจัดตั้ง
                                </a>
                                <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#correctModal<?= $row['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>ส่งกลับแก้ไข
                                </button>
                                <button class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $row['id'] ?>">
                                    <i class="fa-solid fa-circle-xmark me-1"></i>ปฏิเสธ
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($row['status'] === 'approved' && $row['member_verification_status'] === 'pending' && ($role === 'admin' || $role === 'staff')): ?>
                                <button class="btn btn-sm btn-primary text-white" data-bs-toggle="modal" data-bs-target="#verifyMembersModal<?= $row['id'] ?>">
                                    <i class="fa-solid fa-users-viewfinder me-1"></i>ตรวจรายชื่อสมาชิก
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= (int) $row['id'] ?>">
                                <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไขข้อมูล
                            </button>
                            
                            <?php if ($row['status'] === 'approved' && ($role === 'admin' || $role === 'staff')): ?>
                                <a href="<?= url('cluboffice?club_id=' . (int) $row['id']) ?>" class="btn btn-sm btn-dark text-white">
                                    <i class="fa-solid fa-screwdriver-wrench me-1"></i>คุมหลังบ้านชมรม
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($role === 'admin' || $role === 'staff'): ?>
                                <a href="<?= url('backoffice/clubs/delete/' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-danger"
                                   data-confirm="ยืนยันการลบชมรมและข้อมูลการสมัครทั้งหมดของชมรมนี้?">
                                    <i class="fa-solid fa-trash me-1"></i>ลบ
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= renderPagination($currentPage, $totalPages, 'backoffice/clubs', $limit) ?>
</div>

<!-- Modals -->
<?php foreach ($clubs as $row): ?>
<!-- Modal: View Proposal Details -->
<div class="modal fade" id="viewProposalModal<?= (int) $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--brand">
                <h5 class="modal-title"><i class="fa-solid fa-file-invoice me-2"></i>รายละเอียดการเสนอขอก่อตั้งชมรม: <?= e($row['club_name']) ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="fw-bold text-muted small">ชื่อชมรม</label>
                    <div class="fs-5 fw-bold text-primary-custom"><?= e($row['club_name']) ?></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted small">รายละเอียดชมรมเบื้องต้น</label>
                    <p class="border p-3 rounded bg-light"><?= nl2br(e($row['description'])) ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted small">อาจารย์ที่ปรึกษาชมรม</label>
                    <div class="fw-bold"><i class="fa-solid fa-user-tie text-primary me-2"></i><?= e($row['advisor_name'] ?: 'ไม่ระบุ') ?></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted small">วัตถุประสงค์ของการจัดตั้ง</label>
                    <ul class="list-group">
                        <?php 
                        $objs = [];
                        if (!empty($row['objectives'])) {
                            $objs = json_decode($row['objectives'], true) ?: [];
                        }
                        if (empty($objs)):
                        ?>
                            <li class="list-group-item text-muted">ไม่ระบุวัตถุประสงค์</li>
                        <?php else: ?>
                            <?php foreach ($objs as $idx => $obj): ?>
                                <li class="list-group-item"><strong>ข้อที่ <?= $idx + 1 ?>:</strong> <?= e($obj) ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if ($row['establishment_document']): ?>
                <div class="mb-3 border-top pt-3">
                    <label class="fw-bold text-muted d-block mb-2">เอกสารเสนอขอก่อตั้งที่แนบมา</label>
                    <a href="<?= url($row['establishment_document']) ?>" class="btn btn-primary text-white" target="_blank">
                        <i class="fa-solid fa-download me-1"></i> ดาวน์โหลดไฟล์เอกสารเสนอจัดตั้ง (<?= e(pathinfo($row['establishment_document'], PATHINFO_EXTENSION)) ?>)
                    </a>
                </div>
                <?php endif; ?>
                
                <?php if ($row['rejection_reason']): ?>
                <div class="mb-3 border-top pt-3">
                    <label class="fw-bold text-danger d-block mb-1">รายละเอียดคำสั่งให้แก้ไข / เหตุผลที่ปฏิเสธ</label>
                    <div class="p-3 bg-danger-subtle text-danger border rounded border-danger-subtle font-monospace" style="white-space: pre-wrap;"><?= e($row['rejection_reason']) ?></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Send Back for Correction -->
<div class="modal fade" id="correctModal<?= (int) $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>ส่งกลับแก้ไขคำขอจัดตั้งชมรม</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/clubs/correct') ?>">
                <input type="hidden" name="club_id" value="<?= (int) $row['id'] ?>">
                <div class="modal-body text-start">
                    <p>ชมรม: <strong><?= e($row['club_name']) ?></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ระบุรายละเอียดสิ่งที่ต้องการให้แก้ไข <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="5" placeholder="กรุณาระบุสิ่งที่ต้องการให้นักศึกษาดำเนินการแก้ไขข้อมูล เช่น เอกสารเสนอขอก่อตั้งไม่ลงลายมือชื่อ, ให้ปรับแก้ชื่อชมรม หรือวัตถุประสงค์..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning text-dark">ส่งกลับแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Reject Club Proposal -->
<div class="modal fade" id="rejectModal<?= (int) $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-circle-xmark me-2"></i>ปฏิเสธคำขอเสนอจัดตั้งชมรม</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/clubs/reject') ?>">
                <input type="hidden" name="club_id" value="<?= (int) $row['id'] ?>">
                <div class="modal-body text-start">
                    <p>ชมรม: <strong><?= e($row['club_name']) ?></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ระบุเหตุผลการปฏิเสธจัดตั้ง <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="5" placeholder="ระบุเหตุผลการปฏิเสธการพิจารณาคำขอ..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger text-white">ปฏิเสธจัดตั้ง</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Verify Members Phase II -->
<div class="modal fade" id="verifyMembersModal<?= (int) $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-users-viewfinder me-2"></i>ตรวจสอบเงื่อนไขและรายชื่อสมาชิก: <?= e($row['club_name']) ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <h6 class="fw-bold text-primary-custom mb-3 border-bottom pb-2">เกณฑ์และสถานะการตรวจสอบรายชื่อสมาชิก (เป้าหมาย: อย่างน้อย 50 คน และมาจากอย่างน้อย 3 คณะ)</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded <?= $row['member_count'] >= 50 ? 'bg-success-subtle border-success-subtle text-success' : 'bg-danger-subtle border-danger-subtle text-danger' ?>">
                            <div class="small fw-bold">1. จำนวนสมาชิกทั้งหมด</div>
                            <div class="fs-4 fw-bold mt-1"><?= (int)$row['member_count'] ?> / 50 คน</div>
                            <div class="small mt-1">
                                <?php if ($row['member_count'] >= 50): ?>
                                    <i class="fa-solid fa-circle-check me-1"></i> ผ่านเกณฑ์ขั้นต่ำ
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-xmark me-1"></i> ยังไม่ครบ 50 คน
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded <?= $row['unique_faculties'] >= 3 ? 'bg-success-subtle border-success-subtle text-success' : 'bg-danger-subtle border-danger-subtle text-danger' ?>">
                            <div class="small fw-bold">2. ความหลากหลายของคณะ</div>
                            <div class="fs-4 fw-bold mt-1"><?= (int)$row['unique_faculties'] ?> / 3 คณะ</div>
                            <div class="small mt-1">
                                <?php if ($row['unique_faculties'] >= 3): ?>
                                    <i class="fa-solid fa-circle-check me-1"></i> ผ่านเกณฑ์ความหลากหลาย
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-xmark me-1"></i> ยังไม่ถึง 3 คณะที่ต่างกัน
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <a href="<?= url('backoffice/clubs/members?club_id=' . (int)$row['id']) ?>" class="btn btn-outline-primary" target="_blank">
                        <i class="fa-solid fa-users me-1"></i> เปิดดูและตรวจสอบรายชื่อสมาชิกทั้งหมดในแท็บใหม่
                    </a>
                </div>

                <hr>

                <!-- Actions for member verification -->
                <div class="row g-3 mt-2">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-circle-check me-1"></i>อนุมัติผ่านการตรวจสอบรายชื่อ</h6>
                        <p class="small text-muted mb-3">หากตรวจสอบแล้วสมาชิกและคุณสมบัติถูกต้องตามเงื่อนไข หรือพิจารณาเห็นชอบให้ผ่านเกณฑ์การตรวจสอบได้</p>
                        <form method="POST" action="<?= url('backoffice/clubs/verify-members/approve') ?>">
                            <input type="hidden" name="club_id" value="<?= (int) $row['id'] ?>">
                            <button type="submit" class="btn btn-success w-100 py-2 shadow-sm" onclick="return confirm('ยืนยันอนุมัติการตรวจสอบรายชื่อสมาชิกและผ่านเกณฑ์ของชมรมนี้?')">
                                <i class="fa-solid fa-circle-check me-1"></i> อนุมัติผ่านการตรวจรายชื่อ
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-warning-ink mb-3"><i class="fa-solid fa-pen-to-square me-1"></i>ส่งกลับแก้ไขรายชื่อสมาชิก</h6>
                        <form method="POST" action="<?= url('backoffice/clubs/verify-members/correct') ?>">
                            <input type="hidden" name="club_id" value="<?= (int) $row['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">ระบุหมายเหตุ/รายละเอียดที่ต้องให้ปรับปรุงแก้ไข</label>
                                <textarea name="member_verification_comment" class="form-control form-control-sm" rows="3" placeholder="ระบุสิ่งที่ต้องการให้ประธานชมรมปรับปรุงแก้ไขรายชื่อสมาชิก เช่น จำนวนคณะไม่ครบตามเกณฑ์..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning text-dark w-100 py-2 shadow-sm">
                                <i class="fa-solid fa-pen-to-square me-1"></i> ส่งกลับให้แก้ไขรายชื่อ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Edit Club Info -->
<div class="modal fade" id="editModal<?= (int) $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--brand">
                <h5 class="modal-title">แก้ไขข้อมูลชมรม</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/clubs/update') ?>" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="club_id" value="<?= (int) $row['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อชมรม</label>
                        <input type="text" name="club_name" class="form-control" value="<?= e($row['club_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="4" required><?= e($row['description']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">จำกัดจำนวนสมาชิก (คน)</label>
                            <input type="number" name="max_members" class="form-control" value="<?= (int) $row['max_members'] ?>" required>
                        </div>
                        <?php if ($role === 'admin' || $role === 'staff'): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">รหัสนักศึกษาประธานชมรม</label>
                            <input type="text" name="pres_student_id" class="form-control" value="<?= e($row['pres_id']) ?>" placeholder="เว้นว่างถ้าไม่มี">
                            <small class="text-muted">ระบบจะอัปเดตยศให้รหัสนี้เป็นประธานอัตโนมัติ</small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row border-top pt-3 mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">อัปโหลดโลโก้ใหม่</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">อัปโหลด QR Code ใหม่</label>
                            <input type="file" name="qr_code" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php if ($role === 'admin' || $role === 'staff'): ?>
<div class="modal fade" id="addClubModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold">สร้างชมรมใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/clubs/store') ?>" enctype="multipart/form-data">
                <div class="modal-body">
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">สร้างชมรม</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
