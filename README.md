AI-Community Complaint Management System

A Web-Based Platform for Accessible and Efficient Community Complaint Management

Developed by: Jan Marinelle Auditor

Live Website: https://ai-community-complaints-management.vercel.app/

About the Project

The AI-Community Complaint Management System is a web-based application designed to modernize the reporting, management, and monitoring of community complaints in Barangay North Poblacion, Maramag, Bukidnon, Philippines.

Developed by Jan Marinelle Auditor, this project aims to provide residents with an accessible digital platform for submitting community concerns while enabling authorized barangay administrators to review, organize, and manage complaints more efficiently.

The system aims to improve communication between residents and barangay officials by centralizing complaint records and providing a structured process for complaint handling. With AI-assisted processing and multilingual support where configured, the platform seeks to make complaint reporting more accessible and help administrators organize incoming reports.

By using web technologies and digital tools, the project supports the improvement of local community services through a more organized, convenient, and transparent complaint management process.

Project Objectives
Provide residents with an accessible online platform for submitting community complaints.
Digitize and organize the complaint management process.
Help administrators review and manage submitted complaints.
Support complaint categorization and priority assessment.
Allow residents to monitor complaint status and progress.
Improve communication between residents and barangay administrators.
Explore AI-assisted complaint analysis and multilingual processing.
Promote efficient and transparent community service delivery.
Key Features
Resident Portal
Online complaint submission.
Submission of complaint descriptions and relevant information.
Complaint status tracking.
Access to complaint history according to account permissions.
Multilingual reporting where supported by the application.
Administrator Dashboard
Centralized complaint management.
Review and validation of submitted complaints.
Complaint categorization and prioritization.
Complaint status management.
Monitoring of complaint progress.
Complaint assignment and routing where implemented.
AI-Assisted Complaint Processing
Assistance with complaint classification.
Language processing and translation where configured.
Suggested complaint priority levels.
Support for organizing and processing incoming complaints.

AI-generated recommendations should be reviewed by authorized personnel before administrative decisions are made.

Technology Stack
Frontend Technologies
React — Builds interactive and reusable user interface components, if included in the implemented frontend.
JavaScript — Implements application logic and interactive functionality.
Vite — Provides frontend development tooling and production builds.
Tailwind CSS — Supports styling and responsive layouts where configured.
Axios — Handles HTTP requests where used by the application.
Backend Technologies
PHP — Server-side programming language.
Laravel 12 — Backend framework for application routing, business logic, and server-side operations.
Laravel Eloquent ORM — Provides database interaction and record management.
Composer — Manages PHP dependencies.
Database Technologies
Supabase — Cloud platform configured for database connectivity.
PostgreSQL — Relational database technology for structured application data.
Laravel Migrations — Manage database schema changes where used.
Authentication and Authorization
Laravel Authentication — Supports server-side authentication functionality.
Supabase Auth — Authentication service where integrated.
Role-Based Access Control (RBAC) — Supports differentiated resident and administrator permissions where implemented.
Artificial Intelligence
Google Gemini API — AI service configured for AI-assisted complaint processing.
Gemini 1.5 Flash — Model referenced in the example environment configuration.
AI-Assisted Analysis — Supports complaint categorization, prioritization, and language processing where implemented.
Deployment and Version Control
Vercel — Hosting and deployment platform.
GitHub — Source code hosting and version control.
Node.js and npm — JavaScript dependency management and frontend build tooling.

Note: The technology stack should reflect the actual dependencies and services used by the deployed application. Configuration references alone do not confirm that every service is active.

System Architecture

The application follows a web-based architecture consisting of the following layers:

Presentation Layer: Provides the interface for residents and administrators.
Application Layer: Processes application requests and business logic.
Database Layer: Stores complaint records and related application information.
Authentication Layer: Manages user identity and access permissions.
AI Integration Layer: Provides AI-assisted processing through configured services.
Deployment Layer: Publishes the application for online access.
How to Use the System
For Residents
Open the live website.
Sign in using a resident account or register if registration is available.
Submit a complaint with the required details.
Review the submitted complaint information.
Monitor the complaint status through the application.
For Administrators
Open the live website.
Sign in using an authorized administrator account.
Access the administrative dashboard.
Review and validate submitted complaints.
Update complaint status and manage the records according to the available functions.

