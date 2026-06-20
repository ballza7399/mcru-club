<?php
/**
 * @var array $policies
 * @var array $consents
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-scale-balanced text-warning me-2"></i>จัดการนโยบายความยินยอมและข้อตกลง (PDPA & TOS)</h4>
</div>

<div class="row g-4">
    <!-- Left column: policy configuration forms -->
    <div class="col-lg-7">
        <div class="card-custom p-4 border shadow-sm mb-4" style="background: var(--surface); border-color: var(--border);">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="fa-solid fa-file-pen text-primary me-2"></i>แก้ไขและปรับปรุงรายละเอียดนโยบาย
            </h5>
            
            <ul class="nav nav-tabs nav-tabs-academic border-0 gap-2 mb-4" id="policyTabs" role="tablist">
                <?php foreach ($policies as $index => $policy): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 border-0 <?= $index === 0 ? 'active' : '' ?>" 
                                id="<?= $policy['policy_key'] ?>-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#tab-<?= $policy['policy_key'] ?>" 
                                type="button" 
                                role="tab" 
                                aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                            <?= e($policy['title']) ?>
                        </button>
                    <?php endforeach; ?>
                </li>
            </ul>
            
            <div class="tab-content" id="policyTabsContent">
                <?php foreach ($policies as $index => $policy): ?>
                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" 
                         id="tab-<?= $policy['policy_key'] ?>" 
                         role="tabpanel" 
                         aria-labelledby="<?= $policy['policy_key'] ?>-tab">
                        <form action="<?= url('backoffice/pdpa/update') ?>" method="POST">
                            <input type="hidden" name="policy_key" value="<?= e($policy['policy_key']) ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">หัวข้อนโยบาย</label>
                                <input type="text" name="title" class="form-control" value="<?= e($policy['title']) ?>" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">รหัสอ้างอิงระบบ</label>
                                    <input type="text" class="form-control bg-light" value="<?= e($policy['policy_key']) ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">เลขเวอร์ชันนโยบาย (Version)</label>
                                    <input type="text" name="version" class="form-control" value="<?= e($policy['version']) ?>" required>
                                    <div class="form-text text-warning small"><i class="fa-solid fa-circle-exclamation me-1"></i>การเปลี่ยนเลขเวอร์ชันจะบังคับให้ผู้ใช้ทุกคนต้องกดยอมรับนโยบายนี้ใหม่อีกครั้งในการเข้าใช้ครั้งต่อไป</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">เนื้อหาข้อตกลงโดยละเอียด</label>
                                <textarea name="content" class="form-control" rows="12" required><?= e($policy['content']) ?></textarea>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-academic-primary px-4 py-2 border-0">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกและบังคับใช้
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Right column: user consent logs -->
    <div class="col-lg-5">
        <div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="fa-solid fa-clock-rotate-left text-success me-2"></i>ประวัติการยินยอมล่าสุด (100 รายการ)
            </h5>
            
            <?php if (empty($consents)): ?>
                <div class="text-center py-5 text-muted bg-light rounded-4 border" style="border-style: dashed !important; border-color: var(--border-strong) !important;">
                    <i class="fa-solid fa-signature fs-2 mb-2 opacity-50"></i>
                    <p class="m-0 small">ยังไม่มีผู้ใช้งานกดยินยอมนโยบายในขณะนี้</p>
                </div>
            <?php else: ?>
                <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                    <table class="table table-hover align-middle table-sm border-0" style="font-size: 0.85rem;">
                        <thead class="bg-light sticky-top" style="z-index: 2;">
                            <tr class="border-bottom" style="border-color: var(--border-strong) !important;">
                                <th class="py-2 text-dark fw-bold">ผู้ยอมรับ</th>
                                <th class="py-2 text-dark fw-bold">นโยบาย / เวอร์ชัน</th>
                                <th class="py-2 text-dark fw-bold">วันเวลา / IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consents as $c): ?>
                                <tr class="border-bottom" style="border-color: var(--border) !important;">
                                    <td class="py-2">
                                        <div class="fw-bold text-dark"><?= e($c['name']) ?></div>
                                        <small class="text-muted font-monospace"><?= e($c['student_id']) ?></small>
                                    </td>
                                    <td class="py-2">
                                        <?php if ($c['policy_key'] === 'terms_of_service'): ?>
                                            <span class="badge bg-primary-custom text-primary small px-2 py-1 rounded">TOS</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-custom text-success small px-2 py-1 rounded">Privacy</span>
                                        <?php endif; ?>
                                        <span class="text-dark fw-medium ms-1">v<?= e($c['version']) ?></span>
                                    </td>
                                    <td class="py-2">
                                        <div class="text-dark small"><i class="fa-regular fa-clock me-1 text-muted"></i><?= date('d/m/Y H:i', strtotime($c['consented_at'])) ?></div>
                                        <small class="text-muted font-monospace"><i class="fa-solid fa-network-wired me-1 opacity-75"></i><?= e($c['ip_address'] ?? '-') ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
