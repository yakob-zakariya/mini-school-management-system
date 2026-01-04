# 🎓 Mini School Management System

A comprehensive school management system built with Laravel 11 and Filament 3, designed to manage students, teachers, grades, subjects, enrollments, and marks efficiently.

## 🌐 Live Demo

**🚀 Application URL:** [https://mini-school-management-system-main-iiudvb.laravel.cloud/admin](https://mini-school-management-system-main-iiudvb.laravel.cloud/admin)

### 👥 Demo Users

Try the application with these pre-configured demo accounts:

| Role        | Email                      | Password   | Access Level                               |
| ----------- | -------------------------- | ---------- | ------------------------------------------ |
| **Admin**   | `admin@school.com`         | `password` | Full system access - manage all resources  |
| **Teacher** | `yahya.teacher@school.com` | `password` | View and enter marks for assigned subjects |
| **Student** | `alice@school.com`         | `password` | View own marks (read-only)                 |

---

## ✨ Features

### 👨‍💼 Admin Features

-   ✅ **User Management** - Create and manage admins, teachers, and students
-   ✅ **Grade Management** - Create grades/classes with sections and assign class teachers
-   ✅ **Subject Management** - Create subjects and assign them to grades with teachers
-   ✅ **Enrollment Management** - Enroll students in subjects
-   ✅ **Marks Management** - View all marks across the system
-   ✅ **Role-based Access Control** - Using Spatie Laravel Permission

### 👨‍🏫 Teacher Features

-   ✅ **My Grades** - View only grades where they teach subjects
-   ✅ **Enter Marks** - Bulk entry interface for entering marks for their students
    -   Quiz marks (out of 10)
    -   Assignment marks (out of 20)
    -   Midterm marks (out of 20)
    -   Final marks (out of 50)
    -   Auto-calculated total, percentage, and grade letter
-   ✅ **View Marks** - View marks for subjects they teach

### 👨‍🎓 Student Features

-   ✅ **My Marks** - View their own marks for all enrolled subjects
-   ✅ **Read-only Access** - Cannot create, edit, or delete any data
-   ✅ **Grade Overview** - See quiz, assignment, midterm, final scores with total percentage and grade letter

---

## 🏗️ Database Schema

The database schema design is available in the root directory: [`database-schema-design.png`](./database-schema-design.png)

### Key Tables:

-   **users** - Stores all users (admins, teachers, students)
-   **grades** - Grade/class information with sections and academic year
-   **subjects** - Subject information
-   **grade_subject** - Pivot table linking grades and subjects with assigned teachers
-   **enrollments** - Student enrollments in subjects
-   **marks** - Student marks with quiz, assignment, midterm, final scores
-   **roles & permissions** - Spatie permission tables for role-based access control

---

## 🛠️ Technology Stack

-   **Framework:** Laravel 12
-   **Admin Panel:** Filament 4
-   **Authentication:** Laravel Shield
-   **Authorization:** Laravel Schiled
-

---

## 📦 Installation

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   MySQL or MariaDB
-   Node.js & NPM

### Steps

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd mini-school-management-system
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install NPM dependencies**

    ```bash
    npm install
    npm run build
    ```

4. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure database**

    Edit `.env` file with your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=school_management
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. **Run migrations and seeders**

    ```bash
    php artisan migrate:fresh --seed
    ```

7. **Start the development server**

    ```bash
    php artisan serve
    ```

8. **Access the application**

    Open your browser and navigate to: `http://localhost:8000/admin`

---

## 📊 Grading System

The system uses the following grading scale:

| Percentage | Grade Letter |
| ---------- | ------------ |
| 90% - 100% | A+           |
| 85% - 89%  | A            |
| 80% - 84%  | B+           |
| 75% - 79%  | B            |
| 70% - 74%  | C+           |
| 65% - 69%  | C            |
| 60% - 64%  | D            |
| Below 60%  | F            |

### Marks Distribution (Total: 100)

-   **Quiz:** 10 marks
-   **Assignment:** 20 marks
-   **Midterm Exam:** 20 marks
-   **Final Exam:** 50 marks

---

## 🔐 Security Features

-   ✅ Role-based access control (Admin, Teacher, Student)
-   ✅ Teachers can only view/edit marks for subjects they teach
-   ✅ Students can only view their own marks
-   ✅ Password hashing using Laravel's bcrypt
-   ✅ CSRF protection on all forms
-   ✅ SQL injection prevention through Eloquent ORM

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
