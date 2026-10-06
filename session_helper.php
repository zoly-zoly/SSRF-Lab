<?php
// MOCK INTERNAL NETWORK HELPER FOR LOCAL SSRF LAB
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$lab_root = $base_url . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

// Mock Internal Targets
$internal_targets = [
    'admin_panel' => [
        'url' => $lab_root . '/internal_api.php?action=admin_status',
        'description' => 'Internal Administrative Dashboard'
    ],
    'cloud_metadata' => [
        'url' => $lab_root . '/internal_api.php?action=metadata',
        'description' => 'Simulated Cloud Instance Metadata Service'
    ]
];

// Helper to reset the lab
if (isset($_GET['reset_session'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>