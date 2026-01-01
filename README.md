To-Do List App (Local development)

Quick start (XAMPP + Windows):

1. Start XAMPP and ensure Apache + MySQL are running.
2. Place this project folder under XAMPP's `htdocs` (already in place at `c:/xampp/htdocs/my-website`).
3. Import the database schema using phpMyAdmin or CLI:

MySQL shell (example):

```
mysql -u root -p
SOURCE C:/xampp/htdocs/my-website/sql/schema.sql;
```

4. If your MySQL `root` has a password, update `inc/db.php` and set `$DB_PASS` accordingly.
5. Open http://localhost/my-website/ in your browser.
6. Register a new account and log in.

Backup and scheduling guidance
- Manual backups: Use the Export JSON / CSV / SQL buttons in the app to download your data.
- Server-side backup: Click "Server Backup" to create a timestamped JSON file in `backups/`.
- Scheduled backups (Windows Task Scheduler): create a scheduled task that calls a script (PowerShell or curl) to hit the backup endpoint while authenticated. For example, a simple curl call (requires the site to allow non-interactive authentication or a token):

PowerShell (example to download JSON backup):

```powershell
curl "http://localhost/my-website/api/export.php?action=backup" -UseBasicParsing -OutFile "C:\backups\todo-backup.json"
```

Security notes
- This project is for learning purposes. Do not expose it to the public internet without adding CSRF protection, stricter input validation, rate limiting, and HTTPS.
- Regularly rotate DB credentials and use a non-root MySQL user in production.

Next steps you can request
- Add CSV import, conflict resolution UI, or scheduled server-side backup automation.
- Add stronger security: CSRF tokens, prepared statements already used, but consider more validation and limiting file upload sizes.

"# Advanced-to-do-list-website" 
"# Advanced-to-do-list-website" 
