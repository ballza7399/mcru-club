CREATE TABLE `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสไอดีหลักของการตั้งค่า',
  `setting_key` VARCHAR(100) NOT NULL UNIQUE COMMENT 'รหัสคีย์การตั้งค่า เช่น footer_address, footer_phone',
  `setting_value` TEXT COMMENT 'ค่าที่บันทึกของตัวแปรการตั้งค่า',
  `setting_group` VARCHAR(50) DEFAULT 'general' COMMENT 'กลุ่มการตั้งค่า',
  `description` VARCHAR(255) COMMENT 'คำอธิบายความหมายของการตั้งค่าสำหรับแสดงผลที่หน้าบ้านหรือหลังบ้าน'
) COMMENT = 'ตารางเก็บข้อมูลการตั้งค่าเว็บไซต์ (Site Settings) รวมถึงลิงก์ข้อมูลการติดต่อและโซเชียลใน Footer';

-- ใส่ข้อมูลตั้งค่าเริ่มต้นสำหรับ Footer
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('footer_about_text', 'ระบบจัดการและรวมศูนย์ข้อมูลชมรมนักศึกษา มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง ส่งเสริมการทำกิจกรรม แลกเปลี่ยนเรียนรู้ สร้างมิตรภาพ และพัฒนาทักษะชีวิตของนักศึกษานอกห้องเรียนอย่างสร้างสรรค์', 'footer', 'ข้อความแนะนำระบบสั้นๆ ใน Footer'),
('footer_facebook_url', 'https://facebook.com', 'footer', 'ลิงก์ Facebook ของมหาวิทยาลัย/สโมสร'),
('footer_youtube_url', 'https://youtube.com', 'footer', 'ลิงก์ YouTube ของมหาวิทยาลัย/สโมสร'),
('footer_website_url', 'https://www.mcru.ac.th', 'footer', 'ลิงก์เว็บไซต์หลักของมหาวิทยาลัย'),
('footer_contact_address', 'มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง เลขที่ 46 หมู่ 3 ต.จอมบึง อ.จอมบึง จ.ราชบุรี 70150', 'footer', 'ที่อยู่สำหรับติดต่อใน Footer'),
('footer_contact_phone', '032-261-790', 'footer', 'เบอร์โทรศัพท์ติดต่อใน Footer'),
('footer_contact_email', 'info@mcru.ac.th', 'footer', 'อีเมลติดต่อใน Footer');
