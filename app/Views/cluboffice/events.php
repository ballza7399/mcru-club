<?php
/**
 * @var array $events
 * @var array $club
 * @var int $currentPage
 * @var int $totalPages
 * @var int $limit
 */
$clubIdQuery = '?club_id=' . (int)$club['id'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-calendar-day text-warning me-2"></i>จัดการกิจกรรมของชมรม</h4>
    <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fa-solid fa-plus me-1"></i> เพิ่มกิจกรรมใหม่
    </button>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <?php if (empty($events)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-calendar-xmark fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีข้อมูลกิจกรรมของชมรมนี้ในระบบ</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>หัวข้อกิจกรรม</th>
                        <th>วันที่จัด</th>
                        <th>เวลา</th>
                        <th>สถานที่</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $row): ?>
                        <tr>
                            <td class="fw-bold text-primary-custom" style="max-width: 250px;">
                                <?= e($row['title']) ?>
                                <?php if (!empty($row['description'])): ?>
                                    <div class="text-muted small font-normal text-truncate" style="max-width:240px;"><?= e($row['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['event_date'])) ?></td>
                            <td>
                                <?php if ($row['start_time']): ?>
                                    <?= date('H:i', strtotime($row['start_time'])) ?> - <?= $row['end_time'] ? date('H:i', strtotime($row['end_time'])) : 'สิ้นสุดกิจกรรม' ?> น.
                                <?php else: ?>
                                    <span class="text-muted small">ไม่ระบุเวลา</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['location'] ?: 'ไม่ระบุสถานที่') ?></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEventModal<?= $row['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไข
                                </button>
                                <a href="<?= url('cluboffice/events/delete/' . (int)$row['id']) . $clubIdQuery ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   data-confirm="ยืนยันต้องการลบกิจกรรมชมรมนี้ออกจากปฏิทิน?">
                                    <i class="fa-solid fa-trash me-1"></i>ลบ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'cluboffice/events', $limit) ?>
    <?php endif; ?>
</div>

<!-- Modal: Add Event -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เพิ่มกิจกรรมชมรมใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('cluboffice/events/store') . $clubIdQuery ?>">
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">หัวข้อกิจกรรม <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="เช่น ประชุมเตรียมความพร้อมชมรม" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียดกิจกรรม</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="พิมพ์รายละเอียดและเป้าหมายของกิจกรรมเพิ่มเติม..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">วันที่จัดกิจกรรม <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">เวลาเริ่มต้น</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">เวลาสิ้นสุด</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">สถานที่ดำเนินงาน</label>
                        <input type="text" name="location" class="form-control" placeholder="เช่น ห้องแล็บคอมพิวเตอร์ อาคาร 3 ชั้น 2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">บันทึกกิจกรรม</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals: Edit Event -->
<?php foreach ($events as $row): ?>
    <div class="modal fade" id="editEventModal<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white">แก้ไขข้อมูลกิจกรรม</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('cluboffice/events/update') . $clubIdQuery ?>">
                    <div class="modal-body text-start">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">หัวข้อกิจกรรม <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="<?= e($row['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">รายละเอียดกิจกรรม</label>
                            <textarea name="description" class="form-control" rows="4"><?= e($row['description']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">วันที่จัดกิจกรรม <span class="text-danger">*</span></label>
                                <input type="date" name="event_date" class="form-control" value="<?= e($row['event_date']) ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">เวลาเริ่มต้น</label>
                                <input type="time" name="start_time" class="form-control" value="<?= e($row['start_time']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">เวลาสิ้นสุด</label>
                                <input type="time" name="end_time" class="form-control" value="<?= e($row['end_time']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">สถานที่ดำเนินงาน</label>
                            <input type="text" name="location" class="form-control" value="<?= e($row['location']) ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn-primary-custom">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
