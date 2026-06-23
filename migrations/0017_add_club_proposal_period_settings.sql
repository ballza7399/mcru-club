-- Migration: Add club proposal opening period settings
-- --------------------------------------------------

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('club_proposal_period_start', '2026-06-01 00:00:00', 'club_proposal', 'วัน-เวลาเริ่มต้นในการเปิดรับเสนอขอก่อตั้งชมรม'),
('club_proposal_period_end', '2026-06-30 23:59:59', 'club_proposal', 'วัน-เวลาสิ้นสุดในการเปิดรับเสนอขอก่อตั้งชมรม'),
('club_proposal_period_enabled', 'true', 'club_proposal', 'เปิดใช้งานการตรวจสอบช่วงเวลาเปิดรับเสนอจัดตั้งชมรม (true = เปิดตรวจสอบ, false = ปิดตรวจสอบ/อนุญาตตลอดเวลา)');
