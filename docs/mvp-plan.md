# Lobby TV MVP Plan

## 1. Goal and MVP Scope
- **Goal:** Centralize content and scheduling for lobby TVs, delivering a reliable "player" view that loops playlists, displays ticker content, and optional sidebar widgets. Admins will manage assets, playlists, and device assignments via WordPress.
- **MVP Features:**
  - Support local videos and images as assets.
  - Playlists with ordering, per-item durations, start/end dates, and overrides.
  - Devices (screens) assignable directly to a playlist or to a shared channel grouping.
  - Ticker with manually curated messages plus optional RSS feed ingestion.
  - Optional sidebar supporting clock and logo widgets.
  - Fullscreen player route per device that loops content and updates automatically.
  - Simple device registration flow backed by tokens.
  - Offline-friendly playback with prefetching and resilient loops.

## 2. High-Level Architecture
- Plugin slug: `cstn-signage`.
- Core relationship model: **Assets → Playlists → Channels → Screens**.
- Public delivery: one player page per screen that fetches a JSON manifest via REST.
- Update model: player polls for configuration changes; admins can force refresh a screen.
- Offline support: Service Worker prefetching of manifests and assets.
- Future roadmap: Android wrapper via WebView that receives the screen token.

## 3. Data Model
### Custom Post Types
- **`cstn_tv_asset`**
  - Meta: type (video, image, web), `attachment_id` or `source_url`, `duration_sec`, `start_at`, `end_at`, daypart rules, `mute`, `fit` (contain/cover).
- **`cstn_tv_playlist`**
  - Meta: ordered list of asset IDs with per-item overrides (duration, mute, fit, availability windows).
- **`cstn_tv_channel`**
  - Meta: primary playlist, optional schedule map for daypart swaps, default ticker config.
- **`cstn_tv_screen`**
  - Meta: `screen_code`, token, channel ID or direct playlist ID, timezone, orientation, sidebar layout, ticker config, last heartbeat, current manifest version.

### Taxonomies
- `cstn_location` for channels and screens.
- `cstn_category` (optional) for assets.

### Global Options
- Default ticker feeds, emergency override source, cache TTL, video player defaults, CDN base URL, PWA toggles.

## 4. Admin Features
- **Assets:** upload/link MP4/WebM/images, validate formats, extract metadata, set availability windows and dayparts.
- **Playlists:** drag-and-drop ordering, per-item overrides, randomize toggle, preview support.
- **Channels:** assign primary playlist, optional daypart schedule map, default ticker/sidebar settings.
- **Screens:** register with generated token, assign to channel or playlist, configure layout (ticker, sidebar widgets, colors), monitor status (heartbeat, manifest version, now playing), force refresh.
- **Ticker:** manual messages with pin order and expiry, RSS ingestion with limits and cadence, emergency override source.
- **Health dashboard:** screen list with status and errors, change log of publishes, logging of playback errors.
- **Roles & capabilities:** dedicated "Digital Signage Manager" role with full control; editors manage assets and playlists.
- **Logging:** collect playback error logs with rotation.

## 5. Player Features
- Layout: ticker across top; optional sidebar with clock/logo; main stage loops video/image assets.
- Behavior:
  - Fetch bootstrap data, then manifest with asset list and hashes.
  - Prefetch assets prior to starting loop.
  - Refresh ticker on interval; skip failed items with logging.
  - Autoplay muted by default; admin can enable audio.
  - Local clock continues even if feeds fail; orientation handled via CSS.

## 6. REST API Surface (`cstn-tv/v1`)
- Auth via screen token parameter/header; admin tools use nonce.
- `GET /bootstrap?screen=SLUG&token=…` → theme/layout/ticker config/manifest version.
- `GET /manifest?screen=SLUG&token=…` → ordered items with metadata (`url`, `type`, `duration`, `fit`, `mute`, availability, `sha256`).
- `GET /ticker?screen=SLUG&token=…` → merged manual + RSS items with text/expiry.
- `POST /heartbeat` → `{screen, token, version, now_playing_id, errors[]}` to persist status.
- `POST /ack-refresh` → client acknowledges forced refresh.
- Use ETags and `If-None-Match` to minimize payload; respond 429 to overly chatty clients.

## 7. Caching and Offline Strategy
- Manifests expose stable asset URLs and hashes.
- Service Worker caches bootstrap, manifest, and assets per screen version.
- On version change, prefetch new assets, swap caches, purge old data.
- Provide fallback slide when all else fails; optionally store last manifest locally for offline startup.

