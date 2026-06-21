<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดคิวเข้าใช้งาน - MCRU Clubs</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= BASE_URL ?>/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fallback auto-refresh for clients without JavaScript -->
    <noscript>
        <meta http-equiv="refresh" content="5;url=<?= BASE_URL ?>/">
    </noscript>

    <style>
        :root {
            --primary-blue: #0b2c5c;
            --primary-soft: #1a4980;
            --accent-gold: #f9a826;
            --bg-light: #f4f7f6;
            --surface: #ffffff;
            --text-dark: #233143;
            --text-muted: #586577;
            --shadow-sm: 0 2px 8px rgba(11, 44, 92, 0.08);
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Kanit', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            /* เผื่อพื้นที่ด้านล่างสำหรับการ์ดต่อคิวและเซิร์ฟเวอร์ */
            padding-bottom: 165px;
        }

        .waiting-card {
            background: var(--surface);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(11, 44, 92, 0.08);
            border: 1px solid rgba(11, 44, 92, 0.1);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .waiting-banner {
            height: 8px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-soft), var(--accent-gold));
        }

        .waiting-body {
            padding: 40px 30px;
        }

        .spinner-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
        }

        .spinner-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(11, 44, 92, 0.05);
            animation: pulse-ring 2s infinite ease-in-out;
        }

        .spinner-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--primary-blue);
            animation: spin 3s infinite linear;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.6); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: scale(1.2); opacity: 0; }
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        h3 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 15px;
        }

        .queue-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .progress-bar-container {
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--accent-gold), var(--primary-blue));
            border-radius: 10px;
            animation: fill-loading 5s linear infinite;
        }

        @keyframes fill-loading {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(249, 168, 38, 0.1);
            color: #b45309;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-badge i {
            animation: heartbeat 1.5s infinite ease-in-out;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* --- CSS Animations for Queue Graphic --- */
        @keyframes flash-red {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; fill: #ef4444; }
        }
        @keyframes flash-green {
            0%, 100% { opacity: 1; fill: #22c55e; }
            50% { opacity: 0.3; }
        }
        @keyframes flash-blue {
            0%, 100% { opacity: 0.3; }
            30% { opacity: 1; fill: #3b82f6; }
        }
        @keyframes flash-yellow {
            0%, 100% { opacity: 1; fill: #eab308; }
            70% { opacity: 0.3; }
        }
        .led-flash-red { animation: flash-red 0.8s infinite; }
        .led-flash-green { animation: flash-green 1.2s infinite; }
        .led-flash-blue { animation: flash-blue 1.5s infinite; }
        .led-flash-yellow { animation: flash-yellow 1s infinite; }

        @keyframes fixing-action {
            0%, 100% { transform: rotate(0deg); transform-origin: 75px 58px; }
            50% { transform: rotate(-22deg); transform-origin: 75px 58px; }
        }
        .fixing-arm {
            animation: fixing-action 0.5s infinite ease-in-out;
        }

        @keyframes idle-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .student-char {
            animation: idle-bounce 2s infinite ease-in-out;
            flex-shrink: 0;
        }
        .student-char.silhouette {
            filter: grayscale(1) opacity(0.35);
        }

        /* --- Bubble Chat Animations --- */
        .speech-bubble {
            position: absolute;
            bottom: 90px;
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--primary-blue);
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 550;
            box-shadow: var(--shadow-sm);
            z-index: 100;
            white-space: nowrap;
            opacity: 0;
            transform: translateY(10px) translateX(-50%);
            transition: opacity 0.3s var(--ease-out), transform 0.3s var(--ease-out);
            pointer-events: none;
        }
        .speech-bubble::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid var(--primary-blue);
        }
        .speech-bubble::before {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid white;
            z-index: 2;
        }
        .speech-bubble.fade-in {
            opacity: 1;
            transform: translateY(0) translateX(-50%);
        }
        .speech-bubble.fade-out {
            opacity: 0;
            transform: translateY(-10px) translateX(-50%);
        }
    </style>
</head>
<body>

<div class="waiting-card">
    <div class="waiting-banner"></div>
    <div class="waiting-body">
        
        <!-- Animated Pulse Spinner -->
        <div class="spinner-container">
            <div class="spinner-pulse"></div>
            <div class="spinner-pulse" style="animation-delay: 0.6s;"></div>
            <div class="spinner-icon">
                <i class="fa-solid fa-circle-notch fa-4x"></i>
            </div>
        </div>

        <h3>ระบบจัดคิวเข้าใช้บริการ</h3>
        <p class="queue-desc">
            ขณะนี้มีผู้เข้าใช้งานระบบ MCRU Clubs พร้อมกันเป็นจำนวนมาก เพื่อรักษาเสถียรภาพการประมวลผลของเครื่องเซิร์ฟเวอร์ ระบบกำลังจัดลำดับคิวให้คุณเข้าใช้งานโดยอัตโนมัติ
        </p>

        <!-- Progress Loading Indicator -->
        <div class="progress-bar-container">
            <div class="progress-bar-fill"></div>
        </div>

        <div class="status-badge mb-2">
            <i class="fa-solid fa-users-viewfinder me-2"></i> <span id="queue-status-text">กำลังรอสิทธิ์การเข้าใช้งานระบบ...</span>
        </div>

        <!-- รายละเอียดลำดับคิวที่จะอัปเดตแบบเรียลไทม์ -->
        <div class="mt-3 p-3 rounded-3" id="queue-info-box" style="background: rgba(11, 44, 92, 0.04); border: 1px solid rgba(11, 44, 92, 0.08); display: none;">
            <div class="row align-items-center">
                <div class="col-6 border-end">
                    <span class="text-muted d-block small mb-1" style="font-size: 0.75rem;">คิวของคุณคือ</span>
                    <span class="h3 mb-0 text-primary fw-bold" id="queue-position-val">0</span>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block small mb-1" style="font-size: 0.75rem;">คิวรอทั้งหมด</span>
                    <span class="h4 mb-0 text-secondary fw-semibold"><span id="queue-total-val">0</span> คิว</span>
                </div>
            </div>
        </div>

        <p class="small text-muted mb-0 mt-3" style="font-size: 0.8rem;">
            ระบบจะตรวจสอบห้องว่างและพยายามพาคุณเข้าสู่เว็บไซต์อีกครั้งในทุก ๆ 5 วินาที
        </p>

        <div class="footer-text">
            MCRU Clubs © มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง
        </div>
    </div>
</div>

<!-- Bottom Queue Graphics Container -->
<div class="fixed-bottom w-100 overflow-hidden" id="queue-container" style="height: 140px; background: linear-gradient(180deg, rgba(244, 247, 246, 0) 0%, rgba(15, 44, 92, 0.05) 100%); border-top: 1px dashed rgba(11, 44, 92, 0.12); z-index: 5;">
    
    <!-- Bubble Chat Wrapper -->
    <div id="speech-bubble" class="speech-bubble">
        <span id="bubble-text">ข้อความพรีวิว...</span>
    </div>

    <div class="container-fluid h-100 d-flex align-items-end justify-content-between px-0">
        
        <!-- Student Queue Section (Stretching all the way to the left) -->
        <div class="d-flex align-items-end justify-content-end gap-2 pb-1 flex-grow-1 overflow-hidden" id="student-queue-wrapper" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 12%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 12%);">
            <?php
            // รายการ SVG แม่แบบของนักศึกษาที่มีหน้าตาหลากหลาย
            $studentTemplates = [
                // Template 0 (เดิมคือ Student 12)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="36" r="10" fill="#ffedd5" />
                    <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#0369a1" />
                </svg>',
                // Template 1 (เดิมคือ Student 1)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="34" r="10" fill="#fed7aa" />
                    <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
                    <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
                    <line x1="40" y1="24" x2="22" y2="33" stroke="#f9a826" stroke-width="1.5" />
                    <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#1a4980" />
                </svg>',
                // Template 2 (เดิมคือ Student 2)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="37" r="9" fill="#fbcfe8" />
                    <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#475569" />
                    <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#ea580c" />
                    <rect x="20" y="62" width="7" height="18" rx="2" fill="#f9a826" />
                </svg>',
                // Template 3 (เดิมคือ Student 3)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="36" r="10" fill="#ffedd5" />
                    <rect x="30" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
                    <rect x="42" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
                    <line x1="38" y1="36" x2="42" y2="36" stroke="#334155" stroke-width="2" />
                    <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#059669" />
                </svg>',
                // Template 4 (เดิมคือ Student 4)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="34" r="10" fill="#fbcfe8" />
                    <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
                    <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
                    <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#0284c7" />
                </svg>',
                // Template 5 (เดิมคือ Student 5)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="35" r="9" fill="#fed7aa" />
                    <path d="M30,35 C30,22 50,22 50,35 C50,42 30,42 30,35" fill="#475569" />
                    <path d="M26,85 L54,85 L48,56 L32,56 Z" fill="#db2777" />
                </svg>',
                // Template 6 (เดิมคือ Student 6)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="34" r="10" fill="#ffedd5" />
                    <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
                    <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
                    <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#a21caf" />
                </svg>',
                // Template 7 (เดิมคือ Student 7)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="37" r="9" fill="#fed7aa" />
                    <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#0b2c5c" />
                    <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#be185d" />
                </svg>',
                // Template 8 (เดิมคือ Student 8)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="36" r="10" fill="#fbcfe8" />
                    <rect x="30" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
                    <rect x="42" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
                    <line x1="38" y1="36" x2="42" y2="36" stroke="#334155" stroke-width="2" />
                    <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#0f766e" />
                </svg>',
                // Template 9 (เดิมคือ Student 9)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="38" r="9" fill="#ffedd5" />
                    <path d="M30,38 C30,25 50,25 50,38 C50,45 30,45 30,38" fill="#475569" />
                    <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#6d28d9" />
                </svg>',
                // Template 10 (เดิมคือ Student 10)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="35" r="10" fill="#fed7aa" />
                    <polygon points="25,30 40,25 55,30 40,35" fill="#475569" />
                    <rect x="38" y="32" width="4" height="5" fill="#475569" />
                    <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#15803d" />
                </svg>',
                // Template 11 (เดิมคือ Student 11)
                '<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75" style="animation-delay: {delay};">
                    <circle cx="40" cy="37" r="9" fill="#fbcfe8" />
                    <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#1e293b" />
                    <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#b91c1c" />
                </svg>'
            ];

            // แสดงเริ่มต้นสัก 12 คน
            for ($i = 12; $i >= 1; $i--) {
                $delay = ($i * 0.15) . 's';
                $template = $studentTemplates[$i % 12];
                echo str_replace('{delay}', $delay, $template);
            }
            ?>
        </div>
        
        <!-- Server & IT Staff Section (Aligned to the far right) -->
        <div class="pb-1 ps-2" style="min-width: 140px; z-index: 10;">
            <svg viewBox="0 0 130 100" width="130" height="95">
                <!-- Server Cabinet -->
                <rect x="5" y="10" width="45" height="80" rx="4" fill="#334155" />
                <rect x="10" y="15" width="35" height="70" rx="2" fill="#1e293b" />
                
                <!-- Server Slots & LED Indicator Lights -->
                <rect x="14" y="22" width="27" height="4" rx="1" fill="#475569" />
                <circle cx="18" cy="24" r="1.5" fill="#ef4444" class="led-flash-red" />
                <circle cx="23" cy="24" r="1.5" fill="#22c55e" class="led-flash-green" />
                
                <rect x="14" y="32" width="27" height="4" rx="1" fill="#475569" />
                <circle cx="18" cy="34" r="1.5" fill="#3b82f6" class="led-flash-blue" />
                <circle cx="23" cy="34" r="1.5" fill="#eab308" class="led-flash-yellow" />
                
                <rect x="14" y="42" width="27" height="4" rx="1" fill="#475569" />
                <circle cx="18" cy="44" r="1.5" fill="#22c55e" class="led-flash-green" />
                <circle cx="23" cy="44" r="1.5" fill="#ef4444" class="led-flash-red" />

                <rect x="14" y="52" width="27" height="4" rx="1" fill="#475569" />
                <circle cx="18" cy="54" r="1.5" fill="#eab308" class="led-flash-yellow" />
                <circle cx="23" cy="54" r="1.5" fill="#3b82f6" class="led-flash-blue" />
                
                <!-- Hanging Patch Cables -->
                <path d="M40,25 C45,35 48,30 42,50" fill="none" stroke="#2563eb" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M38,35 C42,42 45,45 35,55" fill="none" stroke="#dc2626" stroke-width="1.2" stroke-linecap="round"/>

                <!-- IT Staff Character -->
                <!-- Pants -->
                <path d="M72,80 L78,80 L78,92 L72,92 Z" fill="#1e293b" />
                <path d="M82,80 L88,80 L88,92 L82,92 Z" fill="#1e293b" />
                <!-- Orange Shirt -->
                <path d="M68,54 L92,54 L88,82 L72,82 Z" fill="#ea580c" />
                <!-- Neck -->
                <rect x="76" y="46" width="8" height="9" fill="#ffedd5" />
                <!-- Face -->
                <circle cx="80" cy="39" r="10" fill="#ffedd5" />
                <!-- University Cap -->
                <path d="M70,36 C70,28 90,28 90,36 Z" fill="#0b2c5c" />
                <rect x="80" y="33" width="12" height="3" rx="1" fill="#0b2c5c" />
                
                <!-- Fixing Arm / Wrench (Animated Group) -->
                <g class="fixing-arm">
                    <!-- Arm vector -->
                    <path d="M72,58 C62,54 54,48 54,48" stroke="#ffedd5" stroke-width="4.5" stroke-linecap="round" fill="none" />
                    <!-- Wrench vector -->
                    <path d="M50,46 L57,51" stroke="#94a3b8" stroke-width="3" stroke-linecap="round" fill="none" />
                    <circle cx="50" cy="46" r="2.5" fill="none" stroke="#94a3b8" stroke-width="2" />
                </g>
            </svg>
        </div>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // รายการข้อความสุ่มพูดคุยของนักศึกษาแบบจัดเต็ม
    const messages = [
        "ชมรมบอร์ดเกมจะเต็มก่อนไหมเนี่ยยย 😭",
        "เน็ตมหาลัยช้าจังเล้ยยย! ช้อนสิทธิ์ไม่ทัน!",
        "คุณพี่ IT สู้เขา! ขันน็อตทีคร้าบบบ!",
        "จะทันโควตาชมรมคอมไหมนะ เสียวจัง...",
        "เมื่อยขาแล้วนะครับอาจารย์ ยืนรอตั้งแต่บ่าย",
        "มีใครสนใจเข้าชมรมพัฒนาเว็บกับเราบ้าง?",
        "อยากรู้จังว่าชมรมสัตว์เลี้ยงมีสัตว์อะไรบ้าง",
        "IT ขันน็อตตัวสีน้ำเงินตัวนั้นด่วนเลย!",
        "โควตาชมรมจะเพิ่มสิทธิ์รับไหมน้าาา",
        "ปีนี้มีชมรมเปิดใหม่เพียบเลย ลุ้นมาก",
        "อาจารย์ครับ ระบบล่มหรือยังครับเนี่ย 🥺",
        "ต่อแถวยาวจนจะถึงหน้ามอแล้วนะ!",
        "เพื่อนรัก อย่าเพิ่งกดจองชมรมตัดหน้าตูนะ",
        "มีใครมีโค้ดส่วนลดค่าคาเฟอีนไหม ง่วงนอนแล้ว",
        "ตื่นมารอลงทะเบียนตั้งแต่ตี 5 เพื่อสิ่งนี้...",
        "ชมรมดนตรีสากลเปิดรับกี่คนนะปีนี้?",
        "เว็บล่มคือประเพณีอันดีงามของมหาวิทยาลัย 😂",
        "ใครใช้บอทลงทะเบียน สารภาพมาซะดีๆ!",
        "เน็ตหอในแรงกว่าเน็ตมือถือฉันอีกหรอเนี่ย",
        "รออยู่นะจ๊ะ ชมรม E-Sports รอพี่ก่อนนน",
        "ขอพึ่งสิ่งศักดิ์สิทธิ์ประจำมหาลัย แปะสาธุ 99 🙏",
        "พนักงาน IT ทำงานเร็วจี๋ สู้ๆ ครับพี่!",
        "ระบบเสถียรมากครับ ยืนยันโดยคิวที่ล้นหลาม",
        "จองชมรมถ่ายภาพทัน จะเอาไปถ่ายรูปเท่ๆ ลงสตอรี่",
        "แถวขยับบ้างยังน่ะ คนข้างหน้าเดินเร็วกว่านี้หน่อยยย",
        "อยากเข้าชมรมอาหารญี่ปุ่น จะได้กินฟรีไหมนะ",
        "สู้ๆ นะครับพี่คนซ่อมเซิร์ฟเวอร์ อย่าลืมเสียบปลั๊กนะ!",
        "คนเข้าเว็บเยอะขนาดนี้ นึกว่ากดบัตรคอนเสิร์ต!",
        "กด F5 จนแป้นพิมพ์จะพังแล้วจ้าาา",
        "ขอร้องล่ะระบบ อย่าเพิ่งเด้งตอนกำลังเลือกนะ"
    ];

    const bubble = document.getElementById('speech-bubble');
    const bubbleText = document.getElementById('bubble-text');
    const queueContainer = document.getElementById('queue-container');

    const queueInfoBox = document.getElementById('queue-info-box');
    const queuePositionVal = document.getElementById('queue-position-val');
    const queueTotalVal = document.getElementById('queue-total-val');
    const queueStatusText = document.getElementById('queue-status-text');
    const queueWrapper = document.getElementById('student-queue-wrapper');

    // แม่แบบ SVG นักศึกษาฝั่งคลายเอนต์สำหรับการเรนเดอร์คิวไดนามิก
    const studentTemplates = [
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="36" r="10" fill="#ffedd5" />
            <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#0369a1" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="34" r="10" fill="#fed7aa" />
            <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
            <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
            <line x1="40" y1="24" x2="22" y2="33" stroke="#f9a826" stroke-width="1.5" />
            <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#1a4980" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="37" r="9" fill="#fbcfe8" />
            <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#475569" />
            <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#ea580c" />
            <rect x="20" y="62" width="7" height="18" rx="2" fill="#f9a826" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="36" r="10" fill="#ffedd5" />
            <rect x="30" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
            <rect x="42" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
            <line x1="38" y1="36" x2="42" y2="36" stroke="#334155" stroke-width="2" />
            <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#059669" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="34" r="10" fill="#fbcfe8" />
            <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
            <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
            <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#0284c7" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="35" r="9" fill="#fed7aa" />
            <path d="M30,35 C30,22 50,22 50,35 C50,42 30,42 30,35" fill="#475569" />
            <path d="M26,85 L54,85 L48,56 L32,56 Z" fill="#db2777" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="34" r="10" fill="#ffedd5" />
            <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
            <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
            <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#a21caf" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="37" r="9" fill="#fed7aa" />
            <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#0b2c5c" />
            <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#be185d" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="36" r="10" fill="#fbcfe8" />
            <rect x="30" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
            <rect x="42" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
            <line x1="38" y1="36" x2="42" y2="36" stroke="#334155" stroke-width="2" />
            <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#0f766e" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="38" r="9" fill="#ffedd5" />
            <path d="M30,38 C30,25 50,25 50,38 C50,45 30,45 30,38" fill="#475569" />
            <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#6d28d9" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="35" r="10" fill="#fed7aa" />
            <polygon points="25,30 40,25 55,30 40,35" fill="#475569" />
            <rect x="38" y="32" width="4" height="5" fill="#475569" />
            <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#15803d" />
        </svg>`,
        `<svg class="student-char student-node" viewBox="0 0 80 100" width="55" height="75">
            <circle cx="40" cy="37" r="9" fill="#fbcfe8" />
            <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#1e293b" />
            <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#b91c1c" />
        </svg>`
    ];

    let currentQueuePosition = 0;

    // เรนเดอร์คนในคิวตามลำดับจริง
    function renderQueue(queuePosition) {
        if (!queueWrapper) return;
        if (currentQueuePosition === queuePosition) return;
        currentQueuePosition = queuePosition;

        queueWrapper.innerHTML = '';
        const count = Math.min(queuePosition, 20);

        for (let i = count; i >= 1; i--) {
            const delay = (i * 0.15) + 's';
            const templateIndex = i % 12;
            const html = studentTemplates[templateIndex];

            // แปลง SVG String เป็น DOM Node
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'image/svg+xml');
            const svgNode = doc.documentElement;

            svgNode.style.animationDelay = delay;

            // หากมีคิวมากกว่า 20 และตัวนี้อยู่ที่ท้ายแถว (5 ตัวสุดท้ายของความยาว 20) ให้ทำเป็นเงาสีเทา (Silhouette)
            if (queuePosition > 20 && i > 15) {
                svgNode.classList.add('silhouette');
            }

            queueWrapper.appendChild(svgNode);
        }
    }

    // ระบบตรวจสอบคิวแบบ AJAX ทุกๆ 5 วินาที เพื่อหลีกเลี่ยงการรีเฟรชหน้าเบราว์เซอร์
    function checkQueueStatus() {
        fetch('<?= BASE_URL ?>/api/queue-status')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    if (data.can_enter) {
                        // เมื่อคิวผ่านแล้ว พาเข้าหน้าเว็บทันที
                        window.location.href = '<?= BASE_URL ?>/';
                    } else {
                        // อัปเดตข้อมูลคิวในหน้าจอ
                        if (queueInfoBox && queuePositionVal && queueTotalVal && queueStatusText) {
                            queuePositionVal.innerText = data.queue_position;
                            queueTotalVal.innerText = data.total_waiting;
                            queueStatusText.innerText = 'ขณะนี้คิวเต็ม กำลังจัดคิวเข้าใช้งาน...';
                            queueInfoBox.style.display = 'block';
                            
                            // เรนเดอร์แถวคิวไดนามิก
                            renderQueue(data.queue_position);
                        }
                    }
                }
            })
            .catch(err => console.error('Error checking queue:', err));
    }

    // ตรวจสอบคิวครั้งแรกและรันเบื้องหลังทุก 5 วินาที
    checkQueueStatus();
    setInterval(checkQueueStatus, 5000);

    function triggerSpeechBubble() {
        // ดึงเฉพาะนักศึกษาที่กำลังแสดงผลอยู่ใน viewport ปัจจุบัน (ไม่ล้นขอบจอไปฝั่งซ้าย)
        const allNodes = document.querySelectorAll('.student-node');
        const visibleNodes = Array.from(allNodes).filter(el => {
            const rect = el.getBoundingClientRect();
            // เช็กว่าขอบขวาของโหนดมีค่ามากกว่า 0 และขอบซ้ายยังไม่เลยความกว้างของหน้าจอ
            return rect.right > 0 && rect.left < window.innerWidth;
        });

        if (visibleNodes.length > 0 && bubble && bubbleText && queueContainer) {
            // สุ่มเลือกตัวละครนักศึกษา 1 ตัว
            const randomStudent = visibleNodes[Math.floor(Math.random() * visibleNodes.length)];
            
            // คำนวณตำแหน่งพิกัดเพื่อแสดง Bubble ตรงกับหัวของนักศึกษาตัวที่สุ่มได้
            const containerRect = queueContainer.getBoundingClientRect();
            const studentRect = randomStudent.getBoundingClientRect();
            
            // จัดกึ่งกลางพิกัด Left สัมพัทธ์กับ parent container
            const leftOffset = (studentRect.left - containerRect.left) + (studentRect.width / 2);
            
            // สุ่มข้อความและกำหนดพิกัด
            bubbleText.innerText = messages[Math.floor(Math.random() * messages.length)];
            bubble.style.left = `${leftOffset}px`;
            
            // แสดงผลแอนิเมชัน Fade In
            bubble.style.display = 'block';
            // ปล่อยให้เบราว์เซอร์รับรู้การแสดงผลก่อนเปลี่ยนคลาสเพื่อแอนิเมชัน
            setTimeout(() => {
                bubble.classList.remove('fade-out');
                bubble.classList.add('fade-in');
            }, 10);

            // ซ่อน Bubble หลังจากแสดงผลไปแล้ว 2.8 วินาที
            setTimeout(() => {
                bubble.classList.remove('fade-in');
                bubble.classList.add('fade-out');
                // รอแอนิเมชันจางหายเสร็จสิ้นแล้วค่อยซ่อนถาวร
                setTimeout(() => {
                    bubble.style.display = 'none';
                }, 300);
            }, 2800);
        }
    }

    // เริ่มทำงานสุ่มคำพูดครั้งแรกหลังจากเข้าหน้า 1.2 วินาที
    setTimeout(triggerSpeechBubble, 1200);

    // วนรอบสุ่มแสดงคำพูดทุกๆ 4.5 วินาที
    setInterval(triggerSpeechBubble, 4500);
});
</script>

</body>
</html>
