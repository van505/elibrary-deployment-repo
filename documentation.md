# SECTION 1 — TITLE PAGE

**Project Title:** ELibrary — Digital E-Book Library System  
**Course Name and Code:** [PLACEHOLDER]  
**Student Name:** [PLACEHOLDER]  
**Instructor Name:** [PLACEHOLDER]  
**School Name:** [PLACEHOLDER]  
**Date Submitted:** 2026-05-01  

---

# SECTION 2 — INTRODUCTION

### Project Overview
The ELibrary system is a full-featured digital e-book library built on the robust Laravel framework. It provides a comprehensive platform where administrators can manage and curate a large catalog of e-books, authors, categories, and tiered subscription plans. Users can register as Members to browse the catalog, subscribe to different access tiers (Free, Basic, Premium), read books directly through the web application, and maintain a detailed reading history alongside gamified reading streaks.

### Problem Statement
Traditional physical libraries have inherent limitations in accessibility, availability, and scale. They require physical presence, operate during restricted hours, and are limited by the number of physical copies available. A digital e-library system solves these problems by providing 24/7 access to an unlimited number of concurrent readers, accessible from anywhere with an internet connection, and incorporates modern gamification to encourage continuous reading habits.

### Objectives
1. Provide a central digital repository for e-books organized by categories, authors, and curated collections.
2. Implement a tiered subscription system to govern access levels for different e-books.
3. Allow members to seamlessly read, bookmark, wishlist, and review e-books.
4. Encourage consistent reading habits through gamification elements like daily reading streaks.
5. Equip administrators with robust management tools, analytics, and reporting capabilities.
6. Maintain a secure and accountable system through activity logging, Two-Factor Authentication (2FA), and strict role-based access control.

### Scope and Limitations
**Scope:** The system covers secure user authentication, guided member onboarding, comprehensive e-book cataloging, streaming/reading files (PDF, EPUB, MP3), subscription management, community review moderation, and detailed administrative reporting.  
**Limitations:** The system does not currently include a native mobile application for iOS/Android. It requires an active internet connection as it does not support offline downloading. It also focuses entirely on digital assets and does not track physical library inventory.

---

# SECTION 3 — SYSTEM FEATURES

### ADMIN FEATURES
* **Dashboard Analytics** — View high-level statistics like total active members, books, and recent transactions.
* **Ebook Management** — Add, edit, archive, and manage access levels and visibility of digital e-books.
* **Author Management** — Maintain a structured database of authors and dynamically link them to multiple e-books.
* **Category Management** — Organize the e-book catalog with descriptive, color-coded categories.
* **Collection Management** — Curate specific e-books into logical series or thematic collections.
* **Member Management** — View detailed member profiles, suspend accounts, and track reading streaks.
* **Subscription Plan Management** — Create and configure subscription tiers (Free, Basic, Premium) with specific reading limits.
* **Transaction History** — View the financial ledger of member subscription payments and active statuses.
* **Reviews Moderation** — Approve, reject, or delete user-submitted e-book reviews to maintain quality.
* **Announcements** — Broadcast platform-wide messages or alerts to all members via dashboard banners.
* **Notifications** — Send targeted alerts or system updates to administrators or members.
* **Reports & Analytics** — Generate detailed metrics on the most read books, user engagement, and system growth.
* **Activity/Audit Log** — Track every critical action taken by administrators for security and accountability.
* **Settings Configuration** — Manage global application settings and platform variables.
* **User Management** — Manage backend administrative users and their secure access.
* **Archive System** — Restore or permanently delete soft-deleted records like books and authors safely.

