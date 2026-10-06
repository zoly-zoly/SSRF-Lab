<?php
// OPEN REDIRECT RELAY ENDPOINT
$target = $_GET['url'] ?? '';
if (!empty($target)) {
    header("Location: " . $target);
    exit();
}
echo "Open Redirect Relay active. Usage: ?url=http://...";
?>