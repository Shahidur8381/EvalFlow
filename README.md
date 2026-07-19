# EvalFlow — Modern Examination Evaluation Platform

EvalFlow is a centralized, role-based platform designed to streamline online examination workflows. It connects Administrators, Students, and Evaluators to automate course creation, question paper design, timed submissions, per-question grading, and a transparent financial ledger with SSLCommerz payments.

## Features

### 👨‍💼 Administrator
- **Course & Exam Management:** Create courses and exams with strict time windows.
- **Question Bank:** Add questions with specified marks and optional PDF attachments.
- **User Management:** Oversee all users, create evaluator accounts, and manage role assignments.
- **Finance Dashboard:** Monitor platform revenue and approve evaluator payout requests.

### 🎓 Student
- **Digital Wallet:** Deposit funds securely using **SSLCommerz** (bKash, Nagad, Cards).
- **Timed Exams:** Access exam papers strictly within the allowed time window.
- **Script Submission:** Upload PDF answer scripts (auto-deducts exam fees from wallet).
- **Transparent Results:** View detailed per-question marks and evaluation feedback.

### 📝 Evaluator
- **Assigned Grading:** Access only exams assigned by the administrator.
- **Split-Screen Grading:** View student PDF scripts side-by-side with the grading form.
- **Earnings:** Automatically earn a percentage of the exam fee (৳0.75 per mark) upon grading.
- **Withdrawals:** Request payouts to mobile banking accounts (minimum ৳100).

## Technology Stack

- **Backend:** Laravel 11 (PHP 8.3)
- **Database:** MySQL 8.x
- **Frontend:** Blade Templates, Vanilla CSS
- **Authentication:** Laravel Breeze
- **Payment Gateway:** SSLCommerz Sandbox
- **PDF Viewer:** Browser Native `<iframe>`

## Database Architecture

EvalFlow uses a relational database with 8 core tables:
- `users`: Stores all roles and digital wallet balances.
- `courses` & `exams`: Hierarchical structure for academic assessments.
- `questions`: Individual questions mapped to exams.
- `scripts`: Student PDF submissions.
- `script_marks`: Per-question evaluation data (`UNIQUE` constraint per script/question).
- `transactions`: Immutable financial ledger for deposits, fees, and earnings.
- `withdrawals`: Evaluator payout requests pending admin approval.

## Requirements
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd EvalFlow
   ```
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```
3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your database credentials and SSLCommerz API keys:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=evalflow
   DB_USERNAME=root
   DB_PASSWORD=

   STORE_ID=your_sandbox_store_id
   STORE_PASSWORD=your_sandbox_store_password
   ```
4. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```
5. **Start the Development Server:**
   ```bash
   php artisan serve
   ```

## Documentation

A comprehensive **Project Report** containing detailed flowcharts, ER diagrams, security analysis, and route architecture is available in the `/REPORTS` directory (`EvalFlow_Project_Report.html`).
