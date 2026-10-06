<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Internal Admin API Chain.
    // The server fetches content but fails to restrict requests to internal administration endpoints.
    // It only allows 'http://' or 'https://' schemes, assuming this is sufficient.
    
    $parsed = parse_url($url);
    $scheme = isset($parsed['scheme']) ? $parsed['scheme'] : '';
    
    if ($scheme !== 'http' && $scheme !== 'https') {
        $error = "Access Denied: Only HTTP/HTTPS protocols are permitted!";
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = "CURL Error: " . curl_error($ch);
        }
        curl_close($ch);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level 10: Critical | SSRF Lab</title>
    <style>
        body { font-family: sans-serif; background-color: #0f172a; color: #f1f5f9; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 700px; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-top: 5px solid #8b5cf6; }
        h1 { color: #8b5cf6; margin-top: 0; }
        code { background: #0f172a; padding: 2px 6px; border-radius: 4px; color: #f43f5e; font-family: monospace; }
        pre { background: #0f172a; padding: 15px; border-radius: 6px; overflow-x: auto; border: 1px solid #334155; }
        .btn { display: inline-block; background-color: #8b5cf6; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 15px; border: none; cursor: pointer; }
        .btn:hover { background-color: #7c3aed; }
        a.back { color: #94a3b8; text-decoration: none; display: inline-block; margin-top: 20px; }
        a.back:hover { color: #f1f5f9; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid #ef4444; }
        .success-box { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 15px; border-radius: 6px; color: #34d399; margin-top: 20px; text-align: left; word-break: break-all; white-space: pre-wrap; font-family: monospace; font-size: 0.9rem;}
    </style>
</head>
<body>
    <div class="box">
        <h1>Level 10: Critical — Internal Admin API Chain</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <p>The "Final Boss" of SSRF. Many applications sit in the same internal network as high-privilege administrative APIs that have no authentication because they "trust the internal network." If an attacker can force the server to send a request to these APIs, they can perform unauthorized actions.</p>
        
        <h3>The Target:</h3>
        <p>There is an internal API endpoint located at <code>http://localhost:8080/ssrf-lab/internal_api.php</code>. It accepts an <code>action</code> parameter. The action <code>delete_user</code> requires a <code>user</code> parameter.</p>

        <h3>Your Goal:</h3>
        <p>Chain SSRF to delete the user <code>bob</code> from the internal system. </p>
        <p><strong>The Attack:</strong> Construct a URL that forces the server to hit the internal API with the delete command: <br><code>http://localhost:8080/ssrf-lab/internal_api.php?action=delete_user&user=bob</code></p>

        <form action="level10.php" method="GET" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #334155;">
            <label for="url">URL to Fetch:</label><br>
            <input type="text" id="url" name="url" placeholder="http://example.com" style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
            <input type="submit" value="Execute SSRF Action" class="btn">
        </form>

        <?php if (!empty($response)): ?>
            <h3>Internal API Response:</h3>
            <div class="success-box"><?php echo htmlspecialchars($response); ?></div>
            
            <?php if (strpos($response, 'permanently deleted') !== false): ?>
                <div style="background: rgba(139, 92, 246, 0.2); border: 1px solid #8b5cf6; padding: 10px; border-radius: 6px; color: #a78bfa; margin-top: 15px; font-weight: bold; text-align: center;">
                    🔥 CRITICAL SUCCESS: You chained SSRF to execute an unauthorized administrative action!
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <br>
        <a href="index.php" class="back">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
