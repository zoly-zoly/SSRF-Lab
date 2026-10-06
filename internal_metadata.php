<?php
// SIMULATED CLOUD METADATA ENDPOINT
header("Content-Type: text/plain");

// SSRF targets often aim for metadata services like 169.254.169.254
// Since we are on localhost, we'll simulate the response here.

echo "================================================\n";
echo "☁️  CLOUD METADATA SERVICE (SIMULATED) ☁️\n";
echo "================================================\n";
echo "INSTANCE_ID: i-0abc123def456\n";
echo "LOCAL_HOSTNAME: internal-node-01.local\n";
echo "PUBLIC_IPV4: 52.12.34.56\n";
echo "SECURITY_GROUPS: internal-only-sg\n";
echo "\n";
echo "--- IAM SECURITY CREDENTIALS ---\n";
echo "ROLE_NAME: SSRF-Lab-Admin-Role\n";
echo "ACCESS_KEY_ID: AKIAIOSFODNN7EXAMPLE\n";
echo "SECRET_ACCESS_KEY: wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY\n";
echo "TOKEN: FwoGZXIvYXdzEAAA...[REDACTED]...\n";
echo "================================================\n";
echo "🎉 SUCCESS: You have exfiltrated Cloud Metadata!";
?>
