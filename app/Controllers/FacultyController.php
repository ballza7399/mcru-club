<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Faculty;
use App\Models\Major;

class FacultyController extends Controller
{
    public function manage(): void
    {
        $this->requireRole('admin');
        
        $facultyModel = new Faculty;
        $majorModel = new Major;
        
        // Pagination for faculties
        $currentPageFac = isset($_GET['page_fac']) ? max(1, (int)$_GET['page_fac']) : 1;
        $limitFac = isset($_GET['limit_fac']) ? max(5, min(100, (int)$_GET['limit_fac'])) : 10;
        $offsetFac = ($currentPageFac - 1) * $limitFac;
        $totalFaculties = $facultyModel->countAll();
        $totalPagesFac = (int)ceil($totalFaculties / $limitFac);
        $faculties = $facultyModel->allPaginated($limitFac, $offsetFac);

        // Pagination for majors
        $currentPageMaj = isset($_GET['page_maj']) ? max(1, (int)$_GET['page_maj']) : 1;
        $limitMaj = isset($_GET['limit_maj']) ? max(5, min(100, (int)$_GET['limit_maj'])) : 10;
        $offsetMaj = ($currentPageMaj - 1) * $limitMaj;
        $totalMajors = $majorModel->countAll();
        $totalPagesMaj = (int)ceil($totalMajors / $limitMaj);
        $majors = $majorModel->allPaginated($limitMaj, $offsetMaj);

        // ดึงคณะทั้งหมดแบบไม่แบ่งหน้า เพื่อใช้แสดงผลใน drop-down เมนูใน modal
        $allFacultiesList = $facultyModel->all();
        
        $this->view('faculties/manage', [
            'faculties' => $faculties,
            'majors' => $majors,
            'allFacultiesList' => $allFacultiesList,
            'currentPageFac' => $currentPageFac,
            'totalPagesFac' => $totalPagesFac,
            'currentPageMaj' => $currentPageMaj,
            'totalPagesMaj' => $totalPagesMaj,
            'limitFac' => $limitFac,
            'limitMaj' => $limitMaj
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin');
        
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $this->flash('กรุณากรอกชื่อคณะ');
            $this->redirect('/backoffice/faculties');
        }
        
        $ok = (new Faculty)->create($name);
        if ($ok) {
            $this->flash('เพิ่มคณะใหม่สำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาด หรือมีชื่อคณะนี้ในระบบแล้ว');
        }
        $this->redirect('/backoffice/faculties');
    }

    public function update(): void
    {
        $this->requireRole('admin');
        
        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $this->flash('กรุณากรอกชื่อคณะ');
            $this->redirect('/backoffice/faculties');
        }
        
        $ok = (new Faculty)->update($id, $name);
        if ($ok) {
            $this->flash('แก้ไขข้อมูลคณะสำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการแก้ไขข้อมูล');
        }
        $this->redirect('/backoffice/faculties');
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin');
        
        $facId = (int)$id;
        $ok = (new Faculty)->delete($facId);
        if ($ok) {
            $this->flash('ลบคณะและสาขาวิชาในสังกัดเรียบร้อยแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการลบคณะ');
        }
        $this->redirect('/backoffice/faculties');
    }

    public function storeMajor(): void
    {
        $this->requireRole('admin');
        
        $facultyId = (int)($_POST['faculty_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        
        if ($facultyId === 0 || $name === '') {
            $this->flash('กรุณากรอกข้อมูลให้ครบถ้วน');
            $this->redirect('/backoffice/faculties');
        }
        
        $ok = (new Major)->create($facultyId, $name);
        if ($ok) {
            $this->flash('เพิ่มสาขาวิชาใหม่สำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาด หรือมีชื่อสาขาวิชานี้ในคณะแล้ว');
        }
        $this->redirect('/backoffice/faculties');
    }

    public function updateMajor(): void
    {
        $this->requireRole('admin');
        
        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        
        if ($name === '') {
            $this->flash('กรุณากรอกชื่อสาขาวิชา');
            $this->redirect('/backoffice/faculties');
        }
        
        $ok = (new Major)->update($id, $name);
        if ($ok) {
            $this->flash('แก้ไขชื่อสาขาวิชาสำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการแก้ไขสาขาวิชา');
        }
        $this->redirect('/backoffice/faculties');
    }

    public function deleteMajor(string $id): void
    {
        $this->requireRole('admin');
        
        $majorId = (int)$id;
        $ok = (new Major)->delete($majorId);
        if ($ok) {
            $this->flash('ลบสาขาวิชาออกจากคณะเรียบร้อยแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการลบสาขาวิชา');
        }
        $this->redirect('/backoffice/faculties');
    }
}
