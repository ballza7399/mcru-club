<?php
/**
 * @var array $settings
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-list-check text-warning me-2"></i>จัดการข้อมูล Footer และช่องทางติดต่อ
    </h4>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <div class="border-bottom pb-2 mb-4">
        <h5 class="fw-bold text-dark m-0">
            <i class="fa-solid fa-gears text-primary me-2"></i>ตั้งค่าข้อมูลส่วนท้ายเว็บไซต์ (Footer Settings)
        </h5>
        <p class="text-muted small m-0 mt-1">ตั้งค่าลิงก์เชื่อมโยงโซเชียลมีเดีย ข้อมูลสถานที่ติดต่อมหาวิทยาลัย และข้อความประชาสัมพันธ์สั้นๆ ของระบบ</p>
    </div>

    <form action="<?= url('backoffice/settings/footer/update') ?>" method="POST">
        <div class="row g-4">
            <!-- Left Column: About & Contact -->
            <div class="col-lg-7">
                <div class="p-3 rounded-4 mb-4" style="background: rgba(11, 44, 92, 0.02); border: 1px solid var(--border-strong);">
                    <h6 class="fw-bold text-primary-custom mb-3"><i class="fa-solid fa-building me-2"></i>ข้อมูลประชาสัมพันธ์และสถานที่</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">เกี่ยวกับระบบสั้นๆ (About Text)</label>
                        <textarea name="footer_about_text" class="form-control" rows="4" placeholder="กรอกข้อความแนะนำระบบสั้นๆ..." required><?= e($settings['footer_about_text'] ?? '') ?></textarea>
                        <div class="form-text small">ข้อความนี้จะแสดงด้านล่างโลโก้สัญลักษณ์ MCRU ในส่วน Footer เพื่อแนะนาจุดประสงค์ของระบบ</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">สถานที่ติดต่อ / ที่อยู่ (Address)</label>
                        <textarea name="footer_contact_address" class="form-control" rows="4" placeholder="กรอกที่อยู่อย่างละเอียด..." required><?= e($settings['footer_contact_address'] ?? '') ?></textarea>
                        <div class="form-text small">สามารถขึ้นบรรทัดใหม่ได้ตามความเหมาะสมเมื่อแสดงผล</div>
                    </div>
                </div>

                <div class="p-3 rounded-4" style="background: rgba(11, 44, 92, 0.02); border: 1px solid var(--border-strong);">
                    <h6 class="fw-bold text-primary-custom mb-3"><i class="fa-solid fa-address-book me-2"></i>ข้อมูลเบอร์โทรศัพท์และอีเมล</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">เบอร์โทรศัพท์ติดต่อ (Phone)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                                <input type="text" name="footer_contact_phone" class="form-control border-start-0 ps-0" value="<?= e($settings['footer_contact_phone'] ?? '') ?>" placeholder="เช่น 032-261-790" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">อีเมลติดต่อ (Email Address)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="footer_contact_email" class="form-control border-start-0 ps-0" value="<?= e($settings['footer_contact_email'] ?? '') ?>" placeholder="เช่น info@mcru.ac.th" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Social Links -->
            <div class="col-lg-5">
                <div class="p-3 rounded-4 h-100" style="background: rgba(11, 44, 92, 0.02); border: 1px solid var(--border-strong);">
                    <h6 class="fw-bold text-primary-custom mb-3"><i class="fa-solid fa-share-nodes me-2"></i>ลิงก์โซเชียลมีเดียและเว็บไซต์หลัก</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">ลิงก์ Facebook (Facebook URL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-primary border-end-0" style="width: 45px; justify-content: center;"><i class="fa-brands fa-facebook-f fs-5"></i></span>
                            <input type="url" name="footer_facebook_url" class="form-control border-start-0 ps-0 text-dark" value="<?= e($settings['footer_facebook_url'] ?? '') ?>" placeholder="https://facebook.com/yourpage">
                        </div>
                        <div class="form-text small">ปล่อยว่างไว้เพื่อซ่อนไอคอนในหน้าหลักได้</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">ลิงก์ YouTube (YouTube Channel URL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-danger border-end-0" style="width: 45px; justify-content: center;"><i class="fa-brands fa-youtube fs-5"></i></span>
                            <input type="url" name="footer_youtube_url" class="form-control border-start-0 ps-0 text-dark" value="<?= e($settings['footer_youtube_url'] ?? '') ?>" placeholder="https://youtube.com/c/yourchannel">
                        </div>
                        <div class="form-text small">ปล่อยว่างไว้เพื่อซ่อนไอคอนในหน้าหลักได้</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">ลิงก์เว็บไซต์สถาบัน (Website URL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-success border-end-0" style="width: 45px; justify-content: center;"><i class="fa-solid fa-globe fs-5"></i></span>
                            <input type="url" name="footer_website_url" class="form-control border-start-0 ps-0 text-dark" value="<?= e($settings['footer_website_url'] ?? '') ?>" placeholder="https://www.mcru.ac.th">
                        </div>
                        <div class="form-text small">ปล่อยว่างไว้เพื่อซ่อนไอคอนในหน้าหลักได้</div>
                    </div>

                    <div class="alert alert-info mt-4 rounded-3 border-0 small" style="background: rgba(11, 44, 92, 0.05); color: var(--primary-blue);">
                        <i class="fa-solid fa-circle-info me-2 text-warning"></i><strong>ข้อแนะนำ:</strong> การกรอกลิงก์โซเชียลมีเดีย ควรกรอกลิงก์เต็มโดยเริ่มต้นด้วย <code>http://</code> หรือ <code>https://</code> เพื่อความปลอดภัยและทำงานได้ถูกต้อง
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end border-top mt-4 pt-4" style="border-color: var(--border-strong) !important;">
            <a href="<?= url('backoffice') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill me-2">
                <i class="fa-solid fa-xmark me-1"></i> ยกเลิก
            </a>
            <button type="submit" class="btn btn-academic-primary px-5 py-2 border-0">
                <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกการเปลี่ยนแปลง
            </button>
        </div>
    </form>
</div>
