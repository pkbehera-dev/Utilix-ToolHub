# 🧰 ToolBox — Online Utility & Developer Suite

A modern, highly performant, and privacy-focused online utility platform containing a full suite of developer, design, and daily productivity tools. Built entirely from scratch using **Vanilla PHP** and zero-dependency **Vanilla Javascript**, ToolBox delivers an ultra-fast, zero-bloat browser experience.

---

## ✨ Features

- **🚀 Peak Performance:** Zero framework overhead (no Laravel/Symfony, no React, no heavy vendor packages). Pages load in milliseconds.
- **🔒 Security-Hardened:**
  - Strict input validation and SQL injection protection via PDO prepared statements.
  - Native Google Authenticator (TOTP) 2FA for administrative security without composer dependencies.
  - Rate limiting (max 10 requests/minute/IP) to prevent API abuse.
  - Brute force protection on login (locks admin account for 15 minutes after 5 failed attempts).
  - Custom Content Security Policy (CSP), CSRF protection, and secure cookie configurations.
- **🔗 Google Safe Browsing Integration:** The URL shortener automatically runs link checks against the Google Safe Browsing API before short-coding.
- **🎨 Premium Visual Layouts:** Responsive glassmorphism interface featuring system-matched Light/Dark mode toggles and a fluid keyboard-friendly Command Palette (`Ctrl + K`).
- **🎛️ Obfuscated Admin Panel:** Full CRUD capabilities for managing categories and tools from a secure, hidden route.

---

## 🛠️ Technology Stack

- **Core Engine:** PHP 8.x (Raw, Object-Oriented MVC)
- **Database:** MySQL / MariaDB (via PDO)
- **Frontend Logic:** Native Vanilla JavaScript (ES6+)
- **Styling:** Custom CSS3 with design tokens (variables)
- **External APIs:** Google Safe Browsing API, Google Authenticator app for TOTP.

---

## 📁 Directory Structure

```text
ToolBox/
├── assets/                  # Public assets (compiled CSS, client JS, images)
│   ├── css/                 # Modern styling sheets
│   └── js/                  # App components & search palette scripts
├── database/                # SQL schema initialization scripts
├── src/                     # Core MVC source files
│   ├── Config/              # Configuration helpers (App, DB)
│   ├── Controllers/         # MVC Controllers (Home, Admin, Auth, Url)
│   ├── Core/                # Router, Session, Security, and Env Parser
│   └── Views/               # Page layouts, sub-views, and admin screens
├── .env.example             # Template for system parameters
├── .gitignore               # Ignored version control paths
├── index.php                # Front Controller (entry point)
├── LICENSE                  # GNU GPL v3 License
└── update_db.php            # Migration runner script for DB updates
```

---

## ⚙️ Installation & Setup

### 1. Requirements
* PHP 8.0+
* MySQL / MariaDB
* Apache (with `mod_rewrite` enabled)
* Web server (e.g., XAMPP, WampServer, Laragon)

### 2. Database Initialization
1. Create a MySQL database named `toolbox`.
2. Import the starter structure located in `database/schema.sql`.

### 3. Environment Settings
Rename `.env.example` to `.env` and fill out your local parameters:
```ini
# Application Setup
APP_ENV=development
APP_URL=http://localhost/ToolBox
APP_NAME=ToolBox
APP_SUPPORT_EMAIL=support@pkbehera.in

# Database Setup
DB_HOST=127.0.0.1
DB_NAME=toolbox
DB_USER=root
DB_PASS=
DB_CHAR=utf8mb4

# API Keys
SAFE_BROWSING_API_KEY=your-api-key-here
```

### 4. Run Migration Updates
Visit the database update script in your browser to run migrations and append fields (like TOTP secrets and rate limit registries):
```text
http://localhost/ToolBox/update_db.php
```

### 5. Admin Credentials & 2FA
* The admin login path can be customized via the `ADMIN_PREFIX` variable in `.env` (defaults to `/control-panel` and routes to `/login` initially).
* Configure your primary admin password and scan the generated QR code inside the Admin Settings panel using a Google Authenticator mobile app to enable TOTP 2FA.

---

## 📄 License

This project is licensed under the terms of the **GNU GPL v3** License. See the [LICENSE](LICENSE) file for the full text.
