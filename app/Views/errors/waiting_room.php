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
            transition: transform 0.3s ease;
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

        /* Pulse animation effect */
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

</body>
</html>
