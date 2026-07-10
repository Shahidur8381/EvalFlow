# Financial Ledger Implementation Plan

## Goal Description
Implement a financial ecosystem across all roles:
1. **Students** can top up their credit via SSLCommerz API (Sandbox) and pay to participate in exams (credit cost = total marks).
2. **Evaluators** earn 0.75 TK per mark evaluated. They can request withdrawals (min 100 TK) to bKash/Nagad/DBBL.
3. **Admins** have a financial dashboard to track system revenue and approve pending withdrawal requests.

> [!IMPORTANT]
> **User Review Required**: Please review the database schema changes and the SSLCommerz mock integration details to ensure they meet your requirements.

## Open Questions
- Do you want us to use a specific Laravel SSLCommerz package, or implement the raw HTTP API requests directly (which is often cleaner and easier to customize)? *Our plan assumes implementing the raw HTTP API (V4).*
- When an admin "clears" a payment request, does the admin actually transfer money manually outside the system and just mark it as "Approved" here? *Our plan assumes yes, it simply updates the status.*
- How do we handle evaluator earnings for fractional marks (e.g., 5.5 marks)? *Our plan will use decimal column types and multiply by 0.75, storing decimal earnings.*

## Proposed Changes

### Database Changes
#### [NEW] `balance` column in `users`
- Add `balance` (decimal 10, 2, default 0.00) to `users` table.

#### [NEW] `transactions` table
- `user_id`
- `type` (enum: 'deposit', 'exam_fee', 'earning', 'withdrawal')
- `amount` (decimal 10,2)
- `trx_id` (string, unique for SSLCommerz)
- `status` (enum: 'pending', 'completed', 'failed')
- `description` (string)

#### [NEW] `withdrawals` table
- `user_id` (Evaluators only)
- `amount` (decimal 10,2)
- `method` (enum: 'bkash', 'nagad', 'dbbl')
- `account_number` (string)
- `status` (enum: 'pending', 'approved', 'rejected')

---

### Student Subsystem
#### [NEW] `StudentFinanceController.php`
- `deposit()`: Shows a form to input amount.
- `initiatePayment()`: Calls SSLCommerz API to get gateway URL and redirects.
- `paymentSuccess/Fail/Cancel()`: Callback routes for SSLCommerz to verify transaction and credit the user's balance.

#### [MODIFY] `StudentExamController.php`
- Add middleware or logic to `showExam` or `upload` to verify if the student has already paid the fee for this exam, or deducts it upon starting.
- *Wait, when is the fee deducted?* The best approach: Deduct fee when the student uploads their script (or explicitly registers for the exam). I will deduct the fee right when they upload the script to keep it simple, checking if `balance >= exam->total_marks`. If not, block upload.

---

### Evaluator Subsystem
#### [NEW] `EvaluatorFinanceController.php`
- `index()`: Financial dashboard showing total earned, current balance, and withdrawal history.
- `withdraw()`: Form submission to request withdrawal. Checks `if (balance >= 100)`.

#### [NEW] `EnsureMinimumWithdrawal` Middleware (as requested)
- Middleware that checks if `request()->amount >= 100`.

#### [MODIFY] `EvaluatorScriptController.php`
- In `storeMarks()`, when an exam script changes status to `evaluated`, calculate `marks_obtained * 0.75` and add it to the evaluator's balance. (Only do this once per script to avoid infinite earning).

---

### Admin Subsystem
#### [NEW] `AdminFinanceController.php`
- `index()`: Dashboard showing total system revenue (Deposits - Earnings), and a table of pending withdrawals.
- `approveWithdrawal()`: Mark withdrawal as approved.

---

## Verification Plan

### Automated Tests
- Test middleware blocks withdrawals < 100 TK.

### Manual Verification
1. Log in as Student, try to submit exam without balance (Should fail).
2. Deposit 500 TK via SSLCommerz sandbox (Will use mock/sandbox credentials).
3. Submit exam (Balance should decrease by exam total marks).
4. Log in as Evaluator, grade script. Balance should increase by (Marks * 0.75).
5. Request withdrawal for 150 TK (Should succeed). Request 50 TK (Should fail middleware).
6. Log in as Admin, see withdrawal request, approve it.
