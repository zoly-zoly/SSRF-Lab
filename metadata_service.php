<?php
// MOCK CLOUD METADATA SERVICE (Simulates AWS / GCP metadata IP 169.254.169.254)
header("Content-Type: application/json");

$response = [
    "instance_id" => "i-09f8e7d6c5b4a3211",
    "ami_id" => "ami-0c55b159cbfafe1f0",
    "instance_type" => "c5.2xlarge",
    "iam" => [
        "role" => "Production-Deployer-Role",
        "security-credentials" => [
            "Production-Deployer-Role" => [
                "Code" => "Success",
                "LastUpdated" => "2026-03-30T12:00:00Z",
                "Type" => "AWS-HMAC",
                "AccessKeyId" => "AKIAIOSFODNN7EXAMPLE",
                "SecretAccessKey" => "wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY",
                "Token" => "IQoJb3JpZ2luX2VjEEMaCXVzLWVhc3QtMSJHMEUCIQDt1F2v1Y...[MOCK_TEMPORARY_TOKEN_ACTIVE]"
            ]
        ]
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>