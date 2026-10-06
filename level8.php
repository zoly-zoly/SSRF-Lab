<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: file:// protocol.
    // The server uses file_get_contents() on the user-supplied URL.
    // It fails to restrict the protocol, allowing the use of file:// to read local system files.
    
    // Simple blacklist for 'http' to encourage 'file' use
    if (strpos($url, 'http') === 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    } else {
        // Fallback to native PHP file fetching
        $response = @file_get_contents($url);
    }
    
    if ($response === false) { $error = "Error: Unable to fetch resource."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level 8: High | SSRF Lab</title>
    <style>
        body { font-family: sans-serif; background-color: #0f172a; color: #f1f5f9; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 650px; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-top: 5px solid #ef4444; }
        h1 { color: #ef4444; margin-top: 0; }
        pre { background: #0f172a; padding: 15px; border-radius: 6px; overflow-x: auto; border: 1px solid #334155; }
        .btn { display: inline-block; background-color: #ef4444; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 15px; border: none; cursor: pointer; }
        .success-box { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 15px; border-radius: 6px; color: #34d399; margin-top: 20px; text-align: left; word-break: break-all; white-space: pre-wrap; font-family: monospace; font-size: 0.9rem;}
        a.back { color: #94a3b8; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Level 8: High — Local File Read (file://)</h1>
        <p>SSRF isn't just about internal HTTP servers. Many backend functions (like <code>file_get_contents</code> in PHP) support multiple protocols. If the protocol is not restricted to <code>http/https</code>, you can use <code>file://</code> to read local files from the server.</p>
        <h3>Source Code:</h3>
        <pre><code>// VULNERABLE: Direct use of file_get_contents on user input
$content = file_get_contents($_GET['url']);</code></pre>
        <h3>Your Goal:</h3>
        <p>Read the sensitive server file <code>/etc/passwd</code> (simulated as <code>../../../../etc/passwd</code> or direct <code>file:///etc/passwd</code> if permissions allow).</p>
        <form action="level8.php" method="GET">
            <input type="text" name="url" placeholder="file:///etc/passwd" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
            <input type="submit" value="Read File" class="btn">
        </form>
        <?php if (!empty($response)): ?>
            <h3>File Content:</h3>
            <div class="success-box"><?php echo htmlspecialchars($response); ?></div>
        <?php endif; ?>
        <br><a href="index.php" class="back">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