## 8. Scheduling and Rules
- Asset-level: start/end dates, daypart windows, weekday masks.
- Playlist-level: optional time slices with include/exclude rules.
- Channel-level: daypart-driven playlist swaps.
- Emergency override: display override slide/video at defined interval when active; gracefully skip unavailable items.

## 9. Security
- Screen tokens are opaque, revocable, scope-limited to client endpoints.
- Tokens never grant admin privileges.
- Support signed asset URLs when using CDN.
- Enforce capability checks, sanitization, and escaping; rate limit client endpoints.

## 10. Plugin Structure (WPPB)
```
cstn-signage/
├─ includes/
│  ├─ class-cstn-signage.php                (core loader)
│  ├─ class-cstn-signage-activator.php      (roles, rewrites, cron)
│  ├─ class-cstn-signage-deactivator.php
│  ├─ class-cstn-signage-cpt.php            (CPTs and taxonomies)
│  ├─ class-cstn-signage-admin.php          (menus, settings, list tables)
│  ├─ class-cstn-signage-public.php         (shortcodes, templates, assets)
│  ├─ rest/
│  │  ├─ class-cstn-signage-rest-bootstrap.php
│  │  ├─ class-cstn-signage-rest-manifest.php
│  │  ├─ class-cstn-signage-rest-ticker.php
│  │  └─ class-cstn-signage-rest-heartbeat.php
│  ├─ services/
│  │  ├─ class-cstn-signage-manifest-builder.php
│  │  ├─ class-cstn-signage-ticker-service.php
│  │  └─ class-cstn-signage-asset-resolver.php
│  └─ utils/
│     ├─ class-cstn-signage-rules.php
│     └─ class-cstn-signage-logger.php
├─ admin/
│  ├─ partials/
│  └─ css/js/
├─ public/
│  ├─ css/js/
│  └─ partials/
└─ cstn-signage.php
```
- Hook registration should occur in `define_admin_hooks()` / `define_public_hooks()` rather than constructors.

## 11. Player Template Options
- Route `/tv/{screen-slug}` via rewrite targeting dedicated template.
- Shortcode `[cstn_tv screen="SLUG"]` for testing/embedding.
- Minimal DOM with fullscreen background, watchdog timer, and soft reload on manifest change.
- Kiosk-friendly: keyboard lock, pointer hiding after idle.

## 12. Sidebar and Widgets
- Initial widgets: clock/date, logo image, static message stack, QR code link, weather placeholder (server-side fetch).
- Configurable per Channel or Screen; all optional.

## 13. Ticker Sources
- Manual queue with priority pins and expiry.
- RSS parser with sanitization and truncation.
- Emergency text from WP category flag or dedicated option.
- Merge and sort by priority before rotation.

## 14. Monitoring and Operations
- Admin screen table with status, location, last heartbeat, now playing, app version, errors.
- Screen detail view: logs, force refresh, rotate token.
- Notifications via email/Slack when screens miss check-ins.

## 15. Performance & Media Guidance
- Prefer MP4 H.264 baseline; optional HLS via `hls.js` for long videos.
- Recommend reasonable bitrates for Wi-Fi.
- Option to store media on S3/CDN with signed URLs.
- Pre-generate manifests on publish; cron job keeps RSS data fresh.

## 16. Post-MVP Roadmap
- Image slide designer, daypart visual editor, multiple layouts/themes.
- Web page capture slides, WebSocket push refresh.
- Device screenshot ping, Capacitor/TWA wrapper.
- Role-based approvals, playback analytics.

## 17. Risks & Mitigations
- Autoplay issues → default muted playback, provide Fire OS guidance.
- Network instability → offline cache, fallback slide, short assets.
- Large files → CDN option, preflight validation warnings.
- Content sprawl → leverage channels and locations to reduce duplication.

## 18. MVP Acceptance Criteria
- Create assets, build playlist, assign screen, observe looping playback.
- Ticker updates live without restarting playback.
- Channel/playlist edits propagate within polling interval and play new items.
- Screen heartbeat table shows active status; force refresh works.
- Player continues from cache during outages and resumes updates afterward.

## 19. Next Steps
1. Confirm data model and endpoint definitions.
2. Define detailed metabox fields and initial settings schema.
3. Build CPTs and admin UI following WPPB patterns.
4. Implement manifest builder and REST endpoints.
5. Develop player template with JS loop and Service Worker.
6. Pilot deployment with a single Firestick in a low-traffic lobby.
