# TuroTugma Scheduler Design

## Overview

The scheduler generates timetables (schedules) that assign teachers to sections for specific subjects during specific time periods. It must handle two different educational levels (Junior High and Senior High) with distinct curricula, manage multiple schedule views, and automatically resolve conflicts while maintaining academic and administrative constraints.

---

## 1. Curriculum Structure

### Junior High School (Grades 7-10)

**Characteristics:**

- Grades 7 through 10 have **identical subjects** across all sections
- All regular sections: **8 core subjects**, each 1 period
- Special sections: **9 subjects** (8 core + 1 additional/specialized subject)
- Subject distribution is uniform across all JH sections
- Periods vary by session type:
  - Regular session: Full periods
  - Shortened session: Reduced periods

**Standard JH Subjects (Core):**

- English
- Filipino
- Mathematics
- Science
- Social Studies
- Physical Education
- Values Education
- Technology & Livelihood Education (TLE)

**Special Section Examples:**

- Art section: adds Art as 9th subject
- Music section: adds Music as 9th subject

---

### Senior High School (Grades 11-12)

**Characteristics:**

- Organized by **5 Strands** (curriculum tracks):
  1. STEM (Science, Technology, Engineering, Mathematics)
  2. ABM (Accountancy, Business, Management)
  3. HUMSS (Humanities & Social Sciences)
  4. ICT (Information & Communications Technology)
  5. Home Economics / Technical-Vocational (varies per school)

- Subjects differ by:
  - **Strand** (STEM subjects ≠ ABM subjects)
  - **Grade level** (Grade 11 subjects ≠ Grade 12 subjects, even within same strand)

- Some **core subjects shared** across all strands (English, Filipino, Math, Science, Social Studies)
- Many **strand-specific subjects** unique to each track

**Example Distribution:**

- STEM Grade 11: Physics, Chemistry, Biology, Advanced Math, Computer Science, + Core subjects
- STEM Grade 12: Engineering, Advanced Physics, Research, + Core subjects
- ABM Grade 11: Accounting, Business Math, Economics, + Core subjects
- ABM Grade 12: Advanced Accounting, Business Law, Entrepreneurship, + Core subjects

**Session Variations:**

- Regular session: Full course load
- Shortened session: Reduced course load per strand/grade

---

## 2. Schedule Views (Three Perspectives)

### View 1: Master Schedule (Grid View)

**Purpose:** Overview of entire scheduling across all sections

**Layout:**

```text
┌──────────────────┬─────────────────┬─────────────────┬─────────────────┐
│ Section          │ Period 1 (8:00) │ Period 2 (8:45) │ Period 3 (9:30) │ ...
├──────────────────┼─────────────────┼─────────────────┼─────────────────┤
│ Grade 7 - Rizal  │ Mr. Cruz/Math   │ Ms. Santos/Eng  │ Mr. Reyes/Sci   │ ...
│ Grade 7 - Bonifacio│ Ms. Garcia/Math│ Mr. Lopez/Eng   │ Ms. Ramos/Sci   │ ...
│ Grade 8 - Aguinaldo│ Mr. Fernandez..│ Ms. Navarro..   │ Mr. Gonzales..  │ ...
│ ...              │ ...             │ ...             │ ...             │
└──────────────────┴─────────────────┴─────────────────┴─────────────────┘
```

**Cell Content:** Teacher Name / Subject

**Filters/Controls:**

- Filter by section (JH/SH section select)
- Filter by grade level
- View by session type (regular/shortened)
- Export as PDF/image

---

### View 2: Section/Class Schedule

**Purpose:** Detailed view for a single class/section

**Layout:**

