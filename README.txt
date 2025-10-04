=== Lobby TV ===
Contributors: centerstone
Donate link: https://centerstone.org/
Tags: digital signage, playlists, lobby tv, kiosk, ticker
Requires at least: 6.2
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lobby TV centralizes digital signage assets, playlists, and screens so clinic lobbies can deliver curated, always-on programming.

== Description ==

Lobby TV is a WordPress plugin built for Centerstone's network of lobby displays. It uses custom post types to organize assets, playlists, channels, and screens while exposing a fullscreen player optimized for kiosk environments. Administrators can manage media, schedule daypart overrides, configure ticker messages, and monitor screen health from within WordPress.

Key capabilities include:

* Upload, link, and schedule image, video, and web assets.
* Build playlists with per-item duration, mute, and fit overrides.
* Register screens, associate them with channels or direct playlists, and track heartbeat status.
* Curate ticker content with manual items and RSS feed ingestion.
* Deliver a Service Worker-enabled player that loops content even when offline.

Additional architectural details and roadmap notes are documented in [docs/mvp-plan.md](docs/mvp-plan.md).

== Installation ==

1. Upload the `lobby-tv` plugin directory to your site's `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Navigate to **Lobby TV → Settings** to review default ticker feeds, emergency overrides, and storage settings.
4. Create assets, playlists, channels, and screens using the custom post type menus. Assign screens to a channel or playlist to generate a player route.
5. Visit `/tv/{screen-slug}` or embed the `[cstn_tv screen="SLUG"]` shortcode to preview the player experience.

== Frequently Asked Questions ==

= Does the player work offline? =

The player leverages Service Worker caching to prefetch manifests and assets. When connectivity drops it will continue looping cached content and resume updates when online.

= Can I show different content on different screens? =

Yes. Each screen can be assigned to a dedicated playlist or to a shared channel. Channels can swap playlists automatically based on schedules or dayparts.

= How often does the ticker refresh? =

Ticker refresh intervals are configurable in plugin settings. Screens poll the ticker endpoint on a rolling basis and display manual and RSS items in priority order.

== Screenshots ==

1. Admin playlist editor listing assets and durations.
2. Screen detail view with heartbeat, now playing status, and force refresh controls.
3. Player layout with fullscreen stage, ticker, and optional sidebar widgets.

== Changelog ==

= 0.1.0 =
* Initial plugin scaffolding based on the WordPress Plugin Boilerplate.
* Defines custom post types for assets, playlists, channels, and screens.
* Adds REST endpoints for bootstrap, manifest, ticker, and heartbeat workflows.

== Upgrade Notice ==

= 0.1.0 =
Initial development release. Expect significant changes before a stable 1.0 version.
