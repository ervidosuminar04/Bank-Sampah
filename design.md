# Design System — Realive
*Bank Sampah Platform · Mobile & Web*

---

## 1. Brand Identity

### Brand Name
**Realive** — *Re* (recycle/renew) + *alive* (living, thriving). The name carries energy and optimism.

### Logo
The Realive logo consists of two elements:
- **Icon**: An open trash bin with a recycling symbol, filled with floating waste silhouettes (bottles, cups). The bin uses a yellow → green diagonal gradient, with a warm orange/red sun shape peeking from the top-left.
- **Wordmark**: "Realive" in a rounded, semi-bold sans-serif, rendered in a dark green → mid green gradient (left to right).

### Logo Usage Rules
- **Minimum size**: 120px wide on screen, 30mm in print.
- **Clear space**: Equal to the height of the "R" letterform on all sides.
- **On dark backgrounds**: Use full-color logo — the yellow/green gradient reads well on black.
- **On light backgrounds**: Use full-color logo or dark green wordmark variant.
- **Never**: Stretch, recolor, add drop shadows, or place on clashing mid-tone backgrounds.
- **Approved backgrounds**: Black (`#000000`), White (`#FFFFFF`), Deep Forest (`#1A3A1A`), or the brand gradient.

### Design Direction: **Bold Gradient Energy**
Realive's visual identity is built on motion and transformation — expressed through diagonal gradients that shift from warm yellows to energetic greens. The brand feels alive, forward-moving, and community-driven. Not corporate, not clinical — vibrant and accessible.

### Core Metaphor
> Waste comes in warm — it leaves green.

The yellow→green gradient is the visual manifestation of transformation: raw waste (warm, abundant) becoming something valuable and alive (green, growing). Every gradient in the UI echoes this journey.

---

## 2. Color System

### Brand Palette — Extracted from Assets

```
/* Warm Spectrum (Energy / CTA) */
--color-solar:      #FFD700   /* Pure yellow — brand primary */
--color-sunburst:   #FFA500   /* Amber orange — mid warm */
--color-ember:      #F5511E   /* Vivid orange — gradient mid */
--color-flame:      #E63946   /* Red-orange — gradient end / alert */

/* Green Spectrum (Brand / Growth) */
--color-lime:       #C8E000   /* Yellow-green — gradient start */
--color-sprout:     #7DB825   /* Mid green — interactive */
--color-forest:     #2D6A2D   /* Deep forest — brand anchor */
--color-canopy:     #1A3A1A   /* Very dark green — text on light */

/* Neutrals */
--color-black:      #0A0A0A   /* Near-black — dark pattern bg */
--color-white:      #FFFFFF   /* Pure white — light surfaces */
--color-mist:       #F4F7F0   /* Warm white-green — page bg */
--color-fog:        #8A9E8A   /* Muted green-grey — disabled */
--color-smoke:      #D4DDD4   /* Light grey-green — borders */
```

### Brand Gradients

These are the DNA of Realive's visual identity. Use them consistently.

```css
/* Primary Brand Gradient — yellow to forest green (diagonal) */
--gradient-brand:
  linear-gradient(135deg, #FFD700 0%, #7DB825 50%, #2D6A2D 100%);

/* Warm Accent Gradient — yellow to red (horizontal, energy/alerts) */
--gradient-warm:
  linear-gradient(90deg, #FFD700 0%, #F5511E 60%, #E63946 100%);

/* Dark Pattern Background — for pattern overlays */
--gradient-dark-bg:  #000000;

/* Light Pattern Background — for light pattern variant */
--gradient-pattern-light:
  linear-gradient(135deg, #FFD700 0%, #7DB825 50%, #2D6A2D 100%);
```

### Semantic Tokens

```
--bg-page:          var(--color-mist)       /* app background */
--bg-surface:       var(--color-white)      /* cards, sheets */
--bg-dark:          var(--color-black)      /* dark sections, pattern bg */
--text-primary:     var(--color-canopy)     /* main body text */
--text-secondary:   var(--color-forest)     /* secondary text */
--text-muted:       var(--color-fog)        /* captions, placeholders */
--text-on-dark:     var(--color-white)      /* text on dark/gradient bg */
--text-on-gradient: var(--color-white)      /* text on brand gradient */
--accent-cta:       var(--color-solar)      /* primary CTA */
--accent-success:   var(--color-forest)     /* success states */
--accent-alert:     var(--color-flame)      /* errors, warnings */
--border-default:   var(--color-smoke)      /* input borders */
--border-focus:     var(--color-sprout)     /* focus ring */
```

