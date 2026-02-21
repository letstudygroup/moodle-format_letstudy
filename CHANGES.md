# Changelog

## v2.1.1 (2026-02-21)

### Fixed
- Map Journey layout: sections invisible when animations enabled (missing CSS class qualifier).
- Map animation staggered delays offset corrected for proper nth-child indexing.
- Consistent animation fill mode (`both`) across all layouts.

### Changed
- Lowered minimum Moodle requirement from 5.0 to 4.5 for wider compatibility.
- Updated version metadata and release number.

## v2.1.0 (2026-02-21)

### Fixed
- Metro Tiles and Map Journey: `ls-bounceIn` animation name mismatch corrected to `ls-bounce`.
- Added `-webkit-backdrop-filter` vendor prefix for Safari compatibility on Metro Tiles.
- Added missing `defined('MOODLE_INTERNAL')` guards in db/upgrade.php and language files.
- Tab ARIA accessibility: added `id`, `tabindex`, `aria-controls`, `aria-labelledby` attributes.

### Changed
- Replaced all hardcoded success colours (#43a047) with CSS custom properties
  (`--ls-success`, `--ls-success-light`, `--ls-success-dark`).
- Icon picker now uses Moodle `core/str` module for translatable strings.
- Removed unused CSS (`.format-letstudy__branding`, `.format-letstudy__path-connector`).
- Added `$plugin->supported` version range to version.php.
- Updated plugin name to "LetStudy Course Formats".

### Added
- Icon picker internationalisation strings (title, search, browse) in English and Greek.

## v2.0.0 (2026-02-20)

### Added
- 10 visual layout views: Cards, Tabs, List, Timeline, Learning Path, Kanban Board,
  Metro Tiles, Map Journey, 3D Bookshelf, Polaroid Wall.
- Per-course brand and secondary colour customisation with colour picker.
- Per-section icon picker (FontAwesome) with visual browser.
- Per-section colour overrides and background images.
- Section progress tracking (circular and linear indicators).
- Real-time progress updates without page reload.
- Four entrance animation styles (Fade, Slide, Zoom, Bounce).
- Kanban auto-sorting based on student completion progress.
- Activity-level Kanban on single section pages.
- Full reactive course editor (drag-and-drop) support.
- Backup and restore support.
- Privacy API null provider implementation.
- English and Greek translations.

## v1.0.0 (2026-02-18)

### Added
- Initial release with Cards layout.
- Basic section progress tracking.
- Brand colour customisation.