The available actions depend on the account role and implemented application features.

Installation and Setup
Prerequisites

Install the following tools:

PHP
Composer
Node.js
npm
Git
Step 1: Clone the Repository
git clone https://github.com/marinelleeaux/Ai-community-complaints-management-system.git

Step 2: Navigate to the Project Directory
cd Ai-community-complaints-management-system


If the application is located in a subdirectory, navigate to the directory containing the relevant project configuration files.

Step 3: Install Backend Dependencies

For a Laravel backend, run:

composer install

Step 4: Install Frontend Dependencies

Navigate to the directory containing the relevant package.json file, then run:

npm install

Step 5: Configure Environment Variables

Configure the required environment variables according to the project's environment template and service configuration.

Do not publish API keys, database credentials, authentication secrets, or production passwords.

Step 6: Run the Application

For a Laravel backend:

php artisan serve


For a Vite frontend, if configured:

npm run dev


Run these commands in the appropriate project directories. Additional database setup may be required depending on the implementation.

Production Build

For a Vite frontend, generate production assets using:

npm run build


For a standard Vite configuration, the output is generated in the dist/ directory.

If the project uses Laravel for its frontend rendering or a different build configuration, follow the actual project setup.

Deployment

The live application is hosted at:

https://ai-community-complaints-management.vercel.app/

For deployment, ensure that the project uses the correct application root directory, build configuration, environment variables, and backend service settings.

A Laravel application may require additional server-side runtime configuration. Confirm that the selected hosting platform supports the backend runtime and that all database and AI services are reachable.

Demo Accounts

Demo accounts may be used for authorized testing when available.

Administrator Account
Role: Administrator
Email: admin@northpoblacion.gov.ph
Password: Obtain through the authorized demo credentials channel.
Resident Account
Role: Resident
Email: janmarinellea@gmail.com
Password: Obtain through the authorized demo credentials channel.

Security Notice: Do not publish active passwords in a public repository. Change any exposed credentials and share demo account details privately.

Privacy and Security

The system may process complaint information containing personal details. Appropriate safeguards should be implemented to protect residents and their information.

Enforce authentication and role-based access controls.
Protect administrator accounts with strong passwords.
Store credentials securely.
Validate and sanitize submitted information.
Restrict complaint records to authorized users.
Keep API keys and sensitive configuration values out of source control.
Avoid exposing personal information in public screenshots and logs.
Follow applicable privacy requirements, including the Philippine Data Privacy Act of 2012 (Republic Act No. 10173), where applicable.
Future Enhancements

Potential improvements include:

Email and SMS notifications.
Complaint analytics and reporting dashboards.
Enhanced mobile responsiveness and accessibility.
Expanded language support.
Administrative audit logs.
Additional complaint tracking and reporting features.
Improved security monitoring and privacy controls.

These items are potential enhancements and may not be implemented in the current version.

Project Information
Item	Details
Project Name	AI-Community Complaint Management System
Project Type	Web-Based Application
Developer	Jan Marinelle Auditor
Intended Users	Residents and authorized barangay administrators
Community	Barangay North Poblacion, Maramag, Bukidnon, Philippines
Backend Framework	Laravel 12, according to project configuration
Backend Language	PHP
Frontend Build Tool	Vite
Database	PostgreSQL through configured Supabase connectivity
Deployment Platform	Vercel
Source Code	GitHub Repository
Live Website	Visit Application
License

No license has been specified for this project. Add an appropriate LICENSE file if the project is intended for public distribution or reuse.

Developed by Jan Marinelle Auditor

AI-Community Complaint Management System — Supporting accessible complaint reporting and organized community service delivery.
