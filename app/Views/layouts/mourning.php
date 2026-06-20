<?php
/**
 * หน้าจอแสดงความอาลัย (Mourning Screen Overlay)
 * แสดงผลก่อนเข้าใช้ระบบ 3 วินาที (หรือตามที่ระบุ) และตรวจสอบ sessionStorage ป้องกันการแสดงผลซ้ำในเซสชันเดียวกัน
 * มีระบบดวงดาววนรอบและระยิบระยับแบบเปิด-ปิดได้
 */
$mourningImg = getSetting('mourning_image_url', 'https://www.mcru.ac.th/images/imgUpload/20260612_4.png');
$mourningDuration = (int)getSetting('mourning_duration', '3');
$starsEnabled = getSetting('mourning_stars_enabled', '1') === '1';
?>
<div id="mourningOverlay" class="mourning-overlay-active" style="display: none;">
    <?php if ($starsEnabled): ?>
        <!-- Canvas สำหรับทำอนิเมชั่นดวงดาวระยิบระยับและหมุนวน -->
        <canvas id="mourningStarsCanvas"></canvas>
    <?php endif; ?>
    
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

#mourningStarsCanvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.mourning-container {
    position: relative;
    z-index: 10;
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
    box-shadow: 0 10px 40px rgba(255, 215, 0, 0.08), 0 10px 30px rgba(0, 0, 0, 0.8);
    border-radius: 12px;
    overflow: hidden;
    background: #111;
    border: 1px solid rgba(255, 215, 0, 0.15); /* เพิ่มขอบสีทองบางเบาเพิ่มความพรีเมียม */
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
        
        <?php if ($starsEnabled): ?>
        // 4. เริ่มทำงานระบบ Particle ดวงดาวระยิบระยับและหมุนรอบ (สำหรับเบราว์เซอร์ที่มี JS)
        initMourningStars();
        <?php endif; ?>
    }

    // ฟังก์ชันสร้างอนิเมชั่นดวงดาวใน Canvas
    function initMourningStars() {
        const canvas = document.getElementById('mourningStarsCanvas');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        let animationFrameId;
        
        // ตั้งค่าขนาด Canvas ให้เต็มหน้าจอ
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        const stars = [];
        const starCount = 65; // จำนวนดวงดาวทั้งหมด
        
        // ฟังก์ชันสร้างข้อมูลดาวเริ่มต้น
        function createStar(isOrbiting = false) {
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            
            // สุ่มลักษณะของดาว
            return {
                isOrbiting: isOrbiting,
                // พิกัดดาว
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                // ตัวแปรสำหรับดาวที่หมุนวนรอบ (Orbit)
                angle: Math.random() * Math.PI * 2,
                speed: 0.001 + Math.random() * 0.003, // ความเร็วโคจรช้าๆ ให้ดูสงบนิ่ง สุภาพ
                orbitX: 200 + Math.random() * 350,   // รัศมีโคจรแนวแกน X (รอบๆ กรอบรูป)
                orbitY: 150 + Math.random() * 250,   // รัศมีโคจรแนวแกน Y
                // ตัวแปรความระยิบระยับ (Twinkle)
                size: 1 + Math.random() * 3,          // ขนาดดาว 1-4px
                alpha: Math.random(),
                twinkleSpeed: 0.008 + Math.random() * 0.015,
                colorType: Math.random() > 0.4 ? 'gold' : 'white' // สลับสีดวงดาว ทอง/ขาวนวล
            };
        }
        
        // เติมดาวลงในอาร์เรย์
        for (let i = 0; i < starCount; i++) {
            // สลับให้มีทั้งดาวโคจรรอบ และดาววิบวับลอยด้านหลัง
            stars.push(createStar(i % 2 === 0));
        }
        
        // วาดรูปดาว 4 แฉก
        function drawFourPointStar(x, y, size, alpha, colorType) {
            ctx.save();
            ctx.globalAlpha = alpha;
            ctx.fillStyle = colorType === 'gold' ? 'rgba(255, 215, 0, 0.8)' : 'rgba(255, 255, 255, 0.85)';
            ctx.shadowBlur = size * 2;
            ctx.shadowColor = colorType === 'gold' ? '#ffd700' : '#ffffff';
            
            ctx.beginPath();
            ctx.moveTo(x, y - size);
            ctx.lineTo(x + size * 0.25, y - size * 0.25);
            ctx.lineTo(x + size, y);
            ctx.lineTo(x + size * 0.25, y + size * 0.25);
            ctx.lineTo(x, y + size);
            ctx.lineTo(x - size * 0.25, y + size * 0.25);
            ctx.lineTo(x - size, y);
            ctx.lineTo(x - size * 0.25, y - size * 0.25);
            ctx.closePath();
            ctx.fill();
            ctx.restore();
        }
        
        // ลูปอนิเมชั่นหลัก
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            
            stars.forEach(star => {
                // 1. คำนวณตำแหน่งดวงดาว
                if (star.isOrbiting) {
                    // ดวงดาวที่หมุนวนช้าๆ เป็นวงรีรอบรูปภาพตรงกลาง
                    star.angle += star.speed;
                    star.x = centerX + Math.cos(star.angle) * star.orbitX;
                    star.y = centerY + Math.sin(star.angle) * star.orbitY;
                } else {
                    // ดวงดาววิบวับฉากหลังลอยละล่องช้าๆ ขึ้นด้านบน
                    star.y -= 0.15;
                    if (star.y < 0) {
                        star.y = canvas.height;
                        star.x = Math.random() * canvas.width;
                    }
                }
                
                // 2. ปรับความสว่าง (ความระยิบระยับ Twinkle)
                star.alpha += star.twinkleSpeed;
                if (star.alpha > 1 || star.alpha < 0.1) {
                    star.twinkleSpeed = -star.twinkleSpeed;
                }
                
                // เพื่อความนุ่มนวล จำกัดค่า alpha ไม่ให้หลุดช่วง
                star.alpha = Math.max(0.1, Math.min(1, star.alpha));
                
                // 3. วาดดาวบน Canvas
                drawFourPointStar(star.x, star.y, star.size, star.alpha, star.colorType);
            });
            
            animationFrameId = requestAnimationFrame(animate);
        }
        
        animate();
        
        // เคลียร์ event และอนิเมชั่นเมื่อข้ามหน้าจอเพื่อประหยัดหน่วยความจำ
        const overlay = document.getElementById('mourningOverlay');
        if (overlay) {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'style' && overlay.style.display === 'none') {
                        cancelAnimationFrame(animationFrameId);
                        window.removeEventListener('resize', resizeCanvas);
                        observer.disconnect();
                    }
                });
            });
            observer.observe(overlay, { attributes: true });
        }
    }
})();
</script>