### MEMBER FEATURES
* **Dashboard & Reading Streak** — Track daily reading habits with a visual streak counter and personalized recommendations.
* **Onboarding Flow** — Complete a guided setup to select preferred categories and finalize profile data upon registration.
* **Browse Ebooks** — Search, filter, and discover e-books seamlessly by category, author, or access level.
* **Ebook Access / Reading** — Stream and read e-books (PDF, EPUB) or listen to audiobooks directly within the browser.
* **Reading History (My Ebooks)** — View a comprehensive log of all previously accessed and read e-books.
* **Bookmarks** — Save specific e-books for quick access and continued reading later.
* **Wishlist** — Add locked or interesting e-books to a personal wishlist for future access.
* **My Reviews** — Write, edit, and manage personal reviews and ratings left on e-books.
* **My Subscription** — View current subscription status, upgrade/downgrade plans, and track monthly usage limits.
* **Collections Browsing** — Discover curated sets or series of e-books beautifully packaged by administrators.
* **Profile Management** — Update personal information, physical address, and profile avatars.
* **Security Settings** — Manage passwords and enable Two-Factor Authentication (2FA) for enhanced account security.
* **Notifications & Announcements** — Receive automated system updates and view global broadcast banners.
* **Help Page** — Access platform support, FAQs, and system documentation.

---

# SECTION 4 — SYSTEM ARCHITECTURE

### 4A. Architecture Overview
The ELibrary system follows the robust Model-View-Controller (MVC) architecture utilizing the Laravel 12 framework. 
* **The Model layer** interacts with the MySQL database using the Eloquent ORM to handle data logic, soft deletes, and complex relationships. 
* **The Controller layer** processes incoming HTTP requests, enforces business logic and middleware protections (role-based permissions), and securely passes data to the views. 
* **The View layer**, rendered using Laravel Blade templates and styled with Tailwind CSS, presents a premium user interface. Interactive frontend components (like modals, dropdowns, alerts, and dynamic forms) are powered by lightweight Alpine.js, ensuring a highly reactive user experience without the overhead of a heavy Single Page Application framework.

### 4B. Database Schema Summary

| TABLE | Columns | Purpose |
| :--- | :--- | :--- |
| **activity_logs** | id, user_id, action, module, description, ip_address, user_agent, browser, platform, created_at | Tracks all significant administrative actions for audit and security purposes. |
| **admin_notifications** | id, type, message, is_read, action_url, created_at, updated_at | Stores system alerts and notifications specifically for administrators. |
| **announcements** | id, title, message, type, is_active, starts_at, ends_at, created_by, created_at, updated_at | Manages global broadcast banners displayed to users across the platform. |
| **authors** | id, first_name, middle_name, last_name, bio, nationality, created_at, updated_at, deleted_at | Stores biographical information about book authors. |
| **categories** | id, name, slug, description, color, created_at, updated_at, deleted_at | Categorizes e-books for easier navigation and filtering. |
| **collection_ebooks** | id, collection_id, ebook_id, order_number, created_at, updated_at | Pivot table linking e-books to specific curated collections in a specific order. |
| **collections** | id, name, slug, description, cover_image, is_active, created_by, created_at, updated_at | Stores curated groupings or series of e-books. |
| **ebook_access** | id, member_id, ebook_id, accessed_at, created_at, updated_at | Logs every time a member opens or reads an e-book to track reading history. |
| **ebook_authors** | id, ebook_id, author_id, created_at, updated_at | Pivot table supporting multiple authors per e-book. |
| **ebook_bookmarks** | id, member_id, ebook_id, created_at | Stores user-saved bookmarks for quick access. |
| **ebook_tags** | id, ebook_id, tag_name, created_at, updated_at | Stores searchable keyword tags associated with e-books. |
| **ebook_wishlists** | id, member_id, ebook_id, created_at, updated_at | Stores e-books that members wish to read in the future. |
| **ebooks** | id, category_id, title, isbn, publisher, publish_year, file_path, cover_image, file_type, access_level, status, preview_pages, is_featured, is_spotlighted, created_at, updated_at, deleted_at | The core catalog table storing all metadata and file references for digital books. |
| **member_notifications** | id, member_id, type, message, is_read, created_at, updated_at | Stores targeted alerts and updates for individual members. |
| **members** | id, user_id, member_code, first_name, middle_name, last_name, phone, address, avatar, onboarding_completed, onboarding_step, preferred_categories, status, current_streak, longest_streak, last_read_date, suspension_reason, suspended_at, created_at, updated_at, deleted_at | Stores extended profile data, gamification metrics (streaks), and status for registered readers. |
| **password_history** | id, user_id, password, created_at | Keeps a history of old passwords to prevent users from reusing recent passwords. |
| **reviews** | id, member_id, ebook_id, rating, comment, status, created_at, updated_at, deleted_at | Stores user ratings and written feedback for e-books. |
| **settings** | id, key, value, type, description, created_at, updated_at | Stores dynamic global application configuration variables. |
| **subscription_plans** | id, name, slug, price, ebook_limit, description, is_active, level, created_at, updated_at, deleted_at | Defines the available membership tiers and their respective limits. |
| **subscriptions** | id, member_id, plan_id, status, started_at, expires_at, created_at, updated_at | Tracks active and expired memberships for individual users. |
| **transactions** | id, member_id, plan_id, amount, payment_method, status, reference_no, paid_at, created_at, updated_at | Logs financial payments made for subscription plan upgrades. |
| **users** | id, email, google_id, first_name, last_name, email_verified_at, password, role, remember_token, two_factor_enabled, google2fa_secret, two_factor_recovery_codes, email_otp_code, email_otp_expires_at, created_at, updated_at | Core authentication table managing credentials, roles, and 2FA security. |

