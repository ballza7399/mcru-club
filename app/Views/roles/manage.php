<?php
/**
 * @var array $roles
 * @var array $permissions
 * @var array $rolePerms
 * @var string $pageTitle
 */

// แยกประเภทของบทบาทและสิทธิ์การใช้งานตาม Scope
$systemRoles = [];
$clubRoles = [];
foreach ($roles as $r) {
    if ($r['scope'] === 'system') {
        $systemRoles[] = $r;
    } else {
        $clubRoles[] = $r;
    }
}

$systemPermissions = [];
$clubPermissions = [];
foreach ($permissions as $p) {
    if ($p['scope'] === 'system') {
        $systemPermissions[] = $p;
    } else {
        $clubPermissions[] = $p;
    }
}

// ตรวจสอบแท็บที่ต้องแสดงเป็น Active
$activeTab = $_GET['type'] ?? 'system';
if (!in_array($activeTab, ['system', 'club'], true)) {
    $activeTab = 'system';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="text-primary-custom fw-bold m-0">
            <i class="fa-solid fa-shield-halved text-warning me-2" style="color: var(--accent-gold) !important;"></i>จัดการตารางสิทธิ์การใช้งาน (Role Matrix)
        </h4>
        <p class="text-muted small m-0 mt-1">กำหนดสิทธิ์การเข้าถึงเมนูและฟังก์ชันหลังบ้านของแต่ละบทบาทและตำแหน่ง</p>
    </div>
</div>

<!-- Tabs Control -->
<ul class="nav nav-tabs mb-4" id="matrixTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $activeTab === 'system' ? 'active' : '' ?> fw-bold px-4 py-2.5" id="system-tab" data-bs-toggle="tab" data-bs-target="#system-pane" type="button" role="tab">
            <i class="fa-solid fa-server me-2"></i>ระบบหลัก (System Scope)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $activeTab === 'club' ? 'active' : '' ?> fw-bold px-4 py-2.5" id="club-tab" data-bs-toggle="tab" data-bs-target="#club-pane" type="button" role="tab">
            <i class="fa-solid fa-people-roof me-2"></i>ตำแหน่งภายในชมรม (Club Scope)
        </button>
    </li>
</ul>

<form method="POST" action="<?= url('backoffice/roles/permissions/sync') ?>" data-confirm="ยืนยันการบันทึกตารางสิทธิ์การใช้งาน (Role Matrix) ทั้งหมดหรือไม่?">
    <div class="tab-content" id="matrixTabContent">
        <!-- 1. System Scope Tab -->
        <div class="tab-pane fade <?= $activeTab === 'system' ? 'show active' : '' ?>" id="system-pane" role="tabpanel" aria-labelledby="system-tab" tabindex="0">
            <div class="card-custom p-4 border shadow-sm mb-4" style="background: var(--surface); border-color: var(--border);">
                <div class="table-responsive">
                    <table class="table align-middle table-bordered role-matrix-table">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle text-start" style="min-width: 280px; background-color: #f8fafc;">
                                    <span class="fw-bold text-dark"><i class="fa-solid fa-list-check me-2 text-primary"></i>เมนูระบบ / สิทธิ์ระบบหลัก</span>
                                </th>
                                <?php foreach ($systemRoles as $r): ?>
                                    <th class="text-center align-middle" style="width: 140px; background-color: #f8fafc;">
                                        <div class="fw-bold text-primary-custom" style="font-size: 0.95rem;"><?= e($r['role_name']) ?></div>
                                        <span class="badge bg-primary-soft mt-1 font-monospace" style="font-size: 0.65rem;">
                                            <?= e($r['role_key']) ?>
                                        </span>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $systemGroups = [
                                'การจัดการชมรม & ระบบ' => [
                                    'icon' => 'fa-solid fa-layer-group text-primary',
                                    'keys' => ['manage_clubs', 'manage_club_proposals']
                                ],
                                'เนื้อหา & ประชาสัมพันธ์ข่าวสาร' => [
                                    'icon' => 'fa-solid fa-bullhorn text-danger',
                                    'keys' => ['manage_system_news', 'manage_system_events']
                                ],
                                'ผู้ใช้งาน & ความปลอดภัย' => [
                                    'icon' => 'fa-solid fa-users-gear text-success',
                                    'keys' => ['manage_users', 'manage_roles']
                                ],
                                'การตั้งค่าระบบทั่วไป' => [
                                    'icon' => 'fa-solid fa-gears text-warning',
                                    'keys' => ['manage_faculties', 'manage_pdpa', 'manage_footer', 'manage_mourning', 'manage_opengraph', 'manage_proposal_period']
                                ]
                            ];

                            foreach ($systemGroups as $groupName => $groupConfig):
                            ?>
                                <tr class="table-group-header" style="background-color: rgba(11, 44, 92, 0.03);">
                                    <td colspan="<?= count($systemRoles) + 1 ?>" class="fw-bold text-dark py-2" style="font-size: 0.9rem;">
                                        <i class="<?= $groupConfig['icon'] ?> me-2"></i><?= $groupName ?>
                                    </td>
                                </tr>
                                <?php 
                                foreach ($groupConfig['keys'] as $pKey):
                                    $perm = null;
                                    foreach ($systemPermissions as $p) {
                                        if ($p['perm_key'] === $pKey) {
                                            $perm = $p;
                                            break;
                                        }
                                    }
                                    if (!$perm) continue;
                                ?>
                                    <tr class="matrix-row">
                                        <td class="text-start ps-4">
                                            <div class="fw-semibold text-dark" style="font-size: 0.88rem;"><?= e($perm['perm_name']) ?></div>
                                            <small class="text-muted font-monospace" style="font-size: 0.72rem;"><?= e($perm['perm_key']) ?></small>
                                        </td>
                                        <?php foreach ($systemRoles as $r): ?>
                                            <?php 
                                                $isAdmin = ($r['role_key'] === 'admin');
                                                $isChecked = in_array($perm['id'], $rolePerms[$r['id']], true) || $isAdmin;
                                            ?>
                                            <td class="text-center align-middle">
                                                <div class="form-check d-inline-block m-0">
                                                    <input class="form-check-input matrix-checkbox" 
                                                           type="checkbox" 
                                                           name="matrix[<?= $r['id'] ?>][]" 
                                                           value="<?= $perm['id'] ?>" 
                                                           id="chk_<?= $r['id'] ?>_<?= $perm['id'] ?>"
                                                           <?= $isChecked ? 'checked' : '' ?>
                                                           <?= $isAdmin ? 'disabled onclick="return false;"' : '' ?>
                                                           style="width: 19px; height: 19px; cursor: <?= $isAdmin ? 'not-allowed' : 'pointer' ?>;">
                                                    <?php if ($isAdmin): ?>
                                                        <input type="hidden" name="matrix[<?= $r['id'] ?>][]" value="<?= $perm['id'] ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. Club Scope Tab -->
        <div class="tab-pane fade <?= $activeTab === 'club' ? 'show active' : '' ?>" id="club-pane" role="tabpanel" aria-labelledby="club-tab" tabindex="0">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="text-muted small">
                    <i class="fa-solid fa-circle-info me-1 text-primary"></i>ทุกชมรมจะใช้รายการตำแหน่งและกำหนดสิทธิ์เหล่านี้ร่วมกัน
                </div>
                <button type="button" class="btn btn-gold-custom btn-sm rounded-pill px-3 py-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="fa-solid fa-plus me-1"></i> เพิ่มตำแหน่งชมรมใหม่
                </button>
            </div>
            
            <div class="card-custom p-4 border shadow-sm mb-4" style="background: var(--surface); border-color: var(--border);">
                <div class="table-responsive">
                    <table class="table align-middle table-bordered role-matrix-table">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle text-start" style="min-width: 280px; background-color: #f8fafc;">
                                    <span class="fw-bold text-dark"><i class="fa-solid fa-list-check me-2 text-warning"></i>สิทธิ์การบริหารชมรม / ตำแหน่งในชมรม</span>
                                </th>
                                <?php foreach ($clubRoles as $r): ?>
                                    <?php 
                                        $isCustom = (strpos($r['role_key'], 'custom_') === 0);
                                    ?>
                                    <th class="text-center align-middle" style="width: 140px; background-color: #f8fafc; position: relative; min-width: 130px;">
                                        <div class="d-flex flex-column align-items-center justify-content-center position-relative py-2">
                                            <?php if ($isCustom): ?>
                                                <a href="<?= url('backoffice/roles/delete/' . (int) $r['id']) ?>" 
                                                   class="text-danger position-absolute" 
                                                   style="top: -2px; right: 2px;"
                                                   title="ลบตำแหน่งนี้" 
                                                   data-confirm="คุณต้องการลบตำแหน่งนี้หรือไม่? สมาชิกที่สวมตำแหน่งนี้จะกลับไปเป็นสมาชิกทั่วไป"
                                                   data-confirm-title="ยืนยันการลบตำแหน่งชมรม"
                                                   data-confirm-color="#dc3545"
                                                   data-confirm-btn="ลบตำแหน่ง">
                                                    <i class="fa-regular fa-trash-can" style="font-size: 0.85rem;"></i>
                                                </a>
                                            <?php endif; ?>
                                            <div class="fw-bold text-primary-custom" style="font-size: 0.95rem;"><?= e($r['role_name']) ?></div>
                                            <span class="badge bg-warning-soft mt-1 font-monospace" style="font-size: 0.65rem;">
                                                <?= e($r['role_key']) ?>
                                            </span>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-group-header" style="background-color: rgba(249, 168, 38, 0.03);">
                                <td colspan="<?= count($clubRoles) + 1 ?>" class="fw-bold text-dark py-2" style="font-size: 0.9rem;">
                                    <i class="fa-solid fa-people-group text-warning me-2"></i>ระดับบริหารและเนื้อหาภายในชมรม
                                </td>
                            </tr>
                            <?php 
                            $clubKeys = ['manage_club_info', 'manage_club_members', 'manage_club_roles', 'post_club_news', 'manage_club_events', 'manage_club_gallery'];
                            foreach ($clubKeys as $pKey):
                                $perm = null;
                                foreach ($clubPermissions as $p) {
                                    if ($p['perm_key'] === $pKey) {
                                        $perm = $p;
                                        break;
                                    }
                                }
                                if (!$perm) continue;
                            ?>
                                <tr class="matrix-row">
                                    <td class="text-start ps-4">
                                        <div class="fw-semibold text-dark" style="font-size: 0.88rem;"><?= e($perm['perm_name']) ?></div>
                                        <small class="text-muted font-monospace" style="font-size: 0.72rem;"><?= e($perm['perm_key']) ?></small>
                                    </td>
                                    <?php foreach ($clubRoles as $r): ?>
                                        <?php 
                                            // ประธานชมรมจะได้เช็คสิทธิ์โดยสมบูรณ์ตามโมเดล
                                            $isChecked = in_array($perm['id'], $rolePerms[$r['id']], true);
                                        ?>
                                        <td class="text-center align-middle">
                                            <div class="form-check d-inline-block m-0">
                                                <input class="form-check-input matrix-checkbox" 
                                                       type="checkbox" 
                                                       name="matrix[<?= $r['id'] ?>][]" 
                                                       value="<?= $perm['id'] ?>" 
                                                       id="chk_<?= $r['id'] ?>_<?= $perm['id'] ?>"
                                                       <?= $isChecked ? 'checked' : '' ?>
                                                       style="width: 19px; height: 19px; cursor: pointer;">
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Footer -->
    <div class="card-custom p-3 border shadow-sm text-end" style="background: var(--surface); border-color: var(--border);">
        <button type="submit" class="btn btn-academic-primary border-0 px-5 py-2.5 rounded-pill shadow-sm">
            <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกตารางสิทธิ์การใช้งานทั้งหมด (Save Matrix)
        </button>
    </div>
</form>

<!-- Modal: Add Club Role -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%); border-radius: var(--radius-lg) var(--radius-lg) 0 0; border-bottom: none;">
                <h5 class="modal-title fw-bold" id="addRoleModalLabel">
                    <i class="fa-solid fa-people-roof me-2 text-warning" style="color: var(--accent-gold) !important;"></i>เพิ่มตำแหน่งชมรมส่วนกลางใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/roles/store') ?>">
                <div class="modal-body p-4">
                    <input type="hidden" name="type" value="club">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-2">ชื่อตำแหน่งหน้าที่</label>
                        <div class="field">
                            <input type="text" name="role_name" class="form-control field__control" placeholder="เช่น เหรัญญิก, ฝ่ายประชาสัมพันธ์, ฝ่ายวิชาการ" required>
                        </div>
                        <small class="text-muted d-block mt-2">
                            * เมื่อเพิ่มตำแหน่งสำเร็จแล้ว จะปรากฏคอลัมน์ใหม่ในตารางของแท็บตำแหน่งภายในชมรม เพื่อให้คุณกำหนดสิทธิ์ให้กับตำแหน่งนี้ได้ (ทุกชมรมจะใช้รายการตำแหน่งนี้ร่วมกัน)
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-gold-custom rounded-pill px-4">
                        <i class="fa-solid fa-circle-plus me-1"></i> สร้างตำแหน่ง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#matrixTabs {
    border-bottom: 2px solid var(--border-strong) !important;
}
#matrixTabs .nav-link {
    border: 1px solid transparent;
    border-bottom: none;
    color: var(--primary-blue);
    border-radius: 12px 12px 0 0;
    margin-bottom: -2px;
    transition: all var(--dur) var(--ease-out);
}
#matrixTabs .nav-link.active {
    background-color: var(--primary-blue) !important;
    color: #ffffff !important;
    border-color: var(--primary-blue) !important;
}
#matrixTabs .nav-link:hover:not(.active) {
    background-color: rgba(11, 44, 92, 0.04);
    border-color: transparent;
}

