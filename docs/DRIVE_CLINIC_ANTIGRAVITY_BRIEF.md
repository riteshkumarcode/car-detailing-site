# The Drive Clinic — Build Brief for Antigravity

How to use this file:

1. Save this whole file in the project as `docs/PROJECT_BRIEF.md`.
2. Put the design screenshots in `docs/design/` using the file names listed in section 5.
3. Open a new Antigravity session and paste the **Kickoff prompt** (Part B, Prompt 0).
4. Run one milestone per session after that (Prompts 1, 2, 2B, then 3 to 9), in order. Review the agent's plan before you approve it.

---

# PART A — MASTER PROJECT BRIEF (the agent reads this every session)

## 1. Your role and how you work

You are a senior full-stack Laravel engineer building a production system for a real small business that opens soon. You care about correctness, speed on cheap Android phones, and code another Indian Laravel developer can take over easily.

Working rules. These apply to every session:

- **Plan first.** Before writing code, produce an Implementation Plan artifact: the files you'll create or change, migrations, packages to add, and how you'll verify. Wait for my approval.
- **One milestone per session.** Only build what the current milestone asks for. Do not add features, pages, dark mode, extra auth flows or abstractions that are not in this brief.
- **Ask before:**
  - deleting files;
  - dropping or renaming database columns or tables;
  - running `migrate:fresh` on anything except the local database;
  - adding any package not listed in section 3;
  - running destructive terminal commands;
  - pushing to git remotes or deploying.
- **Never invent business facts.** This includes prices, address, phone, hours, owner name, reviews and statistics. Where data is missing, use a clearly marked placeholder in square brackets, e.g. `[Street address]`, stored in settings or seed data so the owner can replace it from the admin. Never hard-code it into templates.
- **Everything the owner might change is data, not code:**
  - prices and services;
  - plans;
  - checklist items;
  - score weights;
  - capacity and opening hours;
  - FAQs;
  - text on the homepage.
- **Verify before reporting done:**
  - run the test suite;
  - open every page you touched in the browser agent at **375px** and **1440px** wide, and check it against the matching design screenshot;
  - report what you checked and any differences.
- **End every session with a short report:**
  - what you built;
  - files changed;
  - tests added and their results;
  - screenshots you checked;
  - open questions;
  - what's left for the next milestone.

## 2. What we are building

**The Drive Clinic** is a premium car wash and detailing studio launching in Nanak Nagar, Jammu (J&K, India).

- **Positioning:** "Your Car's Healthcare Centre". We don't just wash cars; we diagnose, detail, protect and maintain them.
- **Business size:** 1 branch, 3–4 bays, 3 staff plus the owner. Basic wash takes about 25 minutes, so roughly 25 cars a day.
- **Customers in month one:** mostly walk-ins.

We are building one system with two faces:

1. **Public website** (`thedriveclinic.in`). Its job is to turn visitors into leads and bookings.
2. **Staff and admin app** (`app.thedriveclinic.in`). It covers:
   - CRM;
   - vehicles;
   - booking and job board;
   - Digital Car Health Check;
   - Digital Car Passport;
   - billing;
   - Drive Club memberships;
   - dashboard;
   - roles;
   - website content admin.

Guiding principle from the client: lean, fast, professional and commercially useful. Every feature must help win customers, run the floor, or bring customers back.

## 3. Locked technical decisions (do not change without asking)

**Application shape**

- One Laravel application, one database, served on two domains with domain routing:
  - `thedriveclinic.in` serves the public site;
  - `app.thedriveclinic.in` serves the Filament panel and staff screens.
- Locally, use `drive-clinic.test` and `app.drive-clinic.test`, or equivalent. Ask me which local environment I use (Herd, Sail or Valet) before setup.

**Core stack**

- **Framework:** latest stable Laravel and PHP 8.3+. Check the current versions; don't assume.
- **Public site:** Blade + Tailwind CSS + Alpine.js. Livewire is used only for the interactive parts (booking flow and Health Check lead form). Pages are server-rendered for SEO. No SPA.
- **Admin and staff:** latest stable Filament panel at `app.` subdomain, set up as an installable PWA (manifest + service worker for the app shell only; no offline data sync). Mobile-first staff screens are custom Livewire pages inside the panel where Filament's default tables are too heavy for a phone.
- **Database:** MySQL 8 (or PostgreSQL if I say so). Every core table carries `branch_id` from day one. Only Branch 1 exists and there is no multi-branch UI.

**Approved packages**

