<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Whitelist Suffix Match Flaw.
    // The developer only allows URLs that end with 'trusted.com'.
    if (preg_match('/trusted\.com$/i', parse_url($url, PHP_URL_HOST))) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        if (curl_errno($ch)) { $error = "CURL Error: " . curl_error($ch); }
        curl_close($ch);
    } else {
        $error = "Access Denied: Host must be 'trusted.com'!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level 3: Medium | SSRF Lab</title>
    <style>
        body { font-family: sans-serif; background-color: #0f172a; color: #f1f5f9; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 650px; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-top: 5px solid #f59e0b; }
        h1 { color: #f59e0b; margin-top: 0; }
        pre { background: #0f172a; padding: 15px; border-radius: 6px; overflow-x: auto; border: 1px solid #334155; }
        .btn { display: inline-block; background-color: #f59e0b; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 15px; border: none; cursor: pointer; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .success-box { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 15px; border-radius: 6px; color: #34d399; margin-top: 20px; text-align: left; word-break: break-all; white-space: pre-wrap; font-family: monospace; font-size: 0.9rem;}
        a.back { color: #94a3b8; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Level 3: Medium — Whitelist Suffix Match</h1>
        <?php if (!empty($error)): ?><div class="alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <p>The developer implements a whitelist. However, the regex check only verifies that the host <strong>ends with</strong> <code>trusted.com</code>. This allows an attacker to register a subdomain or use a host that naturally ends with that string.</p>
        <h3>Source Code:</h3>
        <pre><code>if (preg_match('/trusted\.com$/i', parse_url($url, PHP_URL_HOST))) {
    // Execute Fetch
}</code></pre>
        <h3>Your Goal:</h3>
        <p>Bypass the whitelist. Use a host like <code>localhost.trusted.com</code> (if it points to 127.0.0.1) or an attacker-controlled domain like <code>attacker-trusted.com</code>. For this lab, you can mock the host <code>localhost.trusted.com</code> pointing to your local machine.</p>
        <form action="level3.php" method="GET">
            <input type="text" name="url" placeholder="http://localhost.trusted.com:8080/..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
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
