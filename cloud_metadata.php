<?php
header("Content-Type: application/json");
?>
{
  "Code": "Success",
  "LastUpdated": "2026-10-04T12:00:00Z",
  "Type": "AWS-HMAC",
  "AccessKeyId": "ASIAIOSFODNN7EXAMPLE",
  "SecretAccessKey": "wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY",
  "Token": "IQoJb3JpZ2luX2VjEEMaCXVzLWVhc3QtMSJHMEUCIQDt1F2v1Y...[AWS_SESSION_TOKEN_SIMULATED]",
  "Expiration": "2026-10-04T18:00:00Z",
  "InstanceProfileArn": "arn:aws:iam::123456789012:instance-profile/prod-web-scraper",
  "RoleArn": "arn:aws:iam::123456789012:role/prod-admin-role",
  "Flag": "FLAG{SSRF_CLOUD_METADATA_EXFILTRATED_SUCCESSFULLY}"
}
