<?php
// MOCK OPEN REDIRECT ENDPOINT FOR SSRF CHAINING
$target = isset($_GET['url']) ? $_GET['url'] : '';
if (!empty($target)) {
    header("Location: " . $target);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Open Redirect</title></head>
<body>
    <p>Usage: <code>level4_redirect.php?url=destination</code></p>
</body>
</html>
