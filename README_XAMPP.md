# SIRA Cafe - XAMPP Ready Project
Minimal SIRA Cafe online store built with PHP + MySQL + Tailwind (CDN).
Theme: Google Kanit font + Maroon red.

## What's included
- PHP files (index, product, cart, auth, seller/, admin/)
- inc/ (config.php, header.php, footer.php, functions.php)
- assets/ (css, uploads, logo/favicon placeholders)
- sql/schema.sql
- Basic role-based auth: user, seller, admin
- Passwords hashed using password_hash()
- File upload for product images (seller)
- Modal quick view (simple JS)
- README_XAMPP.md (this file)

## Installation (XAMPP - Windows)
1. Copy the folder `SIRA_Cafe` into `C:\xampp\htdocs\` (or use XAMPP's htdocs).
2. Start Apache and MySQL from XAMPP Control Panel.
3. Create a new MySQL database, e.g. `sira_cafe`.
4. Import `sql/schema.sql` into the database (via phpMyAdmin).
5. Edit `inc/config.php` and set DB credentials (default assume root with no password).
6. Open browser: `http://localhost/SIRA_Cafe/`
7. Default admin account: create via register page and set role to 'admin' in DB OR insert manually.

## Notes
- Uploads go to `assets/uploads/` (ensure writable).
- Tailwind used from CDN for convenience. For production, compile Tailwind properly.
- This is a starting template: extend validation, security, and UI as needed.
