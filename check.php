<?php
if (function_exists('sqlsrv_connect')) {
    echo "✅ SQLSRV is working!";
} else {
    echo "❌ SQLSRV still not found.";
}
?>