### 4C. Technology Stack

| Component | Technology Used |
| :--- | :--- |
| Backend Framework | Laravel 12 |
| Frontend Templating | Blade + Tailwind CSS |
| JavaScript Interactivity | Alpine.js |
| Database | MySQL |
| Authentication | Laravel Breeze (with 2FA capabilities) |
| File Storage | Laravel Storage (Local / Public disk) |
| Package Manager | Composer (PHP) + npm (JS) |
| Development Server | php artisan serve / Vite |

---

# SECTION 5 — USER ROLES AND PERMISSIONS

| Role | Access Level | Key Permissions |
| :--- | :--- | :--- |
| **Admin** | Full Access | Manage Ebooks, Authors, Categories, Collections, Members, Subscriptions, View Reports, System Settings, Audit Logs, Announcements, Notifications. |
| **Member** | Restricted | Browse catalog, Read/stream books, Track reading history/streaks, Bookmark, Review, Manage personal subscription and profile. |

**Admin Workflow:** An administrator logs into the secure backend dashboard to monitor platform health via dynamic analytics. They curate the library by uploading new e-books, linking authors, assigning access levels (e.g., Premium vs Free), and organizing thematic collections. They also moderate incoming user reviews to ensure quality, configure subscription plans, and resolve member account issues or suspensions.

**Member Workflow:** A member signs up, completes an intuitive onboarding flow to define reading interests, and lands on a personalized dashboard highlighting their reading streak. They browse the catalog using advanced filters, select an e-book based on their active subscription tier, and stream it securely in the browser. They can leave a review after reading, bookmark items for later, and upgrade their subscription if they wish to access higher-tier content.

---

# SECTION 6 — SYSTEM MODULES

### Authentication & Registration
Handles secure user login, registration, and session management using Laravel Breeze. It serves both Admin and Member roles. Key actions include email verification, password resets, Google OAuth integration, and Two-Factor Authentication (2FA) setup to protect user accounts.

### Ebook Management
The core engine of the catalog used by Administrators to upload and index digital files. It allows Admins to create book records, assign metadata (ISBN, publisher, year), manage the physical file (PDF/EPUB/MP3), and control visibility through status and access tier toggles.

### Author Management
A module for Administrators to catalog the creators of the e-books. Key actions include adding biographical data and linking authors to multiple e-books through a many-to-many relationship, enabling members to easily filter books by specific writers.

### Category & Tag Management
Used by Administrators to intuitively organize the library. Categories define the primary genre (with custom UI colors), while tags allow for granular keyword associations. Members use these taxonomies heavily to filter and discover content on the frontend.

