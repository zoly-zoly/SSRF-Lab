<?php
// SERVER-SIDE REQUEST FORGERY (SSRF) LAB HELPER & INTERNAL SERVICE SIMULATOR

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$lab_root = $base_url . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

// Simulated internal secret tokens & mock services
if (!isset($_SESSION['ssrf_internal_db'])) {
    $_SESSION['ssrf_internal_db'] = [
        'cloud_metadata' => [
            'ami_id' => 'ami-0c55b159cbfafe1f0',
            'instance_id' => 'i-09f1a2b3c4d5e6f7a',
            'iam_role' => 'EC2-Internal-Admin-Role',
            'security_credentials' => [
                'AccessKeyId' => 'ASIAIOSFODNN7EXAMPLEKEY',
                'SecretAccessKey' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
                'Token' => 'AQoDYXdzEJr1EXAMPLE...[TRUNCATED_SESSION_TOKEN]'
            ]
        ],
        'admin_panel' => [
            'status' => 'Internal Administration Node',
            'maintenance_mode' => false,
            'internal_flag' => 'FLAG{SSRF_MASTER_LEVEL_COMPLETED_SUCCESS}',
            'db_backup_path' => '/var/backup/database_prod.dump'
        ],
        'blind_logs' => []
    ];
}

// Helper to log requests for Blind SSRF (Level 6)
if (isset($_GET['log_blind_hit'])) {
    $hit_info = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'query' => $_SERVER['QUERY_STRING'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Internal Scraper'
    ];
    $_SESSION['ssrf_internal_db']['blind_logs'][] = $hit_info;
    header('Content-Type: application/json');
    echo json_encode(['status' => 'logged', 'hit' => $hit_info]);
    exit();
}

// Helper to reset the lab
if (isset($_GET['reset_ssrf'])) {
    unset($_SESSION['ssrf_internal_db']);
    header("Location: index.php");
    exit();
}
?>