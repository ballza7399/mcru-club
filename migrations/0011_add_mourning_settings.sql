-- เพิ่มการตั้งค่าสำหรับระบบแสดงหน้าจอไว้อาลัย
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('mourning_enabled', '1', 'mourning', 'เปิด (1) หรือ ปิด (0) การแสดงผลหน้าจอไว้อาลัยก่อนเข้าเว็บไซต์'),
('mourning_image_url', 'https://www.mcru.ac.th/images/imgUpload/20260612_4.png', 'mourning', 'ลิงก์รูปภาพของหน้าจอแสดงความไว้อาลัย'),
('mourning_duration', '3', 'mourning', 'ระยะเวลาการแสดงผลรูปภาพไว้อาลัยก่อนปิด (หน่วยเป็นวินาที)');