.role-matrix-table {
    border-collapse: separate;
    border-spacing: 0;
}
.role-matrix-table th {
    border-bottom: 2px solid var(--border-strong) !important;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}
.role-matrix-table td {
    padding: 12px 16px;
    border-color: var(--border) !important;
}
/* รองรับกรณีมีบทบาทเยอะ ๆ ด้วยการตรึงคอลัมน์แรกไว้ขณะเลื่อนในแนวนอน (Sticky Columns) */
.role-matrix-table th:first-child,
.role-matrix-table td:first-child {
    position: sticky;
    left: 0;
    background-color: var(--surface) !important;
    z-index: 10;
    border-right: 2px solid var(--border-strong) !important;
    box-shadow: 4px 0 8px rgba(0, 0, 0, 0.05);
}
.role-matrix-table th:first-child {
    z-index: 11;
    background-color: #f8fafc !important;
}
/* ไฮไลต์แถวเมื่อชี้เมาส์เพื่อให้ไม่หลงแถวเมื่อเลื่อนตามแนวนอน */
.matrix-row:hover {
    background-color: rgba(249, 168, 38, 0.02) !important;
}
.matrix-row:hover td:first-child {
    background-color: #fcf8f2 !important; /* เปลี่ยนสีคอลัมน์สติ๊กกี้ให้สอดคล้องกันตอน Hover */
}
.bg-primary-soft {
    background-color: rgba(11, 44, 92, 0.08) !important;
    color: var(--primary-blue) !important;
}
.bg-warning-soft {
    background-color: rgba(249, 168, 38, 0.08) !important;
    color: var(--accent-gold-deep) !important;
}
.btn-academic-primary {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%);
    color: #ffffff !important;
    font-weight: 600;
    transition: all var(--dur) var(--ease-out);
}
.btn-academic-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(11, 44, 92, 0.3);
    background: linear-gradient(135deg, var(--primary-soft) 0%, #205c9e 100%);
}
</style>
