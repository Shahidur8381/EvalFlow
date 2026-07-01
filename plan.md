\# 🚀 EvalFlow Simplified Development Roadmap

\*\*Duration:\*\* 12–14 Weeks (MVP Focus)

\## 🎯 Core Objectives

Build a streamlined platform for uploading, evaluating, and managing answer scripts with a basic annotation engine and doubt-solving system. We are focusing on core functionality to ensure a working product by the end of the semester.

\## 👥 Key Roles

1\. \*\*Admin\*\*: Manages users, courses, exams, and assigns scripts.

2\. \*\*Evaluator\*\*: Checks scripts, adds annotations, and assigns marks.

3\. \*\*Student\*\*: Views evaluated scripts, sees marks, and asks doubts.

4\. \*\*Doubt Solver\*\*: Resolves student tickets.

\## 📦 Simplified Database Tables

\- `users` (id, name, email, role, password)

\- `courses` (id, name, code)

\- `exams` (id, course\_id, title)

\- `scripts` (id, exam\_id, student\_id, file\_path, status, total\_marks)

\- `annotations` (id, script\_id, evaluator\_id, data\_json)

\- `doubt\_tickets` (id, script\_id, student\_id, solver\_id, message, status)

\## 🚀 Sprints (MVP Scope)

\### Sprint 1: Foundation (Weeks 1–3)

\*\*Goals:\*\* Project setup, Authentication, Dashboards

\- \*\*Tech Stack:\*\* Laravel, React, Vite, TailwindCSS (Monolith via Inertia or standard API).

\- \*\*Features:\*\* 

&#x20; - Login / Register.

&#x20; - Role-based basic dashboards (Admin, Evaluator, Student).

\### Sprint 2: Core Data \& Uploads (Weeks 4–6)

\*\*Goals:\*\* Manage courses, exams, and scripts.

\- \*\*Features:\*\*

&#x20; - Admin can create courses and exams.

&#x20; - Upload student answer scripts (PDFs).

&#x20; - Basic PDF viewer integrated into the frontend.

\### Sprint 3: Evaluation Engine (Weeks 7–9)

\*\*Goals:\*\* The core grading workflow.

\- \*\*Features:\*\*

&#x20; - Assign scripts to evaluators.

&#x20; - Basic Annotation Tools (Highlight, Text, Comments).

&#x20; - Save annotations and calculate total marks.

&#x20; - Submit final evaluation.

\### Sprint 4: Doubt Solving \& Search (Weeks 10–12)

\*\*Goals:\*\* Communication and finding data.

\- \*\*Features:\*\*

&#x20; - Students can raise a doubt ticket on an evaluated script.

&#x20; - Doubt solver can reply and resolve tickets.

&#x20; - Basic search/filtering for scripts and students.

\### Sprint 5: Polish \& Launch (Weeks 13–14)

\*\*Goals:\*\* Finalizing the product.

\- \*\*Features:\*\*

&#x20; - UI refinement and responsive design.

&#x20; - Basic reports (Student Results, Pending Scripts).

&#x20; - Final testing and Deployment (e.g., Vercel + Railway/Render).

\---

\*Note: This plan has been simplified from the original to remove complex features (like advanced analytics, 10+ annotation tools, deep rubrics, and multiple review layers) to ensure the project remains achievable and functional within the timeframe.\*



