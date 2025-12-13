---
description: Check System Readiness for Release
---

# System Release Readiness Checklist

1.  **Environment Configuration**
    - [ ] `.env` file exists and `APP_ENV=production`.
    - [ ] `APP_DEBUG=false` (Critical for security).
    - [ ] `APP_KEY` is generated.
    - [ ] SSL is enabled (https).

2.  **Database**
    - [ ] Migrations are up to date: `php artisan migrate --force`.
    - [ ] Seeders run (if new setup): `php artisan db:seed`.

3.  **Optimization Commands**
    - [ ] `php artisan optimize` (Cache config and routes).
    - [ ] `php artisan view:cache` (Compile Blade views).

4.  **Frontend Build**
    - [ ] `npm ci` (Clean install dependencies).
    - [ ] `npm run build` (Minify CSS/JS).

5.  **Permissions**
    - [ ] `storage/` and `bootstrap/cache/` directories are writable by the web server user.

6.  **PWA/Mobile Checks**
    - [ ] `public/manifest.json` exists and validates using a Schema Validator.
    - [ ] `public/sw.js` exists (Service Worker).
    - [ ] `favicon.ico` and app icons (192, 512) are present in `public/images/`.
