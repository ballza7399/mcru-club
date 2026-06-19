-- สร้างฐานข้อมูลระบบจัดการชมรม (เวอร์ชัน 4 - ระบบประธานชมรม)
CREATE DATABASE IF NOT EXISTS club_management CHARACTER SET utf8 COLLATE utf8_general_ci;
USE club_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    faculty VARCHAR(100),
    major VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('student', 'president', 'admin') DEFAULT 'student'
);

CREATE TABLE clubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(100) NOT NULL,
    description TEXT,
    club_logo VARCHAR(255),
    qr_code VARCHAR(255),
    max_members INT DEFAULT 50,
    president_id INT DEFAULT NULL,
    FOREIGN KEY (president_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    club_id INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (club_id) REFERENCES clubs(id)
);

-- ข้อมูลผู้ใช้งานตัวอย่าง
-- แอดมิน: admin / admin123
-- ประธานชมรม: 660002 / 123456
-- นักศึกษาทั่วไป: 660001 / 123456
INSERT INTO users (student_id, email, password, name, faculty, major, phone, role) VALUES
('admin', 'admin@mbcr.ac.th', 'admin123', 'ผู้ดูแลระบบ', '-', '-', '-', 'admin'),
('660001', 'student@mbcr.ac.th', '123456', 'สมชาย ใจดี', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'เทคโนโลยีสารสนเทศ (IT)', '0812345678', 'student'),
('660002', 'president@mbcr.ac.th', '123456', 'ประธาน ชมรมที่1', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'วิทยาการคอมพิวเตอร์ (CS)', '0899999999', 'president');

-- ข้อมูล 5 ชมรมตัวอย่าง 
INSERT INTO clubs (club_name, description, max_members, president_id) VALUES
('ชมรมนวัตกรรม IoT และสมาร์ทฟาร์ม', 'มุ่งเน้นการสร้างสรรค์นวัตกรรม Internet of Things (IoT) การเขียนโปรแกรมควบคุมไมโครคอนโทรลเลอร์ (เช่น ESP32) และการใช้งานเซนเซอร์ต่างๆ เพื่อนำมาประยุกต์ใช้กับระบบสมาร์ทฟาร์มอัตโนมัติ', 30, 3),
('ชมรมพัฒนาเว็บแอปพลิเคชัน', 'พื้นที่สำหรับผู้ที่สนใจการเขียนโปรแกรมฝั่ง Frontend และ Backend (PHP, MySQL, Bootstrap) เพื่อสร้างระบบและแอปพลิเคชันบนเว็บไซต์ที่ใช้งานได้จริง', 40, NULL),
('ชมรมช่างซ่อมคอมพิวเตอร์และฮาร์ดแวร์', 'เรียนรู้และฝึกปฏิบัติจริงเกี่ยวกับการประกอบเครื่องคอมพิวเตอร์ การแก้ไขปัญหาฮาร์ดแวร์ (เช่น อาการจอดำ, เสียง Beep Code) การดูแลรักษาปรินเตอร์ และการวางระบบเครือข่าย', 20, NULL),
('ชมรมคนรักอนิเมะและซีรีส์จีน (Donghua)', 'ศูนย์รวมคนรักศิลปะและวัฒนธรรมความบันเทิงจากจีน พูดคุยแลกเปลี่ยนเรื่องราวอนิเมะจีน (ตงฮวา) ซีรีส์แนวกำลังภายใน นิยายแปล และวัฒนธรรมร่วมสมัย', 50, NULL),
('ชมรมคนรักสัตว์เลี้ยงขนาดเล็ก', 'แลกเปลี่ยนความรู้ในการดูแลและเพาะเลี้ยงสัตว์เลี้ยงขนาดเล็ก แนะนำวิธีการให้อาหาร การจัดการที่อยู่อาศัย และการสังเกตพฤติกรรมสัตว์อย่างถูกต้อง', 25, NULL);