### Collection Management
Allows Administrators to logically group related e-books into curated series or thematic lists (e.g., "Summer Reading"). These collections are featured prominently on the landing page and member dashboard to drive user engagement and content discovery.

### Subscription & Plans
Governs access control across the platform. Administrators define the pricing and limits of different tiers (Free, Basic, Premium). Members use this module to view their current active plan, monitor usage limits, and easily upgrade or downgrade their access level.

### Transaction History
A secure ledger module where Administrators can audit payments made by members for subscription upgrades. It tracks payment methods, amounts, transaction references, and statuses, ensuring highly accurate financial record-keeping.

### Member Management
The CRM interface for Administrators to oversee registered readers. Admins can view individual reading histories, adjust account statuses, process suspensions, and monitor user engagement metrics like reading streaks and login activity.

### Reading History & Streak
A highly engaging member-facing gamification module that tracks daily reading habits. It automatically updates a member's "current streak" and "longest streak" when they access a book, displaying motivational milestones and custom iconography on their dashboard.

### Bookmarks & Wishlists
Used exclusively by Members to curate personal reading lists. Bookmarks allow quick access to currently engaged books, while wishlists allow users to save locked or highly interesting books for future reading when they upgrade their subscription.

### Reviews & Ratings
A community interaction module where Members can leave 1-to-5 star ratings and written comments on e-books they have read. Administrators use the backend interface to strictly moderate, approve, or reject these reviews before they appear publicly on the catalog.

### Announcements
A platform broadcast tool for Administrators to publish global alerts (e.g., scheduled maintenance, new features). These announcements intelligently appear as dismissible banners on the Member dashboard.

### Notifications
A targeted messaging system used by both roles. Members receive automated alerts regarding subscription expirations or review approvals, while Admins receive crucial system alerts and operational updates.

### Reports & Analytics
An administrative dashboard module that aggregates deep system data. It provides visual insights into total accesses, active vs. inactive members, highly popular books, and general platform growth over time.

### Activity Log / Audit Trail
A strict backend security module that quietly records all administrative actions (creates, updates, deletes) alongside IP addresses and user agents. Used by high-level Admins to ensure total accountability and accurately track system changes.

### Settings
A dynamic configuration panel allowing Administrators to adjust global application variables dynamically without altering code, such as site names, reading limits, or platform contact emails.

### Help & Support
A member-facing resource module providing quick access to FAQs, contact forms, or system documentation to effectively assist users in navigating the platform.

---

# SECTION 7 — CONCLUSION

**Summary**
The ELibrary platform is a comprehensive, scalable digital e-book distribution system. It successfully bridges the gap between complex content management and user engagement by combining a robust administrative backend with a gamified, highly intuitive member experience. 

**Key Technical Achievements**
The system expertly leverages Laravel's elegant Eloquent ORM to manage complex data relationships, integrates secure role-based access control, and implements modern security features like Two-Factor Authentication and comprehensive audit logging. The UI achieves a premium, cohesive aesthetic using Tailwind CSS and Alpine.js for seamless interactivity without heavy javascript overhead. The automated gamification engine (reading streaks) reliably tracks user engagement without impacting platform performance.

**Learnings**
Building this project powerfully demonstrated the advantage of the MVC pattern in organizing large codebases, the critical importance of database normalization for query performance, and exactly how gamification (streaks) can be technically implemented using precise date-tracking logic and robust service classes.

**Possible Future Improvements**
Potential future expansions include developing a dedicated native mobile application utilizing the existing backend as an API, integrating an advanced in-browser PDF/EPUB reader featuring rich text annotations and exact page-tracking, implementing push notifications, and adding machine-learning based book recommendations based strictly on a user's reading history and preferred categories.

---

# SECTION 8 — REFERENCES
- **Laravel:** https://laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Alpine.js:** https://alpinejs.dev
- **MySQL:** https://dev.mysql.com/doc/
