<?php
/**
 * หน้าจอแสดงความอาลัย (Mourning Screen Overlay)
 * แสดงผลก่อนเข้าใช้ระบบ 3 วินาที (หรือตามที่ระบุ) และตรวจสอบ sessionStorage ป้องกันการแสดงผลซ้ำในเซสชันเดียวกัน
 */
$mourningImg = getSetting('mourning_image_url', 'https://www.mcru.ac.th/images/imgUpload/20260612_4.png');
$mourningDuration = (int)getSetting('mourning_duration', '3');
?>
<div id="mourningOverlay" class="mourning-overlay-active" style="display: none;">
    <div class="mourning-container">
        <!-- Mourning Image -->
        <div class="mourning-image-wrapper">
            <img src="<?= e($mourningImg) ?>" class="mourning-img img-fluid" alt="ร่วมลงนามไว้อาลัย">
        </div>
        
        <!-- Controls -->
        <div class="mourning-controls">
            <button id="btnEnterSite" class="btn btn-mourning-enter" onclick="dismissMourning()">
                <i class="fa-solid fa-right-to-bracket me-2"></i>เข้าสู่เว็บไซต์ MCRU Clubs (<span id="mourningCountdown"><?= $mourningDuration ?></span>)
            </button>
        </div>
    </div>
</div>

<style>
#mourningOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: #000000;
    z-index: 9999999;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 1;
    transition: opacity 0.8s ease;
    overflow-y: auto;
}

.mourning-container {
    width: 100%;
    max-width: 900px;
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
}

.mourning-image-wrapper {
    width: 100%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    border-radius: 12px;
    overflow: hidden;
    background: #111;
}

.mourning-img {
    width: 100%;
    height: auto;
    max-height: 75vh;
    object-fit: contain;
    display: block;
}

.mourning-controls {
    width: 100%;
    display: flex;
    justify-content: center;
}

.btn-mourning-enter {
    background: transparent;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.4);
    padding: 10px 24px;
    border-radius: 30px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    cursor: pointer;
    font-family: 'Kanit', sans-serif;
}

.btn-mourning-enter:hover {
    background: #ffffff;
    color: #000000;
    border-color: #ffffff;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
}

@media (max-width: 768px) {
    .mourning-container {
        gap: 15px;
        padding: 10px;
    }
    .btn-mourning-enter {
        width: 100%;
        padding: 12px 20px;
    }
}
</style>

<script>
(function() {
    // 1. ตรวจสอบว่าเคยเข้าชมหน้านี้ไปแล้วใน Session นี้หรือไม่
    if (sessionStorage.getItem('mourning_shown') === 'true') {
        return;
    }

    // 2. แสดงผลหน้าจอไว้อาลัย
    const overlay = document.getElementById('mourningOverlay');
    if (overlay) {
        overlay.style.setProperty('display', 'flex', 'important');
        document.body.style.overflow = 'hidden'; // ป้องกันการเลื่อนหน้าจอด้านหลัง
        
        let timeLeft = <?= $mourningDuration ?>;
        const countdownEl = document.getElementById('mourningCountdown');
        
        // 3. เริ่มจับเวลานับถอยหลัง
        const timer = setInterval(() => {
            timeLeft--;
            if (countdownEl) {
                countdownEl.innerText = timeLeft;
            }
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                dismissMourning();
            }
        }, 1000);
        
        // ผูกฟังก์ชันข้าม
        window.dismissMourning = function() {
            clearInterval(timer);
            overlay.style.opacity = '0';
            document.body.style.overflow = ''; // คืนค่าการเลื่อนหน้าจอปกติ
            
            // รอให้ animation fade-out ทำงานเสร็จแล้วลบออกจาก display
            setTimeout(() => {
                overlay.style.setProperty('display', 'none', 'important');
                sessionStorage.setItem('mourning_shown', 'true');
            }, 800);
        };
    }
})();
</script>
