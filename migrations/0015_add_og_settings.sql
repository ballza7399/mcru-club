-- Migration: 0015_add_og_settings
-- Description: Add keys for managing Open Graph and Twitter Card sharing meta tags

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('og_title', 'MCRU Clubs - แหล่งรวมกิจกรรมและชมรมนักศึกษา', 'opengraph', 'หัวข้อการแชร์เว็บไซต์ (og:title) ไปยังสื่อสังคมออนไลน์ (เช่น Facebook, LINE)'),
('og_description', 'ระบบจัดการและรวมศูนย์ข้อมูลชมรมนักศึกษา มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง มาร่วมทำกิจกรรมและค้นหาสิ่งที่คุณรักไปด้วยกัน', 'opengraph', 'คำอธิบายการแชร์เว็บไซต์ (og:description)'),
('og_image', '', 'opengraph', 'รูปภาพประกอบสำหรับการแชร์เว็บไซต์ (og:image) แนะนำรูปภาพขนาด 1200x630px');
