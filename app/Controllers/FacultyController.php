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
        
        $faculties = $facultyModel->all();
        $majors = $majorModel->all();
        
        $this->view('faculties/manage', [
            'faculties' => $faculties,
            'majors' => $majors
        ]);
    }

    public function store(): void
    {
        $this->requireRole('admin');
        
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $this->flash('กรุณากรอกชื่อคณะ');
            $this->redirect('/faculties/manage');
        }
        
        $ok = (new Faculty)->create($name);
        if ($ok) {
            $this->flash('เพิ่มคณะใหม่สำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาด หรือมีชื่อคณะนี้ในระบบแล้ว');
        }
        $this->redirect('/faculties/manage');
    }

    public function update(): void
    {
        $this->requireRole('admin');
        
        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $this->flash('กรุณากรอกชื่อคณะ');
            $this->redirect('/faculties/manage');
        }
        
        $ok = (new Faculty)->update($id, $name);
        if ($ok) {
            $this->flash('แก้ไขข้อมูลคณะสำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการแก้ไขข้อมูล');
        }
        $this->redirect('/faculties/manage');
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
        $this->redirect('/faculties/manage');
    }

    public function storeMajor(): void
    {
        $this->requireRole('admin');
        
        $facultyId = (int)($_POST['faculty_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        
        if ($facultyId === 0 || $name === '') {
            $this->flash('กรุณากรอกข้อมูลให้ครบถ้วน');
            $this->redirect('/faculties/manage');
        }
        
        $ok = (new Major)->create($facultyId, $name);
        if ($ok) {
            $this->flash('เพิ่มสาขาวิชาใหม่สำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาด หรือมีชื่อสาขาวิชานี้ในคณะแล้ว');
        }
        $this->redirect('/faculties/manage');
    }

    public function updateMajor(): void
    {
        $this->requireRole('admin');
        
        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        
        if ($name === '') {
            $this->flash('กรุณากรอกชื่อสาขาวิชา');
            $this->redirect('/faculties/manage');
        }
        
        $ok = (new Major)->update($id, $name);
        if ($ok) {
            $this->flash('แก้ไขชื่อสาขาวิชาสำเร็จแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการแก้ไขสาขาวิชา');
        }
        $this->redirect('/faculties/manage');
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
        $this->redirect('/faculties/manage');
    }
}
