-- เพิ่มคีย์การตั้งค่าสำหรับควบคุมพฤติกรรมการแสดงผลหน้าไว้อาลัย
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('mourning_homepage_only', '1', 'mourning', 'เปิด (1) เพื่อแสดงหน้าจอไว้อาลัยเฉพาะหน้าหลัก (Home Page) หรือ ปิด (0) เพื่อแสดงทุกหน้า'),
('mourning_every_time', '1', 'mourning', 'เปิด (1) เพื่อแสดงหน้าจอไว้อาลัยทุกครั้งที่โหลดหน้านั้นใหม่ หรือ ปิด (0) เพื่อแสดงครั้งเดียวต่อหนึ่งเซสชันเบราว์เซอร์');
