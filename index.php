<?php
require_once 'session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛡️ SSRF Masterclass Lab Suite (10 Levels)</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --accent-low: #10b981;
            --accent-medium: #f59e0b;
            --accent-high: #ef4444;
            --accent-critical: #8b5cf6;
            --text-color: #f1f5f9;
            --text-muted: #94a3b8;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            font-size: 2.6rem;
            margin-bottom: 5px;
            text-align: center;
            letter-spacing: -0.5px;
        }
        p.subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 25px;
            text-align: center;
            max-width: 700px;
            line-height: 1.6;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            max-width: 1300px;
            width: 100%;
        }
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            border-top: 5px solid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);
        }
        .card.low { border-color: var(--accent-low); }
        .card.medium { border-color: var(--accent-medium); }
        .card.high { border-color: var(--accent-high); }
        .card.critical { border-color: var(--accent-critical); }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: bold;
            border-radius: 20px;
            margin-bottom: 15px;
            align-self: flex-start;
            text-transform: uppercase;
        }
        .card.low .badge { background-color: rgba(16, 185, 129, 0.15); color: var(--accent-low); }
        .card.medium .badge { background-color: rgba(245, 158, 11, 0.15); color: var(--accent-medium); }
        .card.high .badge { background-color: rgba(239, 68, 68, 0.15); color: var(--accent-high); }
        .card.critical .badge { background-color: rgba(139, 92, 246, 0.15); color: var(--accent-critical); }

        h2 {
            margin: 0 0 10px 0;
            font-size: 1.3rem;
            line-height: 1.3;
        }
        .desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.2s;
            font-size: 0.9rem;
        }
        .card.low .btn { background-color: var(--accent-low); }
        .card.low .btn:hover { background-color: #059669; }
        .card.medium .btn { background-color: var(--accent-medium); }
        .card.medium .btn:hover { background-color: #d97706; }
        .card.high .btn { background-color: var(--accent-high); }
        .card.high .btn:hover { background-color: #dc2626; }
        .card.critical .btn { background-color: var(--accent-critical); }
        .card.critical .btn:hover { background-color: #7c3aed; }
        
        .footer {
            margin-top: 60px;
            color: var(--text-muted);
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>🛡️ SSRF Masterclass Lab Suite</h1>
    <p class="subtitle">Ultimate 10-level training to master Server-Side Request Forgery. Bypass IP filters, exploit parser differentials, exfiltrate cloud metadata, and read internal local files.</p>

    <div class="grid">
        <!-- LEVEL 1 -->
        <div class="card low">
            <div>
                <span class="badge">Level 1: Low</span>
                <h2>Unrestricted SSRF</h2>
                <p class="desc">A basic fetch utility with no validation. Force the server to request its own internal admin panel.</p>
            </div>
            <a href="level1.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 2 -->
        <div class="card low">
            <div>
                <span class="badge">Level 2: Low</span>
                <h2>Localhost Blacklist</h2>
                <p class="desc">The developer blocked '127.0.0.1' and 'localhost'. Bypass this check using alternative IP notations like Decimal or Octal.</p>
            </div>
            <a href="level2.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 3 -->
        <div class="card medium">
            <div>
                <span class="badge">Level 3: Medium</span>
                <h2>Userinfo Delimiter Confusion</h2>
                <p class="desc">Bypass whitelist-based filters using the '@' symbol and authority component discrepancies between parsers.</p>
            </div>
            <a href="level3.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 4 -->
        <div class="card medium">
            <div>
                <span class="badge">Level 4: Medium</span>
                <h2>Open Redirect Chaining</h2>
                <p class="desc">Chain a local Open Redirect vulnerability to bypass SSRF protection filters on the primary fetch utility.</p>
            </div>
            <a href="level4.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 5 -->
        <div class="card high">
            <div>
                <span class="badge">Level 5: High</span>
                <h2>DNS / TOCTOU Simulation</h2>
                <p class="desc">The server checks the IP address before fetching. Bypass this logic by exploiting DNS resolution timing discrepancies.</p>
            </div>
            <a href="level5.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 6 -->
        <div class="card high">
            <div>
                <span class="badge">Level 6: High</span>
                <h2>Blind SSRF via Webhook</h2>
                <p class="desc">No data is returned from the request. Confirm the vulnerability by observing server response times or external interactions.</p>
            </div>
            <a href="level6.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 7 -->
        <div class="card high">
            <div>
                <span class="badge">Level 7: High</span>
                <h2>Arbitrary File Read (file://)</h2>
                <p class="desc">The fetch utility allows protocols other than HTTP. Use the 'file://' scheme to read sensitive local system files like /etc/passwd.</p>
            </div>
            <a href="level7.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 8 -->
        <div class="card high">
            <div>
                <span class="badge">Level 8: High</span>
                <h2>Cloud Metadata Exfiltration</h2>
                <p class="desc">Simulate a request to the cloud metadata service (169.254.169.254) to steal IAM credentials and instance roles.</p>
            </div>
            <a href="level8.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 9 -->
        <div class="card critical">
            <div>
                <span class="badge">Level 9: Critical</span>
                <h2>Image Scraper SSRF</h2>
                <p class="desc">A utility that "validates" images but follows redirects. Bypass strict file-extension checks to hit internal APIs.</p>
            </div>
            <a href="level9.php" class="btn">Enter Lab</a>
        </div>

        <!-- LEVEL 10 -->
        <div class="card critical">
            <div>
                <span class="badge">Level 10: Critical</span>
                <h2>SSRF to Admin API Execution</h2>
                <p class="desc">The final boss. Chain SSRF to execute sensitive state-changing actions on a internal-only protected API endpoint.</p>
            </div>
            <a href="level10.php" class="btn">Enter Lab</a>
        </div>
    </div>

    <div class="footer">
        <p>Created by <strong>Zoly</strong> for Security Mastery. Run locally with <code>php -S localhost:8080</code> or your local server configuration.</p>
    </div>

</body>
</html>
