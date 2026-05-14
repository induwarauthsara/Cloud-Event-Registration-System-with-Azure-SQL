<?php
// ============================================================
//  register.php — Receives form submission and saves to Azure SQL
// ============================================================

require 'db_config.php';

// ── 1. Collect and sanitize form input ──────────────────────
function clean($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$full_name        = clean($_POST['full_name']        ?? '');
$email            = clean($_POST['email']            ?? '');
$university       = clean($_POST['university']       ?? '');
$course           = clean($_POST['course']           ?? '');
$year             = clean($_POST['year']             ?? '');
$session_interest = clean($_POST['session_interest'] ?? '');

// ── 2. Basic validation ──────────────────────────────────────
$errors = [];
if (empty($full_name))  $errors[] = "Full name is required.";
if (empty($email))      $errors[] = "Email is required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email.";
if (empty($university)) $errors[] = "University is required.";
if (empty($course))     $errors[] = "Course is required.";

if (!empty($errors)) {
    echo "<h2 style='color:red;font-family:sans-serif'>❌ Validation Errors</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><a href='index.html'>← Go Back</a>";
    exit;
}

// ── 3. Insert into Azure SQL using a parameterized query ─────
//  ✅ Using parameters (?) prevents SQL Injection attacks!
$sql = "INSERT INTO registrations
            (full_name, email, university, course, year_of_study, session_interest)
        VALUES
            (?, ?, ?, ?, ?, ?)";

$params = [$full_name, $email, $university, $course, $year, $session_interest];
$result = sqlsrv_query($conn, $sql, $params);

// ── 4. Handle the result ──────────────────────────────────────
if ($result === false) {
    $errs = sqlsrv_errors();

    // Friendly message for duplicate email
    if (isset($errs[0]['code']) && $errs[0]['code'] == 2627) {
        echo "
        <html><body style='font-family:sans-serif;text-align:center;padding:60px;'>
        <h2>⚠️ Already Registered</h2>
        <p>The email <strong>" . htmlspecialchars($email) . "</strong> is already registered.</p>
        <a href='index.html'>← Try Again</a>
        </body></html>";
    } else {
        echo "<h2 style='color:red'>❌ Database Error</h2>";
        echo "<pre>" . print_r($errs, true) . "</pre>";
    }
    exit;
}

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="refresh" content="4;url=registrations.php" />
  <title>Registered!</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8;
           display: flex; flex-direction: column; align-items: center;
           justify-content: center; min-height: 100vh; }
    .box { background: white; border-radius: 16px; padding: 2.5rem 2rem;
           text-align: center; max-width: 460px; width: 100%;
           box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .check { font-size: 4rem; }
    h1 { color: #1a6b2e; font-size: 1.6rem; margin: 1rem 0 0.5rem; }
    p  { color: #555; font-size: 15px; line-height: 1.7; }
    a  { display: inline-block; margin-top: 1.5rem; color: #0078d4;
         font-weight: 700; text-decoration: none; }
    .cloud-note { background: #e8f1fb; border-radius: 10px; padding: 12px 16px;
                  margin-top: 1.2rem; font-size: 13px; color: #0052a5; }
  </style>
</head>
<body>
  <div class="box">
    <div class="check">✅</div>
    <h1>You're registered, <?= htmlspecialchars($full_name) ?>!</h1>
    <p>Your registration has been saved to <strong>Azure SQL</strong> in the cloud.</p>
    <div class="cloud-note">
      ☁️ This data is stored in Microsoft Azure — not on anyone's laptop!
      Anyone on the team can query it right now from anywhere in the world.
    </div>
    <p style="margin-top:1rem;font-size:13px;color:#888;">Redirecting to live dashboard in 4 seconds…</p>
    <a href="registrations.php">View Live Dashboard →</a>
  </div>
</body>
</html>
