CREATE TABLE `policies` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสไอดีหลักของนโยบาย',
  `policy_key` VARCHAR(50) NOT NULL UNIQUE COMMENT 'รหัสคีย์นโยบาย เช่น privacy_policy, terms_of_service',
  `title` VARCHAR(255) NOT NULL COMMENT 'ชื่อหัวข้อนโยบาย',
  `content` TEXT NOT NULL COMMENT 'เนื้อหาข้อกำหนดหรือนโยบายโดยละเอียด',
  `version` VARCHAR(20) NOT NULL DEFAULT '1.0' COMMENT 'เลขเวอร์ชันของนโยบายสำหรับตรวจสอบความยินยอมล่าสุด',
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันเวลาที่แก้ไขนโยบายล่าสุด'
) COMMENT = 'ตารางเก็บข้อมูลเงื่อนไขข้อกำหนดนโยบาย (TOS/Policy) สำหรับระบบ PDPA';

CREATE TABLE `user_consents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสไอดีประวัติการกดยินยอม',
  `user_id` INT NOT NULL COMMENT 'รหัสไอดีผู้ใช้ อ้างอิงตาราง users',
  `policy_key` VARCHAR(50) NOT NULL COMMENT 'รหัสคีย์นโยบายที่ยอมรับ อ้างอิงตาราง policies',
  `version` VARCHAR(20) NOT NULL COMMENT 'เลขเวอร์ชันที่ผู้ใช้กดยอมรับ',
  `ip_address` VARCHAR(45) DEFAULT NULL COMMENT 'ที่อยู่ไอพีขณะทำการกดยอมรับเพื่อความปลอดภัยในการตรวจสอบ',
  `consented_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ทำการกดยินยอม',
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) COMMENT = 'ตารางเก็บประวัติความยินยอมนโยบาย PDPA ของผู้ใช้แต่ละคน';

-- เพิ่มข้อมูลเริ่มต้น (Default Policies)
INSERT INTO `policies` (`policy_key`, `title`, `content`, `version`) VALUES 
('terms_of_service', 'เงื่อนไขและข้อตกลงการใช้งาน (Terms of Service)', 'ยินดีต้อนรับสู่ระบบจัดการชมรม MCRU การสมัครใช้งานและเข้าใช้งานระบบนี้หมายถึงท่านตกลงยอมรับเงื่อนไขดังต่อไปนี้...\n1. ข้อมูลที่ลงทะเบียนจะต้องเป็นข้อมูลจริงของนักศึกษา มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง\n2. ห้ามลงข้อมูลที่ละเมิดศีลธรรม กฎหมาย หรือลิขสิทธิ์ของผู้อื่น\n3. ระบบจัดตั้งขึ้นเพื่อส่งเสริมกิจกรรมนักศึกษาและการเรียนรู้ภายในมหาวิทยาลัย', '1.0'),
('privacy_policy', 'นโยบายความเป็นส่วนตัว (Privacy Policy)', 'นโยบายความเป็นส่วนตัวนี้อธิบายถึงวิธีที่เราเก็บรวบรวม ใช้ และเปิดเผยข้อมูลส่วนบุคคลของท่านภายใต้พระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)...\n1. ข้อมูลที่เราเก็บรวบรวม ได้แก่ ชื่อ-นามสกุล รหัสนักศึกษา คณะ/สาขา และช่องทางติดต่อสื่อสาร\n2. วัตถุประสงค์ในการเก็บข้อมูลเพื่อใช้ในการบริหารงานภายในชมรม การตรวจสอบสิทธิ์การเข้าร่วมกิจกรรม และการแจ้งเตือนประชาสัมพันธ์ข่าวสาร\n3. เราจะไม่เผยแพร่ข้อมูลของท่านแก่บุคคลภายนอกที่ไม่เกี่ยวข้องโดยเด็ดขาด ยกเว้นได้รับความยินยอมจากท่าน', '1.0');
