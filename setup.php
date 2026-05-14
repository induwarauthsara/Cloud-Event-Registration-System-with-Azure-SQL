<?php
// ============================================================
//  setup.php — Run this ONCE to create the database table
//  Visit: http://your-site/setup.php
//  Delete this file after running it!
// ============================================================

require 'db_config.php';   // Connect to Azure SQL

// SQL to create the registrations table
$sql = "
    IF NOT EXISTS (
        SELECT * FROM sysobjects
        WHERE name='registrations' AND xtype='U'
    )
    CREATE TABLE registrations (
        id                INT IDENTITY(1,1) PRIMARY KEY,
        full_name         NVARCHAR(150)  NOT NULL,
        email             NVARCHAR(200)  NOT NULL UNIQUE,
        university        NVARCHAR(200)  NOT NULL,
        course            NVARCHAR(150)  NOT NULL,
        year_of_study     NVARCHAR(50),
        session_interest  NVARCHAR(100),
        registered_at     DATETIME       DEFAULT GETDATE()
    );
";

$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    echo "<h2 style='color:red'>❌ Table creation failed!</h2>";
    echo "<pre>" . print_r(sqlsrv_errors(), true) . "</pre>";
} else {
    echo "
    <html><head><title>Setup</title>
    <style>body{font-family:sans-serif;max-width:600px;margin:60px auto;text-align:center;}</style>
    </head><body>
    <h1>✅ Table Created Successfully!</h1>
    <p>The <strong>registrations</strong> table is ready in your Azure SQL database.</p>
    <p style='color:red;font-weight:bold;'>⚠️ Please DELETE this setup.php file now!</p>
    <a href='index.html'>← Go to Registration Form</a>
    </body></html>
    ";
}

sqlsrv_close($conn);
?>
