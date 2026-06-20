<?php
/**
 * @var string|null $error
 */
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
            <div class="text-center mb-4 border-bottom pb-3">
                <h3 class="text-primary-custom fw-bold"><i class="fa-solid fa-file-signature text-warning me-2"></i>ยื่นขอเสนอจัดตั้งชมรมใหม่</h3>
                <p class="text-muted m-0">กรอกข้อมูลพื้นฐานของชมรมเพื่อส่งให้ผู้ดูแลระบบตรวจสอบและอนุมัติจัดตั้ง</p>
            </div>

            <div class="alert alert-warning mb-4 small text-start">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>หมายเหตุสำคัญ:</strong> ระบบนี้เป็นเพียงช่องทางสำหรับเสนอขอเพิ่มข้อมูลชมรมเข้าสู่ระบบออนไลน์ของมหาวิทยาลัยเท่านั้น ไม่ได้รับจัดตั้งชมรมอย่างเป็นทางการโดยตรง โดยกระบวนการและขั้นตอนการจัดตั้งชมรมอย่างเป็นทางการจะดำเนินการผ่านทาง <strong>กองพัฒนานักศึกษา</strong> ตามระเบียบของมหาวิทยาลัย
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-exclamation me-2"></i><?= e($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?= url('clubs/register') ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="fa-solid fa-layer-group text-primary me-1"></i>ชื่อชมรม <span class="text-danger">*</span></label>
                    <input type="text" name="club_name" class="form-control" placeholder="ระบุชื่อชมรม เช่น ชมรมถ่ายภาพและมีเดีย" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="fa-solid fa-align-left text-primary me-1"></i>รายละเอียดชมรม <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" placeholder="ระบุวัตถุประสงค์และรายละเอียดหรือกิจกรรมย่อยของชมรม..." required></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-users text-primary me-1"></i>จำนวนสมาชิกสูงสุดที่เปิดรับ</label>
                        <input type="number" name="max_members" class="form-control" value="50" min="5" max="500" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-image text-primary me-1"></i>โลโก้ชมรม (ถ้ามี)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">รองรับไฟล์รูปภาพ PNG, JPG, GIF</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-qrcode text-primary me-1"></i>QR Code กลุ่มติดต่อสมัครชมรม (ถ้ามี)</label>
                        <input type="file" name="qr_code" class="form-control" accept="image/*">
                        <small class="text-muted">คิวอาร์โค้ด LINE หรือกลุ่มติดต่อสื่อสาร</small>
                    </div>
                </div>
                
                <div class="border-top pt-3 mt-4 text-end">
                    <a href="<?= url('clubs') ?>" class="btn btn-outline-secondary me-2 rounded-pill px-4">ยกเลิก</a>
                    <button type="submit" class="btn-primary-custom rounded-pill px-4 py-2 border-0"><i class="fa-solid fa-paper-plane me-1"></i>ส่งข้อเสนอจัดตั้งชมรม</button>
                </div>
            </form>
        </div>
    </div>
</div>
