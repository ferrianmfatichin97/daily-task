<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

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
