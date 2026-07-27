# JHG Website — Design System: Figma vs CSS

> **Purpose:** Living reference that lists the design setup defined in the Figma file and
> compares it against what is actually implemented in the `jhg-maven` theme CSS.
> Update this file whenever the Figma design or the theme tokens change.

- **Figma file:** [JHG Website](https://www.figma.com/design/djQpDT648clwVUKB8hGYi1/JHG-Website?node-id=0-1)
- **Theme:** `/wp-content/themes/jhg-maven/`
- **Primary token source (CSS):** [`assets/css/jhg-base.css`](../assets/css/jhg-base.css) — `:root { … }`
- **Font loaded in:** [`wp-content/themes/jhg-maven/inc/enqueue.php`](wp-content/themes/jhg-maven/inc/enqueue.php) (`jhg-fonts` → `assets/css/font.css`)
- **Last updated:** 2026-07-21
- **Figma scope sampled:** All 15 desktop frames swept (Home, Team, JHG Story, Why-JHG,
  Services + 5 sub-service pages, Courses, Resources, Blog, Blog single, Contact). See
  [Section 5 — Per-page audit](#5-per-page-audit).

---

## 1. How the values were obtained

- **CSS side:** read directly from the theme source (exact, authoritative).
- **Figma side:** inspected element-by-element in the Figma **Design panel** (view access only;
  Dev Mode / named styles are locked on the current plan, and the file has **no published
  color/text styles** — values are applied directly to layers). Figma values are therefore
  *sampled* from real layers on the `Desktop-Home` frame, not read from a token library.

---

## 2. Figma design setup (sampled)

### Layout
| Property | Figma value |
|---|---|
| Canvas / artboard width | **1440 px** (`Desktop-Home`, fixed) |
| Content column (layout grid) | **1200 px** ("1200px CT" layout guide style) |
| Frame side padding (home) | 243 px L/R |
| Decorative panel corner radius | **50 px** (asymmetric: top-right + bottom-left) |

### Color
| Role | Figma hex |
|---|---|
| Page background | **#F4F5F8** |
| Primary / heading navy | **#0A295D** |
| Accent / divider red | **#ED1C24** |
| Body / paragraph text | **#646464** (neutral grey) |
| Gradient panels | Raster image `Background New.png` (magenta → purple → blue). Not a live gradient token. |

### Typography
| Style | Font | Weight | Size | Line height | Case | Colour |
|---|---|---|---|---|---|---|
| Section title / eyebrow ("WHAT WE DO") | Avant Garde | 7 (Bold) | **28 px** | 20 px | UPPERCASE | #0A295D |
| Sub-heading ("Full-Service HR…") | Avant Garde | 5 (Book) | **24 px** | 34 px | — | #646464 |
| Lead / intro paragraph | Avant Garde | 5 (Book) | **24 px** | ~ | — | #646464 |
| Running body paragraph | Avant Garde | 5 (Book) | **18 px** | 28 px | — | #646464 |

> **Font family in Figma = `Avant Garde` (ITC Avant Garde Gothic).** Weight numbers are the
> Avant Garde numeric naming: `5` ≈ Book/Regular, `7` ≈ Bold.

---

## 3. CSS design setup (authoritative — `jhg-base.css`)

### Color tokens
| Token | Value | Notes |
|---|---|---|
| `--jhg-navy` | #16215a | primary navy (headings) |
| `--jhg-navy-deep` | #0d1640 | darker navy (footer, offcanvas) |
| `--jhg-red` | #ed1c24 | CTA / accent |
| `--jhg-red-dark` | #cc171e | red hover |
| `--jhg-magenta` | #b81d7a | gradient start / eyebrow |
| `--jhg-purple` | #3a1d74 | gradient end |
| `--jhg-gold` | #f5a623 | highlight |
| `--jhg-ink` | #1a1a1a | default body text |
| `--jhg-muted` | #6b7280 | muted text |
| `--jhg-stone` | #78716c | secondary muted |
| `--jhg-light` | #f4f5f8 | page background |
| `--jhg-white` | #ffffff | — |
| `--jhg-gradient` | `linear-gradient(110deg, #b81d7a 0%, #3a1d74 100%)` | brand gradient |

### Font
| Property | Value |
|---|---|
| `--jhg-font` | `"Avant Garde", system-ui, -apple-system, "Segoe UI", sans-serif` |
| Faces | **Book** (`AvantGardeBook` → weight range 100–500) · **Bold** (`AvantGardeBold` → 600–900) |
| Source | Self-hosted in `assets/font/`; declared in [`assets/css/font.css`](wp-content/themes/jhg-maven/assets/css/font.css) |

### Type scale (rem, `1rem = 16px`)
| Token | Clamp / value | ≈ px range |
|---|---|---|
| `--jhg-title-display-size` | clamp(2.25rem, 5vw, 3.75rem) | 36 → 60 |
| `--jhg-title-3xl-size` | clamp(1.5rem … 1.875rem) | 24 → 30 |
| `--jhg-title-2xl-size` | clamp(1.75rem … 2.5rem) | 28 → 40 |
| `--jhg-title-xl-size` | clamp(1.25rem … 1.5rem) | 20 → 24 |
| `--jhg-title-lg-size` | clamp(1.125rem … 1.25rem) | 18 → 20 |
| `--jhg-title-md-size` | clamp(1.375rem … 1.5rem) | 22 → 24 |
| `--jhg-title-sm-size` | 1rem | 16 |
| `--jhg-body-xl-size` | clamp(1.25rem … 1.5rem) | 20 → 24 |
| `--jhg-body-lg-size` | 1.125rem | 18 |
| `--jhg-body-md-size` (base) | clamp(1rem … 1rem) | 16 |
| `--jhg-body-sm-size` | clamp(0.8125rem … 0.9375rem) | 13 → 15 |
| `--jhg-body-xs-size` | 0.75rem | 12 |

### Other tokens
| Token | Value | ≈ px |
|---|---|---|
| `--jhg-radius` | 0.875rem | 14 |
| `--jhg-radius-sm` | 0.625rem | 10 |
| `--jhg-btn-radius` | 0.625rem | 10 |
| `--jhg-btn-min-height` | 3rem | 48 |
| `--jhg-section-gap` | 2.1875rem | 35 |
| Button text | uppercase, weight 400, letter-spacing 0.02em | — |

---

## 4. Comparison — Figma vs CSS

Legend: ✅ match · ⚠️ close / needs decision · ❌ mismatch

| # | Item | Figma | CSS | Verdict | Note |
|---|---|---|---|---|---|
| 1 | **Font family** | Avant Garde | Avant Garde (self-hosted) | ✅ | Integrated 2026-07-21 from `assets/font/` (Book + Bold). |
| 2 | Page background | #F4F5F8 | #f4f5f8 (`--jhg-light`) | ✅ | Exact. |
| 3 | Accent red | #ED1C24 | #ed1c24 (`--jhg-red`) | ✅ | Exact. |
| 4 | **Primary navy** | #0A295D | #16215a (`--jhg-navy`) | ❌ | Different shade. Figma rgb(10,41,93) vs CSS rgb(22,33,90). |
| 5 | **Body text colour** | #646464 | #6b7280 (`--jhg-muted`) / #1a1a1a (`--jhg-ink`) | ⚠️ | Figma is a pure grey; CSS muted is cooler. Not exact. |
| 6 | Running body size | 18 px | 18 px (`--jhg-body-lg-size`) | ✅ | Maps to `body-lg`. |
| 7 | Lead / intro size | 24 px | 24 px (`--jhg-body-xl-size` max) | ✅ | — |
| 8 | Section title size | 28 px | 30 px (`title-3xl`) / 40 (`title-2xl`) | ⚠️ | Close to `title-3xl` top end. |
| 9 | Content width | 1200 px | Bootstrap `.container` | ⚠️ | Confirm container max-width equals 1200 px. |
| 10 | Brand gradient | Raster image (magenta→purple→blue) | `linear-gradient(110deg, #b81d7a → #3a1d74)` | ⚠️ | CSS is a live 2-stop gradient; Figma uses a 3-tone image. Visual endpoints differ. |
| 11 | Panel corner radius | 50 px | 14 px (`--jhg-radius`) | ❌ | Figma uses large 50px asymmetric corners on colour panels; theme uses 14px + separate corner-image decorations. |

### Headline takeaways
1. **Font family** now matches Figma (self-hosted Avant Garde Book + Bold).
2. **Navy shade** and **body-grey shade** differ slightly from the design and should be reconciled.
3. **Red** and **page background** are pixel-accurate. ✅

---

## 5. Per-page audit

All 15 desktop frames were swept in Figma. **Headline result: the design system is highly
consistent across every page** — same font (Avant Garde), same `#F4F5F8` background, same
navy/grey/red palette, 1440 px canvas, and shared components. Only minor hex drift exists
*within the Figma itself* (see [§5.2](#52-inconsistencies-inside-the-figma-itself)).

Every page shares this baseline: **canvas 1440 px · bg #F4F5F8 · font Avant Garde · side
padding 243 px · section title 28 px bold uppercase #0A295D · sub-heading/lead 24 px · body
18 px · body colour #646464 · accent #ED1C24**. The table below lists only what is *new or
notable* per page.

| Frame | Height | Status | New / notable components & tokens |
|---|---|---|---|
| Desktop-Home | 5436 | ✅ baseline (reference) | Hero = raster image. Services band = `Background New.png` gradient, radius 50px. |
| Desktop-Team | 3710 | ✅ consistent | **Team card:** name Avant Garde Bold 24px #646464 (center); role Avant Garde Regular 18px; hairline divider #9B9B9B. |
| Desktop-JHG Story | 5017 | ✅ consistent | **"Meet the Founder" band** reuses `Background New.png` gradient (radius 50px, top-right + bottom-left), body text white #FFFFFF 18px. Core-services icon grid. |
| Desktop-Why-JHG | 6514 | ✅ consistent | Image+text sections, gradient feature cards (magenta/blue, 50px corners), testimonials, checklists. No new tokens. |
| Desktop-Services | — | ✅ consistent (inferred) | Services overview; shares service/card components. Not deep-sampled. |
| Desktop-Services_Health_&_Safety | 2332 | ✅ consistent | Service-detail template: hero image + text sections. Representative of all 5 sub-service pages. |
| Desktop-Services_Payroll_Services | — | ✅ consistent | Same service-detail template. |
| Desktop-Services_JHG_Membership | 4011 | ✅ consistent | **Pricing cards:** white bg, radius **30px**, border 2px **#1D2D5B**; plan price Avant Garde Bold **30px** (red #E30A13 on highlighted plan, navy otherwise), "/annually" suffix 20px bold; pill buttons (navy + red). |
| Desktop-Services_Employment_Equity | — | ✅ consistent | Same service-detail template. |
| Desktop-Services_HR_Retainer_Services | — | ✅ consistent | Same service-detail template. |
| Desktop-Courses | 5948 | ✅ consistent | Info page: hero + alternating image/text sections + gradient band. No new tokens. |
| Desktop-Resources | — | ✅ consistent | Resource card grid. No new tokens. |
| Desktop-Blog | — | ✅ consistent | Blog listing card grid. No new tokens. |
| Desktop Actual Blog Page Layout | 3998 | ✅ consistent | Blog single: hero + article body (18px baseline) + related-post card grid. |
| Desktop_Contact | 2760 | ✅ consistent | **Form field:** white bg, height **50px**, radius **7px**, padding 7/15px; map + details block; red "Send" submit button. |

### 5.1 New component tokens discovered
These were resolved at module level in [§6](#6-module-level-css-comparison) — the figures below
are superseded by that section (two entries I first flagged as mismatches turned out to **match**
once compared to the real module CSS instead of the generic `--jhg-radius` token).

| Component | Figma value | Actual module CSS | Verdict (see §6) |
|---|---|---|---|
| Gradient panel corner radius | 50px (TR + BL) | `3.125rem` (50px) TR + BL | ✅ **match** (was wrongly "mismatch") |
| Pricing card radius | 30px | `1.875rem` = 30px | ✅ **match** (was wrongly "mismatch") |
| Pricing card border navy | #1D2D5B | 2px `--jhg-navy-deep` #0d1640 | ⚠️ width ✓, shade differs |
| Pricing price size | 30px Bold | title utility (≈30px) | ✅ |
| Form field height | 50px | `3rem` = 48px | ⚠️ 2px shorter |
| Form field radius | 7px | `0.375rem` = 6px | ⚠️ 1px tighter |
| Team member name | 24px Bold #646464 | Bold `--jhg-stone` #78716c | ⚠️ grey shade differs |

### 5.2 Inconsistencies *inside the Figma itself*
The design file is not perfectly tokenised — the same role uses slightly different hex values
across frames. Worth flagging to the designer:
- **Navy:** `#0A295D` (headings) vs `#1D2D5B` (pricing-card border/buttons).
- **Red:** `#ED1C24` (accent lines) vs `#E30A13` (pricing price/button).
- **Grey:** `#646464` (body) vs `#9B9B9B` (hairline dividers).

This means "match the Figma exactly" is ambiguous for navy/red — the theme's single
`--jhg-navy` / `--jhg-red` is arguably *cleaner* than the design. Recommend agreeing one
canonical navy and one red with the designer.

---

## 6. Module-level CSS comparison

Figma component values compared against the specific theme module CSS (not the generic tokens).
**Headline: the modules are faithful implementations.** Once measured against the real module
rules, most "mismatches" disappear — radii, structure, buttons and backgrounds match. What
remains is (a) the font family, (b) colour-*shade* drift (which the Figma is itself inconsistent
on), and (c) 1–2px sizing rounding.

### 6.1 Pricing cards — [`modules/pricing-plans.css`](../assets/css/modules/pricing-plans.css)
| Property | Figma | CSS (`.jhg-pricing-card`) | Verdict |
|---|---|---|---|
| Card background | #FFFFFF | `--jhg-white` #ffffff | ✅ |
| Card radius | 30px | `1.875rem` = 30px | ✅ |
| Card border width | 2px | `0.125rem` = 2px | ✅ |
| Card border colour | #1D2D5B | `--jhg-navy-deep` #0d1640 | ⚠️ navy shade |
| Price weight | Bold (700) | 700 | ✅ |
| Price size | 30px | title utility (≈30px) | ✅ |
| Growth price colour | #E30A13 | `--jhg-red` #ed1c24 | ⚠️ red shade |
| Default price colour | navy | `--jhg-navy-deep` #0d1640 | ⚠️ navy shade |
| Feature / name text | ~#646464 grey | `--jhg-stone` #78716c | ⚠️ grey shade |
| CTA button shape | pill | `border-radius: 2.5rem` (40px) pill | ✅ |
| CTA fills | navy / red | `.jhg-btn-navy` / `.jhg-btn-red` | ✅ (shade drift) |
| Card width | 370px | `24rem` = 384px | ⚠️ ~14px wider |

### 6.2 Contact form — [`modules/contact-form.css`](../assets/css/modules/contact-form.css)
| Property | Figma | CSS (`.wpcf7-form-control`) | Verdict |
|---|---|---|---|
| Field background | #FFFFFF | `--jhg-white` | ✅ |
| Field height | 50px | `3rem` = 48px | ⚠️ 2px shorter |
| Field radius | 7px | `0.375rem` = 6px | ⚠️ 1px tighter |
| Field padding | 7 / 15px | `0.375rem 0.875rem` = 6 / 14px | ✅ ~match |
| Field border | light grey | 1px `--jhg-gray-300` #d1d5db | ✅ |
| Placeholder colour | grey | #a3a3a3 | ✅ |
| Submit background | red | `--jhg-red` #ed1c24 | ✅ |
| Submit radius | small | `0.375rem` = 6px | ✅ |
| Submit weight / case | — | 700, uppercase | ✅ |

### 6.3 Team cards — [`modules/team-grid.css`](../assets/css/modules/team-grid.css)
| Property | Figma | CSS (`.jhg-team-card`) | Verdict |
|---|---|---|---|
| Card background | white | `--jhg-white` | ✅ |
| Card radius | ~20px | `1.25rem` = 20px | ✅ |
| Name weight | Bold (700) | 700 | ✅ |
| Name colour | #646464 | `--jhg-stone` #78716c | ⚠️ grey shade |
| Role | 18px uppercase | `--jhg-stone` @ 0.6, uppercase, 400 | ✅ (shade drift) |
| Divider | #9B9B9B, 1px | 1px `--jhg-neutral-400` @ 50% | ✅ close |

### 6.4 Gradient / colour panels — `card-grid.css`, `stats-band.css`, `audience-band.css`, `text-media.css`
| Property | Figma | CSS | Verdict |
|---|---|---|---|
| Panel corner radius | 50px (TR + BL) | `3.125rem` = 50px `border-top-right` + `border-bottom-left` | ✅ **match** |
| Panel fill | `Background New.png` gradient image | CSS gradient / bg-image per module | ⚠️ verify endpoints |

### 6.5 Net conclusion
The module CSS is a **high-fidelity build of the Figma**. Across pricing, contact, team and
gradient-panel modules the structure, radii, borders, buttons and backgrounds all line up. The
only systematic gaps are:
1. **Colour shade drift** — the theme's `--jhg-navy-deep`/`--jhg-red`/`--jhg-stone` vs the
   Figma's per-instance hexes. Because the Figma itself is inconsistent (see §5.2), the theme's
   single-token approach is defensible; just ratify the canonical values.
2. **1–2px rounding** — form field 48 vs 50px, radius 6 vs 7px. Cosmetic; optional to align.
3. **Font** — ✅ Avant Garde Book + Bold self-hosted (2026-07-21).

---

## Open items / TODO
- [x] Decide on font: source **Avant Garde** or ratify **Inter** as the approved build font. → **Avant Garde self-hosted** (`assets/font/` + `assets/css/font.css`).
- [ ] Reconcile primary navy (`#0A295D` vs `#16215a`).
- [ ] Reconcile body text grey (`#646464` vs `#6b7280`).
- [ ] Confirm Bootstrap container max-width vs Figma 1200 px content.
- [x] Sample additional Figma frames — all 15 desktop frames swept (see §5).
- [ ] Agree one canonical navy (#0A295D vs #1D2D5B) and one red (#ED1C24 vs #E30A13) with designer.
- [x] Compare pricing-card styles to `modules/pricing-plans.css` (§6.1 — radius matches 30px).
- [x] Compare contact form field to `modules/contact-form.css` (§6.2 — 48 vs 50px, 6 vs 7px).
- [x] Compare team cards to `modules/team-grid.css` (§6.3).
- [x] Confirm 50px panel corners in CSS (§6.4 — `card-grid`/`stats-band`/`audience-band`/`text-media`).
- [ ] Capture button fills from Figma (#1D2D5B / #E30A13) and ratify vs `.jhg-btn-navy`/`.jhg-btn-red`.
- [ ] Verify gradient endpoints against `Background New.png` (image vs CSS gradient).
- [ ] Optional: bump form field height 48→50px and radius 6→7px if pixel-exact is required.

## Change log
| Date | Change |
|---|---|
| 2026-07-14 | Initial version. CSS tokens read from `jhg-base.css`; Figma values sampled from `Desktop-Home`. |
| 2026-07-14 | Extended audit to all 15 desktop frames (§5). Added per-page findings, new component tokens (pricing cards, form fields, gradient panels, team cards), and documented navy/red/grey hex drift inside the Figma. |
| 2026-07-14 | Added §6 module-level CSS comparison (pricing-plans, contact-form, team-grid, gradient panels). Corrected §5.1: pricing-card radius (30px) and 50px panel corners actually **match** the module CSS. Conclusion: modules are a high-fidelity build; remaining gaps are font family + colour-shade drift + 1–2px rounding. |
| 2026-07-21 | Integrated self-hosted **Avant Garde** (Book + Bold) from `assets/font/`. Replaced Google Fonts Inter with `assets/css/font.css`; `--jhg-font` now `"Avant Garde"`. |
