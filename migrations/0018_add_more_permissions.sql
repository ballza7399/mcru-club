-- Migration: Add more permissions for RBAC Sidebar Menu Matrix
-- -------------------------------------------------------------

INSERT INTO `permissions` (`id`, `perm_key`, `perm_name`, `scope`) VALUES
(11, 'manage_club_proposals', 'ตรวจสอบการขอจัดตั้งชมรม', 'system'),
(12, 'manage_roles', 'จัดการสิทธิ์การใช้งานและบทบาท', 'system'),
(13, 'manage_faculties', 'จัดการคณะ & สาขาวิชา', 'system'),
(14, 'manage_pdpa', 'จัดการนโยบาย PDPA', 'system'),
(15, 'manage_footer', 'จัดการข้อมูล Footer', 'system'),
(16, 'manage_mourning', 'ตั้งค่าหน้าไว้อาลัย', 'system'),
(17, 'manage_opengraph', 'ตั้งค่าการแชร์ (Open Graph)', 'system'),
(18, 'manage_proposal_period', 'จัดการช่วงเวลาเสนอจัดตั้งชมรม', 'system')
ON DUPLICATE KEY UPDATE perm_name=VALUES(perm_name), scope=VALUES(scope);

-- Link new permissions to Admin (role_id = 1)
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18)
ON DUPLICATE KEY UPDATE role_id = role_id;

-- Link manage_club_proposals and manage_clubs to Staff (role_key = 'staff')
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT id, 11 FROM roles WHERE role_key = 'staff'
ON DUPLICATE KEY UPDATE role_id = role_id;