```text
Section: Grade 7 - Rizal
╔═════════════════════════════════════════════════════════════════════════════╗
║ DAY / PERIOD │ MON        │ TUE        │ WED        │ THU        │ FRI      ║
╠═════════════════════════════════════════════════════════════════════════════╣
║ Period 1     │ Math       │ Math       │ Math       │ Math       │ Math     ║
║ (8:00-8:45)  │ Mr. Cruz   │ Mr. Cruz   │ Mr. Cruz   │ Mr. Cruz   │ Mr. Cruz ║
║              │ [Adviser]  │            │            │            │          ║
╠═════════════════════════════════════════════════════════════════════════════╣
║ Period 2     │ English    │ English    │ English    │ English    │ English  ║
║ (8:45-9:30)  │ Ms. Santos │ Ms. Santos │ Ms. Santos │ Ms. Santos │ Ms. Santo║
╠═════════════════════════════════════════════════════════════════════════════╣
║ Period 3     │ Science    │ Science    │ Science    │ Science    │ Science  ║
║ (9:30-10:15) │ Mr. Reyes  │ Mr. Reyes  │ Mr. Reyes  │ Mr. Reyes  │ Mr. Reyes║
║ ...          │ ...        │ ...        │ ...        │ ...        │ ...      ║
╚═════════════════════════════════════════════════════════════════════════════╝
```

**Features:**

- Day-by-day breakdown (Monday-Friday, or filtered days)
- Shows: Period number, Subject, Teacher name, and "Adviser" tag for Period 1
- Filters:
  - Select section (dropdown)
  - Select days of week
  - View by session type

---

### View 3: Teacher Schedule

**Purpose:** Personal timetable for a single teacher

**Layout:**

```text
Teacher: Mr. Cruz
╔═════════════════════════════════════════════════════════════════════════════╗
║ PERIOD │ MON              │ TUE              │ WED              │ THU      ║
╠═════════════════════════════════════════════════════════════════════════════╣
║ P1     │ Grade 7 - Rizal  │ Grade 7 - Rizal  │ Grade 7 - Rizal  │ Grade 7 -║
║        │ Math [Adviser]   │ Math             │ Math             │ Rizal    ║
╠═════════════════════════════════════════════════════════════════════════════╣
║ P2     │ Grade 8 - Aguinal│ Grade 8 - Aguinal│ Grade 8 - Aguinal│ Grade 8 -║
║        │ Math             │ Math             │ Math             │ Aguinaldo║
╠═════════════════════════════════════════════════════════════════════════════╣
║ P3     │ [Free]           │ Grade 9 - Mabini │ Grade 9 - Mabini │ [Free]   ║
║        │                  │ Math             │ Math             │          ║
║ ...    │ ...              │ ...              │ ...              │ ...      ║
╚═════════════════════════════════════════════════════════════════════════════╝

Summary:
- Total Load: 18 hours/week (out of 24 max)
- Classes: 6 sections, all Math
- Free Periods: 2 (P3 Mon, P3 Thu)
- Advisory Role: Grade 7 - Rizal
```

**Features:**

- Show all assignments for the teacher
- Days of week selectable (Mon-Fri or subset)
- Summary statistics: total load, classes count, free periods, adviser role
- Highlight free periods (green), overloaded periods (red)
- Filters:
  - Select teacher
  - Select days of week
  - View by session type

---

## 3. Business Rules

### Assignment Rules

1. **Each Period 1 Teacher = Class Adviser**
   - The teacher assigned to Period 1 in a section automatically becomes that section's class adviser
   - Advisory field in teacher profile is populated based on Period 1 assignment

2. **No Concurrent Classes**
   - A teacher cannot teach two different sections in the same period
   - Scheduler must check for time conflicts during assignment

3. **Subject Expertise Match**
   - Teacher must have the subject in their "subjects[]" array
   - Teacher must have the grade level in their "grade_levels[]" array
   - Teacher qualification validation before assignment

4. **Workload Balance**
   - Respect `max_load_per_week` and `max_load_per_day` limits
   - Do not exceed teacher's availability constraints
   - Flag overloaded assignments in conflict report

