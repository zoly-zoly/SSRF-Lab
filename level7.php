<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$msg = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Blind SSRF.
    // The server fetches the URL but DOES NOT return the body.
    // However, it might return a timing difference or HTTP status.
    
    $start = microtime(true);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $end = microtime(true);
    $time = round(($end - $start) * 1000, 2);
    
    if (curl_errno($ch)) {
        $error = "CURL Error: Request failed.";
    } else {
        $msg = "Success: Resource fetched in {$time}ms. (Response body is hidden for security!)";
    }
    curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level 7: High | SSRF Lab</title>
    <style>
        body { font-family: sans-serif; background-color: #0f172a; color: #f1f5f9; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 650px; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-top: 5px solid #ef4444; }
        h1 { color: #ef4444; margin-top: 0; }
        pre { background: #0f172a; padding: 15px; border-radius: 6px; overflow-x: auto; border: 1px solid #334155; }
        .btn { display: inline-block; background-color: #ef4444; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 15px; border: none; cursor: pointer; }
        .alert-success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid #10b981; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        a.back { color: #94a3b8; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Level 7: High — Blind SSRF</h1>
        <?php if (!empty($msg)): ?><div class="alert-success"><?php echo $msg; ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <p>The server fetches the URL but hides the output. This is a <strong>Blind SSRF</strong>. You can still confirm the vulnerability by using a service you control (like Webhook.site) or by measuring the time it takes for the server to respond.</p>
        <h3>Source Code:</h3>
        <pre><code>curl_exec($ch);
// echo "Fetched!"; (No body returned)</code></pre>
        <h3>Your Goal:</h3>
        <p>Confirm the SSRF by pointing the server to an external listener or by observing a timing difference between an open port (fast) and a closed port (timeout).</p>
        <form action="level7.php" method="GET">
            <input type="text" name="url" placeholder="http://your-webhook-id.site" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
            <input type="submit" value="Fetch (Blind)" class="btn">
        </form>
        <br><a href="index.php" class="back">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
