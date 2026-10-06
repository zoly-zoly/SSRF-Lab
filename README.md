# 🛡️ SSRF Masterclass Suite (10 Levels)

> **⚠️ WARNING: EDUCATIONAL PURPOSES ONLY**
> This repository contains intentionally vulnerable code designed for security training and educational purposes. **DO NOT** deploy this code in a production environment. Hosting this environment on a public-facing server without strict isolation (e.g., containerization, network segmentation) is dangerous and can lead to server compromise.

---

[![Security Level: Educational](https://img.shields.io/badge/Security_Level-Educational-blue.svg)]()
[![PHP](https://img.shields.io/badge/Language-PHP-777bb4.svg)]()

Welcome to the **SSRF Masterclass Lab Suite**. This comprehensive, self-contained training arena contains 10 levels of increasing complexity, taking you from fundamental fetch utilities to advanced blacklist bypasses, DNS rebinding, cloud metadata exfiltration, and internal API chaining.

---

## 🎯 Lab Overview

| Level | Name | Primary Vulnerability |
| :--- | :--- | :--- |
| 🟢 | **Level 1** | Basic Unrestricted SSRF |
| 🟢 | **Level 2** | Blacklist Bypass (Decimal/Hex) |
| 🟡 | **Level 3** | Whitelist Suffix Match Flaw |
| 🟡 | **Level 4** | Userinfo Delimiter Confusion (@) |
| 🔴 | **Level 5** | Open Redirect Chaining |
| 🔴 | **Level 6** | DNS Pinning / TOCTOU |
| 🔴 | **Level 7** | Blind SSRF exfiltration |
| 🔴 | **Level 8** | Local File Read (file://) |
| 🟣 | **Level 9** | Cloud Metadata Exfiltration |
| 🟣 | **Level 10** | Internal Admin API Chain |

---

## 🚀 Quick Start

Ensure you have [PHP](https://www.php.net/) installed on your system.

```bash
# 1. Clone the repository (or copy the files)
git clone https://github.com/zoly-zoly/ssrf-lab.git
cd ssrf-lab

# 2. Start the local server
# (You can use any available port)
php -S localhost:8080
```

👉 **Access the lab at:** `http://localhost:8080`

> 💡 **IMPORTANT NOTE ON EXPLOIT PAYLOADS:**
> Many payloads in this lab reference `localhost:8080`. If you run your server on a different port (e.g., 8000), remember to update the port number in your exploit URLs!

---

## 📓 Lab Walkthrough & Solutions

### 🟢 LEVEL 1: Basic Unrestricted SSRF
*   **Vulnerability:** Raw URL fetch with no filters.
*   **Goal:** Access `internal_api.php?action=admin_status`.

### 🟢 LEVEL 2: Blacklist Bypass
*   **Vulnerability:** Filter blocks `127.0.0.1` but fails on decimal/hex IPs.
*   **Bypass:** Use `http://2130706433:8080/...` or `http://0x7f000001:8080/...`.

### 🟡 LEVEL 3: Whitelist Suffix Match
*   **Vulnerability:** Regex only checks if host *ends with* `trusted.com`.
*   **Bypass:** Use `http://localhost.trusted.com:8080/...`.

### 🟡 LEVEL 4: Userinfo Delimiter Confusion
*   **Vulnerability:** Parser discrepancy with the `@` symbol.
*   **Bypass:** `http://google.com@localhost:8080/...`.

### 🔴 LEVEL 5: Open Redirect Chaining
*   **Vulnerability:** Strict whitelist bypassed via a redirector on the trusted domain.
*   **Bypass:** Point to a local open redirect (from your first lab!) that redirects to `localhost:8080`.

### 🔴 LEVEL 6: DNS Pinning / TOCTOU
*   **Vulnerability:** Server resolves for check but cURL re-resolves for fetch.
*   **Bypass:** Use a DNS rebinding service like `rbndr.us`.

### 🔴 LEVEL 7: Blind SSRF
*   **Vulnerability:** No body output, but timing/external hits confirm the bug.
*   **Verification:** Use an external collaborator or webhook.site listener.

### 🔴 LEVEL 8: Local File Read (file://)
*   **Vulnerability:** Server supports `file://` protocol in addition to `http://`.
*   **Exploit:** `file:///etc/passwd`.

### 🟣 LEVEL 9: Cloud Metadata Exfiltration
*   **Vulnerability:** Blocks specific IP string but follows redirects.
*   **Bypass:** Use decimal IP `http://2852039166/` to steal simulated IAM keys.

### 🟣 LEVEL 10: Internal Admin API Chain
*   **Vulnerability:** Direct access to internal-only administration endpoints.
*   **Exploit:** Chain SSRF to hit `internal_api.php?action=delete_user&user=bob`.

---

## 🛡️ Security Policy & Disclaimer
Please see [SECURITY.md](SECURITY.md) for licensing and ethical usage instructions.

---
*Created with ❤️ by **Zoly** for Bug Bounty Mastery.*
