# ☁️ Cloud Event Registration System
### From Localhost Chaos to Cloud Databases with Azure SQL

A beginner-friendly demo project built for the session **"From Localhost Chaos to Cloud Databases with Azure SQL"**. This application allows users to register for an event through a web form, while all submitted data is stored directly in **Microsoft Azure SQL Database** — demonstrating real-time, multi-device cloud collaboration for student developers.

---

## 📸 What It Does

- 📝 **Registration Form** — Users fill in their name, email, university, course, year, and session interest
- ☁️ **Azure SQL Backend** — All data is saved directly to a cloud database, not a local machine
- 📋 **Live Dashboard** — View all registrations in real time from any device, anywhere in the world
- 🔒 **Secure by Default** — Parameterized queries, TLS encryption, and Azure Firewall rules

---

## 📁 Project Files

| File | What it does |
|---|---|
| `index.html` | Registration form (the frontend) |
| `db_config.php` | Your Azure SQL connection settings ⚠️ add to `.gitignore` |
| `setup.php` | Run **once** to create the database table, then delete |
| `register.php` | Validates form data and inserts into Azure SQL |
| `registrations.php` | Live dashboard — reads and displays all registrations |

---

## 🧩 Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3 |
| Backend | PHP 8 with Microsoft SQLSRV Extension |
| Database | Azure SQL Database (SQL Server in the cloud) |
| Security | Parameterized queries, Azure Firewall, TLS encryption |
| Driver | Microsoft ODBC Driver 18 for SQL Server |

---

## 🚀 Full Setup Guide

### Step 1 — Create your Azure SQL Database

