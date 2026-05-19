# UI Generation Prompt

## Tujuan

Dokumen ini berisi prompt untuk generate UI dengan gaya `neo-brutalism` yang fleksibel untuk berbagai jenis project. Fokusnya ada di visual direction, bukan domain tertentu.

## Master Prompt

```text
Design a web interface in a strong neo-brutalist editorial style.

Visual direction:
- high contrast
- sharp rectangular shapes
- thick black borders
- hard box shadows with no blur
- bold uppercase typography
- structured but slightly asymmetric layout
- solid color blocking instead of soft gradients
- raw, tactile, poster-like interface
- no generic SaaS look
- no soft modern dashboard style
- no rounded floating cards

Core palette:
- warm off-white or cream background
- black ink for borders and typography
- strong red accent for primary emphasis or alerts
- bright yellow for highlights and information blocks
- soft violet for secondary panels
- optional green for positive states

Style references:
- editorial poster
- control board
- printed interface
- brutalist web layout
- notice board with sharp hierarchy

Components should feel:
- bold
- dense
- tactile
- functional
- immediate

Typography:
- use Space Grotesk as the primary typeface
- use a bold grotesk / geometric sans feel matching Space Grotesk
- heavy headings
- uppercase labels with wide tracking
- text hierarchy should be obvious at a glance

Spacing and layout:
- dense but readable
- avoid excessive empty space
- sections must feel intentional
- allow controlled asymmetry without hurting usability

Interactions:
- buttons should feel mechanical and pressed
- hover states should be subtle but sharp
- focus states should use contrast, not soft glow
- forms must stay clear and practical

Responsive behavior:
- must work on desktop and mobile
- desktop should use a left sidebar and main content area when appropriate
- mobile should stack cleanly without losing the visual identity

App shell:
- use a sticky topbar / header
- use a desktop left sidebar navigation
- use horizontal mobile navigation when needed
- use badge-like status labels in the header area
- preserve the same shell behavior across pages for consistency

Dark mode:
- preserve the brutalist character
- keep contrast high
- do not turn it into neon cyberpunk
- include a visible light / dark mode toggle
- allow the user to switch themes manually
- keep the same typography, border weight, and brutalist identity in both modes

Tables:
- table rows and cells should feel like a ledger
- use strong borders and clear row separation
- keep table cell content vertically aligned to the middle by default
- preserve readable alignment for dense tabular content
- avoid overly airy table spacing

Forms:
- inputs, selects, and textareas should use the same brutalist system
- focus states should use contrast and background shifts, not glow
- controls should match the same font and border behavior as the rest of the UI

Do not use:
- glassmorphism
- soft shadows
- blurry depth
- pastel SaaS gradients
- generic startup UI patterns
- overused rounded cards
- decorative illustrations that weaken the layout

The final result should feel strict, bold, tactile, and editorial.
```

## Prompt Untuk Dashboard / Panel

```text
Create a dashboard or control panel interface in a neo-brutalist editorial style.

Requirements:
- strong content hierarchy
- bold section blocks
- thick borders on important areas
- hard shadows with no blur
- large focal headline or key metric area
- supporting panels for secondary content
- structured but slightly asymmetric composition
- use Space Grotesk typography
- include the same desktop sidebar and sticky header shell
- include a light / dark mode toggle in the header
- use badge-like labels inside the top area

The design should feel like a control board, not a generic admin template.
```

## Prompt Untuk CRUD / Data Management Page

```text
Create a CRUD or data management page in a neo-brutalist style.

The page should include:
- bold page header
- strong filter or utility section
- practical form layout
- a table or list with heavy visual structure
- clear action buttons for create, edit, delete, or save

Visual requirements:
- thick borders
- hard shadows
- no rounded corners
- dense but readable spacing
- table should feel structured and tactile, not minimal or flat
- use Space Grotesk consistently
- keep table content vertically aligned in the middle
- reuse the same sidebar + sticky header shell
- include a light / dark mode toggle
```

## Prompt Untuk Form Page

```text
Create a form-focused page in neo-brutalist style.

Requirements:
- bold labels
- clear input grouping
- large, practical inputs
- strong submit and secondary buttons
- high contrast focus states
- optional side panel for info, help text, or status
- use Space Grotesk consistently
- reuse the same sticky header and sidebar shell when this page is inside an app
- include a light / dark mode toggle when relevant

The form should feel immediate, usable, and visually assertive.
```

## Prompt Untuk Landing Page

```text
Create a landing page in a neo-brutalist editorial web style.

The page should include:
- a bold hero section
- strong typography
- sharp bordered content blocks
- high contrast CTA buttons
- feature sections with visual hierarchy
- a layout that feels more like a poster or control board than a startup website
- use Space Grotesk typography
- include visible light / dark mode switching if the page is part of a product surface

Do not use soft marketing visuals or generic SaaS composition.
```

## Prompt Untuk Mobile UI

```text
Create a mobile-first interface in neo-brutalist style.

Requirements:
- preserve thick borders and hard shadows
- stacked layout with strong hierarchy
- bold buttons and labels
- compact but readable sections
- maintain the same brutalist identity without becoming cluttered
- preserve the same typeface, shell logic, and theme behavior from desktop
```

## Prompt Pendek

```text
Generate a neo-brutalist web UI with Space Grotesk typography, a desktop left sidebar, a sticky header, light/dark mode toggle, thick black borders, hard shadows, cream background, bold uppercase typography, sharp rectangular sections, vertically centered ledger-style tables, strong color blocking, editorial layout, and no generic SaaS styling.
```

## Variabel Yang Bisa Ditambah

Tambahkan konteks sesuai project:

- jenis produk atau aplikasi
- jenis halaman
- daftar komponen yang dibutuhkan
- target device
- stack implementasi seperti `Blade`, `Tailwind CSS`, `jQuery`, atau `HTML`

Contoh tambahan:

```text
for an inventory system
for a booking admin panel
for a school dashboard
for a reporting page
for a settings form
implemented with Laravel Blade and Tailwind CSS
```

## Catatan Pakai

- Pakai `Master Prompt` sebagai base
- Tambahkan konteks domain project setelah itu
- Kalau perlu, gabungkan dengan prompt halaman yang relevan
- Kalau targetnya code generator, sebut stack implementasi secara eksplisit
