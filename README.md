<p align="center"><a href="https://bankdptaspen.co.id/" target="_blank"><img src="https://bankdptaspen.co.id/wp-content/uploads/2024/01/Logo-Bank-DP-Taspen-Version-New.png" width="400" alt="Bank BPR DP Taspen Logo"></a></p>

# 📋 Task Management System

Aplikasi **Manajemen Tugas Harian** berbasis **Laravel 11 + FilamentPHP + Laravel Shield**.  
Mendukung multi-role (Super Admin, Manager, Employee) dengan pencatatan riwayat perubahan (history) seperti status, komentar, dan due date.  

---

## 🚀 Features
- **Authentication & Authorization**
  - Super Admin dengan akses penuh.
  - Manager dengan akses terbatas.
  - Employee hanya bisa melihat & mengelola tugas miliknya.
- **Task Management**
  - CRUD Tugas harian (To Do, In Progress, Done).
  - Assign task ke user tertentu.
  - Riwayat perubahan otomatis (status, komentar, due date).
- **Comments & History Tracking**
  - Komentar per task.
  - Timeline riwayat perubahan mirip histori belanja online.
- **Role & Permission**
  - Dibangun menggunakan [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction) + [Laravel Shield](https://github.com/bezhanSalleh/filament-shield).
  - Permission otomatis digenerate per resource.
- **Filament Admin Panel**
  - UI modern, cepat, dan mudah digunakan.
  - Filter, search, badge status dengan warna berbeda.

---

## 🛠 Installation

Clone project:
```bash
git clone https://github.com/username/task-management.git
cd task-management
