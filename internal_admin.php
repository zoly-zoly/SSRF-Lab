<?php
require_once 'session_helper.php';

// Simulate internal firewall: Only allows access if the request originates from localhost/127.0.0.1
$client_ip = $_SERVER['REMOTE_ADDR'];

// Return plain text or styled banner
if ($client_ip !== '127.0.0.1' && $client_ip !== '::1') {
    http_response_code(403);
    echo "403 Forbidden: Access to internal admin panel is strictly restricted to local loopback (127.0.0.1). Your IP: " . htmlspecialchars($client_ip);
    exit();
}
?>
============================================================
🚨 INTERNAL ADMINISTRATION CONSOLE (RESTRICTED ACCESS)
============================================================

STATUS: Authenticated via Loopback (127.0.0.1)
SERVER_HOSTNAME: prod-internal-core-01.securesite.local
KERNEL_VERSION: Linux 6.8.0-security-hardened

--- SENSITIVE SYSTEM METRICS ---
CPU_LOAD: 12% | MEMORY_USAGE: 4.2GB / 32GB
ACTIVE_SESSIONS: 42
SYSTEM_FLAG: FLAG{SSRF_INTERNAL_ADMIN_LOOPBACK_BYPASS}

--- INTERNAL SECRETS & KEYS ---
DATABASE_ROOT_PASSWORD: RootMasterPassword_99x#_DB
BACKUP_ENCRYPTION_KEY: aes-256-cbc:b9a8f7c6e5d4c3b2a1...

============================================================
🎉 SUCCESS: You reached the restricted internal admin panel!
============================================================
