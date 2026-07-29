# Editor (Pixelwars) — Child Theme Project

## What this project is
A WordPress **child theme** (`editor-wp-child`) that customizes the premium
**Editor** theme by Pixelwars (a classic, PHP-based theme — NOT a block/FSE theme)
to build out **Matt Waldman's Rookie Scouting Portfolio (RSP)** site.
All customizations go in the child theme. The parent is never edited.

## Repo layout
- `editor-wp-child/` — the child theme. This is the only thing that gets edited and deployed.
- `reference/editor-wp/` — the parent theme, READ-ONLY. For reading class names, enqueue logic, and templates to override. Never edit or deploy this.

## Design source (claude.ai/design)
- The visual designs are authored in a claude.ai/design project: **"Matt Waldman RSP Wordpress Design"**, project ID `371cf04f-d343-452c-afd8-5ae372d1fde0`.
- Read it directly with the `DesignSync` tool (`list_files` / `get_file`, projectId above) — no need to ask for a re-exported zip each time; just pull the latest.
- Files are `.dc.html` "Design Component" prototypes (streaming HTML format, not production code — treat rendered layout/color/copy as the source of truth, not the literal markup). Full file list: `Header 1C.dc.html` (canonical header/footer chrome, done), `Article Template.dc.html` (done), `Resources Page.dc.html` (up next), `About Page.dc.html`, `Buy the RSP.dc.html`, `Member Login.dc.html`, `Site Index.dc.html` (dev-only, not for production). Images in `uploads/`. No design file exists for the homepage/blog-listing view — built it anyway (see Current state) reusing the article template's card language rather than leaving the parent's old Bootstrap blog markup in place.
- Design tokens use `oklch()` colors throughout — keep using `oklch()` in child CSS to stay consistent with the design's palette (dark navy `oklch(0.19 0.02 260)`, gold accent `oklch(0.78 0.14 80)`, brighter CTA gold `oklch(0.83 0.15 92)`).
- Fonts: **Manrope** (400–800) + **Cinzel** (600–700) + **Lora** (400, 500, italic 400) all enqueued via Google Fonts in `functions.php`.
- Shared page frame: `#page` is a centered **1280px** near-white column on a grey backdrop, applied once in `style.css` so header/content/footer on every template share it automatically.

