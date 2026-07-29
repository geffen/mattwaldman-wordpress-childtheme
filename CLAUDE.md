# Editor (Pixelwars) — Child Theme Project

## What this project is
A WordPress **child theme** (`editor-wp-child`) that customizes the premium
**Editor** theme by Pixelwars (a classic, PHP-based theme — NOT a block/FSE theme).
All customizations go in the child theme. The parent is never edited.

## Repo layout
- `editor-wp-child/` — the child theme. This is the only thing that gets edited and deployed.
- `reference/editor-wp/` — the parent theme, READ-ONLY. For reading class names, enqueue logic, and templates to override. Never edit or deploy this.

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
- Host: IONOS WordPress hosting. Access via **SFTP + SSH** account (SFTP scope is limited to `wp-content`, which is all we need).
- Deploy destination: `wp-content/themes/editor-wp-child/`
- Deploy only the child theme folder. Never upload the parent from here (IONOS already has it; the parent should get its updates from IONOS/Pixelwars, not from this repo).
- Use SSH key auth. Example (adjust user/host/path):
  - rsync (WSL2/Linux): `rsync -avz --delete ./editor-wp-child/ USER@HOST:wp-content/themes/editor-wp-child/`
  - scp (Windows/Git Bash, no rsync): `scp -r ./editor-wp-child/* USER@HOST:wp-content/themes/editor-wp-child/`
- Test on staging before production if available. Keep `--delete` off until the sync is trusted.

## Safety notes
- Never commit license keys or credentials. If the repo is public, consider gitignoring `reference/editor-wp/` since the parent is a paid theme.
- The parent theme gets updates from IONOS/Pixelwars — leave those on; the child theme insulates our changes from them.

## TODO (fill in the actual design work)
- [ ] Decide the visual changes (colors / fonts / header / post styling / etc.)
- [ ] Write child CSS overrides against `main.css` class names
- [ ] Override any templates that need structural changes
- [ ] Deploy to staging, verify in browser, then production
