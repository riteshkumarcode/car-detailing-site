# Netlify Deployment Guide (Hybrid Architecture)

This project is configured with a **hybrid architecture**:
1. **Public Marketing Website on Netlify:** Blazing-fast static site serving all marketing pages, compiled Vite assets, self-hosted fonts, motion effects, and built-in Netlify Forms.
2. **Laravel Dynamic Backend (Railway, Render, Fly.io, or VPS):** Serves the Filament studio admin panel (`/admin`), staff operations board, Livewire capacity engine, invoice PDF generation, and SQLite/PostgreSQL database.

---

## 1. What Has Been Built for Netlify

- **Static Site Generator (`php artisan site:export-static`):**
  Renders all public routes, services catalog, service detail pages, contact page, booking page, sitemap, and robots into static HTML in the `dist/` directory.
- **Root-Relative Asset Resolution:**
  All stylesheet, script, and image references link directly to `/build/assets/...` and `/favicon.svg`.
- **Netlify Form Integration:**
  The contact form is enhanced with `data-netlify="true"`, honeypot spam protection, and a client-side success alert. Netlify automatically captures form submissions directly in your Netlify site dashboard without requiring a backend server.
- **Netlify Configuration (`netlify.toml`):**
  Defines the publish folder (`dist`), security headers (CSP, nosniff, frame-options), 1-year immutable caching for Vite assets, and redirect rules.
- **Automated CI/CD Workflow (`.github/workflows/deploy-netlify.yml`):**
  Automatically runs PHP migrations, builds Vite, exports the static site, and deploys to Netlify on every push to `main`.
- **Backend Containerization (`Dockerfile` & `render.yaml`):**
  Ready-to-deploy Docker image for the Laravel backend.

---

## 2. Quickest Way to Deploy to Netlify (Drag & Drop)

1. Generate the latest static export locally:
   ```bash
   npm run export:static
   ```
2. Log in to [Netlify App](https://app.netlify.com).
3. Navigate to **Sites** and drag the **`dist`** folder into the **"Drag and drop your site output folder here"** area.
4. Your website is immediately live with a custom `.netlify.app` URL and free SSL!

---

## 3. Deploying via Netlify CLI

1. Ensure the static site is built:
   ```bash
   npm run export:static
   ```
2. Deploy using Netlify CLI:
   ```bash
   npm run deploy:netlify
   ```
   (If you haven't logged in to Netlify CLI yet, run `npx netlify login` once).

---

## 4. Continuous Deployment via GitHub Actions

If your repository is hosted on GitHub:
1. In your Netlify dashboard, obtain:
   - **Netlify Site ID:** Site configuration > General > Site details > Site ID
   - **Netlify Personal Access Token:** User settings > Applications > OAuth > New access token
2. In your GitHub repository:
   - Go to **Settings > Secrets and variables > Actions**
   - Add `NETLIFY_SITE_ID`
   - Add `NETLIFY_AUTH_TOKEN`
3. Every git commit pushed to `main` will automatically build and deploy to Netlify.

---

## 5. Connecting Frontend (Netlify) to Backend (Railway / Render / VPS)

When you deploy your Laravel backend (e.g. to `https://api.thedriveclinic.com` or Railway):

1. Open [`netlify.toml`](file:///d:/car%20detailing%20site/netlify.toml)
2. Uncomment the backend proxy rules and set your backend URL:
   ```toml
   [[redirects]]
     from = "/admin/*"
     to = "https://your-backend-url.com/admin/:splat"
     status = 200
     force = true

   [[redirects]]
     from = "/livewire/*"
     to = "https://your-backend-url.com/livewire/:splat"
     status = 200
     force = true

   [[redirects]]
     from = "/api/*"
     to = "https://your-backend-url.com/api/:splat"
     status = 200
     force = true
   ```
3. Re-deploy your Netlify site. Now visitors on your Netlify domain can access `/admin` and live interactive Livewire components transparently routed to your backend server!

---

## 6. Commands Reference

| Command | Action |
| :--- | :--- |
| `npm run build` | Compiles Vite CSS, JS, and motion scripts to `public/build` |
| `php artisan site:export-static` | Exports public pages and assets to `dist/` |
| `npm run export:static` | Runs Vite build and static export in one command |
| `npm run deploy:netlify` | Builds, exports, and publishes `dist/` to Netlify via CLI |
| `php artisan test` | Runs the full 87-test verification suite |
