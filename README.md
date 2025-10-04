# Lobby TV

Lobby TV is a WordPress plugin that powers Centerstone's digital signage experience for clinic and office lobbies. The plugin centralizes the management of assets, playlists, and screens while delivering a resilient fullscreen player that can be deployed to any kiosk or smart TV. This repository contains the plugin source, documentation, and supporting assets that make up the minimum viable product described in [docs/mvp-plan.md](docs/mvp-plan.md).

## Key Features

- **Asset management:** Upload or link video, image, and web assets with availability windows, duration overrides, and metadata tailored for digital signage playback.
- **Playlist orchestration:** Build ordered playlists with per-item overrides and scheduling logic that feed into shared channels or individual screens.
- **Screen & channel routing:** Register screens with unique tokens, associate them with channels or playlists, and monitor their current manifest and heartbeat status.
- **Ticker & sidebar widgets:** Curate ticker messages, ingest RSS feeds, and configure optional sidebar widgets such as clocks, logos, and announcements.
- **Offline-friendly player:** Deliver a fullscreen player route backed by REST endpoints, Service Worker caching, and automatic refresh handling to keep signage running even with intermittent connectivity.

## Repository Structure

The plugin follows the [WordPress Plugin Boilerplate](https://wppb.io/) conventions. Key directories include:

- `cstn-signage.php` – main plugin bootstrap file.
- `includes/` – core loader, custom post types, REST controllers, and services used by both the admin and player experiences.
- `admin/` – admin-facing assets, menus, list tables, and settings screens.
- `public/` – frontend/player assets, templates, and enqueue logic.
- `docs/` – planning documents such as the MVP implementation plan.

Review the detailed architecture, data model, and REST surface in [docs/mvp-plan.md](docs/mvp-plan.md) for a deeper dive into planned components and workflows.

## Development Setup

1. **Clone and install dependencies**
   ```bash
   git clone https://github.com/centerstone/lobby-tv.git
   cd lobby-tv
   npm install
   ```
2. **Local WordPress environment** – Place this repository inside your WordPress `wp-content/plugins/` directory or use a local environment such as [Local](https://localwp.com/) or [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/).
3. **Activate the plugin** – In the WordPress admin dashboard, activate the “Lobby TV” plugin. Initial activation hooks will register custom post types, roles, and rewrite rules.
4. **Build assets** – As build tooling is introduced, follow the scripts defined in `package.json` (e.g., `npm run build` or `npm run dev`). Placeholder commands exist today; implementation will be expanded as development progresses.

## Configuration Overview

- **Custom post types:** Assets, playlists, channels, and screens establish the relationships required to deliver targeted content.
- **Taxonomies:** Locations and categories help organize channels and assets.
- **Global options:** Configure ticker defaults, emergency overrides, cache TTLs, and media storage settings from the plugin settings pages.
- **REST API (`cstn-tv/v1`):** Player clients use bootstrap, manifest, ticker, and heartbeat endpoints to fetch content and report status.

## Testing the Player

During development you can preview the player experience by visiting the generated screen route (e.g., `/tv/{screen-slug}`) or by using the `[cstn_tv screen="SLUG"]` shortcode on a WordPress page. The player will poll for configuration updates, loop through its playlist, and refresh ticker content as defined in the associated channel or playlist.

## Contributing

1. Fork the repository and create a feature branch.
2. Follow the coding standards established by WordPress and the patterns defined in the WordPress Plugin Boilerplate.
3. Update documentation and tests relevant to your change.
4. Submit a pull request describing the updates and referencing any related issues or MVP roadmap sections.

## License

This project is released under the [GNU General Public License v2 or later](LICENSE.txt).
