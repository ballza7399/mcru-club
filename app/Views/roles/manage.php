<?php
/**
 * @var array $roles
 * @var array $permissions
 * @var array $rolePerms
 * @var string $pageTitle
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="text-primary-custom fw-bold m-0">
            <i class="fa-solid fa-shield-halved text-warning me-2" style="color: var(--accent-gold) !important;"></i>จัดการตารางสิทธิ์การใช้งาน (Role Matrix)
        </h4>
        <p class="text-muted small m-0 mt-1">กำหนดสิทธิ์การเข้าถึงเมนูและฟังก์ชันหลังบ้านของแต่ละบทบาทและตำแหน่ง</p>
    </div>
</div>

<form method="POST" action="<?= url('backoffice/roles/permissions/sync') ?>" data-confirm="ยืนยันการบันทึกตารางสิทธิ์การใช้งาน (Role Matrix) ใหม่ทั้งหมดหรือไม่?">
    <div class="card-custom p-4 border shadow-sm mb-4" style="background: var(--surface); border-color: var(--border);">
        <div class="table-responsive">
            <table class="table align-middle table-bordered role-matrix-table">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-start" style="min-width: 280px; background-color: #f8fafc;">
                            <span class="fw-bold text-dark"><i class="fa-solid fa-list-check me-2 text-primary"></i>เมนูระบบ / สิทธิ์การเข้าถึง</span>
                        </th>
                        <?php foreach ($roles as $r): ?>
                            <?php 
                                $isSystem = ($r['scope'] === 'system');
                                $roleBadgeTone = $isSystem ? 'bg-primary-soft' : 'bg-warning-soft text-warning-ink';
                            ?>
                            <th class="text-center align-middle" style="width: 140px; background-color: #f8fafc;">
                                <div class="fw-bold text-primary-custom" style="font-size: 0.95rem;"><?= e($r['role_name']) ?></div>
                                <span class="badge <?= $roleBadgeTone ?> mt-1 font-monospace" style="font-size: 0.65rem;">
                                    <?= e($r['role_key']) ?>
                                </span>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // จัดกลุ่มสิทธิ์ตามหมวดหมู่เพื่อการอ่านที่ง่ายขึ้น
                    $groups = [
                        'การจัดการชมรม' => [
                            'icon' => 'fa-solid fa-layer-group text-primary',
                            'keys' => ['manage_clubs', 'manage_club_proposals', 'manage_club_members', 'manage_club_roles', 'manage_club_info']
                        ],
                        'เนื้อหา & ประชาสัมพันธ์' => [
                            'icon' => 'fa-solid fa-bullhorn text-danger',
                            'keys' => ['manage_system_news', 'post_club_news', 'manage_system_events', 'manage_club_events', 'manage_club_gallery']
                        ],
                        'ผู้ใช้งาน & ความปลอดภัย' => [
                            'icon' => 'fa-solid fa-users-gear text-success',
                            'keys' => ['manage_users', 'manage_roles']
                        ],
                        'การตั้งค่าระบบ' => [
                            'icon' => 'fa-solid fa-gears text-warning',
                            'keys' => ['manage_faculties', 'manage_pdpa', 'manage_footer', 'manage_mourning', 'manage_opengraph', 'manage_proposal_period']
                        ]
                    ];

                    foreach ($groups as $groupName => $groupConfig):
                    ?>
                        <tr class="table-group-header" style="background-color: rgba(11, 44, 92, 0.03);">
                            <td colspan="<?= count($roles) + 1 ?>" class="fw-bold text-dark py-2" style="font-size: 0.9rem;">
                                <i class="<?= $groupConfig['icon'] ?> me-2"></i><?= $groupName ?>
                            </td>
                        </tr>
                        <?php 
                        foreach ($groupConfig['keys'] as $pKey):
                            // ค้นหาวัตถุ Permission ใน $permissions
                            $perm = null;
                            foreach ($permissions as $p) {
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
                                <?php foreach ($roles as $r): ?>
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
                                                <!-- ซ่อนฟิลด์ input เพื่อส่งค่าของแอดมินไว้ลับๆ ไปบันทึกด้วย ป้องกันสิทธิ์หลุด -->
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

    <!-- Actions Footer -->
    <div class="card-custom p-3 border shadow-sm text-end" style="background: var(--surface); border-color: var(--border);">
        <button type="submit" class="btn btn-academic-primary border-0 px-5 py-2.5 rounded-pill shadow-sm">
            <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกตารางสิทธิ์การใช้งานทั้งหมด (Save Matrix)
        </button>
    </div>
</form>

<style>
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
.matrix-row:hover {
    background-color: rgba(249, 168, 38, 0.02) !important;
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
