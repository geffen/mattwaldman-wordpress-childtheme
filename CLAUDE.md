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
- Files are `.dc.html` "Design Component" prototypes (streaming HTML format, not production code — treat rendered layout/color/copy as the source of truth, not the literal markup). **Every screen is now built**: `Header 1C.dc.html`, `Home Page.dc.html`, `Article Template.dc.html`, `About Page.dc.html`, `Buy the RSP.dc.html`, `Resources Page.dc.html`, `Member Login.dc.html`. `Site Index.dc.html` is dev-only — never build it. Images in `uploads/`.
- `design_handoff_rsp_site/README.md` in the design project is a full written spec (per-screen sections, exact tokens, breakpoints) — read it alongside the `.dc.html` files; it sometimes states intent the prototype markup gets wrong (see gotcha 7).
- The design project gets updated over time (`Home Page.dc.html` was added after the header/article/homepage work was already done). Re-pull with DesignSync before starting a template, and check whether already-built screens changed.
- Design tokens use `oklch()` colors throughout — keep using `oklch()` in child CSS to stay consistent with the design's palette (dark navy `oklch(0.19 0.02 260)`, gold accent `oklch(0.78 0.14 80)`, brighter CTA gold `oklch(0.83 0.15 92)`).
- Fonts: **Manrope** (400–800) + **Cinzel** (600–700) + **Lora** (400, 500, italic 400) all enqueued via Google Fonts in `functions.php`.
- Shared page frame: `#page` is a centered **1280px** near-white column on a grey backdrop, applied once in `style.css` so header/content/footer on every template share it automatically.

