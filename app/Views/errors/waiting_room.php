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
    
    <!-- Meta auto-refresh to check queue space every 5 seconds -->
    <meta http-equiv="refresh" content="5;url=<?= BASE_URL ?>/">

    <style>
        :root {
            --primary-blue: #0b2c5c;
            --primary-soft: #1a4980;
            --accent-gold: #f9a826;
            --bg-light: #f4f7f6;
            --surface: #ffffff;
            --text-dark: #233143;
            --text-muted: #586577;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Kanit', sans-serif;
            min-height: 100vh;
            display: flex;
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
        }
        .bounce-delay-1 { animation-delay: 0.2s; }
        .bounce-delay-2 { animation-delay: 0.6s; }
        .bounce-delay-3 { animation-delay: 1.0s; }
        .bounce-delay-4 { animation-delay: 1.4s; }
        .bounce-delay-5 { animation-delay: 1.8s; }
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
            <i class="fa-solid fa-users-viewfinder me-2"></i> กำลังรอสิทธิ์การเข้าใช้งานระบบ...
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
<div class="fixed-bottom w-100 overflow-hidden" style="height: 140px; background: linear-gradient(180deg, rgba(244, 247, 246, 0) 0%, rgba(15, 44, 92, 0.05) 100%); border-top: 1px dashed rgba(11, 44, 92, 0.12); z-index: 5;">
    <div class="container h-100 d-flex align-items-end justify-content-between">
        
        <!-- Student Queue Section (Aligned to the left and center) -->
        <div class="d-flex align-items-end gap-3 pb-1 flex-grow-1 overflow-hidden" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 15%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 15%);">
            <div class="flex-grow-1"></div>
            
            <!-- Student 5 -->
            <svg class="student-char bounce-delay-5 d-none d-lg-block" viewBox="0 0 80 100" width="55" height="75">
                <circle cx="40" cy="35" r="9" fill="#fed7aa" />
                <path d="M30,35 C30,22 50,22 50,35 C50,42 30,42 30,35" fill="#475569" />
                <path d="M26,85 L54,85 L48,56 L32,56 Z" fill="#db2777" />
            </svg>
            
            <!-- Student 4 -->
            <svg class="student-char bounce-delay-4 d-none d-md-block" viewBox="0 0 80 100" width="55" height="75">
                <circle cx="40" cy="34" r="10" fill="#fbcfe8" />
                <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
                <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
                <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#0284c7" />
            </svg>
            
            <!-- Student 3 -->
            <svg class="student-char bounce-delay-3" viewBox="0 0 80 100" width="55" height="75">
                <circle cx="40" cy="36" r="10" fill="#ffedd5" />
                <rect x="30" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
                <rect x="42" y="33" width="8" height="6" rx="1.5" fill="none" stroke="#334155" stroke-width="2" />
                <line x1="38" y1="36" x2="42" y2="36" stroke="#334155" stroke-width="2" />
                <path d="M25,85 L55,85 L48,56 L32,56 Z" fill="#059669" />
            </svg>
            
            <!-- Student 2 -->
            <svg class="student-char bounce-delay-2" viewBox="0 0 80 100" width="55" height="75">
                <circle cx="40" cy="37" r="9" fill="#fbcfe8" />
                <path d="M30,37 C30,24 50,24 50,37 C50,44 30,44 30,37" fill="#475569" />
                <path d="M26,85 L54,85 L48,58 L32,58 Z" fill="#ea580c" />
                <rect x="20" y="62" width="7" height="18" rx="2" fill="#f9a826" />
            </svg>
            
            <!-- Student 1 -->
            <svg class="student-char bounce-delay-1" viewBox="0 0 80 100" width="55" height="75">
                <circle cx="40" cy="34" r="10" fill="#fed7aa" />
                <polygon points="25,29 40,24 55,29 40,34" fill="#0b2c5c" />
                <rect x="38" y="31" width="4" height="5" fill="#0b2c5c" />
                <line x1="40" y1="24" x2="22" y2="33" stroke="#f9a826" stroke-width="1.5" />
                <path d="M25,85 L55,85 L50,54 L30,54 Z" fill="#1a4980" />
            </svg>
        </div>
        
        <!-- Server & IT Staff Section (Aligned to the far right) -->
        <div class="pb-1 ps-2" style="min-width: 140px;">
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

</body>
</html>
