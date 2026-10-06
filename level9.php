<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
$response = '';
$error = '';

if (!empty($url)) {
    // VULNERABLE: Cloud Metadata Exfiltration.
    // The server attempts to block standard metadata IPs (169.254.169.254).
    // However, it performs a weak string comparison and fails to account for 
    // redirects or alternative IP representations.
    
    if (strpos($url, '169.254.169.254') !== false) {
        $error = "Access Denied: Requests to the cloud metadata IP are strictly blocked!";
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // FOLLOWS REDIRECTS!
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
    <title>Level 9: Critical | SSRF Lab</title>
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
        <h1>Level 9: Critical — Cloud Metadata Exfiltration</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <p>In modern cloud environments (AWS, GCP, Azure), instances can access a local metadata service at <code>http://169.254.169.254/</code> to retrieve identity information and temporary security credentials. Exfiltrating these credentials via SSRF is a high-impact "Critical" finding as it often leads to complete cloud account takeover.</p>
        
        <h3>Source Code Snippet:</h3>
        <pre><code>&lt;?php
$url = $_GET['url'];

// Weak Blacklist Check
if (strpos($url, '169.254.169.254') !== false) {
    die("Blocked!");
}

// Executes fetch with redirect following enabled
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
?&gt;</code></pre>

        <h3>Your Goal:</h3>
        <p>Exfiltrate the IAM security credentials from the simulated metadata service. Since the IP is blocked in the string, you must bypass the filter.</p>
        <p><strong>The Bypass:</strong> Use a **decimal representation** of the IP (<code>http://2852039166/</code>) or chain it through a local **Open Redirect** (like Level 1 of your Open Redirect lab) to point to <code>http://localhost:8080/ssrf-lab/internal_metadata.php</code>.</p>

        <form action="level9.php" method="GET" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #334155;">
            <label for="url">URL to Fetch:</label><br>
            <input type="text" id="url" name="url" placeholder="http://example.com" style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white;" required><br>
            <input type="submit" value="Exfiltrate Metadata" class="btn">
        </form>

        <?php if (!empty($response)): ?>
            <h3>Scraped Metadata Output:</h3>
            <div class="success-box"><?php echo htmlspecialchars($response); ?></div>
        <?php endif; ?>

        <br>
        <a href="index.php" class="back">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
