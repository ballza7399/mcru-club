<?php
/**
 * @var array $events
 * @var array $clubsList
 * @var string $role
 * @var int|null $clubId
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-regular fa-calendar-check text-primary me-2"></i>จัดการปฏิทินกิจกรรม</h4>
    <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fa-solid fa-plus me-1"></i> เพิ่มกิจกรรมใหม่
    </button>
</div>

<div class="card-custom p-4">
    <?php if (empty($events)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-calendar-times fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีข้อมูลกิจกรรมที่คุณบันทึกไว้ในปฏิทิน</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>วันที่ดำเนินกิจกรรม</th>
                        <th>เวลา</th>
                        <th>ชื่อกิจกรรม/กำหนดการ</th>
                        <th>ชมรมผู้จัด</th>
                        <th>สถานที่</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td class="fw-bold"><?= date('d/m/Y', strtotime($event['event_date'])) ?></td>
                            <td>
                                <?php if ($event['start_time']): ?>
                                    <?= date('H:i', strtotime($event['start_time'])) ?><?= $event['end_time'] ? ' - ' . date('H:i', strtotime($event['end_time'])) : '' ?> น.
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-primary-custom"><?= e($event['title']) ?></td>
                            <td>
                                <span class="badge <?= $event['club_id'] ? 'bg-info' : 'bg-primary' ?> text-white">
                                    <?= $event['club_id'] ? e($event['club_name']) : 'กิจกรรมกลาง' ?>
                                </span>
                            </td>
                            <td><?= e($event['location'] ?: '-') ?></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEventModal<?= $event['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไข
                                </button>
                                <a href="<?= url('events/delete/' . (int) $event['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('ยืนยันที่จะลบกิจกรรมนี้ออกจากปฏิทิน?')">
                                    <i class="fa-solid fa-trash me-1"></i>ลบ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'events/manage') ?>
    <?php endif; ?>
</div>

<!-- =========================================================
     Modals Section (Placed outside tables to prevent flickering)
     ========================================================= -->

<!-- Modal: Add Event -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เพิ่มกิจกรรมใหม่ในปฏิทิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('events/store') ?>">
                <div class="modal-body text-start">
                    <?php if ($role === 'admin'): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ผู้จัดกิจกรรม</label>
                            <select name="club_id" class="form-select">
                                <option value="">-- ส่วนกลาง (มหาวิทยาลัย) --</option>
                                <?php foreach ($clubsList as $c): ?>
                                    <option value="<?= (int)$c['id'] ?>"><?= e($c['club_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="club_id" value="<?= $clubId ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อกิจกรรม/กำหนดการ</label>
                        <input type="text" name="title" class="form-control" placeholder="เช่น อบรมบอร์ดไมโครคอนโทรลเลอร์ ESP32" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียดกิจกรรม</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="ระบุเนื้อหาเพิ่มเติม (ถ้ามี)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">วันที่จัดกิจกรรม</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">เวลาเริ่ม</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">เวลาสิ้นสุด</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">สถานที่</label>
                        <input type="text" name="location" class="form-control" placeholder="ระบุตึกและห้องจัดแสดงผลงาน">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">เพิ่มกิจกรรม</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals: Edit Event -->
<?php foreach ($events as $event): ?>
    <div class="modal fade" id="editEventModal<?= $event['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white">แก้ไขข้อมูลกิจกรรม</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('events/update') ?>">
                    <div class="modal-body text-start">
                        <input type="hidden" name="id" value="<?= $event['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อกิจกรรม/กำหนดการ</label>
                            <input type="text" name="title" class="form-control" value="<?= e($event['title']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">รายละเอียดกิจกรรม</label>
                            <textarea name="description" class="form-control" rows="4"><?= e($event['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">วันที่จัดกิจกรรม</label>
                            <input type="date" name="event_date" class="form-control" value="<?= e($event['event_date']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">เวลาเริ่ม</label>
                                <input type="time" name="start_time" class="form-control" value="<?= $event['start_time'] ? date('H:i', strtotime($event['start_time'])) : '' ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">เวลาสิ้นสุด</label>
                                <input type="time" name="end_time" class="form-control" value="<?= $event['end_time'] ? date('H:i', strtotime($event['end_time'])) : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">สถานที่</label>
                            <input type="text" name="location" class="form-control" value="<?= e($event['location']) ?>">
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
