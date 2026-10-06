<?php
require_once 'session_helper.php';
header("Content-Type: text/plain");

// This file simulates a restricted internal API that is NOT accessible from the outside world.
// In the lab, we "simulate" this by checking if the request came from the server itself.
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'admin_status':
        echo "========================================\n";
        echo "🔐 INTERNAL ADMIN STATUS REPORT\n";
        echo "========================================\n";
        echo "System Uptime: 242 days\n";
        echo "Active Sessions: 4\n";
        echo "Internal Flag: SSRF_GOD_LEVEL_99\n";
        echo "Admin DB Password: SuperSecretInternalPassword123\n";
        break;
        
    case 'metadata':
        echo "========================================\n";
        echo "☁️ CLOUD METADATA SERVICE (v1.0)\n";
        echo "========================================\n";
        echo "Instance ID: i-0abcdef1234567890\n";
        echo "IAM Role: web-app-production-role\n";
        echo "AccessKeyId: ASIAYEXAMPLEACCESSKEY\n";
        echo "SecretAccessKey: wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY\n";
        echo "Token: IQoJb3JpZ2luX2VjEEMaCXVzLWVhc3QtMSJHMEUCIQDt1F2v1Y...\n";
        break;

    default:
        echo "403 Forbidden: Internal access only.";
        break;
}
?>