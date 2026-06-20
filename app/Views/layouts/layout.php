<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>ระบบจัดการชมรม MCRU</title>
<link rel="shortcut icon" href="<?= asset('favicon.ico') ?>" type="image/x-icon">
<link rel="icon" href="<?= asset('favicon.png') ?>" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= asset('style.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect width="100%" height="100%" fill="#f8fafc" rx="10"/><rect width="98%" height="97%" x="1%" y="1%" fill="none" stroke="#e2e8f0" stroke-width="2" stroke-dasharray="6" rx="10"/><g transform="translate(150, 85)" text-anchor="middle"><path d="M-18,-15 L-10,-15 L-7,-22 L7,-22 L10,-15 L18,-15 C22,-15 25,-12 25,-8 L25,12 C25,16 22,19 18,19 L-18,19 C-22,19 -25,16 -25,12 L-25,-8 C-25,-12 -22,-15 -18,-15 Z" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linejoin="round"/><circle cx="0" cy="2" r="7" fill="none" stroke="#94a3b8" stroke-width="2.5"/><circle cx="14" cy="-7" r="1.5" fill="#94a3b8"/><text y="42" font-family="'Kanit', sans-serif" font-size="13" font-weight="500" fill="#64748b">ไม่มีรูปภาพ (No Image)</text></g></svg>`;
        const noImageUrl = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svg)));
        if (e.target.src !== noImageUrl) {
            e.target.src = noImageUrl;
        }
    }
}, true);
</script>
</head>
<body>
<?php if (getSetting('mourning_enabled', '0') === '1'): ?>
    <?php require BASE_PATH . '/app/Views/layouts/mourning.php'; ?>
<?php endif; ?>
<?php require BASE_PATH . '/app/Views/layouts/navbar.php'; ?>
<div class="container pb-5 mt-4 flex-grow-1">
<?php if ($flash): ?>
    <div id="flash-message-data" data-message="<?= e($flash) ?>" style="display: none;"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashDataEl = document.getElementById('flash-message-data');
        if (flashDataEl) {
            const msg = flashDataEl.getAttribute('data-message');
            
            let icon = 'success';
            let title = 'ดำเนินการสำเร็จ';
            
            const errorKeywords = ['กรุณา', 'ข้อผิดพลาด', 'ผิดพลาด', 'ไม่ได้', 'ไม่ผ่าน', 'ขออภัย', 'ล้มเหลว', 'เกิดข้อผิดพลาด'];
            if (errorKeywords.some(kw => msg.includes(kw))) {
                icon = 'error';
                title = 'เกิดข้อผิดพลาด';
            } else if (msg.includes('ส่งข้อเสนอ') || msg.includes('เสนอขอ') || msg.includes('ยินดี')) {
                icon = 'success';
                title = 'ส่งข้อมูลสำเร็จ!';
            }
            
            Swal.fire({
                title: title,
                text: msg,
                icon: icon,
                confirmButtonColor: '#0b2c5c',
                confirmButtonText: 'ตกลง'
            });
        }
    });
    </script>
<?php endif; ?>
<?= $content ?>
</div>
<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
<?php require BASE_PATH . '/app/Views/layouts/pdpa_modal.php'; ?>
</body>
</html>
