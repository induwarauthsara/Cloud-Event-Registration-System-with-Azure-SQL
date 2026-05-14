<?php
// ============================================================
//  registrations.php — Live Dashboard: All Registrations
//  This page reads ALL rows from Azure SQL in real time
// ============================================================

require 'db_config.php';

// ── Fetch total count ────────────────────────────────────────
$countResult = sqlsrv_query($conn, "SELECT COUNT(*) AS total FROM registrations");
$countRow    = sqlsrv_fetch_array($countResult, SQLSRV_FETCH_ASSOC);
$totalCount  = $countRow['total'];

// ── Fetch all registrations (newest first) ───────────────────
$sql  = "SELECT id, full_name, email, university, course, year_of_study,
                session_interest, registered_at
         FROM registrations
         ORDER BY registered_at DESC";
$rows = sqlsrv_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Live Dashboard — Azure SQL</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; padding: 2rem 1rem; }

    .header { text-align: center; margin-bottom: 2rem; }
    .header h1 { font-size: 1.8rem; color: #1a1a2e; font-weight: 700; }
    .header p  { color: #666; font-size: 15px; margin-top: 6px; }

    .stats {
      display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;
      margin-bottom: 1.8rem;
    }
    .stat-card {
      background: white; border-radius: 14px; padding: 1.2rem 2rem;
      text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      min-width: 140px;
    }
    .stat-card .num { font-size: 2.2rem; font-weight: 700; color: #0078d4; }
    .stat-card .lbl { font-size: 13px; color: #888; margin-top: 4px; }

    .azure-badge {
      display: inline-block; background: #0078d4; color: white;
      font-size: 11px; font-weight: 700; letter-spacing: 1px;
      padding: 3px 12px; border-radius: 20px; margin-bottom: 1rem;
      text-transform: uppercase;
    }

    .table-wrap {
      background: white; border-radius: 14px; overflow-x: auto;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin: 0 auto;
      max-width: 1000px;
    }

    table { width: 100%; border-collapse: collapse; }
    thead th {
      background: #0078d4; color: white; font-size: 13px;
      font-weight: 600; padding: 12px 16px; text-align: left;
      white-space: nowrap;
    }
    thead th:first-child { border-radius: 14px 0 0 0; }
    thead th:last-child  { border-radius: 0 14px 0 0; }

    tbody tr:nth-child(even) { background: #f7f9fc; }
    tbody tr:hover { background: #e8f1fb; }
    tbody td {
      padding: 11px 16px; font-size: 14px; color: #333;
      border-bottom: 1px solid #eef1f5;
    }

    .year-pill {
      background: #e6f4ea; color: #1e6b2e;
      border-radius: 20px; padding: 3px 12px; font-size: 12px;
      font-weight: 600; white-space: nowrap;
    }
    .interest-pill {
      background: #fef3cd; color: #7a5500;
      border-radius: 20px; padding: 3px 10px; font-size: 12px;
      font-weight: 600; white-space: nowrap;
    }
    .no-data { text-align: center; padding: 3rem; color: #aaa; font-size: 15px; }

    .actions { text-align: center; margin: 1.5rem 0; }
    .actions a {
      display: inline-block; padding: 10px 22px; border-radius: 8px;
      text-decoration: none; font-size: 14px; font-weight: 600; margin: 4px;
    }
    .btn-primary   { background: #0078d4; color: white; }
    .btn-secondary { background: white; color: #0078d4;
                     border: 1.5px solid #0078d4; }
    .btn-primary:hover   { background: #005a9e; }
    .btn-secondary:hover { background: #e8f1fb; }

    .refresh-note {
      text-align: center; font-size: 12px; color: #aaa; margin-top: 1rem;
    }
    .sql-box {
      background: #1e1e2e; color: #cdd6f4; font-family: monospace;
      font-size: 13px; border-radius: 12px; padding: 1.2rem 1.5rem;
      max-width: 700px; margin: 1.5rem auto; overflow-x: auto;
    }
    .sql-kw { color: #89b4fa; font-weight: bold; }
    .sql-tbl { color: #a6e3a1; }
    .sql-str { color: #f38ba8; }
  </style>
</head>
<body>

<div class="header">
  <div class="azure-badge">☁️ Live · Azure SQL Database</div>
  <h1>📋 Event Registrations Dashboard</h1>
  <p>Data is fetched live from Azure SQL — every refresh shows the latest rows!</p>
</div>

<div class="stats">
  <div class="stat-card">
    <div class="num"><?= $totalCount ?></div>
    <div class="lbl">Total Registrations</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#34a853">☁️</div>
    <div class="lbl">Stored in Azure SQL</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#f4a900">🌍</div>
    <div class="lbl">Accessible Anywhere</div>
  </div>
</div>

<!-- The actual SQL query shown for learning purposes -->
<div class="sql-box">
  <span class="sql-kw">SELECT</span> id, full_name, email, university, course, year_of_study, registered_at<br>
  <span class="sql-kw">FROM</span> <span class="sql-tbl">registrations</span><br>
  <span class="sql-kw">ORDER BY</span> registered_at <span class="sql-kw">DESC</span>;
</div>

<div class="actions">
  <a href="registrations.php" class="btn-primary">🔄 Refresh Data</a>
  <a href="index.html" class="btn-secondary">+ New Registration</a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>University</th>
        <th>Course</th>
        <th>Year</th>
        <th>Interest</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($totalCount == 0): ?>
        <tr>
          <td colspan="8" class="no-data">
            No registrations yet — be the first! 🎉
          </td>
        </tr>
      <?php else: ?>
        <?php while ($row = sqlsrv_fetch_array($rows, SQLSRV_FETCH_ASSOC)): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><strong><?= htmlspecialchars($row['full_name']) ?></strong></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['university']) ?></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td>
              <span class="year-pill"><?= htmlspecialchars($row['year_of_study']) ?></span>
            </td>
            <td>
              <span class="interest-pill"><?= htmlspecialchars($row['session_interest']) ?></span>
            </td>
            <td>
              <?php
                $dt = $row['registered_at'];
                if ($dt instanceof DateTime) {
                    echo $dt->format('d M Y, H:i');
                } else {
                    echo htmlspecialchars((string)$dt);
                }
              ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<p class="refresh-note">
  💡 Open this page on your phone and on the projector at the same time — both show the same live data!
</p>

<?php sqlsrv_close($conn); ?>
</body>
</html>