| Purpose | Package |
|---|---|
| Roles and permissions | `spatie/laravel-permission` + Filament Shield (or equivalent Filament integration) |
| Audit log | `spatie/laravel-activitylog` |
| Backups | `spatie/laravel-backup` (daily, keep ≥30 days, stored off-server) |
| PDF invoices and reports | `spatie/laravel-pdf` (or `barryvdh/laravel-dompdf` if headless Chrome isn't available on the server; ask) |
| Sitemap | `spatie/laravel-sitemap` |
| Image handling | `intervention/image`; client-side compression on photo upload before sending |
| Tests | Pest |

**Storage and integrations**

- **File storage:** S3-compatible (Cloudflare R2 or AWS S3) via Laravel filesystem, with local disk in development.
- **Payments:** Phase 1 records payments manually (Cash / UPI / Card / Other). Keep a `PaymentGateway` interface so Razorpay can be added in Phase 2. Don't build Razorpay now.
- **WhatsApp:** Phase 1 uses `wa.me` click-to-chat links only, with pre-filled text. Keep a `WhatsAppNotifier` interface with a no-op implementation so the Business API can plug in later. No API calls now.

**Hosting**

- VPS (DigitalOcean / Hetzner / Lightsail) via Laravel Forge.
- Queue worker and scheduler.
- SSL everywhere.
- All accounts in the owner's name.

## 4. Brand and design system (from the approved designs)

We have no external brand assets. This design system **is** the brand. Build it as Tailwind theme tokens and reusable Blade components.

### Colours

| Token | Hex | Use |
|---|---|---|
| `teal` | `#1F5E57` | Primary: links, icons, ring of the logo mark, selected states |
| `teal-deep` | `#163F3A` | Dark sections, footer, top announcement bar |
| `amber` | `#F2A93B` | Main CTA buttons (with ink text), score gauge, logo cross |
| `ink` | `#13262B` | Body text, dark panels, secondary buttons |
| `mist` | `#EEF2F0` | Page background |
| `mint` | `#DDE8E3` | Soft panels, icon tiles |
| `paper` | `#F7F9F8` | Inner panels |
| `line` | `#CBD5D1` | Borders and dividers |
| `muted` | `#4A5D61` | Secondary text (passes 4.5:1 on mist and white) |
| `brick` | `#A63A2B` | "Needs attention" status, error text |

Status chips always show a text label as well as the colour:

- **Good:** `#1F5E57` on `#E1EEEA`
- **Fair:** `#7A4E07` on `#FBEBCF`
- **Needs attention:** `#A63A2B` on `#F6E0DB`

### Typography

- **Display (headings, prices, big numbers):** Archivo, weight 800, `font-stretch: 116%`, letter-spacing −0.01em, tight line-height (0.98–1.05). Load via Google Fonts with the width axis: `Archivo:wdth,wght@62..125,400..900`.
- **Body, UI, forms, buttons:** Barlow, weights 400/500/600/700, body size 17px, line-height 1.55.
- Self-host both fonts in production for speed (subset Latin, `font-display: swap`).

### Logo

Use this exact SVG mark: a steering-wheel ring with a clinic cross at the hub.

```svg
<svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
  <circle cx="20" cy="20" r="16.5" fill="none" stroke="#1F5E57" stroke-width="4"/>
  <path d="M20 3.5v9M20 27.5v9M3.5 20h9M27.5 20h9" stroke="#1F5E57" stroke-width="4"/>
  <path d="M16.5 11h7v5.5H29v7h-5.5V29h-7v-5.5H11v-7h5.5z" fill="#F2A93B"/>
</svg>
```

The wordmark sits to the right of the mark, stacked on two lines in Archivo at `font-stretch: 125%`:

- small "The" (weight 600);
- larger "Drive Clinic" (weight 800).

On dark backgrounds the ring and text turn white and the cross stays amber.

Generate the following from the mark:

- favicon set;
- PWA icons (192/512, maskable);
- an Open Graph image.

### Components to build once and reuse

- **Buttons** (min height 52px, radius 10px, weight 700):
  - primary: amber fill + ink text;
  - secondary: ink 2px outline;
  - light: white outline on dark;
  - teal: teal fill + white text.
- **Indian number plate badge:** white plate, 2px ink border, blue "IND" strip on the left, Archivo bold, letter-spaced. Used wherever a registration number is shown.
- **Drive Health Score gauge:** a semicircle SVG arc, amber value on a mint track, with the number in the centre and "out of 100" under it. It must always appear with the disclaimer (see section 7.4).
- **Status chips** as above.
- **Photo placeholder:** diagonal hatch with a camera icon and label. Used until the owner uploads real photos.
- **Section heading:** h2 in display font, sentence case, with an optional muted lead paragraph under it.
- **Floating WhatsApp button**, bottom-right, on every public page.
- **Header** with:
  - a top announcement bar (admin-editable text + link);
  - a sticky white nav: logo, links, "Call", and a "Book your car" button;
  - a mobile menu below 960px.
- **Footer** with three columns: Visit, Services, The clinic. It includes address, hours, privacy and terms.

### Design rules: it must not look AI-generated or templated

- No gradient blobs or washes. The only gradient allowed is the subtle white-to-mist on the home hero.
- No emoji anywhere.
- No ALL-CAPS eyebrow labels above headings.
- No "→" arrows appended to buttons.
- No monospace labels.
- Do not use Inter, Roboto or Arial.
- Do not chop everything into identical shadowed cards. Services are a price-list layout, the "Why us" section is one panel divided by hairlines, and shadows are rare.
- Numbered markers appear only where the content is a real sequence ("How a visit works", the booking steps).
- Copy is plain, sentence case, and in active voice. Button labels say exactly what happens ("Book your car", "Get a free health check", "Confirm booking").
- Icons are a single consistent stroke style: 24px grid, 1.8 stroke, round caps. Use Lucide or Heroicons outline, or the custom inline SVGs from the designs.
- Motion follows section 4A exactly. Every animation must mean something about cars or diagnosis (scanning, finding, recording, before/after). Never add generic motion: bouncing icons, spinning logos, confetti, typewriter text, or particle backgrounds.

## 4A. Motion system (premium, but fast)

The site should feel expensive and alive, like a flagship automotive brand. It must still load in under 3 seconds on 4G and run smoothly on a ₹12,000 Android phone. The motion idea is **"a diagnostic scan"**: the site visibly inspects, finds, records and reveals.

### Tools

- **GSAP 3 with ScrollTrigger** (GSAP and all its plugins are free to use; confirm the current licence when installing). Load it only on public pages, and lazy-load ScrollTrigger after first paint.
- **CSS keyframes** for simple loops (marquee, heartbeat line, pulse rings).
- **Lenis smooth scrolling on desktop only.** Turn it off on touch devices.
- **Never use:** WebGL or three.js, autoplay video backgrounds, Lottie files over 60 KB, or cursor-follower effects.

### Signature moments

1. **Hero load sequence** (about 2.2 s total; starts after the fonts are ready, skippable by scrolling):
   - the headline rises line by line out of a mask, 80 ms apart;
   - the lead text, buttons and facts fade up;
   - the Health Check card slides in with a slight tilt that settles;
   - an amber **scan line** sweeps up and down the top-down car diagram on a loop;
   - three numbered markers **pop** onto the car one by one, each with a pulsing ring;
   - the score gauge arc fills while the number **counts up from 0 to 72**;
   - the five category rows slide in one after another, followed by the recommendation box;
   - two small floating callouts ("3 issues photographed", "Saved to Car Passport") bob gently next to the card. Hide them below 960px.
2. **Heartbeat (ECG) line** under the hero, and again behind the final call-to-action. A teal pulse travels along a faint line on a loop. It's the healthcare metaphor in motion.
3. **Service marquee**: a dark band of large display words ("Foam wash", "Clay bar", "Ceramic coating"…) alternating solid and outlined, with the logo mark between them. It scrolls slowly and pauses on hover.
4. **Before/after wipe**: each gallery pair is one image with a draggable divider. When idle, it sweeps back and forth by itself (pairs offset in time). Dragging stops the auto-sweep for that pair.

### Scroll and hover behaviour

- **Section reveals:** content fades up about 28px as it enters the screen, staggered for lists. Never animate the same element twice.
- **Service rows:** slide in from the left on scroll. On hover, an amber underline sweeps across and the row shifts right 18px.
- **Health check panel:** gentle parallax (±36px).
- **"How a visit works":** each step's progress bar grows from left to right as you scroll.
- **Header:** gains a soft shadow after 140px of scroll. Nav links get an amber underline that slides in on hover.
- **Buttons:** lift 2px with a shadow on hover and press back on tap.
- **Cards** (Drive Club plans, gallery): lift 6px on hover.
- **Map pin:** pulsing ring.
- **WhatsApp button:** a soft ring pulse every few seconds, starting after 2 s.
- **Page headings on inner pages:** use the same masked line rise as the home hero.
- **Booking flow and Health Check form:** each step slides in from the right (back slides from the left). Selected chips spring slightly. The confirmation tick draws itself.
- **Staff app:** no decorative motion. Only fast state transitions (≤150 ms).

### Performance rules (non-negotiable)

- Animate only `transform`, `opacity`, `clip-path` and `stroke-dashoffset`. Never animate width, height, top/left or box-shadow inside a scroll loop.
- **LCP:** the hero headline must already be painted before its animation starts. Never start it from `opacity: 0`, and never wait for JavaScript to show content.
- **Core Web Vitals on mobile:**
  - LCP < 2.5 s;
  - CLS < 0.05;
  - INP < 200 ms;
  - Lighthouse mobile performance ≥ 90 on Home.
- Motion JavaScript ≤ 60 KB gzipped in total.
- Pause loops (marquee, ECG, scan, before/after) when they're off-screen, using IntersectionObserver.
- **Reduced motion:** `prefers-reduced-motion: reduce` turns all motion off. Every element must show its final state, including the score showing 72.
- **Slow devices:** if `navigator.connection.saveData` is on, `deviceMemory ≤ 2`, or `hardwareConcurrency ≤ 4`, switch off parallax, Lenis and the floating callouts. Keep only the simple fades.
- Write all motion code as small reusable modules in `resources/js/motion/` (`hero.js`, `reveal.js`, `marquee.js`, `compare.js`, `ecg.js`, `counter.js`). Trigger them with `data-motion="..."` attributes in Blade, not one-off scripts.
- **Reference:** the motion is already working in the canvas design (Home artboard). Match its timing and feel.

### Accessibility floor

- Every tap target ≥44px.
- Visible focus ring: 3px amber outline.
- Real `<button>`, `<a>` and `<label>` elements.
- `aria-label` on icon-only buttons.
- Text contrast ≥4.5:1.
- Every form field has a label, not only a placeholder.

## 5. Design references in `docs/design/`

Match these screenshots closely. Where a screenshot and this brief disagree, the brief wins, and you tell me.

| File | Page |
|---|---|
| `00-brand-kit.png` | Logo, colours, type, components |
| `01-home.png` | Home |
| `02-services.png` | Services listing |
| `03-service-detail.png` | Individual service (Interior Deep Clean) |
| `04-health-check.png` | Free Health Check lead form |
| `05-booking.png` | Booking flow |
| `06-drive-club.png` | Drive Club |
| `07-gallery.png` | Gallery |
| `08-about.png` | About |
| `09-contact.png` | Contact |
| `10-faq.png` | FAQ |
| `11-passport.png` | Car Passport (customer view, Phase 2 preview) |

## 6. Public website: pages and behaviour

All copy, images, prices, FAQs and section content come from the database or settings and are editable in the admin. Seed the database with the content shown in the designs.

### Global

- Header and footer as in section 4.
- Floating WhatsApp button.
- Announcement bar.
- Every page has an admin-editable meta title and description.
- `LocalBusiness` / `AutoWash` schema.org JSON-LD with address, geo, hours and phone, built from settings.
- Click-to-call and click-to-WhatsApp links everywhere.

### Home (`/`)

Sections in this order:

1. **Hero.** Location pill; h1 "Your car's healthcare centre."; lead text; CTAs "Book your car" and "Get a free health check"; three facts (price from, ~25 min, walk-ins welcome). On the right, the **sample Car Health Check card**: plate, score gauge, top-down car diagram with marked spots, five category rows with status chips, "Recommended today" and "Can wait" boxes, and the disclaimer.
2. **Services** as a price-list: rows with name, category and duration, one-liner, "from ₹X" and a Details link. Pulled from the database.
3. **Free health check explainer** on a dark teal section: what we check (5 areas) and the CTA.
4. **"We don't just wash. We diagnose."** Six differentiators inside one hairline-divided panel.
5. **Drive Club teaser:** three plan cards from the database; the middle one is highlighted.
6. **Before and after** strip that scrolls sideways (swipe on mobile, lazy-loaded) from gallery pairs.
7. **How a visit works:** four numbered steps.
8. **Reviews** from admin-curated testimonials. Hide the section if there are none.
9. **Location:** Google Maps embed (lazy-loaded behind a static preview so it doesn't hurt page speed), address, hours, phone, directions.
10. **FAQ accordion:** the five FAQs flagged "show on home".
11. **Contact:** WhatsApp and call buttons plus a short enquiry form (name, mobile, message). The form creates a CRM lead with `source = contact_form`.
12. **Final CTA band.**

### Services (`/services`)

- Car type switch (Hatchback / Sedan / SUV) that updates prices instantly, and category filter chips (All / Wash / Detailing / Protection).
- Each service shows:
  - category, name, description and duration;
  - "What's included" checklist;
  - price for the selected car type plus all three prices in small text;
  - "Book now" (pre-selects the service in booking) and "Details" buttons.
- A service with no price shows the admin-set label ("After inspection" or `[₹ price]`).

### Service detail (`/services/{slug}`)

One template for all services. It contains:

- breadcrumb, title, the problem it solves and who it's for;
- before/after photos;
- step-by-step process;
- FAQs specific to the service;
- a sticky sidebar with:
  - duration and a price table by vehicle type;
  - "Book this service";
  - what's included;
  - add-ons with prices.

URLs are SEO-friendly slugs, and title/meta are set per service.

### Free Health Check (`/free-car-health-check`)

Livewire form with these fields:

| Field | Rules |
|---|---|
| Name | Required |
| Mobile | Required. 10-digit Indian mobile, regex `^[6-9][0-9]{9}$`; strip spaces and +91 |
| Email | Optional |
| Registration number | Required. Accept any format; store a normalised copy: uppercase, no spaces or dashes |
| Make and model | Required. Free text with datalist suggestions |
| Vehicle type | Required. Hatchback / Sedan / SUV / Other |
| Main concerns | Required. Multi-select chips: Exterior, Paint, Scratches, Swirl marks, Water spots, Interior, Seats, Carpet, Odour, Wheels/Tyres, Glass, Protection, Not sure |
| Preferred date | Required |
| Preferred time | Morning / Afternoon / Evening |

On submit:

1. Create a lead with `source = health_check_form`, visible in the CRM within 5 seconds.
2. Show the confirmation screen with a pre-filled WhatsApp link.
3. Notify staff with a dashboard badge, and email the owner if enabled in settings.
4. Fire GA4 `generate_lead` and Meta Pixel `Lead` events.

### Book (`/book`, accepts `?service=slug`)

Livewire stepper: **Service → Car type → Day and time → Details → Confirmed**.

- Live summary sidebar with the price.
- Day picker shows 7 days.
- Time slots come from the capacity logic (section 7.3). Full slots are shown struck through and disabled.
- Details: name, mobile, registration.
- If the mobile number matches an existing customer, attach the booking to them and suggest their saved vehicles.
- Confirmation shows a WhatsApp link pre-filled with the booking details.
- Fire GA4 `booking_confirmed` (with value) and the Pixel `Schedule` event.

### Other pages

- **Drive Club** (`/drive-club`): plan comparison table from the database (scrolls sideways on mobile), "How membership works" (4 steps), and an enquiry form that creates a lead with `source = drive_club`.
- **Gallery** (`/gallery`): filter chips by area; before/after pairs with captions (problem, service, car model).
- **About** (`/about`): story, how the Drive Health Score works (rating table and disclaimer), the sample report card, and "Four things we promise".
- **Contact** (`/contact`): three contact cards (WhatsApp, Call, Visit), map, opening hours table, and a contact form with a topic dropdown.
- **FAQ** (`/faq`): topic tabs plus an accordion, grouped by category.
- **Privacy** and **Terms:** plain pages editable from the admin. The privacy page must follow the DPDP Act and explain how to request data deletion.
- **Car Passport public view** (`/passport/{signed-token}`): build the page and route now. It is turned off by a settings flag in Phase 1, so a request returns 404 while the flag is off.

## 7. Staff and admin app (`app.thedriveclinic.in`)

### 7.1 Roles (enforced server-side with policies, not just hidden menus)

| Role | Can do | Cannot do |
|---|---|---|
| Owner/Admin | Everything, including pricing, plans, website content, settings, CSV exports and user management | — |
| Manager | Customers, bookings, billing, reports, services, health checks | Settings, user management, exports |
| Staff | Bookings (status updates), customer and vehicle lookup/create, health checks, job status | Revenue figures, pricing, settings, discounts above the configurable limit |

Other requirements:

- Individual logins only.
- Login is rate-limited.
- Password reset.
- An admin can deactivate a user instantly, which kills their sessions.
- The audit log records who created or edited invoices, discounts, customers and settings.

### 7.2 Staff home: search first

The first thing on the staff home screen is one big search box. It searches:

- registration number (partial, normalised);
- customer mobile;
- customer name;
- vehicle model.

Results must come back in under 1 second. Index the right columns.

Below the search:

- "New walk-in" button;
- today's job board, with columns or tabs by status.

Everything must work one-handed on a 6-inch Android phone over patchy 4G.

### 7.3 Bookings, capacity and walk-ins

**Settings (admin-editable):**

- bay capacity (launch 3, can be raised to 4+);
- slot length (30 min);
- opening hours per weekday;
- blocked dates and time ranges (holidays, maintenance).

**Capacity model:**

- Fixed slot grid.
- A job of duration D occupies `ceil(D / slot_length)` consecutive slots.
- A slot is available when the active jobs occupying it number fewer than the capacity. Active means every status except Cancelled and No Show.
- Walk-in jobs consume capacity the same way.
- Changing capacity or hours updates availability immediately.

**Statuses:**

- New → Confirmed → Arrived → Inspection → In Service → Completed, plus Cancelled and No Show.
- One tap to change status.
- Every change is timestamped and logged with the user who made it.
- Completing a job requires an invoice, and completion updates the Car Passport.

**Walk-in flow** (must take ≤60 seconds from arrival to In Service on a phone):

1. Search by mobile or plate.
2. Pick the existing customer, or create one with name + mobile + registration + vehicle type. Warn on a duplicate mobile.
3. Optionally run a Health Check.
4. Pick the services.
5. Create the job as Arrived or In Service.

### 7.4 Digital Car Health Check (signature feature)

A mobile-first Livewire screen with big tap targets.

**Checklist (admin-configurable categories and items):**

| Category | Items |
|---|---|
| Exterior/Paint | Swirl marks, scratches, water spots, oxidation, paint contamination, gloss/finish |
| Interior | Seats, carpet, floor mats, dashboard, door panels, AC vents, odour, stains |
| Wheels & Tyres | Tyre condition, tyre pressure, tread, brake dust, wheel condition |
| Glass | Windshield, side glass, water spots, smearing, wiper condition |
| Protection | Single choice: None known / Wax / Sealant / Ceramic / Unknown |

**What staff can do for each item:**

- rate it Good / Fair / Needs attention;
- add an optional note;
- add an optional photo, using camera capture with `accept="image/*" capture="environment"`. Compress the photo in the browser to roughly 1600px and 80% JPEG before upload.

Staff can also mark areas for attention and add free-text recommendations.

**Drive Health Score (0–100). Implement exactly this, with every number editable in settings:**

1. Item points: Good = 2, Fair = 1, Needs attention = 0.
2. Each category has a weight: Exterior 30, Interior 30, Wheels & Tyres 15, Glass 15, Protection 10.
3. Category score = (points earned ÷ maximum points) × category weight.
4. Protection mapping: Ceramic = Good, Wax or Sealant = Fair, None or Unknown = Needs attention.
5. Total = sum of category scores, rounded to a whole number.
6. Store the weights used with each health check, so a later change to the weights doesn't rewrite history.

**Mandatory disclaimer.** Show it wherever the score appears: on screen, in the Passport, in PDFs and on shared links.

> "The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection."

**Recommendations:**

- The admin maps findings to services, e.g. "Swirl marks = Needs attention → Exterior Detailing".
- The system suggests services in two buckets, **Recommended today** and **Recommended later**, with prices for the car's type.
- Staff can edit, add, remove and reorder recommendations before saving.

**Saving and sharing:**

- A saved check attaches to the vehicle's Car Passport.
- Staff can share it as a PDF or a signed link through a `wa.me` button.

### 7.5 CRM: customers and vehicles

**Customer:**

- Fields: auto ID, name, mobile (unique; the dedupe key; warn on duplicates), email, area, date joined, referral source (dropdown including "Referral" plus a referral code field), and notes (timestamped, with author).
- Computed: first visit, last visit, total visits, total spend, average bill, membership status.
- Manual communication log: call, WhatsApp or note.
- Tags stored now: New, Active, Repeat, High-value, Inactive (30/45/60/90-day filters), Member, Membership expiring. Automation of these tags is Phase 2.
- Search: full-text by name, exact by mobile.
- CSV export of customers is admin only.

**Vehicle:**

- Fields: auto ID, registration (stored normalised; shown with the plate component), make, model, variant, type, colour, linked customer, photos, notes.
- One customer can have many vehicles.
- Each vehicle has its own history.

### 7.6 Digital Car Passport (staff view now; customer link later)

One Passport per vehicle, keyed on the registration number. It shows:

- the vehicle and its owner;
- first visit, last visit, total visits and total spend;
- a chronological timeline of services and health checks. Each entry links to its invoice or check, with scores and photos;
- before/after photos;
- open recommendations and the next recommended service date;
- membership status;
- staff notes.

Render it with a shared Blade view so the same page can be served on the signed public URL later (section 6).

### 7.7 Billing

**Invoice contents:**

- **Number:** sequential per Indian financial year (April–March). The format is admin-configurable, default `TDC/2026-27/0001`. Generate it inside a DB transaction with a row lock so numbers are never duplicated or skipped.
- **Header details:** date/time, customer and mobile, vehicle registration and model.
- **Line items:** service, quantity, unit price.
- **Discount:** ₹ or %, with a **required reason**, and limited by role.
- **Payment and staff:** payment method (Cash / UPI / Card / Other), and the staff member(s) who did the work.

**GST toggle in settings:**

- **On:** invoices show the GSTIN and the CGST/SGST breakup, with admin-editable rates.
- **Off:** a simple bill with no tax.
- Each invoice stores the tax setting and rates that applied when it was issued.

**After issue:**

- Invoices are **immutable once issued**. Corrections are made by cancelling and reissuing, and both actions are logged with a reason.
- The branded PDF invoice uses the logo, colours and fonts, and can be downloaded or printed.
- A "Share on WhatsApp" link sends a signed PDF link.

**At billing**, a member's remaining benefits are suggested and applied automatically.

### 7.8 Drive Club memberships

- **Plans:** fully editable by the admin: name, price, duration, included services with counts (e.g. 12 × Essential Wash), discount % by service category, and benefit text.
- **Seed plans:** Essential ₹1,999/yr, Premium ₹4,999/yr, Elite ₹9,999/yr, with the benefits shown in the design marked as draft.
- **Membership actions:** assign a plan to a customer and vehicle, record the payment, and process renewals.
- **Usage:** each redemption decrements the remaining count and is logged against the job and invoice. Track the expiry date.
- **Deactivated plans** can't be sold again, but existing members keep their benefits.
- **Visibility:** wherever a member's vehicle appears, show the membership badge and remaining benefits.

### 7.9 Dashboard and reports

**Today panel:**

- cars serviced and revenue;
- new vs returning customers;
- bookings: completed, cancelled and pending;
- membership sales.

**Monthly panel:**

- revenue, cars serviced, average bill;
- new vs repeat customers;
- membership sales;
- revenue by service category (Wash / Detailing / Protection / Other);
- total discounts;
- payment method split.

Every figure links through to the records behind it. The dashboard loads in under 3 seconds on mobile.

**Service analytics:** units and revenue per service, with filters for Today, Yesterday, This week, This month and a custom range. Exportable to CSV.

**Reviews funnel:**

- On a completed job, staff tap "Ask for review" to open a `wa.me` link to a page asking "How was your experience?".
- A positive answer goes to the Google review link in settings.
- A problem goes to an internal feedback form, stored against the customer with a resolved/unresolved flag.

### 7.10 Website content admin

The owner edits all of the following without a developer, and changes show on the site within 1 minute (cache invalidated on save):

- services: add, edit, deactivate; prices per vehicle type; images; included lists; process steps; FAQs; add-ons; SEO fields;
- membership plans;
- the announcement bar;
- hero text and image;
- gallery before/after pairs;
- testimonials;
- FAQs;
- opening hours;
- address, phone, WhatsApp number, map coordinates, Google review link and social links;
- privacy and terms text.

## 8. Analytics and SEO

- **Tracking:** GA4 and Meta Pixel, with IDs set in settings. Load them only if the IDs are set.
- **Events:** `generate_lead`, `booking_confirmed`, `whatsapp_click`, `call_click`, `directions_click`.
- **Search Console:** a verification meta tag set from settings.
- **Sitemap:** `sitemap.xml` and `robots.txt`.
- **Pages:** canonical URLs, plus Open Graph and Twitter tags.
- **Target keywords:** use these naturally in the seeded titles, meta and headings: car wash in Jammu, car detailing in Jammu, car detailing Nanak Nagar, car wash Nanak Nagar, interior car cleaning Jammu, car spa Jammu, premium car wash Jammu.

## 9. Non-functional requirements

- **Performance:**
  - public pages load in under 3 seconds on 4G with good Core Web Vitals on mobile;
  - images in WebP/AVIF with responsive `srcset`, lazy-loaded below the fold;
  - no heavy JS libraries on public pages;
  - staff search under 1 second.
- **Security:**
  - SSL;
  - hashed passwords;
  - CSRF protection;
  - rate-limited login and public forms;
  - honeypot fields on public forms;
  - signed URLs for shared PDFs, Passports and health checks;
  - no card data is ever stored.
- **Data protection (India's DPDP Act):**
  - collect only the fields listed in this brief;
  - the admin can delete or anonymise a customer on request;
  - one-click CSV export of customers, vehicles and invoices so the owner is never locked in.
- **Backups:**
  - daily database and media backup to off-server storage;
  - keep for ≥30 days;
  - a documented restore procedure, tested once.
- **Quality:**
  - Pest feature tests for every rule in section 10;
  - seeders that create a realistic demo dataset (2 customers, 3 vehicles, bookings, 1 health check, 2 invoices, 1 membership);
  - clearly marked as demo data so it can be removed before launch.

## 10. Acceptance tests (the client will check every one on a real phone)

**Leads**

- Submitting the Health Check form creates a lead with all fields and `source = health_check_form`. It appears in the CRM within 5 seconds.

**Booking**

- Service → Car → Date → Time → Confirm works end to end.
- Slots stop showing once capacity is full.
- Changing capacity from 3 to 4 frees slots immediately.
- Status changes are timestamped.
- Cancel and No Show free up capacity.

**Walk-ins**

- A new customer, vehicle and job can be created in ≤60 seconds on a phone.
- The duplicate-mobile warning fires.

**Search and CRM**

- Search by name, mobile, plate (partial) and model returns correct results in under 1 second.
- A customer with 2 vehicles shows both, each with its own history.
- CSV export works for the admin only.

**Health Check**

- Rate items, upload ≥3 photos, generate the score, see recommendations with prices, edit them and save. The check appears in the Passport.
- The disclaimer is visible everywhere the score appears.
- An admin edit to a checklist item shows up in the next inspection.

**Passport**

- A test vehicle with 2 visits, 2 invoices, 1 health check and photos shows everything in chronological order.
- It is found by searching the plate.

**Billing**

- An invoice with 2 services and a discount requires a reason.
- The GST toggle works as configured.
- The payment method is recorded.
- The branded PDF downloads.
- The number sequence is correct and resets each financial year.
- The invoice is immutable; cancel + reissue is logged.

**Membership**

- Create a plan, edit it, assign it, redeem a service (the count goes down), see the expiry, record a renewal.
- A deactivated plan can't be sold, but existing members keep their benefits.

**Roles**

- A Staff user opening the revenue or settings URL directly gets 403.
- The audit log shows who issued each invoice and discount.

**Dashboard**

- Today and Monthly figures match the underlying invoices.
- Service analytics filters work for every date range.

**Analytics**

- GA4 records `booking_confirmed` end to end.
- WhatsApp clicks are tracked.

**Operations**

- A daily backup exists.
- A restore has been demonstrated.

## 11. Out of scope: do NOT build

- Native mobile app.
- WhatsApp Business API automation.
- Payment gateway checkout.
- Referral engine. Only capture the referral source and code fields.
- Loyalty points.
- Inventory.
- Multi-branch UI and franchise tools.
- Accounting integration (CSV export is enough).
- AI features.
- E-commerce.
- Customer login and portal. Only the turned-off Passport route.
- WebGL/3D scenes, autoplay video backgrounds, or any motion outside section 4A.

---

# PART B — SESSION PROMPTS (paste one per Antigravity session)

### Prompt 0 — Kickoff (planning only, no code)

```
Read docs/PROJECT_BRIEF.md completely and look at every image in docs/design/.

Do not write any application code in this session. Produce two artifacts for my review:

1. Implementation Plan:
   - your understanding of the project in 10 lines;
   - the full database schema (every table, key columns, indexes, foreign keys; branch_id on core tables);
   - the folder/module structure separating Public site, Admin panel and shared Domain logic (booking capacity, invoice numbering, score calculation);
   - the domain-routing setup for the two subdomains;
   - the exact package list with current stable versions.

2. Task List: milestones 1–9 from Part B of the brief, each broken into concrete tasks.

List any contradictions, missing information or risky assumptions you find in the brief as questions at the end. Then stop and wait for my approval.
```

### Prompt 1 — Foundation

```
Milestone 1 from docs/PROJECT_BRIEF.md: project foundation.

Build:
- a fresh Laravel app with the stack and packages in section 3;
- domain routing for the public site and the app subdomain (local .test domains);
- the Filament panel at the app subdomain;
- roles and policies (section 7.1);
- the audit log;
- the settings store (business details, hours, capacity, slot length, GST, score weights, invoice format, feature flags);
- the branches table with Branch 1;
- the Tailwind theme tokens, self-hosted fonts and the Blade components from section 4 (buttons, plate, score gauge, chips, photo placeholder, header, footer, WhatsApp button), plus the logo, favicon and PWA icons;
- a /styleguide route (local only) showing every component.

Plan first and wait for approval.

Done when:
- both subdomains load;
- the three roles exist, and Pest tests prove that a Staff user gets 403 on settings;
- /styleguide matches docs/design/00-brand-kit.png at 375px and 1440px, checked with the browser agent.
```

### Prompt 2 — Public website pages

```
Milestone 2 from docs/PROJECT_BRIEF.md: build every public page in section 6 except the booking flow and the Health Check form logic (leave styled placeholders for those two).

Requirements:
- all content comes from models or settings, seeded with the copy, services, prices, plans and FAQs shown in the design screenshots;
- missing business facts stay as [bracketed] placeholders in settings;
- the services car-type switch and the FAQ and gallery filters use Alpine;
- add SEO meta, JSON-LD, sitemap and robots (section 8).

Plan first.

Done when every page matches its screenshot in docs/design/ at 375px and 1440px (checked with the browser agent; report any differences), Lighthouse mobile performance is ≥90 on the home page, and none of the "must not look AI-generated" rules in section 4 is broken.
```

### Prompt 2B — Motion layer

```
Milestone 2B from docs/PROJECT_BRIEF.md: build the motion system in section 4A on the public pages finished in Milestone 2. Do not change layouts, copy or data.

Build:
- the motion modules in resources/js/motion/, triggered by data-motion attributes;
- the hero load sequence, scan line, popping markers, score count-up, ECG line, service marquee, before/after wipe with drag, scroll reveals, parallax, progress bars, header shadow, and hover states;
- the reduced-motion and slow-device fallbacks;
- pausing of off-screen loops.

Plan first: list each animation, its trigger, duration, easing, and the file it lives in.

Done when:
- the browser agent records the home page at 1440px and 375px and every signature moment in section 4A plays;
- with reduced motion enabled, every element shows its final state (score reads 72);
- Lighthouse mobile on Home is ≥90 performance with CLS < 0.05;
- motion JS is ≤60 KB gzipped (show the build output).
```

### Prompt 3 — Leads, booking engine and capacity

```
Milestone 3 from docs/PROJECT_BRIEF.md: the Free Health Check Livewire form (section 6), the Book Livewire stepper (section 6) and the capacity engine (section 7.3, settings-driven).

Also build:
- the leads table and lead notifications;
- GA4 and Pixel events;
- the wa.me confirmation links.

Pest tests must cover:
- slots fill up at capacity;
- changing capacity from 3 to 4 frees slots;
- multi-slot service durations;
- blocked dates;
- cancelled and no-show bookings free capacity;
- mobile validation;
- registration normalisation;
- a lead appears in the admin.

Plan first. Done when the tests pass and both flows work end to end in the browser at 375px.
```

### Prompt 4 — CRM, vehicles, search, walk-ins, job board

```
Milestone 4 from docs/PROJECT_BRIEF.md: sections 7.2, 7.3 (statuses and walk-in) and 7.5.

Build:
- the customer and vehicle resources;
- the search-first staff home;
- the walk-in flow;
- the job board with one-tap, timestamped status changes;
- notes and communication log, tags, duplicate-mobile warning;
- CSV export (admin only).

Use custom Livewire pages for the phone screens, with large tap targets.

Plan first.

Done when:
- search returns in under 1 second on a seeded set of 5,000 customers (show the timing);
- a walk-in from arrival to In Service is done in ≤60 seconds (record the steps);
- a customer with 2 vehicles shows separate histories;
- Pest tests pass.
```

### Prompt 5 — Digital Car Health Check

```
Milestone 5 from docs/PROJECT_BRIEF.md: section 7.4 exactly.

Build:
- the admin-configurable checklist;
- the mobile inspection screen with per-item rating, note and camera photo (compressed in the browser);
- the score calculation using the exact formula in 7.4, with weights stored on each check;
- recommendation rules and the today/later buckets, editable before saving;
- PDF and signed share link;
- the disclaimer everywhere the score appears.

Pest tests: score calculation (including the protection mapping and custom weights), and that an admin checklist edit shows up in the next inspection.

Plan first. Done when a full inspection with 3 photos completes on a 375px viewport and appears on the vehicle.
```

### Prompt 6 — Car Passport and billing

```
Milestone 6 from docs/PROJECT_BRIEF.md: sections 7.6 and 7.7.

Build:
- the Passport view as a shared Blade view, plus the signed public route behind a flag that is off by default;
- invoices with financial-year numbering (transaction and lock);
- discounts with a required reason and role limits;
- the GST toggle with the tax settings stored on each invoice;
- payment methods;
- immutability, with cancel + reissue logged;
- the branded PDF;
- the wa.me share link;
- completing a job requires an invoice and updates the Passport.

Pest tests: numbering sequence and financial-year reset, no duplicate numbers when two invoices are issued concurrently, immutability, discount reason required, GST on and off.

Plan first. Done when the Passport acceptance test in section 10 passes with seeded data.
```

### Prompt 7 — Drive Club memberships

```
Milestone 7 from docs/PROJECT_BRIEF.md: section 7.8.

Build:
- plans (admin CRUD);
- assigning a plan, recording payment, expiry and renewal;
- redemptions that decrement counts and are logged against the job and invoice;
- automatic suggestion of member benefits at billing;
- the member badge wherever the vehicle appears;
- deactivated plans can't be sold, but existing members are unaffected.

Plan first. Done when the membership acceptance test in section 10 passes as Pest tests.
```

### Prompt 8 — Dashboard, analytics, reviews, content admin

```
Milestone 8 from docs/PROJECT_BRIEF.md: sections 7.9 and 7.10.

Build:
- the Today and Monthly dashboard panels, with click-through to records;
- service analytics with date filters and CSV export;
- the review funnel page with internal feedback and a resolved flag;
- the complete website content admin, with cache invalidation so public changes show within 1 minute.

Plan first.

Done when:
- dashboard figures reconcile with the seeded invoices (Pest test);
- the dashboard loads in under 3 seconds at 375px;
- an admin price change shows on /services within 1 minute.
```

### Prompt 9 — Hardening, backups, launch readiness

```
Milestone 9 from docs/PROJECT_BRIEF.md: sections 9 and 10.

Do the following:
- add rate limits and honeypots;
- add customer delete/anonymise for DPDP;
- add one-click exports;
- set up spatie/laravel-backup with daily backups kept 30 days on off-server storage, and write docs/RESTORE.md, tested by restoring locally;
- write docs/ADMIN_GUIDE.md covering: add a service, change a price, create an invoice, run a health check, edit website content;
- write docs/DEPLOY.md for Laravel Forge;
- run every acceptance test in section 10 and produce a checklist artifact with pass/fail and evidence for each;
- give the command that removes the demo data.

Ask before touching any production server or credentials. Done when every section 10 item is marked pass or has a written reason.
```