### Usage Rules
- **Primary CTA buttons**: `solar` yellow background with `canopy` dark green text — high contrast, energetic.
- **Brand sections / hero**: Always use `--gradient-brand` (yellow→green).
- **Alerts / Errors**: `--gradient-warm` or solid `flame` — never pure red.
- **Dark mode / dark sections**: `black` background with pattern overlay in gradient silhouettes.
- **Never** mix warm gradient with cool/blue tones — the palette is deliberately warm-to-green only.

---

## 3. Patterns & Textures

Realive has two signature background patterns using waste item silhouettes. These are core brand assets.

### Pattern 1 — Dark Edition
- **Background**: `#000000` (pure black)
- **Motifs**: Bottles, cups, gallons, recycle symbols — scattered at random rotations
- **Fill**: Yellow→green gradient applied to each silhouette
- **Usage**: Hero sections, splash screens, onboarding backgrounds, dark cards, marketing materials

### Pattern 2 — Light Edition
- **Background**: `--gradient-brand` (yellow→forest green, diagonal)
- **Motifs**: Same waste silhouettes — but filled **white** with slight transparency
- **Usage**: Section dividers, feature card backgrounds, pull-quote sections, empty state panels

### Pattern Rules
- Scale pattern at **60–80% opacity** when used behind readable text.
- Never crop a pattern at an awkward point — tile it properly or mask with a gradient fade.
- Pattern density: keep ~30% of the background visible (icons don't crowd).
- On mobile, use patterns sparingly — full-screen pattern only on splash/onboarding.

### Pattern Asset Paths
```
/assets/patterns/pattern-dark.png    — black bg, gradient silhouettes
/assets/patterns/pattern-light.png   — gradient bg, white silhouettes
```

---

## 4. Typography

The logo wordmark uses a rounded semi-bold sans-serif. Match this energy in the UI.

### Font Pairing

| Role | Font | Weight | Notes |
|------|------|--------|-------|
| Display / Hero | **Nunito** | 800, 900 | Rounded, energetic — matches logo spirit |
| Heading | **Nunito** | 700 | Consistent family, strong hierarchy |
| Body | **Nunito Sans** | 400, 600 | Companion sans for readability |
| Mono / Data | **JetBrains Mono** | 400 | Weight values, IDs, transaction codes |
| Label / Badge | **Nunito Sans** | 700 | All-caps, letter-spaced |

### Import
```css
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap');
```

### Scale

```
--text-xs:    0.75rem   / 12px  — badges, captions
--text-sm:    0.875rem  / 14px  — labels, helper text
--text-base:  1rem      / 16px  — body copy
--text-lg:    1.125rem  / 18px  — lead / intro text
--text-xl:    1.25rem   / 20px  — subheadings
--text-2xl:   1.5rem    / 24px  — section titles
--text-3xl:   1.875rem  / 30px  — page titles
--text-4xl:   2.25rem   / 36px  — hero mobile
--text-5xl:   3rem      / 48px  — hero desktop
--text-6xl:   4rem      / 64px  — marketing display
```

### Rules
- Line height: `1.55` for body, `1.15` for headings.
- Letter spacing: `-0.01em` for large headings, `0.1em` for uppercase labels.
- Max line length: `62ch` for body text on all screens.
- **Gradient text**: Use `--gradient-brand` with `background-clip: text` for hero headlines only — sparingly.

---

## 5. Spacing System

8px base grid.

```
--space-1:   4px
--space-2:   8px
--space-3:   12px
--space-4:   16px
--space-5:   20px
--space-6:   24px
--space-8:   32px
--space-10:  40px
--space-12:  48px
--space-16:  64px
--space-20:  80px
--space-24:  96px
```

---

## 6. Border Radius

Realive uses **generously rounded** corners — matching the logo's rounded trash bin icon.

```
--radius-sm:   8px     — inputs, chips, small buttons
--radius-md:   16px    — cards, modals
--radius-lg:   24px    — panels, bottom sheets
--radius-xl:   36px    — hero cards, feature blocks
--radius-full: 9999px  — badges, avatars, pill buttons
```

> **Brand note**: The logo icon has a distinctive rounded trapezoid shape. Echo this in card designs — wider top, slightly narrower feel — using generous top radius with standard bottom radius: `border-radius: 24px 24px 16px 16px`.

---

## 7. Elevation & Shadow

Shadows use green-tinted tones to stay on-brand.

```css
--shadow-sm:    0 1px 4px rgba(26, 58, 26, 0.08);
--shadow-md:    0 4px 16px rgba(26, 58, 26, 0.12);
--shadow-lg:    0 8px 32px rgba(26, 58, 26, 0.18);
--shadow-xl:    0 16px 56px rgba(26, 58, 26, 0.24);
--shadow-glow:  0 0 24px rgba(255, 215, 0, 0.35);   /* solar glow — for CTA/rewards */
--shadow-focus: 0 0 0 3px rgba(125, 184, 37, 0.45); /* sprout focus ring */
```

> **Signature effect**: The solar glow (`--shadow-glow`) appears on reward badges and point milestones — a warm yellow halo that makes achievements feel earned.

---

## 8. Iconography

### Style
- **Type**: Filled silhouette — matching the brand pattern aesthetic.
- **Stroke**: None (filled, not outlined) — consistent with the logo illustration style.
- **Gradient**: Apply `--gradient-brand` to feature icons; use single color for UI icons.
- **Library**: Phosphor Icons (filled variant) or custom SVG.
- **Sizes**: 16px (inline), 20px (UI/nav), 24px (standard), 32px (feature), 48px (empty states), 64px (hero icons).

### Waste Category Icons
Match the pattern silhouette shapes already in the brand:

| Category | Shape | Color |
|----------|-------|-------|
| Plastik (Botol) | Bottle silhouette | `--gradient-brand` |
| Plastik (Galon) | Jug silhouette | `sprout` |
| Kertas | Folded sheet | `sunburst` |
| Logam / Kaleng | Can shape | `fog` (steel) |
| Kaca | Wine bottle | `lime` |
| Gelas / Cup | Disposable cup with straw | `solar` |
| Simbol Daur Ulang | Recycle triangle | `--gradient-brand` |
| B3 | Diamond hazard | `flame` + warning label |

---

## 9. Component Library

### Buttons

```
Primary:     bg=solar (#FFD700), text=canopy, border-radius=full
             hover: bg=sunburst, scale 1.02, shadow-glow
             → Use for main CTAs: "Setorkan", "Daftar", "Konfirmasi"

Secondary:   bg=white, border=2px solid forest, text=forest, radius=full
             hover: bg=mist, border-color=sprout

Ghost:       bg=transparent, text=forest, radius=full
             hover: bg=forest/08

Gradient:    bg=--gradient-brand, text=white, radius=full, shadow=md
             → Use for hero actions, featured promotions

Danger:      bg=flame/10, border=flame, text=flame, radius=full
```

All buttons: `font-family: Nunito Sans`, `font-weight: 700`, `min-height: 48px`, `padding: 0 24px`.

### Cards

```
Standard:    bg=white, shadow=md, radius=md (16px)
Dark:        bg=black, pattern overlay, text=white, radius=md
Gradient:    bg=--gradient-brand, text=white, shadow=lg, radius=lg
Reward:      bg=solar, text=canopy, shadow-glow, radius=lg
             → Point milestones, achievement unlocks
Stat:        bg=mist, border-left=3px solid sprout, radius=sm
```

### Form Inputs

- Background: `white`
- Border: `2px solid var(--color-smoke)`
- Border-radius: `var(--radius-sm)` (8px)
- Focus: `border-color: sprout` + `shadow-focus` ring
- Error: `border-color: flame`
- Label: always above input, `font-weight: 700`, `color: canopy`
- Height: `52px` on mobile, `48px` on desktop

### Bottom Sheet (Mobile)
- Handle bar: `4px × 36px`, `border-radius: full`, `bg: smoke`
- Background: `white`
- Top radius: `24px 24px 0 0`
- Backdrop: `rgba(0, 0, 0, 0.6)` blur `6px`
- Slide-up: `cubic-bezier(0.34, 1.56, 0.64, 1)` 350ms

### Navigation

**Mobile** — Bottom Tab Bar:
- Height: `64px` + safe area inset
- Background: `white`, top border `smoke`
- Active: icon filled with `--gradient-brand`, label in `forest` bold
- Inactive: icon `fog`, label `fog`
- Active indicator: small pill in `solar` behind icon

**Web** — Left Sidebar:
- Width: `256px`, collapses to `80px`
- Background: `canopy` (very dark green)
- Logo area: full brand logo on expanded, icon-only on collapsed
- Active item: `--gradient-brand` left border `4px` + `bg: forest/40`
- Text: `white` active, `fog` inactive

---

## 10. Motion & Animation

### Principles
- **Energetic but not frantic**: Brand energy is high — animations should feel alive, not jittery.
- **Gradient in motion**: Where possible, animate the gradient — shimmer, sweep, or reveal.
- **Celebrate the green**: Point gains, successful deposits, and milestones get the full treatment.

### Timing Tokens

```
--duration-instant:    80ms
--duration-fast:       150ms
--duration-normal:     260ms
--duration-slow:       420ms
--duration-deliberate: 650ms

--ease-standard:  cubic-bezier(0.4, 0, 0.2, 1)
--ease-enter:     cubic-bezier(0, 0, 0.2, 1)
--ease-exit:      cubic-bezier(0.4, 0, 1, 1)
--ease-spring:    cubic-bezier(0.34, 1.56, 0.64, 1)  /* rewards, CTA press */
--ease-settle:    cubic-bezier(0.22, 1, 0.36, 1)     /* cards, sheets */
```

### Signature Animations

**Gradient Sweep** (hero/loading):
```css
@keyframes gradientSweep {
  0%   { background-position: 0% 50%; }
  50%  { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
/* Apply to: background-size: 200% 200%; animation: 4s ease infinite */
```

**Recycle Spin** (loading indicator — brand-specific):
```css
@keyframes recycleSpin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}
/* Use the recycling symbol icon; duration: 1200ms linear infinite */
```

**Point Rise** (reward moment):
```css
@keyframes pointRise {
  0%   { transform: translateY(12px) scale(0.8); opacity: 0; }
  60%  { transform: translateY(-6px) scale(1.1); opacity: 1; }
  100% { transform: translateY(0) scale(1); opacity: 1; }
}
/* Duration: 450ms, ease-spring. Add solar glow shadow. */
```

**Solar Pulse** (CTA attention):
```css
@keyframes solarPulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.5); }
  50%       { box-shadow: 0 0 0 12px rgba(255, 215, 0, 0); }
}
/* Duration: 1800ms ease infinite — idle CTA on dashboard */
```

**Page Entry** (staggered):
```css
@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
/* Each child: animation-delay: calc(var(--i) * 70ms) */
```

---

## 11. Waste Category Colors

Adapted to stay within the warm→green brand palette:

```
Plastik Botol:  #7DB825  (sprout green)
Plastik Galon:  #2D6A2D  (forest)
Kertas:         #FFA500  (sunburst — warm kraft feel)
Logam:          #8A9E8A  (fog — metallic muted)
Kaca:           #C8E000  (lime — glassy clarity)
Gelas / Cup:    #FFD700  (solar — most collected item)
Organik:        #4A8C2A  (mid green)
B3 / Berbahaya: #E63946  (flame red — always paired with ⚠ icon)
```

---

## 12. Responsive Breakpoints

```
--bp-mobile-sm:  320px   /* small phones */
--bp-mobile:     390px   /* standard phone (design base) */
--bp-mobile-lg:  430px   /* large phones */
--bp-tablet:     768px   /* tablet portrait */
--bp-tablet-lg:  1024px  /* tablet landscape */
--bp-desktop:    1280px  /* desktop */
--bp-wide:       1536px  /* wide screen */
```

### Layout Grids

| Breakpoint | Columns | Gutter | Margin |
|------------|---------|--------|--------|
| Mobile | 4 | 16px | 16px |
| Tablet | 8 | 24px | 32px |
| Desktop | 12 | 24px | 48px |
| Wide | 12 | 32px | auto (max 1440px) |

---

## 13. Illustration Style

Based on the brand illustration assets:

- **Style**: Flat vector with gradient fills — no outlines, no textures.
- **Gradient direction**: Always diagonal top-left (yellow/lime) to bottom-right (dark green).
- **Subject matter**: Waste items as heroes — bottles, gallons, cups, the recycle symbol. Humanized and approachable.
- **Background approach**: Either pure black (dark pattern) or the brand gradient (light pattern).
- **Silhouette density**: Items are scattered randomly, varied in rotation and scale.
- **Empty States**: Use the recycling bin illustration (from logo assets) at 50% opacity + encouraging message.
- **No photography**: Brand is fully illustrative. Photos only in user-generated content (profile pics, deposit photos).

---

## 14. Platform-Specific Notes

### Mobile (React Native / Flutter)
- Minimum touch target: `44 × 44px` (iOS HIG & Material standards)
- Haptic feedback on: successful deposit, point rewards, milestone achievements, barcode scan
- Status bar: light content (white icons) on dark/gradient screens
- Bottom safe area: always respected
- Skeleton loaders: `smoke` → `mist` shimmer on card placeholders
- Pull-to-refresh: animate the recycling symbol spinning with `recycleSpin`
- App icon: trash bin illustration on gradient background

### Web (React / Next.js)
- Custom cursor: default arrow, pointer on interactive elements (no custom cursor shape)
- Focus-visible: always `shadow-focus` ring — never remove for keyboard users
- Hover on cards: `translateY(-4px)` + increased shadow — subtle lift
- Sidebar at `<1024px`: slides in as overlay drawer
- Data tables: `JetBrains Mono` for weight (kg) and point columns

---

## 15. Accessibility

- **Contrast**: Solar yellow (`#FFD700`) on dark green (`#2D6A2D`) passes WCAG AA for large text. For body text on yellow, always use `canopy` (`#1A3A1A`).
- **Yellow warning**: `solar` yellow on white fails contrast — never use yellow text on white backgrounds.
- **Focus**: `shadow-focus` ring always visible, never removed.
- **Motion**: Honor `prefers-reduced-motion` — disable `gradientSweep`, `solarPulse`, and `recycleSpin`; keep layout transitions.
- **Touch targets**: All interactive elements minimum `44 × 44px`.
- **Color alone**: Never communicate category or status with color alone — always pair with icon or text label.
- **Screen reader**: All pattern backgrounds are `aria-hidden`; icons have `aria-label`; status updates use `role="status"`.

---

## 16. Voice & Microcopy

| Situasi | Nada | Contoh |
|---------|------|--------|
| Empty state | Semangat, mengundang | "Belum ada setoran nih. Yuk mulai daur ulang bareng Realive!" |
| Sukses setoran | Merayakan | "Yeay! +3.2 kg berhasil tercatat. Kamu makin keren! 🌱" |
| Poin bertambah | Energik | "+150 poin masuk! Terus semangat ya!" |
| Error | Jujur, membantu | "Oops, ada yang salah. Coba lagi atau hubungi bank sampah kamu." |
| Loading | Aktif, bermakna | "Lagi nyiapin data kamu..." |
| Onboarding | Hangat, jelas | "Halo! Pilih bank sampah terdekat untuk mulai." |
| Milestone | Merayakan besar | "Luar biasa! Kamu udah kumpulkan 100 kg sampah. Bumi berterima kasih! 🌍" |

- **Bahasa Indonesia** sebagai bahasa utama.
- Gunakan "kamu" (bukan "Anda") — akrab, bukan formal.
- Selalu beri langkah selanjutnya saat terjadi error.
- Emojis boleh digunakan pada momen selebrasi — jangan berlebihan.

---

## 17. Asset Inventory

| Asset | File | Usage |
|-------|------|-------|
| Logo lengkap | `logo_Realive_3x.png` | Header, splash screen, about page |
| Logo altenatif | `white logo@4x.png` | Header, splash screen, about page, use for dark or gradient backgrounds |
| Logo altenatif | `yellow logo@4x.png` | Header, splash screen, about page, use for dark or gradient backgrounds |
| Logo altenatif | `green logo@4x.png` | Header, splash screen, about page, use for light or gradient backgrounds |
| Ikon tempat sampah | `Ilustrasi_3x.png` | Empty states, onboarding, feature cards |
| Palet warna | `color_palette_3x.png` | Design reference |
| Pattern color 1 | `Pattern_1_3x.png` | Hero sections, light backgrounds |
| Pattern white 1 | `Pattern_2_3x.png` | use for gradient background |
| Pattern white 2 | `white pattern@4x.png` | use for gradient background|
| Pattern color 2 | `white pattern@4x.png` | use for gradient background|
---

*Last updated: May 2026 · Realive Design System v1.0*
