<?php
/**
 * @var array $roles
 * @var array $permissions
 * @var array $rolePerms
 * @var array|null $club
 * @var array $allClubsList
 * @var int $currentClubId
 * @var string $scopeLabel
 */
$userRole = $_SESSION['role'];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-shield-halved me-2"></i>จัดการบทบาท ตำแหน่ง และสิทธิ์การใช้งาน</h4>
        <p class="text-muted m-0">ขอบเขต: <strong><?= $scopeLabel ?></strong> <?= $club ? '(ชมรม: <span class="text-primary">' . e($club['club_name']) . '</span>)' : '' ?></p>
    </div>
    
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <?php if ($userRole === 'admin' && !empty($allClubsList)): ?>
            <label class="form-label m-0 fw-bold text-nowrap">เลือกชมรม:</label>
            <select class="form-select shadow-sm" onchange="window.location.href='<?= url('roles/manage?club_id=') ?>' + this.value">
                <option value="0" <?= $currentClubId === 0 ? 'selected' : '' ?>>-- ระบบหลัก (System Roles) --</option>
                <?php foreach ($allClubsList as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= $currentClubId === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['club_name']) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        
        <?php if ($currentClubId > 0 || $userRole === 'president'): ?>
            <!-- Add Custom Role Button -->
            <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                <i class="fa-solid fa-plus me-1"></i> เพิ่มตำแหน่งในชมรม
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- List of Roles and Permissions Form -->
<div class="row g-4">
    <?php if (empty($roles)): ?>
        <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm">
            <i class="fa-solid fa-shield-slash fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีบทบาทในชมรมนี้</p>
        </div>
    <?php else: ?>
        <?php foreach ($roles as $r): ?>
            <?php 
                $isSystemRole = ($r['scope'] === 'system' || $r['club_id'] === null); 
                $isPresident = ($r['role_key'] === 'president');
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card-custom h-100 d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-primary-custom m-0"><?= e($r['role_name']) ?></h5>
                            <span class="badge <?= $isSystemRole ? 'bg-secondary' : 'bg-info' ?> text-white" style="font-size: 0.7rem;">
                                <?= $isSystemRole ? 'ตำแหน่งเริ่มต้น' : 'ตำแหน่งที่เพิ่มเอง' ?>
                            </span>
                        </div>
                        <?php if (!$isSystemRole): ?>
                            <a href="<?= url('roles/delete/' . (int) $r['id']) ?>" 
                               class="text-danger" 
                               title="ลบตำแหน่งนี้" 
                               onclick="return confirm('คุณต้องการลบตำแหน่งนี้หรือไม่? สมาชิกที่สวมตำแหน่งนี้จะกลับไปเป็นสมาชิกทั่วไป')">
                                <i class="fa-regular fa-trash-can fs-5"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <hr class="my-2 opacity-10">
                    
                    <form method="POST" action="<?= url('roles/permissions/sync') ?>" class="flex-grow-1 d-flex flex-column">
                        <input type="hidden" name="role_id" value="<?= $r['id'] ?>">
                        <input type="hidden" name="club_id" value="<?= $currentClubId ?>">
                        
                        <p class="small fw-bold text-muted mb-2">สิทธิ์การใช้งานที่อนุญาต:</p>
                        
                        <div class="flex-grow-1 overflow-auto pe-2" style="max-height: 250px;">
                            <?php if ($isPresident && !$isSystemRole): ?>
                                <div class="text-success small mb-3">
                                    <i class="fa-solid fa-circle-check me-1"></i> ประธานชมรมได้รับสิทธิ์การจัดการชมรมทั้งหมดโดยอัตโนมัติ
                                </div>
                            <?php endif; ?>
                            
                            <?php foreach ($permissions as $perm): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="<?= $perm['id'] ?>" 
                                           id="perm_<?= $r['id'] ?>_<?= $perm['id'] ?>"
                                           <?= in_array($perm['id'], $rolePerms[$r['id']], true) ? 'checked' : '' ?>
                                           <?= ($isPresident && $userRole !== 'admin') ? 'disabled' : '' ?>>
                                    <label class="form-check-label small text-dark" for="perm_<?= $r['id'] ?>_<?= $perm['id'] ?>">
                                        <?= e($perm['perm_name']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn-primary-custom w-100 py-2" <?= ($isPresident && $userRole !== 'admin') ? 'disabled' : '' ?>>
                                <i class="fa-solid fa-circle-check me-1"></i> บันทึกสิทธิ์
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal: Add Club Role -->
<?php if ($currentClubId > 0 || $userRole === 'president'): ?>
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เพิ่มตำแหน่งชมรมใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('roles/store') ?>">
                <div class="modal-body">
                    <input type="hidden" name="club_id" value="<?= $currentClubId ?>">
                    
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">ชื่อตำแหน่งหน้าที่</label>
                        <input type="text" name="role_name" class="form-control" placeholder="เช่น เหรัญญิกชมรม, ฝ่ายวิชาการ, เลขานุการชมรม" required>
                        <small class="text-muted d-block mt-2">
                            * เมื่อเพิ่มตำแหน่งสำเร็จแล้ว คุณสามารถมาเลือกกำหนดสิทธิ์การใช้งานให้กับตำแหน่งนี้ได้ทางด้านนอก
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">สร้างตำแหน่ง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
