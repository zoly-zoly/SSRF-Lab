# SSRF Deep-Dive: Theoretical Mastery & Internal Network Exploitation

Welcome to the official Wiki for the **SSRF Masterclass Lab Suite**. This wiki is designed to be an advanced architectural reference guide, shifting the focus from "solving the lab" to **why the underlying network logic fails** and how to architect bulletproof, industry-standard SSRF defenses.

---

## 🔬 Part 1: The Core Mechanism of Server-Side Request Forgery

SSRF occurs when a backend server is tricked into initiating an HTTP (or other protocol) request to an arbitrary destination, usually an internal resource that is not intended to be public.

### 1. The Trust Boundary
Most organizations have a "hard shell, soft center" security model:
*   **External Boundary:** Strict authentication, WAFs, and firewalls.
*   **Internal Network:** Trust-based. Services like internal APIs, databases, and metadata endpoints often have **no authentication** because they assume only trusted internal servers can reach them.

SSRF turns a trusted server into a **malicious proxy**, allowing an attacker to "ride" the server's internal network position to bypass external firewalls.

### 2. Beyond HTTP: Multiple Protocol Support
SSRF is often limited to HTTP by developers, but many backend libraries (like cURL or Python's `requests`) support a wide range of protocols:
*   `file://`: Read local system files.
*   `dict://`: Interact with dictionary servers or internal memory caches.
*   `gopher://`: A legacy protocol often used to interact with internal services like Redis or MySQL to perform RCE.

---

## 🎯 Part 2: Advanced SSRF Bypass Archetypes

### 1. IP Representation Bypasses (Level 2)
Blacklists blocking `127.0.0.1` are trivial to bypass because network stacks interpret IPs in multiple formats:
*   **Decimal:** `2130706433`
*   **Hexadecimal:** `0x7f000001`
*   **Octal:** `0177.000.000.001`
*   **Shortened:** `127.1` (Browsers and some OSs expand this to 127.0.0.1)

### 2. Parser Differentials & Delimiters (Level 4)
The backend validator and the execution client (cURL) might use different URL parsing logic.
*   **Delimiter Confusion:** Using `http://expected-domain@malicious-domain.com`. 
*   A weak parser might see `expected-domain` as the host (for validation), while the actual client sees it as a **username** and connects to `malicious-domain.com`.

### 3. DNS Rebinding / TOCTOU (Level 6)
**Time-of-Check Time-of-Use** is a fundamental logic flaw.
1.  The application resolves `attacker.com` -> `1.2.3.4` (Safe). **Validation Passes.**
2.  The application then calls `curl_exec("http://attacker.com")`.
3.  cURL performs its own DNS lookup. The attacker's DNS server now returns `127.0.0.1`.
4.  The server fetches its own internal resources, believing it is still talking to the safe IP.

---

## 🛡️ Part 3: Defensive Architecture (The Bulletproof Solution)

To eliminate SSRF, you must move away from "blacklisting" and implement a **Strict Whitelist and Network Isolation** model.

```
                  ┌────────────────────────────────────────┐
                  │          Outgoing Fetch Request        │
                  └───────────────────┬────────────────────┘
                                      │
                                      ▼
                        [ Strict Protocol Whitelist ]
                             (Allow ONLY http/https)
                                      │
                                      ▼
                        [ FQDN Whitelist / Sandbox ]
                       (Allow ONLY approved domains)
                                      │
                                      ▼
                        [ Network-Level Isolation ]
                       (Server cannot reach 127.0.0.1
                        or 169.254.169.254 via local FW)
                                      │
                                      ▼
                          [ Execute Scoped Fetch ]
```

### 1. Use a Dedicated Proxy / Sandbox
Never allow your primary application server to make arbitrary outgoing requests. Route all outgoing "fetch" requests through a dedicated **Outbound Proxy** that has:
*   No access to the internal network.
*   Strict DNS logging and filtering.

### 2. Disable Protocol Handlers
When using cURL, explicitly disable all protocols except the ones you need:
```php
curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
```

### 3. Block Local Network Access via Firewall
The most effective defense is at the **Network Layer**. Configure your server's firewall (iptables/NSG) to explicitly drop all outgoing traffic destined for internal IP ranges (`10.0.0.0/8`, `172.16.0.0/12`, `192.168.0.0/16`) and the metadata IP (`169.254.169.254`).

---
*Documentation curated by **Zoly** for the advancement of secure application architecture.*