## Current state
- PR: [#1](https://github.com/geffen/mattwaldman-wordpress-childtheme/pull/1), branch `header-nav-redesign`.
- Done: full header/nav rebuild (`header.php`) — quickbar, dropdown nav via `wp_nav_menu()` against the existing `pixelwars_theme_menu_location_1` location, mobile hamburger + slide-in accordion menu, "Buy the RSP" gold-sheen CTA. Nav items are seeded programmatically (see `editor_child_seed_primary_menu()` in `functions.php` — one-time, gated by the `editor_child_rsp_menu_seeded` option) rather than requiring manual Appearance > Menus setup.
- Done: Article Template (`single-post.php`) — see spec below. Two shortcodes (`[rsp_video id="..."]`, `[rsp_promo]`) let Matt place the video block and the RSP pitch pull-quote anywhere in a post body. Real dynamic data throughout (categories, tags, prev/next, related posts by category, sidebar category counts).
- Done: Homepage (`home.php`) — replaces the parent's `index.php` → `blog-regular.php` chain. Post grid reuses the article template's related-post-card look (image, category kicker, title), 3/2/1-col responsive, WP core pagination styled to match.
- Done: dark minimal `footer.php` replacing the parent's widget-based one.
- Done: real nav destinations, partial. Two new taxonomies (`rsp_position`: QB/RB/WR/TE, `rsp_draft_class`: open-ended) and three new categories (Film Room, Articles, Podcasts) are registered/seeded in `functions.php`. `editor_child_sync_primary_menu_urls()` (runs every `init`, cheap) keeps the Film Room / Articles / Podcasts nav items pointed at those categories' real archive links, matched by title so manual Appearance > Menus edits aren't clobbered. Still `#`: RSP Draft Guide, Ranking & Projections, About Us (no pages built yet), and Player Evaluation's "by Position"/"by Draft Class" (taxonomies exist but no landing-page template yet — add their resolved URLs to `$url_map` in that function once one exists).
- Next up: **Resources / About / Buy the RSP / Member Login page templates**.
- Test site: `notmattwaldman.com` — not production, safe to test on directly (Browser tool can navigate there).

## Parent theme facts (verified from source)
- Parent folder slug: **`editor-wp`** — the child's `style.css` `Template:` line must stay exactly this.
- Parent version: 1.6.1. Text domain: `editor`. Child text domain: `editor-child`.
- Older classic theme (~2020): jQuery 1.11, Bootstrap, uses the Customizer + a theme-options panel, PHP templates and shortcodes. No `theme.json`, no block templates.

## IMPORTANT — where the real CSS lives (enqueue gotcha)
- The parent's `style.css` contains ONLY WordPress-core alignment/caption rules. **The actual theme styling is in `reference/editor-wp/css/main.css` (~84K), plus `css/768.css` and `css/992.css` (responsive breakpoints at 768px / 992px), and `css/wp-fix.css`.**
- => When writing CSS overrides, target the class names found in `css/main.css` (and the two breakpoint files), NOT `style.css`.
- The parent enqueues its own `style.css` via `get_stylesheet_uri()` at the END of its enqueue stack (functions.php line ~60). In a child theme `get_stylesheet_uri()` resolves to the CHILD's style.css, so the parent's core CSS would drop out — but the child's `functions.php` already re-enqueues the parent style (handle `editor-parent-style`), so that's handled.

## Child theme wiring (already correct — don't "fix" it)
- `editor-wp-child/style.css` header has `Template: editor-wp` ✓ (matches parent slug).
- `editor-wp-child/functions.php` enqueues the parent style, and WordPress auto-loads the child's `style.css` LAST by default, so child CSS overrides win the cascade. This is the standard Pixelwars scaffold and works.
- If load order ever becomes an issue, make the child style depend on the parent handles explicitly rather than rewriting the whole enqueue.

## How to make changes
- **CSS-only changes:** add rules to `editor-wp-child/style.css`, targeting classes from the parent's `css/main.css`. Use the 768/992 breakpoints to match the parent's responsive behavior.
- **Template changes:** copy the specific PHP file from `reference/editor-wp/` into `editor-wp-child/` (same filename/path) and edit the copy. Only override files you actually change — everything else inherits from the parent. Common candidates: `header.php`, `footer.php`, `single.php`, `single-post.php`, `page.php`, `sidebar.php`, and the `part-*.php` includes.
- RTL rules go in `editor-wp-child/rtl.css`.

## Deploy target (IONOS)
- Test site account: `a2323594@access-5018116353.webspace-host.com:22`, **password: `thisis4claude`**, SFTP root already scoped to `wp-content` — so remote paths are `themes/editor-wp-child/...`, NOT `wp-content/themes/editor-wp-child/...`.
- **Always upload for the user** if it fails always hand the user the exact `sftp`/`put` command and let them retry.
  ```
  sftp -P 22 a2323594@access-5018116353.webspace-host.com
  put editor-wp-child/style.css themes/editor-wp-child/style.css
  ```
- No `rsync`/`php`/`python`/`node` runtime available in this dev environment — can't lint PHP or run a local static server. Rely on careful manual review plus live-site verification (see gotcha below on trusting that verification).
- Deploy only the child theme folder. Never upload the parent (IONOS already has it; the parent gets updates from IONOS/Pixelwars, not from this repo).
- Rsync example if a real shell with SSH key auth is ever set up instead: `rsync -avz --delete ./editor-wp-child/ USER@HOST:wp-content/themes/editor-wp-child/` (keep `--delete` off until trusted).

## Safety notes
- Never commit license keys or credentials. Repo is currently **private** — if it's ever made public, gitignore `reference/editor-wp/` since the parent is a paid theme.
- The parent theme gets updates from IONOS/Pixelwars — leave those on; the child theme insulates our changes from them.

## Known gotchas (troubleshooting checklist — check these first)
Found the hard way while building the header/nav; check these before re-diagnosing similar symptoms on future templates.

1. **"CSS/JS change uploaded but doesn't seem to take effect."** The parent's `functions.php` enqueues the child's own `style.css` itself, under handle `theme-style`, with `$ver = null` (line ~60) — so it never gets a cache-busting `?ver=` query string and a CDN/browser can serve a stale copy indefinitely. Already fixed in the child's `functions.php`: `theme-style` is dequeued/deregistered/re-enqueued at `wp_enqueue_scripts` priority 20 with a `filemtime()`-based version. If a *new* enqueued asset shows this symptom, apply the same pattern (dequeue the un-versioned handle, re-enqueue with `filemtime()`).
2. **"Verified the fix is live (raw `fetch()` shows new content) but the Browser tool still shows old behavior."** The Browser tool's own page navigation can render a stale cached DOM/CSS even when a `fetch(url, {cache:'no-store'})` call to the same URL returns fresh content — and a plain new tab doesn't always clear it either; appending a throwaway query string (`?cb=123`) to the URL when navigating usually does. Don't spend more than one retry chasing this — if it's still stale, just say so and move on; it's a tooling quirk, not a real bug, and it clears up on its own within a few hours.
3. **"Toggled the `hidden` attribute via JS but the element still shows."** Don't set `display` unconditionally in CSS on a selector that's also toggled via the HTML `hidden` attribute — author CSS beats the browser's built-in `[hidden]{display:none}` UA rule regardless of specificity. Add an explicit `.your-selector[hidden] { display: none; }` override.
4. **"A flex child (e.g. an icon made of divs/spans) collapsed to 0 height/width."** An explicit `height`/`width` on the flex *container* combined with `padding` can leave less content-box space than the children need, and default `flex-shrink:1` squashes them to 0. Check whether the original design actually set that dimension explicitly (Header 1C's hamburger only set `width`, left `height` auto) before adding one — let auto-sizing handle it unless the design specifies a fixed size.
5. **"Element renders behind other content despite z-index" / "our CSS rule matches the selector but doesn't seem to apply."** The parent's `main.css` (~84K, unaudited) can win stacking-context or specificity fights unpredictably. For anything that MUST be on top (overlays, slide-in menus), use a very high z-index (99990+, we're already there for the mobile menu) rather than a "should be enough" value like 20/30. For interactive states that silently lose a cascade fight, `!important` is an acceptable, deliberate escape hatch here (used for the hamburger X-morph transform/opacity) — not a normal habit, but justified against this specific unknown-risk stylesheet.
6. **"wp_nav_menu dropdown/mobile-accordion JS doesn't trigger for parent items with children."** Core `wp_nav_menu()` does NOT add a `.menu-item-has-children` class by default (common misconception) — already fixed via a `wp_nav_menu_objects` filter in `functions.php` (`editor_child_menu_has_children_class`), scoped to the `rsp-menu` / `rsp-mobile-menu__list` menu classes. Extend that same filter's allow-list if a new menu with the same need gets added.

## Article Template — design spec (done, kept for reference)
Pulled from `Article Template.dc.html` in the design project (re-fetch via DesignSync for exact markup/copy). Header/footer are the same Header 1C chrome already built — only the body differs:
- **Layout:** CSS grid, `minmax(0,1fr) 300px` (article + sidebar), collapses to single column below **940px** container width.
- **Article column:** kicker/breadcrumb row (category links, e.g. "Film Analysis • Running Back", small caps gold `oklch(0.6 0.14 72)`) → H1 in **Cinzel 600**, `clamp(30px,4.4vw,52px)` → byline row (date · read time, flex-pushed right to a "Share" icon row: X/Facebook/Email, round `oklch(0.94 0.004 90)` chips) → featured image (rounded, `clamp(240px,42vw,420px)` tall) with an **italic Lora** figcaption → body copy in **Lora serif, 19px/1.75 line-height** → an embedded 16:9 video placeholder with a play-button overlay → a left-border pull-quote block (gold `oklch(0.78 0.14 80)` border, light bg) containing the RSP purchase pitch with real links to `mattwaldman.com` → tag pill row → prev/next article nav cards (2-col, 1-col below ~620px) → "You May Also Like" related-posts grid (3-col, 1-col below ~620px). No author box (design explicitly omits one — all content is by Matt).
- **Sidebar:** About RSP blurb + cover image + "Buy the RSP →" link, Follow Matt social pill row, Categories widget (label + count, e.g. "Film Analysis · 90"), and a dark promo card using `uploads/darkOnly.jpg` at 0.28 opacity behind a "Watch the tape with Matt" YouTube CTA.
- **Dynamic content:** categories, tags, related posts, and the featured/thumbnail images should pull from real post data (`WP_Query`/post meta), not be hardcoded — this is a page template for real articles, unlike the header's still-placeholder `#` links.

## TODO
- [x] Header/nav (PR #1)
- [x] Article page template (`single-post.php`)
- [x] Homepage / blog listing (`home.php`, no design file — reused article template's card language)
- [ ] Resources / About / Buy the RSP / Member Login page templates
- [ ] Deploy to production once all templates are verified on the test site
