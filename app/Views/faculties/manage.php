<?php
/**
 * @var array $faculties
 * @var array $majors
 */
?>

<div class="row g-4 mb-4">
    <!-- Left Column: Faculty Management -->
    <div class="col-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-building-columns text-primary me-2"></i>จัดการคณะ</h4>
            <button class="btn-gold-custom btn-sm py-2 px-3" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                <i class="fa-solid fa-plus me-1"></i> เพิ่มคณะ
            </button>
        </div>

        <div class="card-custom p-4">
            <?php if (empty($faculties)): ?>
                <div class="text-center py-4 text-muted">
                    <p class="m-0">ยังไม่มีคณะในระบบ</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ชื่อคณะ</th>
                                <th class="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faculties as $fac): ?>
                                <tr>
                                    <td class="fw-bold text-primary-custom"><?= e($fac['name']) ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFacultyModal<?= $fac['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <a href="<?= url('faculties/delete/' . (int) $fac['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('ยืนยันที่จะลบคณะนี้? การลบคณะจะลบสาขาวิชาในสังกัดทั้งหมดด้วย!')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($currentPageFac, $totalPagesFac, 'faculties/manage', $limitFac, 'page_fac', 'limit_fac') ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Major Management -->
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-graduation-cap text-success me-2"></i>จัดการสาขาวิชา</h4>
            <button class="btn-gold-custom btn-sm py-2 px-3" data-bs-toggle="modal" data-bs-target="#addMajorModal">
                <i class="fa-solid fa-plus me-1"></i> เพิ่มสาขาวิชา
            </button>
        </div>

        <div class="card-custom p-4">
            <?php if (empty($majors)): ?>
                <div class="text-center py-4 text-muted">
                    <p class="m-0">ยังไม่มีสาขาวิชาในระบบ</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>สาขาวิชา</th>
                                <th>สังกัดคณะ</th>
                                <th class="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($majors as $major): ?>
                                <tr>
                                    <td class="fw-bold text-primary-custom"><?= e($major['name']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= e($major['faculty_name']) ?></span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMajorModal<?= $major['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <a href="<?= url('majors/delete/' . (int) $major['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('ยืนยันที่จะลบสาขาวิชานี้ออกจากคณะ?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($currentPageMaj, $totalPagesMaj, 'faculties/manage', $limitMaj, 'page_maj', 'limit_maj') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- =========================================================
     Modals Section (Placed outside tables to prevent flickering)
     ========================================================= -->

<!-- Modal: Add Faculty -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เพิ่มคณะใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('faculties/store') ?>">
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

<!-- Modal: Add Major -->
<div class="modal fade" id="addMajorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เพิ่มสาขาวิชาใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('majors/store') ?>">
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">เลือกคณะที่สังกัด</label>
                        <select name="faculty_id" class="form-select" required>
                            <option value="">-- เลือกคณะ --</option>
                            <?php foreach ($allFacultiesList as $fac): ?>
                                <option value="<?= (int) $fac['id'] ?>"><?= e($fac['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อสาขาวิชา</label>
                        <input type="text" name="name" class="form-control" placeholder="เช่น วิทยาการคอมพิวเตอร์ (CS)" required>
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

<!-- Modals: Edit Faculty -->
<?php foreach ($faculties as $fac): ?>
    <div class="modal fade" id="editFacultyModal<?= $fac['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white">แก้ไขข้อมูลคณะ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('faculties/update') ?>">
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
<?php endforeach; ?>

<!-- Modals: Edit Major -->
<?php foreach ($majors as $major): ?>
    <div class="modal fade" id="editMajorModal<?= $major['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white">แก้ไขข้อมูลสาขาวิชา</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('majors/update') ?>">
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