1. Go to [portal.azure.com](https://portal.azure.com)
2. Click **Create a resource** → search **SQL Database**
3. Fill in:
   - **Database name**: `EventRegistrationDB`
   - **Server**: Create new → choose a unique server name
   - **Authentication**: SQL Authentication
   - Set a strong admin **username** and **password** — save these!
4. **Pricing tier**: Choose **DTU-based → Basic** (~$5/month) or use your free Azure student credits
5. Click **Review + Create** → **Create**
6. Wait ~2 minutes for deployment to complete

> 🎓 **Student?** Get $100 free Azure credit at [azure.microsoft.com/free/students](https://azure.microsoft.com/free/students) — no credit card needed!

---

### Step 2 — Enable Public Network Access & Configure Firewall

By default Azure SQL blocks all public connections. You need to enable access:

1. Go to **portal.azure.com** → **SQL Servers** → your server
2. Click **Networking** in the left menu
3. Under **Public network access**, select **Selected networks** (or **Enable**)
4. Tick the checkbox: ☑ **Allow Azure services and resources to access this server**
5. Under **Firewall rules**, click **+ Add your client IP** to add your current IP
6. For a demo/session where multiple users need access, add this rule:

| Rule Name | Start IP | End IP |
|---|---|---|
| `AllowAll` (demo only) | `0.0.0.0` | `255.255.255.255` |

7. Click **Save**

> ⚠️ The `AllowAll` rule is for demo/development only. Restrict to specific IPs in production.

> ⚠️ If you see the error **"Deny Public Network Access is set to Yes"** — this step fixes it. The firewall rules alone won't work unless Public Network Access is enabled first.

---

### Step 3 — Edit db_config.php

Open `db_config.php` and update these 4 values with your Azure details:

```php
define('DB_SERVER',   'yourserver.database.windows.net'); // from Azure Portal → SQL Server → Overview
define('DB_DATABASE', 'EventRegistrationDB');             // your database name
define('DB_USERNAME', 'your_admin_username');             // set during server creation
define('DB_PASSWORD', 'YourPassword123!');                // set during server creation
```

Your server name is found at: **Azure Portal → SQL Servers → your server → Overview → Server name**

> ❌ **Never commit `db_config.php` to GitHub!** Add it to `.gitignore` immediately.

```
# .gitignore
db_config.php
```

---

### Step 4 — Install PHP SQLSRV Driver (XAMPP / Local PHP)

> **Skip this step** if you are hosting on Azure App Service — the driver is pre-installed there.

This is needed on **every Windows machine** that runs the project locally.

#### 4a — Find your PHP version and Thread Safety

Open XAMPP Shell and run:
```bash
php -v
php -i | findstr "Thread"
```

Note your PHP version (e.g. `8.2`) and whether Thread Safety is `enabled` (TS) or `disabled` (NTS).

> ⚠️ **Important:** The Apache web server PHP version (shown in `phpinfo()`) may differ from your CLI PHP version. Always use the version shown in `phpinfo()` — visit `http://localhost/phpinfo.php` with `<?php phpinfo(); ?>` to confirm.

#### 4b — Download the SQLSRV PHP Extension

Download the drivers ZIP from Microsoft:

👉 **[Download PHP Drivers for SQL Server](https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver17&wt.mc_id=studentamb_435262)**

From the ZIP, pick the **two DLL files** that match your PHP version and Thread Safety:

| Your PHP | Thread Safety | Files to use |
|---|---|---|
| PHP 8.2, TS | enabled | `php_sqlsrv_82_ts_x64.dll` + `php_pdo_sqlsrv_82_ts_x64.dll` |
| PHP 8.2, NTS | disabled | `php_sqlsrv_82_nts_x64.dll` + `php_pdo_sqlsrv_82_nts_x64.dll` |
| PHP 8.3, TS | enabled | `php_sqlsrv_83_ts_x64.dll` + `php_pdo_sqlsrv_83_ts_x64.dll` |
| PHP 8.3, NTS | disabled | `php_sqlsrv_83_nts_x64.dll` + `php_pdo_sqlsrv_83_nts_x64.dll` |

#### 4c — Copy DLL files to XAMPP

Copy **both** chosen DLL files into your PHP extensions folder:
```
C:\xampp\php\ext\
```

#### 4d — Edit php.ini

Open your XAMPP `php.ini` (path shown in `phpinfo()` → **Loaded Configuration File**):
```
C:\xampp\php\php.ini
```

Find the extensions section (search for `extension=php_mysqli`) and add these two lines:
```ini
extension=php_sqlsrv_82_ts_x64
extension=php_pdo_sqlsrv_82_ts_x64
```

Replace `82_ts` with your actual version and thread safety type.

#### 4e — Install Microsoft ODBC Driver 18

The PHP extension alone is not enough — it requires the ODBC Driver for SQL Server installed on Windows.

👉 **[Download ODBC Driver 18 for SQL Server](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server?view=sql-server-ver17&wt.mc_id=studentamb_435262)**

Run the `.msi` installer → click Next → Next → Finish. No extra configuration needed.

#### 4f — Restart Apache & Verify

1. In XAMPP Control Panel → **Stop** Apache → **Start** Apache
2. Create `C:\xampp\htdocs\check.php`:
```php
<?php
if (function_exists('sqlsrv_connect')) {
    echo "✅ SQLSRV is working!";
} else {
    echo "❌ SQLSRV not found. Check php.ini and ext folder.";
}
?>
```
3. Visit `http://localhost/check.php` — you should see ✅
4. Delete `check.php` after confirming

---

### Step 5 — Create the Database Table (Run setup.php Once)

Visit in your browser:
```
http://localhost/your-project/setup.php
```

You should see: **✅ Table Created Successfully!**

Then **delete `setup.php`** immediately — it is a security risk if left accessible.

---

### Step 6 — You're Live! 🎉

1. Open `http://localhost/your-project/index.html`
2. Fill in the registration form and submit
3. Visit `http://localhost/your-project/registrations.php`
4. Your data is now stored in Microsoft Azure — accessible from any device!

---

## 🗄️ Database Table Structure

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

This table is created automatically by `setup.php`. The `registered_at` field is set automatically by SQL Server using `GETDATE()`.

---

## 🔒 Security Notes

| Practice | Why it matters |
|---|---|
| ✅ Parameterized queries (`?` placeholders) | Prevents SQL Injection attacks |
| ✅ `Encrypt=true` in connection | All data travels through TLS — like HTTPS |
| ✅ Azure Firewall rules | Controls exactly which IPs can reach the database |
| ✅ `htmlspecialchars()` on output | Prevents XSS (Cross-Site Scripting) attacks |
| ❌ Never push `db_config.php` to GitHub | Your password would be public — always use `.gitignore` |
| ❌ Never use admin credentials in production | Create a limited SQL user with only INSERT/SELECT permissions |

---

## 🐛 Common Errors & Fixes

| Error | Cause | Fix |
|---|---|---|
| `Call to undefined function sqlsrv_connect()` | SQLSRV PHP extension not installed | Follow Step 4 completely |
| `Deny Public Network Access is set to Yes` | Azure firewall blocking all public connections | Enable Public Network Access in Azure Portal → Networking |
| `Invalid object name 'registrations'` | Table doesn't exist yet | Run `setup.php` first |
| `Login failed for user` | Wrong username or password in `db_config.php` | Double-check credentials in Azure Portal |
| `Cannot open server requested by the login` | Wrong server name or database name | Check server name ends in `.database.windows.net` |
| DLL loaded but still not working | Wrong PHP version or TS/NTS mismatch | Check `phpinfo()` to confirm Apache PHP version, not CLI version |

---

## 🤖 Next Level — Azure AI Foundry Integration

Once your data lives in Azure SQL, adding AI-powered features becomes straightforward:

```
Azure SQL  →  Azure AI Foundry  →  Intelligent Features
```

| Feature | What it does |
|---|---|
| 💬 Smart Chatbot | "How many 3rd year students registered?" — AI queries your DB and answers |
| 📧 Auto Welcome Emails | GPT generates a personalized email for each new registrant |
| 📊 Natural Language Reports | "Summarize this month's registrations with key trends" |
| 🔍 Anomaly Detection | Automatically flag duplicate registrations or unusual patterns |

Explore Azure AI Foundry at [ai.azure.com](https://ai.azure.com)

---

## 📚 Resources

| Resource | Link |
|---|---|
| Azure Free for Students | [azure.microsoft.com/free/students](https://azure.microsoft.com/free/students) |
| Azure Portal | [portal.azure.com](https://portal.azure.com) |
| PHP Drivers for SQL Server | [Download](https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver17&wt.mc_id=studentamb_435262) |
| ODBC Driver 18 for SQL Server | [Download](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server?view=sql-server-ver17&wt.mc_id=studentamb_435262) |
| Azure SQL Documentation | [docs.microsoft.com/azure/sql-database](https://docs.microsoft.com/azure/azure-sql) |
| Azure AI Foundry | [ai.azure.com](https://ai.azure.com) |

---

## 📄 License

This project is open source and available for educational use.

---

*Session: "From Localhost Chaos to Cloud Databases with Azure SQL" · Built with HTML, PHP & Microsoft Azure SQL Database*
