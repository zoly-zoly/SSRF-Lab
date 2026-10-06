<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Open Redirect Chaining.
    // The developer only allows 'trusted.com' as the initial host.
    // However, if trusted.com has an Open Redirect, the SSRF client (cURL)
    // will follow it to any destination if CURLOPT_FOLLOWLOCATION is enabled.
    
    $host = parse_url($url, PHP_URL_HOST);
    if ($host === 'trusted.com') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // FOLLOWS REDIRECTS!
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
    <title>Level 5: High | SSRF Lab</title>
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
        <h1>Level 5: High — Open Redirect Chaining</h1>
        <?php if (!empty($error)): ?><div class="alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <p>The server has a strict whitelist that only permits requests to <code>trusted.com</code>. However, the site <code>trusted.com</code> (simulated here as <code>localhost.trusted.com</code>) has an Open Redirect vulnerability.</p>
        <h3>Source Code:</h3>
        <pre><code>if ($host === 'trusted.com') {
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $res = curl_exec($ch);
}</code></pre>
        <h3>Your Goal:</h3>
        <p>Access the internal admin API. Chain the Open Redirect from the first lab! <br>
        <strong>Mock Open Redirect:</strong> <code>http://localhost:8080/open-redirect-lab/level1.php?redirect=...</code><br>
        <em>Note: For this lab, assume <code>localhost.trusted.com</code> is a trusted domain with a redirector.</em></p>
        <p>Payload: <code>http://localhost.trusted.com:8080/open-redirect-lab/level1.php?redirect=http://localhost:8080/ssrf-lab/internal_api.php?action=admin_status</code></p>
        <form action="level5.php" method="GET">
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
