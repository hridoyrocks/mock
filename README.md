# IELTS Mock Test Platform

A computer-based IELTS mock test platform that allows students to take individual section tests (Listening, Reading, Writing, Speaking) with a real IELTS-like interface.

## Developer 

ROCKS ?

### Section-wise Mock Test System

- Students can choose to take tests for individual sections
- Each section has its own timer and specific features
- Results are tracked separately

### Sections

- **🎧 Listening Section**: One-time audio playback, timer, multiple-choice questions
- **📖 Reading Section**: Scrollable passages, questions on the right side, timer
- **✍️ Writing Section**: Task 1 and Task 2 with auto-save and auto-submit
- **🎤 Speaking Section**: Cue card questions, recording or file upload options

### Admin Panel

- CRUD operations for test sections, test sets, and questions
- View and evaluate student attempts
- Set band scores for Writing and Speaking sections

## Technical Stack

- **Frontend**: Blade templates with Tailwind CSS
- **Backend**: Laravel 12
- **Authentication**: Laravel Fortify
- **Database**: MySQL
- **File Storage**: Local server for audio/images

## Installation

1. Clone the repository
   ```bash
   git clone https://your-repository.git
   cd ielts-mock-platform