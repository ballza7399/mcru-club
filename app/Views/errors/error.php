<?php
/**
 * หน้า error กลาง — standalone ไม่พึ่ง layout/DB เผื่อ error มาจากฐานข้อมูลเอง
 *
 * ตัวแปรที่ส่งเข้ามาจาก ErrorHandler::renderView():
 * @var bool         $debug        โหมด debug หรือไม่
 * @var int          $status       HTTP status code
 * @var string       $userMessage  ข้อความปลอดภัยสำหรับผู้ใช้ (ใช้ตอน production)
 * @var array|string $detail       array รายละเอียดเต็ม (ตอน debug) หรือ '' (ตอน production)
 *
 * โหมด debug → แสดง developer exception page เต็ม (exception chain + stack trace) เหมือน .NET
 * โหมด production → SweetAlert2 แจ้งผู้ใช้แบบกว้าง ๆ
 */
$homeUrl = defined('BASE_URL') ? (BASE_URL . '/') : '/';
$isDevPage = $debug && is_array($detail) && !empty($detail['frames']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เกิดข้อผิดพลาด - ระบบจัดการชมรม MCRU</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php if (!$isDevPage): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php endif; ?>
<style>
:root{--mru-blue:#0b2c5c;--mru-gold:#f9a826;}
body{font-family:'Kanit',sans-serif;margin:0;}
<?php if (!$isDevPage): ?>
body{background:linear-gradient(rgba(11,44,92,.85),rgba(11,44,92,.95));min-height:100vh;display:flex;align-items:center;justify-content:center;color:#fff;text-align:center;}
.fallback{max-width:480px;padding:30px;}
.fallback .code{font-size:5rem;font-weight:700;color:var(--mru-gold);line-height:1;}
<?php else: ?>
/* developer exception page */
body{background:#f4f5f7;color:#1f2933;}
.dev-header{background:var(--mru-blue);color:#fff;padding:24px 32px;}
.dev-header .status{display:inline-block;background:#b91c1c;color:#fff;font-weight:700;border-radius:6px;padding:2px 12px;margin-right:10px;}
.dev-header h1{font-size:1.3rem;margin:10px 0 0;font-weight:600;word-break:break-word;}
.dev-body{padding:24px 32px;max-width:1100px;}
.frame{background:#fff;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:18px;overflow:hidden;}
.frame.inner{border-left:4px solid var(--mru-gold);}
.frame-head{padding:14px 18px;border-bottom:1px solid #eef1f4;}
.frame-head .badge-inner{background:var(--mru-gold);color:#1f2933;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:4px;vertical-align:middle;margin-right:8px;}
.frame-head .cls{font-weight:700;color:#b91c1c;word-break:break-word;}
.frame-head .msg{display:block;margin-top:4px;color:#1f2933;word-break:break-word;}
.frame-loc{font-family:Consolas,Monaco,monospace;font-size:.82rem;color:#64748b;padding:8px 18px;background:#fafbfc;word-break:break-all;}
.frame-trace{margin:0;padding:16px 18px;background:#0f172a;color:#cbd5e1;font-family:Consolas,Monaco,monospace;font-size:.78rem;line-height:1.55;white-space:pre-wrap;word-break:break-word;overflow-x:auto;}
.dev-actions{margin-top:8px;}
<?php endif; ?>
</style>
</head>
<body>

<?php if ($isDevPage): ?>
    <!-- ========== Developer Exception Page (debug=true) ========== -->
    <div class="dev-header">
        <div><span class="status">HTTP <?= (int) $status ?></span> Unhandled Exception</div>
        <h1><?= htmlspecialchars($detail['message'], ENT_QUOTES, 'UTF-8') ?></h1>
    </div>
    <div class="dev-body">
        <?php foreach ($detail['frames'] as $i => $frame): ?>
        <div class="frame <?= $frame['inner'] ? 'inner' : '' ?>">
            <div class="frame-head">
                <?php if ($frame['inner']): ?><span class="badge-inner">INNER #<?= (int) $i ?></span><?php endif; ?>
                <span class="cls"><?= htmlspecialchars($frame['class'], ENT_QUOTES, 'UTF-8') ?></span>
                <span class="msg"><?= htmlspecialchars($frame['message'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="frame-loc"><i class="fa"></i><?= htmlspecialchars($frame['location'], ENT_QUOTES, 'UTF-8') ?></div>
            <pre class="frame-trace"><?= htmlspecialchars($frame['trace'], ENT_QUOTES, 'UTF-8') ?></pre>
        </div>
        <?php endforeach; ?>
        <div class="dev-actions">
            <a href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary fw-bold">กลับสู่หน้าหลัก</a>
        </div>
    </div>

<?php else: ?>
    <!-- ========== Production (debug=false): SweetAlert2 + fallback ========== -->
    <div class="fallback">
        <div class="code"><?= (int) $status ?></div>
        <p class="fs-5 mt-3"><?= htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <a href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning fw-bold mt-3">กลับสู่หน้าหลัก</a>
    </div>

    <script>
    (function () {
        var payload = {
            status: <?= (int) $status ?>,
            title:  <?= json_encode($userMessage, JSON_UNESCAPED_UNICODE) ?>,
            home:   <?= json_encode($homeUrl, JSON_UNESCAPED_UNICODE) ?>
        };
        if (typeof Swal === 'undefined') return; // ใช้ fallback HTML ด้านบน
        Swal.fire({
            icon: payload.status === 404 ? 'warning' : 'error',
            title: 'ข้อผิดพลาด ' + payload.status,
            text: payload.title,
            confirmButtonText: 'กลับสู่หน้าหลัก',
            confirmButtonColor: '#0b2c5c',
            allowOutsideClick: false
        }).then(function () {
            window.location.href = payload.home;
        });
    })();
    </script>
<?php endif; ?>

</body>
</html>
