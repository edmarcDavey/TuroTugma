# TuroTugma — Project Specification

**Project Name:**
TuroTugma – Automated Scheduling System for Teaching Faculty Subject Load Management

**Goal:**
Develop a web-based system that automates the generation and management of teaching schedules for Diadi National High School. The system should intelligently assign teachers to subjects and sections based on their profiles, availability, and role-based constraints. It must also allow real-time monitoring of teacher room assignments and provide an analytics dashboard for workload visualization.

---

## Key Users

- **IT Coordinator** – manages teacher profiles, subjects, grade levels, sections, scheduling logic, and academic configurations.
- **Scheduler (Head Teacher)** – generates and adjusts schedules (automatic or manual), validates conflicts, and finalizes the timetable.
- **Public Viewer (Teachers/Students)** – can view finalized schedules and real-time current room assignments.

---

## Core Functionalities

1. **Teacher Profiling:** Admin adds teachers, assigns subjects, grade levels, and defines available/non-teaching periods.
2. **Data Setup:** IT Coordinator sets grade levels, section names (auto-generator), subjects (Core, TLE, SPA, SPJ, Senior High Tracks), and rooms/buildings.
3. **Scheduling Logic:** System supports regular (50 min) and shortened (40 min) periods with breaks and lunch hours.
4. **Automated Schedule Generation:** Uses AI optimization (Google OR-Tools or Genetic Algorithm) to assign teachers to subjects and time slots without conflicts.
5. **Manual Editing Mode:** Scheduler can manually modify generated schedules; conflicts appear visually (highlighted or flagged).
6. **Final Timetable Export:** Downloadable as PDF, Excel, or CSV.
7. **Current Room Assignment View:** Displays where each teacher is currently teaching or if on leave, meeting, or vacant.
8. **Analytics Dashboard:** Shows workload summary, room utilization, subject load balance, and conflict reports.
9. **Role-Based Access:** IT Coordinator, Scheduler, and Public Viewer roles with separate permissions.

---

## Emerging Technologies & Features

- **AI Optimization Engine** using *Google OR-Tools* or *Genetic Algorithm* for automated scheduling.
- **Real-Time Updates** using *Laravel WebSockets / Pusher.js*.
- **Intelligent Analytics Dashboard** using *Recharts* or *Chart.js* for visual workload analysis.

---

## Tech Stack and Tools (proposed)

- **Frontend:** React.js + Tailwind CSS
- **Backend:** Laravel (PHP 10+)
- **AI Engine:** Python (Flask/FastAPI) + Google OR-Tools or DEAP
- **Database:** MySQL / MariaDB
- **Real-Time Communication:** Laravel WebSockets or Pusher.js
- **Version Control:** GitHub
- **Deployment:** Laravel Forge + Vercel / DigitalOcean

---

## Main Tasks for Copilot (scaffold / initial work)

- Generate boilerplate setup for Laravel + React integrated project.
- Scaffold Python microservice for AI schedule generation.
- Suggest folder structure and communication between Laravel backend and Python API.
- Create models for Teachers, Subjects, Sections, Schedule, and Constraints.
- Help generate UI components (login, dashboard, timetable view, analytics).

---

## Project Vision

TuroTugma aims to reduce human error, save administrative time, and promote efficient teaching load management through AI-driven automation and data transparency — supporting **UN SDG 4.c: Improving teacher working conditions through effective management systems**.

---

## Notes / Next Steps (awaiting sponsor)

- Do not start coding until spec is approved and priorities are set.
- Decide MVP scope and whether to start with a Laravel-only UI or Laravel + React scaffold.
- Choose backend storage (MySQL/MariaDB) and whether to start with Option A (simple role column) or Option B (proper roles table and id_number column) for authentication.