5. **Unavailable Periods Respected**
   - Skip teachers with marked unavailable periods during assignment
   - Advisory field acts as a "preferred" constraint (not hard constraint)

6. **Curriculum Alignment**
   - JH sections must have exactly 8 (regular) or 9 (special) subjects assigned
   - SH sections must match strand-specific curriculum
   - Subject periods must align with session type

---

## 4. Data Model

### Key Entities & Relationships

#### Section

```text
id, name, grade_level_id, strand_id (for SH), session_type, year
├── has many: SubjectAllocation (section → subject → period mapping)
├── has many: ScheduleEntry (assigned teachers)
└── belongs to: GradeLevel, Strand (for SH)
```

#### SubjectAllocation

```text
id, section_id, subject_id, period_number, session_type, required
- Maps which subjects are assigned to which periods for a section
- Example: Grade 7-Rizal, Math, Period 1, Required=true
```

#### ScheduleEntry

```text
id, scheduling_run_id, section_id, subject_id, period_number,
teacher_id, day_of_week, session_type, status (assigned/conflict/unassigned)
├── belongs to: Teacher, Section, Subject, SchedulingRun
└── tracks conflicts, creation timestamp
```

#### SchedulingRun

```text
id, name, session_type, status (draft/generated/published/archived),
created_at, published_at, notes
├── has many: ScheduleEntry (all entries for this run)
├── has many: Conflicts (detected conflicts)
└── tracks generation progress/metadata
```

#### Teacher

```text
id, staff_id, name, designation, advisory (section_id or null),
max_load_per_week, max_load_per_day, availability[], ...
├── has many: subjects[], gradeLevels[]
└── advisory field = the section this teacher advises (if Period 1 assigned)
```

#### GradeLevel

```text
id, name (e.g., "Grade 7", "Grade 11"), year, level_type (JH/SH)
├── has many: Section
└── has many: Subject (curriculum mapping)
```

#### Strand (for SH)

```text
id, name (STEM, ABM, HUMSS, ICT, HE/Tech-Voc), code
├── has many: Subject (strand-specific subjects)
└── has many: Section
```

#### Subject

```text
id, name, code, grade_level_id (or null for core subjects),
strand_id (or null for shared subjects), hours_per_week
├── has many: SubjectAllocation
└── belongs to: GradeLevel, Strand (optional)
```

---

## 5. Scheduling Workflow

### Phase 1: Setup (Section Configuration)

**User creates/edits a section:**

1. Select section name, grade level, strand (if SH)
2. Select session type (regular/shortened)
3. System auto-populates subject list based on:
   - If JH: standard 8 (regular) or 9 (special) subjects
   - If SH: strand + grade level specific subjects
4. Assign periods to each subject (Period 1-9)
5. Mark Period 1 teacher as adviser once assigned
6. Save SubjectAllocation records

---

### Phase 2: Auto-Assignment (Scheduling Run)

**User initiates new scheduling run:**

1. Select session type and sections to schedule
2. System creates SchedulingRun (status=draft)
3. For each section:
   - For each subject slot (subject + period):
     - **Find candidate teachers:**
       - Has subject expertise
       - Has grade level
       - Respect availability constraints
       - Available in that period (no conflicts)
     - **Select best teacher:**
       - Prefer lowest current workload
       - Prefer adviser match (if Period 1)
       - Check workload limits
     - **Assign teacher** → create ScheduleEntry
     - **Track conflicts** if any issues found
4. Generate conflict report:
   - Unassigned slots
   - Teacher overloads
   - Period conflicts
   - Missing advisers
5. Status updates to "generated"

---

### Phase 3: Conflict Resolution

**User reviews conflicts:**

1. View conflict report (dashboard already shows this)
2. For each conflict:
   - Manual override: assign different teacher
   - Suggest alternatives: show available teachers
   - Adjust workload: swap teachers between sections
3. Regenerate conflict check
4. Once satisfied, proceed to publishing

---

### Phase 4: Publishing

