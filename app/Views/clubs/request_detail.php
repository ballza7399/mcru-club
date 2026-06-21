<div class="mb-3">
    <a href="<?= url('backoffice/clubs/requests') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> ย้อนกลับไปรายการคำขอ
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-file-invoice me-2"></i> รายละเอียดคำเสนอขอก่อตั้งชมรม: <?= e($club['club_name']) ?>
    </h4>
</div>

<?php if (!empty($club['rejection_reason'])): ?>
<div class="alert alert-warning border border-warning-subtle d-flex align-items-start mb-4 shadow-sm" style="background-color: #fffdf5; border-radius: 12px;">
    <i class="fa-solid fa-circle-exclamation fs-5 me-3 text-warning mt-1"></i>
    <div>
        <strong class="d-block text-warning-ink mb-1">เหตุผลจากการพิจารณาครั้งล่าสุด:</strong>
        <span class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem;"><?= e($club['rejection_reason']) ?></span>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- ฝั่งซ้าย: ข้อมูลรายละเอียดชมรมและผู้ยื่นเสนอ -->
    <div class="col-lg-8">
        <!-- รายละเอียดชมรม -->
        <div class="card-custom p-4 mb-4">
            <h5 class="fw-bold text-primary-custom mb-3 pb-2 border-bottom">
                <i class="fa-solid fa-circle-info me-2 text-primary-soft"></i>รายละเอียดชมรมเบื้องต้น
            </h5>
            <div class="mb-4">
                <label class="text-muted small fw-bold">ชื่อชมรม (ภาษาไทย)</label>
                <div class="fs-5 fw-bold text-primary-custom"><?= e($club['club_name']) ?></div>
            </div>
            
            <div class="mb-4">
                <label class="text-muted small fw-bold">วัตถุประสงค์ของชมรม</label>
                <p class="text-dark bg-light p-3 rounded-3 border mb-0" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;"><?= e($club['description']) ?></p>
            </div>

            <div class="mb-4">
                <label class="text-muted small fw-bold">วัตถุประสงค์การจัดตั้ง (รายข้อ)</label>
                <?php 
                $objs = [];
                if (!empty($club['objectives'])) {
                    $objs = json_decode($club['objectives'], true) ?: [];
                }
                if (empty($objs)):
                ?>
                    <div class="text-muted small p-2 bg-light rounded border text-center">ไม่ได้ระบุวัตถุประสงค์รายข้อ</div>
                <?php else: ?>
                    <ul class="list-group list-group-flush border rounded overflow-hidden">
                        <?php foreach ($objs as $idx => $obj): ?>
                            <li class="list-group-item bg-light-subtle d-flex align-items-start py-2">
                                <span class="badge bg-primary me-2 mt-1" style="font-size: 0.75rem;"><?= $idx + 1 ?></span>
                                <div class="text-dark flex-grow-1"><?= e($obj) ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-bold d-block">อาจารย์ที่ปรึกษาชมรม</label>
                    <div class="p-3 border rounded bg-light-subtle d-flex align-items-center">
                        <i class="fa-solid fa-user-tie fa-2x text-secondary me-3"></i>
                        <div>
                            <div class="fw-bold text-primary-custom" style="font-size: 0.95rem;"><?= e($club['advisor_name'] ?: 'ยังไม่ได้ระบุ') ?></div>
                            <small class="text-muted">อาจารย์ที่ปรึกษาหลัก</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold d-block">จำนวนสมาชิกเป้าหมาย</label>
                    <div class="p-3 border rounded bg-light-subtle d-flex align-items-center">
                        <i class="fa-solid fa-users-gear fa-2x text-secondary me-3"></i>
                        <div>
                            <div class="fw-bold text-primary-custom" style="font-size: 1.1rem;"><?= (int)($club['max_members'] ?? 50) ?> <span class="text-muted fw-normal" style="font-size: 0.9rem;">คน</span></div>
                            <small class="text-muted">จำกัดจำนวนสมาชิกสูงสุด</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ข้อมูลผู้ยื่นคำขอ -->
        <div class="card-custom p-4">
            <h5 class="fw-bold text-primary-custom mb-3 pb-2 border-bottom">
                <i class="fa-solid fa-id-card me-2 text-primary-soft"></i>ข้อมูลนักศึกษาผู้ยื่นคำเสนอขอจัดตั้ง
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small d-block">ชื่อ-นามสกุลผู้ยื่นคำขอ</label>
                    <span class="fw-bold text-dark"><?= e($club['proposer_name'] ?: 'ไม่ระบุ') ?></span>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">รหัสนักศึกษา</label>
                    <span class="font-monospace fw-bold text-dark"><?= e($club['proposer_student_id'] ?: 'ไม่ระบุ') ?></span>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">คณะ / สาขาวิชา</label>
                    <span class="text-dark">
                        คณะ<?= e($club['proposer_faculty'] ?: 'ไม่ระบุ') ?> 
                        สาขาวิชา<?= e($club['proposer_major'] ?: 'ไม่ระบุ') ?>
                    </span>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">เบอร์โทรศัพท์ติดต่อ</label>
                    <span class="text-dark font-monospace fw-bold">
                        <i class="fa-solid fa-phone text-muted me-1"></i><?= e($club['proposer_phone'] ?: 'ไม่ระบุ') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ฝั่งขวา: โลโก้, QR Code และเอกสารแนบ -->
    <div class="col-lg-4">
        <!-- เอกสารเสนอขอจัดตั้ง -->
        <div class="card-custom p-4 mb-4">
            <h5 class="fw-bold text-primary-custom mb-3 pb-2 border-bottom">
                <i class="fa-solid fa-file-word me-2 text-primary-soft"></i>เอกสารแนบการขอจัดตั้ง
            </h5>
            <?php if ($club['establishment_document']): ?>
                <div class="p-3 border rounded text-center bg-light">
                    <i class="fa-regular fa-file-pdf fa-3x text-danger mb-2 d-block"></i>
                    <div class="small text-truncate mb-3 fw-bold text-dark"><?= e(basename($club['establishment_document'])) ?></div>
                    <a href="<?= url($club['establishment_document']) ?>" class="btn btn-primary text-white w-100 shadow-sm" target="_blank">
                        <i class="fa-solid fa-download me-1"></i> ดาวน์โหลดเอกสารแนบ
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted bg-light rounded border">
                    <i class="fa-regular fa-file-excel fa-2x mb-2 text-secondary"></i>
                    <div class="small">ไม่ได้แนบไฟล์เอกสารประกอบ</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- โลโก้และคิวอาร์โค้ด -->
        <div class="card-custom p-4 text-center">
            <h5 class="fw-bold text-primary-custom text-start mb-3 pb-2 border-bottom">
                <i class="fa-regular fa-image me-2 text-primary-soft"></i>สื่อและสัญลักษณ์ชมรม
            </h5>
            
            <div class="mb-4">
                <label class="text-muted small fw-bold d-block mb-2">ตราสัญลักษณ์ / โลโก้ชมรม</label>
                <?php if (assetExists($club['club_logo'])): ?>
                    <img src="<?= asset($club['club_logo']) ?>" class="img-thumbnail rounded-circle shadow-sm" style="width:130px; height:130px; object-fit:cover;" alt="Logo Preview">
                <?php else: ?>
                    <div class="mx-auto rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width:130px; height:130px;">
                        <i class="fa-solid fa-image fa-2x text-muted"></i>
                    </div>
                    <small class="text-muted d-block mt-2">ไม่มีตราสัญลักษณ์</small>
                <?php endif; ?>
            </div>
            
            <div>
                <label class="text-muted small fw-bold d-block mb-2">QR Code สำหรับรับสมาชิก</label>
                <?php if (assetExists($club['qr_code'])): ?>
                    <img src="<?= asset($club['qr_code']) ?>" class="img-thumbnail rounded shadow-sm" style="width:130px; height:130px; object-fit:cover;" alt="QR Code Preview">
                <?php else: ?>
                    <div class="mx-auto rounded bg-light border d-flex align-items-center justify-content-center" style="width:130px; height:130px;">
                        <i class="fa-solid fa-qrcode fa-2x text-muted"></i>
                    </div>
                    <small class="text-muted d-block mt-2">ไม่มี QR Code</small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ส่วนคำสั่งพิจารณาและจัดการคำขอ -->
