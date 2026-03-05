Perfect. I’ve got your **exact goal** now, so I’ll give you a **clean, minimal, correct setup** for:

* **Amazon Linux 2 (EC2)**
* **ECCUBE2 running as `ftpuser`**
* **PHP connects to ClamAV via TCP (3310)**
* **No Docker**
* **No unnecessary logging / on-access / SELinux pain**
* **Stable + production-safe**

This is the **best way** for your case.

---

# 🎯 Architecture (important)

* **clamd runs as system service (root → drops privileges internally)**
* **clamd listens on TCP 127.0.0.1:3310**
* **PHP (ftpuser) only CONNECTS → no file permissions needed**
* **No socket permissions headaches**
* **No on-access scanning**
* **No extra configs**

---

# 1️⃣ Install ClamAV (this IS enough on Amazon Linux 2)

```bash
sudo yum install -y clamav clamd
```

✔️ **No EPEL**
✔️ **No amazon-linux-extras**
✔️ **This is correct for AL2**

---

# 2️⃣ Create runtime user (REQUIRED)

Amazon Linux **does NOT auto-create** it.

```bash
sudo useradd \
  --system \
  --no-create-home \
  --shell /sbin/nologin \
  clamav
```

Verify:

```bash
id clamav
```

---

# 3️⃣ Create required directories

```bash
sudo mkdir -p /var/lib/clamav
sudo chown -R clamav:clamav /var/lib/clamav
sudo chmod 755 /var/lib/clamav
```

⚠️ No log directory yet — we avoid it.

---

# 4️⃣ Minimal `freshclam` config (THIS fixes your errors)

Edit:

```bash
sudo vi /etc/freshclam.conf
```

**Replace ENTIRE FILE with:**

```conf
DatabaseDirectory /var/lib/clamav
Foreground yes
```

That’s it.
❌ No UpdateLogFile
❌ No PID
❌ No permissions drama

---

# 5️⃣ Download virus database (one time)
(sudo chown root:root /etc/freshclam.conf
sudo chmod 644 /etc/freshclam.conf
)
```bash
sudo -u clamav freshclam
```

✔️ Must be run as `clamav`
✔️ This avoids UID/GID mismatch errors

Verify:

```bash
ls -lh /var/lib/clamav
```

You should see:

* main.cvd / daily.cvd / bytecode.cvd

---

# 6️⃣ Configure clamd (TCP only, PHP-friendly)

Edit:

```bash
sudo vi /etc/clamd.d/scan.conf
```

**Replace ENTIRE FILE with:**

```conf
User clamav
Foreground yes

TCPSocket 3310
TCPAddr 127.0.0.1

DatabaseDirectory /var/lib/clamav
```

❌ No LocalSocket
❌ No logs
❌ No OnAccess

This is **intentional**.

---

# 7️⃣ Start ClamAV daemon

```bash
sudo systemctl enable clamd@scan
sudo systemctl start clamd@scan
```

Check:

```bash
ss -lntp | grep 3310
```

Expected:

```
LISTEN 0 128 127.0.0.1:3310
```

---

# 8️⃣ Test ClamAV (before PHP)

```bash
printf "PING\0" | nc 127.0.0.1 3310
```

Expected:

```
PONG
```

If you see `PONG` → **ClamAV is perfect**

---

# 9️⃣ PHP user permissions (IMPORTANT)

Your setup:

* `ssh_user` → admin
* `ftpuser` → runs ECCUBE + PHP-FPM

✅ **NO permissions needed**
✅ **NO group changes**
✅ **NO sudo**
✅ **NO access to /var/lib/clamav**

Because PHP only opens **TCP**.

---

# 🔟 Your PHP code — verdict

Your PHP code is **CORRECT** ✅
No changes needed.

Just ensure:

```php
$host = '127.0.0.1';
$port = 3310;
```

Do **NOT** use:

```php
$host = 'clamav'; // ❌ unless DNS exists
```

---

# 🧪 Final PHP test (real)

Upload **EICAR test file** and scan:

```bash
echo 'X5O!P%@AP[4\PZX54(P^)7CC)7}$EICAR-STANDARD-ANTIVIRUS-TEST-FILE!$H+H*' > /tmp/eicar.txt
```

Your PHP scanner **must throw**:

```
Virus detected: FOUND
```

---

# 🏁 That’s it. Nothing more.

### What we intentionally did NOT use

* ❌ Medium guide junk
* ❌ OnAccess
* ❌ SELinux tweaks
* ❌ Logging permissions
* ❌ Sockets
* ❌ EPEL

---

If you want next:

* auto daily DB update (cron, safe)
* PHP-FPM timeout tuning for big uploads
* upload temp file flow for ECCUBE

Just say the word 👍



Make sure the log file exists + permissions are correct
sudo touch /var/log/clamd.scan
sudo chown clamav:clamav /var/log/clamd.scan
sudo chmod 640 /var/log/clamd.scan


