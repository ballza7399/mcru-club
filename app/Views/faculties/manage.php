<?php
/**
 * @var array $faculties
 * @var array $allFacultiesList
 * @var int $currentPageFac
 * @var int $totalPagesFac
 * @var int $limitFac
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="text-primary-custom fw-bold m-0">
            <i class="fa-solid fa-building-columns text-primary me-2"></i>จัดการข้อมูลคณะและสาขาวิชา
        </h4>
        <p class="text-muted m-0">ดูแลข้อมูลคณะทั้งหมดและจัดสรรสาขาวิชาในสังกัดอย่างเป็นระบบ</p>
    </div>
    <button class="btn-gold-custom py-2 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
        <i class="fa-solid fa-plus me-1"></i> สร้างคณะใหม่
    </button>
</div>

<div class="row">
    <div class="col-12">
        <?php if (empty($faculties)): ?>
            <div class="card-custom p-5 text-center text-muted">
                <i class="fa-solid fa-building-columns fs-1 mb-3 opacity-25"></i>
                <p class="m-0">ยังไม่มีข้อมูลคณะในระบบ</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-4">
                <?php foreach ($faculties as $fac): ?>
                    <div class="card-custom border shadow-sm" style="background: var(--surface); border-color: var(--border);">
                        <!-- Faculty Card Header -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom" style="background-color: var(--surface-alt);">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold text-primary-custom m-0">
                                    <i class="fa-solid fa-graduation-cap text-warning me-1"></i><?= e($fac['name']) ?>
                                </h5>
                                <span class="badge bg-primary text-white"><?= count($fac['majors']) ?> สาขาวิชา</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addMajorModal<?= $fac['id'] ?>">
                                    <i class="fa-solid fa-plus me-1"></i>เพิ่มสาขา
                                </button>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFacultyModal<?= $fac['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไขคณะ
                                </button>
                                <a href="<?= url('backoffice/faculties/delete/' . (int) $fac['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('ยืนยันที่จะลบคณะ <?= e($fac['name']) ?>? การลบคณะจะลบสาขาวิชาในสังกัดทั้งหมดด้วย!')">
                                    <i class="fa-solid fa-trash me-1"></i>ลบคณะ
                                </a>
                            </div>
                        </div>

                        <!-- Faculty Card Body: Majors List -->
                        <div class="p-3">
                            <?php if (empty($fac['majors'])): ?>
                                <div class="text-center py-3 text-muted">
                                    <span class="small"><i class="fa-solid fa-info-circle me-1"></i>ยังไม่มีสาขาวิชาภายใต้คณะนี้</span>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle m-0 table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ชื่อสาขาวิชา</th>
                                                <th class="text-end" style="width: 150px;">การจัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($fac['majors'] as $major): ?>
                                                <tr>
                                                    <td class="fw-medium text-dark ps-2"><?= e($major['name']) ?></td>
                                                    <td class="text-end">
                                                        <button class="btn btn-xs btn-outline-primary me-1 py-1 px-2" 
                                                                style="font-size: 0.75rem;"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editMajorModal<?= $major['id'] ?>">
                                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                                        </button>
                                                        <a href="<?= url('backoffice/majors/delete/' . (int) $major['id']) ?>" 
                                                           class="btn btn-xs btn-outline-danger py-1 px-2"
                                                           style="font-size: 0.75rem;"
                                                           onclick="return confirm('ยืนยันที่จะลบสาขาวิชา <?= e($major['name']) ?> ออกจากระบบ?')">
                                                            <i class="fa-solid fa-trash"></i> ลบ
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?= renderPagination($currentPageFac, $totalPagesFac, 'backoffice/faculties', $limitFac, 'page_fac', 'limit_fac') ?>
        <?php endif; ?>
    </div>
</div>

<!-- =========================================================
     Modals Section
     ========================================================= -->

<!-- Modal: Add Faculty -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-building-columns me-2"></i>เพิ่มคณะใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/faculties/store') ?>">
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อคณะ</label>
                        <input type="text" name="name" class="form-control" placeholder="เช่น คณะวิทยาศาสตร์และเทคโนโลยี" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">เพิ่มคณะ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals for Each Faculty and Major -->
<?php foreach ($faculties as $fac): ?>
    <!-- Modal: Edit Faculty -->
    <div class="modal fade" id="editFacultyModal<?= $fac['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white"><i class="fa-solid fa-pen-to-square me-2"></i>แก้ไขข้อมูลคณะ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('backoffice/faculties/update') ?>">
                    <div class="modal-body text-start">
                        <input type="hidden" name="id" value="<?= $fac['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อคณะ</label>
                            <input type="text" name="name" class="form-control" value="<?= e($fac['name']) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn-primary-custom">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add Major under this Faculty -->
    <div class="modal fade" id="addMajorModal<?= $fac['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--gold">
                    <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-plus me-2"></i>เพิ่มสาขาวิชาใหม่</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('backoffice/majors/store') ?>">
                    <div class="modal-body text-start">
                        <input type="hidden" name="faculty_id" value="<?= $fac['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">คณะที่สังกัด</label>
                            <input type="text" class="form-control" value="<?= e($fac['name']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อสาขาวิชา</label>
                            <input type="text" name="name" class="form-control" placeholder="เช่น วิทยาการคอมพิวเตอร์" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn-primary-custom">เพิ่มสาขาวิชา</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals for editing majors inside this faculty -->
    <?php foreach ($fac['majors'] as $major): ?>
        <!-- Modal: Edit Major -->
        <div class="modal fade" id="editMajorModal<?= $major['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header--brand">
                        <h5 class="modal-title text-white"><i class="fa-solid fa-pen-to-square me-2"></i>แก้ไขข้อมูลสาขาวิชา</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="<?= url('backoffice/majors/update') ?>">
                        <div class="modal-body text-start">
                            <input type="hidden" name="id" value="<?= $major['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ชื่อสาขาวิชา</label>
                                <input type="text" name="name" class="form-control" value="<?= e($major['name']) ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn-primary-custom">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
