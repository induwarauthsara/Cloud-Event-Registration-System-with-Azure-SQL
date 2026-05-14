# ☁️ Cloud Event Registration System
### Azure SQL Demo Project — Session Guide

---

## 📁 Project Files

| File | What it does |
|---|---|
| `index.html` | Registration form (the frontend) |
| `db_config.php` | Your Azure SQL connection settings |
| `setup.php` | Run once to create the database table |
| `register.php` | Saves form data to Azure SQL |
| `registrations.php` | Live dashboard showing all registrations |

---

## 🚀 Setup in 5 Steps

### Step 1 — Create your Azure SQL Database

1. Go to [portal.azure.com](https://portal.azure.com)
2. Click **Create a resource** → search **SQL Database**
3. Fill in:
   - **Database name**: `EventRegistrationDB`
   - **Server**: Create new → choose a unique server name
   - **Authentication**: SQL Authentication
   - Set a strong admin username and password
4. **Pricing**: Choose **DTU-based → Basic** (cheapest, ~$5/month or use Free Trial)
5. Click **Review + Create** → **Create**

---

### Step 2 — Allow your IP in Azure Firewall

1. Go to your SQL Server in Azure Portal
2. Click **Networking** (left menu)
3. Under **Firewall rules**, click **Add your client IP**
4. Save changes

> ⚠️ Without this step, you'll get "connection refused" errors!

---

### Step 3 — Edit db_config.php

Open `db_config.php` and update these 4 values:

```php
define('DB_SERVER',   'yourserver.database.windows.net'); // from Azure Portal
define('DB_DATABASE', 'EventRegistrationDB');
define('DB_USERNAME', 'your_admin_username');
define('DB_PASSWORD', 'YourPassword123!');
```

Your server name is shown in Azure Portal → SQL Server → Overview → **Server name**

---

### Step 4 — Install PHP SQLSRV Driver (if running locally)

If using XAMPP or PHP locally:

1. Download Microsoft SQLSRV drivers: https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
2. Copy `php_sqlsrv_82_nts_x64.dll` to your PHP `ext/` folder
3. Add to `php.ini`: `extension=php_sqlsrv_82_nts_x64`
4. Restart Apache

> **Alternative**: Host your PHP on Azure App Service — the SQLSRV driver is pre-installed!

---

### Step 5 — Run setup.php Once

Visit in your browser:
```
http://localhost/your-project/setup.php
```

You should see: **✅ Table Created Successfully!**

Then **delete setup.php** (security best practice).

---

## 🎯 Demo Script (for Live Presentation)

1. Open `index.html` on the **projector**
2. Ask an audience member to register on their **phone** using the same URL
3. Show `registrations.php` live — their data appears instantly!
4. Open `registrations.php` on 2 different devices simultaneously
5. Register from one → refresh the other → **same data, live!**

This proves: *"The database lives in the cloud, not on any one laptop."*

---

## 🗄️ The Database Table (reference)

```sql
CREATE TABLE registrations (
    id               INT IDENTITY(1,1) PRIMARY KEY,
    full_name        NVARCHAR(150)  NOT NULL,
    email            NVARCHAR(200)  NOT NULL UNIQUE,
    university       NVARCHAR(200)  NOT NULL,
    course           NVARCHAR(150)  NOT NULL,
    year_of_study    NVARCHAR(50),
    session_interest NVARCHAR(100),
    registered_at    DATETIME DEFAULT GETDATE()
);
```

---

## 🔒 Security Notes (explain to students)

- ✅ **Parameterized queries** prevent SQL Injection (see `register.php`)
- ✅ **Azure Firewall** controls who can connect to your database
- ✅ **Encryption is always on** in Azure SQL (Encrypt=true)
- ❌ **Never push `db_config.php` to GitHub** — add it to `.gitignore`
- ❌ **Never use root/admin credentials** in production — create a limited user

---

## 🤖 Bonus: Azure AI Foundry Integration Idea

After this demo, show how you could add:

```
Azure SQL → Azure AI Foundry → Smart Features:
  • Auto-generate a "Welcome email" for each registrant using GPT
  • Summarize session interest statistics in natural language
  • Build a chatbot that answers questions about who registered
  • Generate a PDF report of all participants automatically
```

---

## 🧩 Tech Stack

- **Frontend**: HTML5, CSS3
- **Backend**: PHP 8 with Microsoft SQLSRV Extension
- **Database**: Azure SQL Database (SQL Server in the cloud)
- **Security**: Parameterized queries, Azure Firewall, TLS encryption

---

*Session: "From Localhost Chaos to Cloud Databases with Azure SQL"*