Inside /etc/clamd.d/scan.conf, these lines are good:

LogFile /var/log/clamd.scan
LogVerbose yes
LogTime yes


Restart clamd (mandatory)
sudo systemctl restart clamd@scan



sudo tail -f /var/log/clamd.scan



 sudo systemctl status clamd@scan --no-pager




 3️⃣ How to fix (3 options)
Option A — Use UNIX socket (preferred if same server)
sudo mkdir -p /run/clamd.scan
sudo chown clamav:clamav /run/clamd.scan
sudo chmod 750 /run/clamd.scan


Configure clamd to use UNIX socket in /etc/clamd.d/scan.conf:

LocalSocket /run/clamd.scan/clamd.sock


Ensure PHP uses the socket:

$scanner = new ClamAVScanner('unix:///run/clamd.scan/clamd.sock', 0);


Permissions:

sudo chown clamav:ftpuser /run/clamd.scan/clamd.sock
sudo chmod 660 /run/clamd.scan/clamd.sock


ftpuser = the user running ECCUBE2 PHP process


"v(>!^[3Lqfn!JWF


sudo chown clamav:fukui_dev_ftpuser /run/clamd.scan/clamd.sock

sudo systemctl restart clamd@scan
sudo systemctl status clamd@scan -l



curl -s http://169.254.169.254/latest/meta-data/instance-type



===========
class IssuerPermission
{
    private $tree;

    public function __construct()
    {
        $this->tree = JsonConfig::load(
            DATA_REALDIR . 'config/issuer_permissions.json'
        );
    }

    public function getTree()
    {
        return $this->tree;
    }

    public function getAllKeys($node = null)
    {
        $node = $node ?: $this->tree;
        $keys = array($node['key']);

        if (!empty($node['children'])) {
            foreach ($node['children'] as $child) {
                $keys = array_merge($keys, $this->getAllKeys($child));
            }
        }
        return $keys;
    }
}


{function name=renderPerm node}
<li>
  <label>
    <input type="checkbox"
           class="perm-checkbox"
           data-key="{$node.key}"
           data-has-children="{if $node.children}1{else}0{/if}">
    {$node.label}
  </label>

  {if $node.children}
  <ul>
    {foreach from=$node.children item=child}
      {renderPerm node=$child}
    {/foreach}
  </ul>
  {/if}
</li>
{/function}

<ul class="perm-root">
  {renderPerm node=$permissionTree}
</ul>

====
<script>
document.addEventListener('change', function (e) {

  if (!e.target.classList.contains('perm-checkbox')) return;

  const checkbox = e.target;
  const li = checkbox.closest('li');

  // 1) Parent toggle → all descendants follow
  li.querySelectorAll('input.perm-checkbox').forEach(cb => {
    cb.checked = checkbox.checked;
  });

  // 2) Child checked → all ancestors checked
  if (checkbox.checked) {
    let parentLi = li.parentElement.closest('li');
    while (parentLi) {
      const parentCb = parentLi.querySelector('> label input.perm-checkbox');
      parentCb.checked = true;
      parentLi = parentLi.parentElement.closest('li');
    }
  }

  // 3) Child unchecked → if no checked siblings → parent unchecked
  if (!checkbox.checked) {
    let parentLi = li.parentElement.closest('li');
    while (parentLi) {
      const anyChecked = parentLi.querySelectorAll(
        'ul input.perm-checkbox:checked'
      ).length;

      if (!anyChecked) {
        parentLi.querySelector('> label input.perm-checkbox').checked = false;
      }
      parentLi = parentLi.parentElement.closest('li');
    }
  }
});
</script>


ALTER TABLE dtb_issuer
ADD COLUMN permissions JSON NULL;


CREATE TABLE dtb_permission_meta (
    permission_code VARCHAR(64) PRIMARY KEY,
    config_json JSON NOT NULL,
    version INT DEFAULT 1,
    del_flg SMALLINT DEFAULT 0,
    create_date DATETIME,
    update_date DATETIME
);



ALTER TABLE dtb_issue_form
ADD COLUMN dashboard_menu_permissions TEXT NULL AFTER tel03;

SET SESSION sql_mode = '';
ALTER TABLE dtb_memberstore
ADD COLUMN dashboard_menu_permissions TEXT NULL AFTER memberstore_commission_rate;

SET SESSION sql_mode = '';
ALTER TABLE dtb_customer3
ADD COLUMN dashboard_menu_permissions TEXT NULL AFTER description;

SET SESSION sql_mode = '';
ALTER TABLE `dtb_memberstore` ADD `point_system_flg` TINYINT NOT NULL DEFAULT '0' AFTER `pokepay_id`;





.survey-media {
    @apply w-[30%] flex-shrink-0 aspect-[16/10] overflow-hidden;
}
.survey-media img {
    @apply w-full h-full object-cover;
}
