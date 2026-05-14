<?php
// ============================================================
//  db_config.php — Azure SQL Database Connection Settings
//  ⚠️  Replace the values below with YOUR Azure SQL details!
// ============================================================

// 1. Go to portal.azure.com → Your SQL Server → Overview
// 2. Copy the "Server name" (looks like: yourserver.database.windows.net)
define('DB_SERVER',   'YOUR_SERVER.database.windows.net');

// 3. The name of your database (created in Azure portal)
define('DB_DATABASE', 'EventRegistrationDB');

// 4. Your SQL Server admin login (set when you created the server)
define('DB_USERNAME', 'YOUR_ADMIN_USERNAME');

// 5. Your SQL Server admin password
define('DB_PASSWORD', 'YOUR_STRONG_PASSWORD!');

// ============================================================
//  DO NOT share this file publicly or push to GitHub!
//  Add db_config.php to your .gitignore file.
// ============================================================

// Build the connection options array (used by sqlsrv_connect)
$connectionOptions = [
    "Database"                  => DB_DATABASE,
    "Uid"                       => DB_USERNAME,
    "PWD"                       => DB_PASSWORD,
    "Encrypt"                   => true,       // Azure SQL always uses encryption
    "TrustServerCertificate"    => false,
    "LoginTimeout"              => 30,
    "CharacterSet"              => "UTF-8",
];

// Create the connection
$conn = sqlsrv_connect(DB_SERVER, $connectionOptions);

// Stop and show an error if the connection failed
if ($conn === false) {
    die(
        "<h2 style='color:red;font-family:sans-serif'>⚠️ Database Connection Failed</h2>" .
        "<pre>" . print_r(sqlsrv_errors(), true) . "</pre>" .
        "<p>Check your db_config.php settings and make sure your IP is allowed in Azure firewall rules.</p>"
    );
}
?>
