<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Time-of-Check Time-of-Use (TOCTOU) / DNS Pinning.
    // The server resolves the IP for validation, but then re-resolves it during execution.
    // This allows an attacker to use a DNS record that points to a safe IP during check,
    // but a malicious IP (localhost) during execution.
    
    $host = parse_url($url, PHP_URL_HOST);
    $ip = gethostbyname($host); // TIME OF CHECK
    
    if ($ip === '127.0.0.1' || $ip === '0.0.0.0') {
        $error = "Access Denied: Resolved to local IP {$ip}!";
    } else {
        // TIME OF USE: cURL re-resolves the host!
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        if (curl_errno($ch)) { $error = "CURL Error: " . curl_error($ch); }
        curl_close($ch);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level 6: High | SSRF Lab</title>
    <style>
        body { font-family: sans-serif; background-color: #0f172a; color: #f1f5f9; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 650px; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-top: 5px solid #ef4444; }
        h1 { color: #ef4444; margin-top: 0; }
        pre { background: #0f172a; padding: 15px; border-radius: 6px; overflow-x: auto; border: 1px solid #334155; }
        .btn { display: inline-block; background-color: #ef4444; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 15px; border: none; cursor: pointer; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .success-box { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 15px; border-radius: 6px; color: #34d399; margin-top: 20px; text-align: left; word-break: break-all; white-space: pre-wrap; font-family: monospace; font-size: 0.9rem;}
        a.back { color: #94a3b8; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Level 6: High — DNS Pinning / TOCTOU</h1>
        <?php if (!empty($error)): ?><div class="alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <p>The server is smart: it resolves the domain to an IP address and blocks it if it's <code>127.0.0.1</code>. However, the <code>curl_exec</code> call re-resolves the domain. An attacker can use a "DNS Rebinding" service to return a safe IP first, and a local IP second.</p>
        <h3>Source Code:</h3>
        <pre><code>$ip = gethostbyname($host);
if ($ip !== '127.0.0.1') {
    // VULNERABLE: cURL re-resolves $url!
    curl_exec($ch);
}</code></pre>
        <h3>Your Goal:</h3>
        <p>Use a DNS rebinding tool (like <code>rbndr.us</code>) to provide a domain that fluctuates between a public IP and <code>127.0.0.1</code>.</p>
        <form action="level6.php" method="GET">
            <input type="text" name="url" placeholder="http://7f000001.c0a80101.rbndr.us:8080/..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
            <input type="submit" value="Fetch Resource" class="btn">
        </form>
        <?php if (!empty($response)): ?>
            <h3>Server Response:</h3>
            <div class="success-box"><?php echo htmlspecialchars($response); ?></div>
        <?php endif; ?>
        <br><a href="index.php" class="back">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