**User publishes a schedule:**

1. Final validation:
   - All slots filled
   - No conflicts remaining (or acknowledged)
   - Advisers assigned (Period 1 teachers)
2. Mark SchedulingRun status = "published"
3. Populate Teacher.advisory field from Period 1 assignments
4. Archive previous runs (status = "archived")
5. Generate exports:
   - Master schedule PDF
   - Section schedules
   - Teacher schedules

---

## 6. Implementation Priority

### Phase 1: Foundation (Week 1)

- [ ] Create/edit Section interface with subject allocation
- [ ] Build SubjectAllocation model and relationships
- [ ] Create view: Master Schedule (read-only grid)

### Phase 2: Auto-Assignment (Week 2)

- [ ] Build SchedulingRun interface
- [ ] Implement auto-assignment algorithm
- [ ] Generate ScheduleEntry records
- [ ] Build Conflict Report view

### Phase 3: Schedule Views (Week 3)

- [ ] Section/Class Schedule view
- [ ] Teacher Schedule view
- [ ] Multi-perspective filtering

### Phase 4: Refinements (Week 4)

- [ ] Manual conflict resolution UI
- [ ] Publishing workflow
- [ ] Export to PDF/print
- [ ] Archive management

---

## 7. Technical Notes

### Database Indexes

- `schedules_entries.teacher_id` (filter by teacher)
- `schedules_entries.section_id` (filter by section)
- `schedules_entries.scheduling_run_id` (filter by run)
- `section_subject_allocation.section_id` (setup view)
- `teacher.advisory` (find adviser)

### Caching

- Cache subject lists by grade level/strand (rarely changes)
- Cache teacher workload during run (invalidate after each assignment)
- Cache conflict counts for dashboard

### API Endpoints Needed

```text
GET  /admin/scheduling/runs                    (list all runs)
POST /admin/scheduling/runs                    (create new run)
GET  /admin/scheduling/runs/{id}               (view run details)
GET  /admin/scheduling/runs/{id}/start         (trigger auto-assign)
GET  /admin/sections/{id}/subjects             (edit section subjects)
PUT  /admin/sections/{id}/subjects             (save subject allocation)
GET  /admin/schedules/master                   (view master schedule)
GET  /admin/schedules/section/{id}             (view section schedule)
GET  /admin/schedules/teacher/{id}             (view teacher schedule)
POST /admin/schedules/conflicts/resolve        (manual override)
POST /admin/scheduling/runs/{id}/publish       (publish run)
```

---

## 8. UI Components Needed

- **Section Setup Modal:** Add/edit subjects per section
- **Scheduling Run Manager:** List, create, generate, publish
- **Master Schedule Grid:** Responsive table with filters
- **Schedule Viewers:** Tab interface for 3 views
- **Conflict Resolution Modal:** Review and fix issues
- **Conflict Legend:** Color-coded severity indicators
- **Export Dialog:** Download schedule as PDF/CSV

---

## 9. Edge Cases & Considerations

1. **Teacher Reassignment:** If adviser changes, update Teacher.advisory
2. **Last-minute Changes:** Can reschedule within same run (draft mode only)
3. **Multiple Sessions:** Handle regular vs shortened seamlessly
4. **Strand Transfers:** SH students changing strands need schedule update
5. **Substitute Teachers:** Track temporary replacements separately
6. **Lunch/Break Periods:** May need to block certain periods across all sections
7. **Subject Sharing:** One teacher multiple sections same subject (handled by entry count)

---

## Summary

The scheduler is a **curriculum-aware, multi-perspective timetable generator** that:

- Understands JH uniformity vs SH strand diversity
- Automatically assigns teachers while respecting constraints
- Provides three complementary schedule views
- Links Period 1 assignments to advisory roles
- Supports conflict detection and manual resolution
- Generates exportable schedules for stakeholders

**Next step:** Begin with Phase 1 (Section Setup interface).
