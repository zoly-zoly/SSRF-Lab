<?php
require_once 'session_helper.php';

// Record the inbound request into the session logger for Blind SSRF verification
$timestamp = date('H:i:s');
$client_ip = $_SERVER['REMOTE_ADDR'];
$query = $_SERVER['QUERY_STRING'];
$headers = getallheaders();

$log_entry = [
    'time' => $timestamp,
    'ip' => $client_ip,
    'query' => $query,
    'user_agent' => isset($headers['User-Agent']) ? $headers['User-Agent'] : 'Unknown'
];

$_SESSION['ssrf_internal_logs'][] = $log_entry;

echo "LOGGED_SUCCESS";
?>