## Current state
- PR: [#1](https://github.com/geffen/mattwaldman-wordpress-childtheme/pull/1), branch `header-nav-redesign`. The user does not care about git/PR workflow — commit when asked, don't volunteer PR hygiene.
- Test site: `notmattwaldman.com` — not production, safe to test on directly. It is a **content copy of the real site**, so some pages/posts are Matt's genuine content, not placeholder. Look before deleting.
- Real/production site: `mattwaldmanrsp.com`. The child theme is **not** deployed there. Its public REST API is readable and was used to import demo content (see below).
- **Every design screen is built.** Remaining work is the two pre-production cleanups in the TODO, plus whatever new design files appear.

### ⚠ The menu is managed by hand in wp-admin — do not re-automate it
The user rebuilt the primary menu themselves in **Appearance → Menus** (location: **"Theme Navigation Menu"**, the only one `header.php` reads). Consequently:
- `editor_child_sync_primary_menu_urls()` is **commented out** in `functions.php` and must stay that way. It ran on every `init` and rewrote menu item URLs by title match, silently reverting CMS edits on the next page load. The function body is kept for reference only.
- The one-time seeders (`editor_child_seed_primary_menu()`, `editor_child_seed_nav_submenus()`, term/page seeders) are all option-gated and have already fired. They won't refire and won't fight manual edits.
- The old auto-seeded menu was called "RSP Primary Navigation"; the live menu is the user's own. If nav changes are needed, **tell the user what to change in wp-admin** rather than writing code.
- Header links that are NOT menu items — the gold "Buy the RSP" CTA, the quickbar tagline, and quickbar "Member Login" — are hardcoded in `header.php` and resolve via `editor_child_get_buy_rsp_url()` / `_about_url()` / `_member_login_url()`. Those still need code changes. Social icons are still `#` pending Matt's real account URLs.

### Templates built (all in `editor-wp-child/`)
| File | Covers |
|---|---|
| `header.php` / `footer.php` | Header 1C chrome; dark minimal footer |
| `front-page.php` | Home page — hero, featured breakdown (sticky else latest), 3 portals, "What is the RSP?" stat grid, latest grid, CTA strip |
| `single-post.php` | Article Template (spec below). Shortcodes `[rsp_video id="..."]` and `[rsp_promo]` let Matt place the video block and RSP pitch anywhere in a post body |
| `home.php` | Blog index. Superseded as homepage by `front-page.php`; kept because WP falls back to it if a separate "Posts page" is ever set |
| `archive.php` | Category/tag/taxonomy/date/author listings, designed card grid |
| `page.php` | Default page — light hero + editable body |
| `template-buy-the-rsp.php` | Buy the RSP (dark frame) |
| `template-about.php` | About (dark frame) |
| `template-resources.php` | Resources — dark hero, 3 portals with live counts, Film Room / Articles / Podcasts sections from real posts |
| `template-category-listing.php` | Film Room / Articles / Podcasts pages — renders editor intro copy, then auto-matches its category **by page slug** and lists it. Override with an `rsp_category` custom field if slug ≠ category slug |
| `template-member-login.php` | Member Login (dark frame). Posts to real `wp-login.php` (`log`/`pwd`/`rememberme`/`redirect_to`); shows signed-in state when logged in. The prototype's form was static — this deliberately is not. Accepts `?redirect_to=` (validated with `wp_validate_redirect()`) so a locked area can round-trip a visitor |
| `template-member-area.php` | **Members-only areas** — RSP Draft Guide + Ranking & Projections. One template, two states at the same URL (locked/public vs unlocked/member). See the members section below |
| `template-term-index.php` | Taxonomy landing page — lists a taxonomy's terms as cards linking to their archives. Used by `/player-evaluation/by-position/` and `/by-draft-class/`. Exists because WP builds **no root archive** for a custom taxonomy (`/position/` and `/draft-class/` both 404), so those nav items had nowhere to point. Matches its taxonomy by page slug, overridable with an `rsp_taxonomy` custom field. Lists terms with `hide_empty => false` on purpose — every term currently has 0 posts |

Dark page column is applied by body class in `editor_child_body_classes()`: front page + Buy/About/Member Login/Member Area templates. Everything else uses the default light column.

### 🔒 Members-only areas & aMember (read `inc/members.php`'s header before touching access)
Matt sells the RSP through **aMember Pro**, live at `https://www.mattwaldman.com/amember/`
(`/signup` and `/login` confirmed). aMember is **not a WordPress plugin** — it's a standalone
PHP app, and its official WP bridge requires it to sit in a subdirectory of the **same
server/filesystem** as WordPress so it can `require` `amember/library/Am/Lite.php` and share
a session cookie. That is **not** the case here:

| Host | IP | Runs |
|---|---|---|
| `mattwaldman.com` | 35.209.31.200 (Google Cloud) | aMember Pro |
| `mattwaldmanrsp.com` | 192.0.78.x (**WordPress.com**) | the current public site |
| `notmattwaldman.com` | 74.208.236.15 (IONOS) | our test site |

Three separate hosts, so the bridge can't work as-is, and aMember's REST module looks
switched off (`/amember/api/users` → 404, `check-access` → 403). Production today gates with
plain WP password-protected posts and sends buyers to aMember to download. **Making real SSO
work is a hosting decision, not a theme change.**

Because of that, **every access decision goes through one function** —
`editor_child_user_has_access( $area_key )` in `editor-wp-child/inc/members.php`. Today it's
backed by a `rsp_member` role with per-area caps (`rsp_access_draft_guide`,
`rsp_access_rankings`); admins/editors always pass so the member view can be proofed. It ends
in `apply_filters( 'editor_child_user_has_access', … )` — **that filter is the swap point.**
When aMember lands on the same host, hooking it (calling `Am_Lite::getInstance()`) converts
the whole site with **zero template edits**. Never scatter capability checks through templates.

Other things worth knowing:
- Areas are defined once in `editor_child_member_areas()` — adding a third is a one-place change.
- A page becomes a member area by slug (`rsp-draft-guide`, `ranking-and-projections`), or via an
  `rsp_member_area` custom field. Same convention as `template-category-listing.php`.
- **Gating the page isn't enough.** Gated posts are protected on three further routes, all in
  `inc/members.php`: `pre_get_posts` (all front-end listings, feeds, REST collections — the
  member-area query opts out with `editor_child_bypass_gate`), `template_redirect` (direct
  permalinks and the gated category archive → bounced to the area page with `?locked=1`), and
  `rest_prepare_post` (single REST items, whose body is stripped). If you add a fourth read
  path, gate it too.
- The locked state is a **PHP branch, not CSS** — no member content reaches the response.
  The teaser list renders titles and dates only, never `the_content()`/`the_excerpt()`.
- Downloads come from an `rsp_downloads` custom field on the page, one per line as
  `Label|URL|Note` (note optional) — same lightweight post-meta convention as `rsp_duration`.
  Rows without a URL are skipped, so a half-finished field degrades quietly.

### Content structure on the test site
- **Categories:** Film Room, Articles, Podcasts (created by `editor_child_seed_content_taxonomy_terms()`), alongside Matt's real legacy categories (Players 2200+, Wide Receiver, etc.). Plus the two **gated** categories `rsp-draft-guide` and `rsp-rankings`, created by `editor_child_seed_member_areas()` — posts put in either are members-only everywhere on the site.
- **Taxonomies:** `rsp_position` (QB/RB/WR/TE, `/position/<term>/`) and `rsp_draft_class` (2024–2026, `/draft-class/<term>/`). Rewrites flushed once via `editor_child_flush_rewrites_for_new_taxonomies()`. Per-term archives render through `archive.php`. **Every term still has 0 posts** — nothing has been tagged yet, so all these archives are empty until Matt tags prospects (the Position / Draft Class boxes appear in the post editor next to Categories). Note there is no `/position/` or `/draft-class/` root archive — that's what `template-term-index.php` is for.
- **Pages** (user-created in wp-admin unless noted): `/buy-the-rsp/`, `/about/`, `/resources/`, `/film-room/`, `/articles/`, `/podcasts/`, `/ranking-and-projections/` and `/player-evaluation/` (both seeded programmatically with placeholder copy — real content still to be written), plus `/rsp-draft-guide/` (seeded by `editor_child_seed_member_areas()`). That seeder also assigns `template-member-area.php` to `/rsp-draft-guide/` and `/ranking-and-projections/`, and rewrites the latter's copy **only** if it still holds the original placeholder sentence — so Matt's own edits survive.
- `/film-room/` originally collided with a 2014 legacy page that owned the slug; the old page was trashed and the slug reclaimed. **Trashing frees a slug** (WP renames it `<slug>__trashed`), so trash first, then rename.

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
- **The user always does the upload; I never run sftp.** Password-based auth means entering the password on their behalf is off-limits regardless of framing. This came up and was settled — don't re-litigate it, just hand over the command.
- **Give bare `put` lines only — no `sftp` connect line.** The user keeps a console session open. Only list files that actually changed that turn:
  ```
  put editor-wp-child/style.css themes/editor-wp-child/style.css
  ```
  Give the full block (connect line included) only if they say the session dropped or ask for it.
- Hand over the `put` command(s) **unprompted** after every code change — don't wait to be asked.
- No `rsync`/`php`/`python`/`node` runtime available in this dev environment — can't lint PHP or run a local static server. Rely on careful manual review plus live-site verification (see gotcha below on trusting that verification).
- Deploy only the child theme folder. Never upload the parent (IONOS already has it; the parent gets updates from IONOS/Pixelwars, not from this repo).
- Rsync example if a real shell with SSH key auth is ever set up instead: `rsync -avz --delete ./editor-wp-child/ USER@HOST:wp-content/themes/editor-wp-child/` (keep `--delete` off until trusted).

## Safety notes
- Never commit license keys or credentials. Repo is currently **private** — if it's ever made public, gitignore `reference/editor-wp/` since the parent is a paid theme.
- The parent theme gets updates from IONOS/Pixelwars — leave those on; the child theme insulates our changes from them.

## Verifying work on the live site
- No `php`/`node`/`wp-cli` in this dev environment — nothing can be linted or run locally. Verification is: hand over the `put`, then inspect the live test site.
- Fastest check is a cache-busted `fetch()` from the Browser tool's JS console against `notmattwaldman.com`, parsing the HTML for expected classes/counts. That caught every real bug this session.
- The site's REST API is handy for reading state without wp-admin access: `/wp-json/wp/v2/pages?slug=…`, `/wp-json/wp/v2/categories`, `?sticky=true`, etc. `/wp-json/wp/v2/menus` needs auth and will 401 — read the rendered nav HTML instead.
- `resize_window` genuinely re-evaluates CSS media queries, so responsive checks are reliable — but it does **not** fire `resize`/`matchMedia` change *events* (see gotcha 10).

## Known gotchas (troubleshooting checklist — check these first)
Found the hard way; check these before re-diagnosing similar symptoms on future work.

1. **"CSS/JS change uploaded but doesn't seem to take effect."** The parent's `functions.php` enqueues the child's own `style.css` itself, under handle `theme-style`, with `$ver = null` (line ~60) — so it never gets a cache-busting `?ver=` query string and a CDN/browser can serve a stale copy indefinitely. Already fixed in the child's `functions.php`: `theme-style` is dequeued/deregistered/re-enqueued at `wp_enqueue_scripts` priority 20 with a `filemtime()`-based version. If a *new* enqueued asset shows this symptom, apply the same pattern (dequeue the un-versioned handle, re-enqueue with `filemtime()`).
2. **"Verified the fix is live (raw `fetch()` shows new content) but the Browser tool still shows old behavior."** The Browser tool's own page navigation can render a stale cached DOM/CSS even when a `fetch(url, {cache:'no-store'})` call to the same URL returns fresh content — and a plain new tab doesn't always clear it either; appending a throwaway query string (`?cb=123`) to the URL when navigating usually does. Don't spend more than one retry chasing this — if it's still stale, just say so and move on; it's a tooling quirk, not a real bug, and it clears up on its own within a few hours.
   **Root cause found and fixed:** IONOS sent *no* cache headers on front-end HTML (no `Cache-Control`/`ETag`/`Expires`), so browsers used heuristic freshness and could hold a stale page for hours — this was behind most "uploaded but not showing" reports, including a stale `/` that made the header logo look like it linked to the wrong page. `editor_child_revalidate_html()` in `functions.php` now sends `Cache-Control: no-cache, must-revalidate, max-age=0` on front-end HTML (admin/feeds/REST excluded). Before diagnosing a "wrong page/stale content" report, confirm against a cache-busted `fetch()` — the server is often already correct.
3. **"Toggled the `hidden` attribute via JS but the element still shows."** Don't set `display` unconditionally in CSS on a selector that's also toggled via the HTML `hidden` attribute — author CSS beats the browser's built-in `[hidden]{display:none}` UA rule regardless of specificity. Add an explicit `.your-selector[hidden] { display: none; }` override.
4. **"A flex child (e.g. an icon made of divs/spans) collapsed to 0 height/width."** An explicit `height`/`width` on the flex *container* combined with `padding` can leave less content-box space than the children need, and default `flex-shrink:1` squashes them to 0. Check whether the original design actually set that dimension explicitly (Header 1C's hamburger only set `width`, left `height` auto) before adding one — let auto-sizing handle it unless the design specifies a fixed size.
5. **"Element renders behind other content despite z-index" / "our CSS rule matches the selector but doesn't seem to apply."** The parent's `main.css` (~84K, unaudited) can win stacking-context or specificity fights unpredictably. For anything that MUST be on top (overlays, slide-in menus), use a very high z-index (99990+, we're already there for the mobile menu) rather than a "should be enough" value like 20/30. For interactive states that silently lose a cascade fight, `!important` is an acceptable, deliberate escape hatch here (used for the hamburger X-morph transform/opacity) — not a normal habit, but justified against this specific unknown-risk stylesheet.
6. **"wp_nav_menu dropdown/mobile-accordion JS doesn't trigger for parent items with children."** Core `wp_nav_menu()` does NOT add a `.menu-item-has-children` class by default (common misconception) — already fixed via a `wp_nav_menu_objects` filter in `functions.php` (`editor_child_menu_has_children_class`), scoped to the `rsp-menu` / `rsp-mobile-menu__list` menu classes. Extend that same filter's allow-list if a new menu with the same need gets added.
7. **The design prototypes have bugs — cross-check the README spec.** `Home Page.dc.html`'s "Latest from the Film Room" band sets no background (so it inherited the dark page column) while styling its titles at `oklch(0.2 …)` and its divider at `oklch(0.88 …)` — dark-on-dark, unreadable. The README called that band "light", which matches the text colors, so it was built light. When markup and README disagree, the README's stated intent plus the color math usually wins — but ask rather than guessing silently.
8. **"A menu item didn't get its URL synced, but its siblings did."** Nav menu item titles come back **HTML-encoded** from `wp_get_nav_menu_items()`, so `Ranking & Projections` arrives as `Ranking &amp; Projections` and a raw string comparison silently fails — no error, the item just keeps its old `#`. Every title match in `functions.php` now runs `html_entity_decode( $item->title, ENT_QUOTES, 'UTF-8' )` first (`editor_child_sync_primary_menu_urls()` and `editor_child_seed_nav_submenus()`). Do the same in any new code that matches menu items by title; items without punctuation will work either way and mask the bug.
9. **"A secondary `WP_Query` returned more posts than `posts_per_page`."** Always pass `'ignore_sticky_posts' => true` on secondary queries. A `WP_Query` with no page-type-identifying query vars gets `is_home = true` internally, and WordPress then splices sticky posts into the results *on top of* `posts_per_page`. The test site has 5 sticky posts, so the front page's latest grid returned 7 items (4 stickies + 3 real posts) instead of 3. `single-post.php`'s related-posts query already had the flag, which is why it was unaffected — match that pattern in any new query.
10. **Header breakpoints are measured, not guessed** (`style.css`, "Header sizing ladder" comment). Full-size nav content is 631px and only clears the logo + CTA above ~1203px, so the CTA drops below **1240px**; logo/nav-font/gaps then scale by `clamp()` down to **768px**, where the hamburger takes over. The 768px cutover exists in **two** places — the `@media` blocks in `style.css` and the `matchMedia()` guard in `js/nav.js` that force-closes the mobile menu on resize. Change both together or the menu can stick open with `body{overflow:hidden}` still applied. The CSS side uses **`max-width: 767.98px`**, not `767px`, on purpose: viewport widths can be fractional (zoom, fractional DPR), and at a real 767.5px both `max-width:767px` and `min-width:768px` evaluate false — a dead zone where the desktop nav renders while the mobile menu is no longer force-hidden. Any breakpoint pair where an explicit `max-width` abuts an explicit `min-width` needs the `.98`. Note the Browser tool's `resize_window` does **not** fire `resize`/`matchMedia` change events, so that guard can't be verified with it — check by dragging a real browser window.

11. **"WordPress function is deprecated / missing."** This install runs **WordPress 7.0.2** — much newer than the ~2020 parent theme. `get_page_by_title()` is deprecated and was avoided in the importer in favour of a `_rsp_imported_from` meta lookup. Check function status against current WP before using anything from older tutorials or the parent theme's own code.

## Article Template — design spec (done, kept for reference)
Pulled from `Article Template.dc.html` in the design project (re-fetch via DesignSync for exact markup/copy). Header/footer are the same Header 1C chrome already built — only the body differs:
- **Layout:** CSS grid, `minmax(0,1fr) 300px` (article + sidebar), collapses to single column below **940px** container width.
- **Article column:** kicker/breadcrumb row (category links, e.g. "Film Analysis • Running Back", small caps gold `oklch(0.6 0.14 72)`) → H1 in **Cinzel 600**, `clamp(30px,4.4vw,52px)` → byline row (date · read time, flex-pushed right to a "Share" icon row: X/Facebook/Email, round `oklch(0.94 0.004 90)` chips) → featured image (rounded, `clamp(240px,42vw,420px)` tall) with an **italic Lora** figcaption → body copy in **Lora serif, 19px/1.75 line-height** → an embedded 16:9 video placeholder with a play-button overlay → a left-border pull-quote block (gold `oklch(0.78 0.14 80)` border, light bg) containing the RSP purchase pitch with real links to `mattwaldman.com` → tag pill row → prev/next article nav cards (2-col, 1-col below ~620px) → "You May Also Like" related-posts grid (3-col, 1-col below ~620px). No author box (design explicitly omits one — all content is by Matt).
- **Sidebar:** About RSP blurb + cover image + "Buy the RSP →" link, Follow Matt social pill row, Categories widget (label + count, e.g. "Film Analysis · 90"), and a dark promo card using `uploads/darkOnly.jpg` at 0.28 opacity behind a "Watch the tape with Matt" YouTube CTA.
- **Dynamic content:** categories, tags, related posts, and the featured/thumbnail images should pull from real post data (`WP_Query`/post meta), not be hardcoded — this is a page template for real articles, unlike the header's still-placeholder `#` links.

## TODO
- [x] Header/nav (PR #1)
- [x] Article page template (`single-post.php`)
- [x] Blog listing (`home.php`)
- [x] Front page (`front-page.php`, from `Home Page.dc.html`)
- [x] Buy the RSP (`template-buy-the-rsp.php`)
- [x] About (`template-about.php`)
- [x] Archive listing (`archive.php` — covers category/tag/taxonomy/date/author with the designed card grid)
- [x] Resources page template (`template-resources.php`)
- [x] Category Listing template (`template-category-listing.php` — Film Room / Articles / Podcasts pages; auto-matches its category by page slug)
- [x] Member Login page template (`template-member-login.php` — posts to real `wp-login.php`, not the prototype's static form; shows a signed-in state when logged in)
- [x] Members-only areas (`template-member-area.php` + `inc/members.php`) — RSP Draft Guide and Ranking & Projections, locked and unlocked states at the same URL
- [ ] **Membership backend decision (blocks real gating):** aMember is on a different host from WordPress, so entitlement is currently the `rsp_member` role assigned by hand in wp-admin. Either (a) move aMember onto the same host as the new WP site and hook `editor_child_user_has_access` to `Am_Lite`, or (b) have aMember's IPN/webhook create/flag WP users on purchase. Until one of those happens, someone must grant the role manually per customer.
- [ ] Matt to write the real public pitch copy for both member areas, and fill in the `rsp_downloads` custom field on each
- [ ] **Before production:** delete the two test-site-only helpers in `functions.php` — `editor_child_seed_demo_articles_category()` (tags 8 posts into Articles) and `editor_child_import_demo_content()` (pulls ~20 Podcasts + ~20 Film Room posts from mattwaldmanrsp.com's public REST API into the matching local categories, sideloading featured images). Both are hard-gated to the notmattwaldman.com host so they're inert elsewhere, but neither should ship. Imported posts carry a `_rsp_imported_from` meta key, so they can be found and bulk-deleted: query posts with that meta key. Sideloaded attachments are *not* tagged, so clean those up via the media library if it matters. To force a re-import after changing the function, bump the `editor_child_content_imported_v2` option name rather than deleting rows.
- [ ] Deploy to production once all templates are verified on the test site
