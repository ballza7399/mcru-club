<footer class="footer-custom mt-auto">
    <!-- Top divider line with Gold-to-Blue academic gradient -->
    <div class="footer-divider-gradient"></div>

    <div class="footer-main py-5">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Column 1: MCRU Brand -->
                <div class="col-lg-5 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="footer-brand-icon me-2">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="footer-brand-text fw-bold">MCRU<span class="fw-light">Clubs</span></span>
                    </div>
                    <p class="footer-desc mb-3">
                        ระบบจัดการและรวมศูนย์ข้อมูลชมรมนักศึกษา มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง ส่งเสริมการทำกิจกรรม แลกเปลี่ยนเรียนรู้ สร้างมิตรภาพ และพัฒนาทักษะชีวิตของนักศึกษานอกห้องเรียนอย่างสร้างสรรค์
                    </p>
                    <div class="footer-socials d-flex gap-2">
                        <a href="https://facebook.com" target="_blank" class="social-link" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://youtube.com" target="_blank" class="social-link" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="https://www.mcru.ac.th" target="_blank" class="social-link" title="Website MCRU"><i class="fa-solid fa-globe"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-3 col-md-6 col-6 ps-md-5">
                    <h5 class="footer-section-title fw-bold mb-3">ลิงก์เมนูหลัก</h5>
                    <ul class="footer-links list-unstyled m-0 p-0">
                        <li><a href="<?= url() ?>"><i class="fa-solid fa-chevron-right me-2"></i>หน้าหลัก</a></li>
                        <li><a href="<?= url('clubs') ?>"><i class="fa-solid fa-chevron-right me-2"></i>รายชื่อชมรม</a></li>
                        <?php if (!empty($_SESSION['user_id']) && $_SESSION['role'] === 'student'): ?>
                            <li><a href="<?= url('clubs/register') ?>"><i class="fa-solid fa-chevron-right me-2"></i>เสนอเพิ่มข้อมูลชมรม</a></li>
                        <?php endif; ?>
                        <li><a href="<?= url('policy') ?>" onclick="openPdpaViewerModal(event)"><i class="fa-solid fa-chevron-right me-2"></i>นโยบาย & เงื่อนไขข้อตกลง (PDPA)</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="col-lg-4 col-md-12">
                    <h5 class="footer-section-title fw-bold mb-3">ข้อมูลการติดต่อ</h5>
                    <ul class="footer-contact-list list-unstyled m-0 p-0">
                        <li class="d-flex align-items-start mb-2">
                            <i class="fa-solid fa-location-dot mt-1 me-2 text-warning"></i>
                            <span>มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง<br>เลขที่ 46 หมู่ 3 ต.จอมบึง อ.จอมบึง จ.ราชบุรี 70150</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fa-solid fa-phone me-2 text-warning"></i>
                            <span>โทรศัพท์: 032-261-790</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-2 text-warning"></i>
                            <span>อีเมล: info@mcru.ac.th</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom copyright bar -->
    <div class="footer-bottom py-3">
        <div class="container">
            <div class="row align-items-center justify-content-between g-2">
                <div class="col-md-7 text-center text-md-start">
                    <p class="m-0 footer-copyright-text">
                        &copy; <?= date('Y') ?> <strong class="text-white">มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง</strong>. All Rights Reserved.
                    </p>
                </div>
                <div class="col-md-5 text-center text-md-end">
                    <p class="m-0 footer-sub-text" style="font-size: 0.8rem; opacity: 0.6;">
                        ระบบกิจกรรมและพัฒนาศักยภาพนักศึกษา (Student Activity System)
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
