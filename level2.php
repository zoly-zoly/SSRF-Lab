<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Blacklist bypass.
    // The developer blocks 127.0.0.1 and localhost strings.
    if (strpos($url, '127.0.0.1') !== false || strpos($url, 'localhost') !== false) {
        $error = "Access Denied: Localhost access is strictly prohibited!";
    } else {
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
    <title>Level 2: Low | SSRF Lab</title>
    <style>
        body { font-family: sans-serif; background-color: #0f172a; color: #f1f5f9; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 650px; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-top: 5px solid #10b981; }
        h1 { color: #10b981; margin-top: 0; }
        pre { background: #0f172a; padding: 15px; border-radius: 6px; overflow-x: auto; border: 1px solid #334155; }
        .btn { display: inline-block; background-color: #10b981; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 15px; border: none; cursor: pointer; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .success-box { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 15px; border-radius: 6px; color: #34d399; margin-top: 20px; text-align: left; word-break: break-all; white-space: pre-wrap; font-family: monospace; font-size: 0.9rem;}
        a.back { color: #94a3b8; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Level 2: Low — Blacklist Bypass</h1>
        <?php if (!empty($error)): ?><div class="alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <p>The developer added a blacklist to block <code>127.0.0.1</code> and <code>localhost</code>. However, IPs have many representations (Decimal, Hex, Octal) that bypass simple string filters.</p>
        <h3>Source Code:</h3>
        <pre><code>if (strpos($url, '127.0.0.1') !== false || strpos($url, 'localhost') !== false) {
    die("Blocked!");
}</code></pre>
        <h3>Your Goal:</h3>
        <p>Access <code>internal_api.php?action=admin_status</code> on the internal server. Use a <strong>Decimal</strong> representation of 127.0.0.1 (<code>2130706433</code>) or <strong>Hex</strong> (<code>0x7f000001</code>).</p>
        <form action="level2.php" method="GET">
            <input type="text" name="url" placeholder="http://2130706433:8080/ssrf-lab/internal_api.php?..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
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