<div class="card-custom p-4 mt-4" style="border-top: 3px solid var(--accent-gold) !important;">
    <h5 class="fw-bold text-primary-custom mb-3">
        <i class="fa-solid fa-gavel me-2 text-primary-soft"></i>การพิจารณาตรวจสอบคำเสนอขอก่อตั้งชมรม
    </h5>
    <form method="POST" action="<?= url('backoffice/clubs/requests/action') ?>">
        <input type="hidden" name="club_id" value="<?= (int)$club['id'] ?>">
        <input type="hidden" id="form-action" name="action" value="">
        
        <div class="mb-4">
            <label class="form-label text-dark fw-bold mb-2">ระบุหมายเหตุ / เหตุผลการพิจารณา (กรณีส่งกลับแก้ไข หรือปฏิเสธจำเป็นต้องระบุ)</label>
            <div class="field">
                <textarea name="rejection_reason" class="field__control pt-3" rows="4" style="height: auto;" placeholder="กรุณาระบุสิ่งที่ต้องการให้นักศึกษาแก้ไขข้อมูล หรือเหตุผลการปฏิเสธจัดตั้ง..."></textarea>
                <i class="fa-solid fa-comment-dots field__icon" style="top: 24px;"></i>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center border-top pt-3 flex-wrap gap-2">
            <div>
                <span class="text-muted small">สถานะการเสนอขอในปัจจุบัน:</span>
                <?php if ($club['status'] === 'approved'): ?>
                    <span class="badge bg-success ms-1"><i class="fa-solid fa-circle-check me-1"></i>อนุมัติจัดตั้งแล้ว</span>
                <?php elseif ($club['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark ms-1"><i class="fa-solid fa-spinner fa-spin me-1"></i>รอตรวจสอบเอกสาร</span>
                <?php elseif ($club['status'] === 'correcting'): ?>
                    <span class="badge bg-info text-white ms-1"><i class="fa-solid fa-pen-to-square me-1"></i>ส่งกลับแก้ไขเอกสาร</span>
                <?php else: ?>
                    <span class="badge bg-danger ms-1"><i class="fa-solid fa-circle-xmark me-1"></i>ปฏิเสธจัดตั้ง</span>
                <?php endif; ?>
            </div>
            
            <div class="d-flex gap-2">
                <?php if ($club['status'] !== 'approved'): ?>
                    <!-- ปุ่มอนุมัติจัดตั้งชมรม -->
                    <button type="submit" class="btn btn-success px-4 py-2 text-white" 
                            onclick="document.getElementById('form-action').value='approve'; this.form.setAttribute('data-confirm', 'ยืนยันการพิจารณาอนุมัติจัดตั้งชมรมนี้ในระยะแรก? นักศึกษาจะได้รับยศเป็นประธานชมรมและสามารถรวบรวมสมาชิกได้ต่อไป'); this.form.setAttribute('data-confirm-color', '#198754');">
                        <i class="fa-solid fa-circle-check me-1"></i> อนุมัติจัดตั้งชมรม
                    </button>
                    
                    <!-- ปุ่มส่งกลับแก้ไขข้อมูล -->
                    <button type="submit" class="btn btn-warning px-4 py-2 text-dark" 
                            onclick="document.getElementById('form-action').value='correct'; this.form.setAttribute('data-confirm', 'ยืนยันการส่งคำขอนี้กลับแก้ไขเพิ่มเติมให้นักศึกษา? (ต้องกรอกหมายเหตุ/เหตุผลด้านบน)'); this.form.setAttribute('data-confirm-color', '#ffc107');">
                        <i class="fa-solid fa-pen-to-square me-1"></i> ส่งกลับแก้ไข
                    </button>
                    
                    <!-- ปุ่มปฏิเสธจัดตั้งชมรม -->
                    <button type="submit" class="btn btn-danger px-4 py-2 text-white" 
                            onclick="document.getElementById('form-action').value='reject'; this.form.setAttribute('data-confirm', 'ยืนยันปฏิเสธการจัดตั้งชมรมนี้อย่างถาวร? (ต้องกรอกหมายเหตุ/เหตุผลด้านบน)'); this.form.setAttribute('data-confirm-color', '#dc3545');">
                        <i class="fa-solid fa-circle-xmark me-1"></i> ปฏิเสธจัดตั้ง
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary px-4 py-2" disabled>
                        <i class="fa-solid fa-lock me-1"></i> คำขอนี้ได้รับการพิจารณาอนุมัติเสร็จสิ้นแล้ว
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
