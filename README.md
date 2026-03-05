# Letstudy Course Format

A modern, highly configurable course format plugin for Moodle that offers 10 unique visual
layouts for presenting course sections. Designed to make courses more engaging and visually
appealing for students while giving teachers full creative control.

## Features

### 10 Layout Views

- **Cards / Grid** - Sections as clickable cards with flip animation and optional background images.
- **Tabs** - Horizontal tab navigation for switching between sections inline.
- **Modern List** - Clean vertical list with progress bars and activity counts.
- **Timeline** - Vertical timeline with alternating content and visual markers.
- **Learning Path** - Zigzag milestone path showing completed, current, and upcoming steps.
- **Kanban Board** - Sections auto-sorted into Pending, In Progress, and Completed columns.
- **Metro Tiles** - Windows-inspired dynamic grid with varying tile sizes and bold colours.
- **Map Journey** - Board-game style winding road with stops, flags, and a finish trophy.
- **3D Bookshelf** - Books on a wooden shelf with 3D hover effects.
- **Polaroid Wall** - Polaroid photos pinned to a wall with subtle rotations.

### Customisation Options

- **Brand & secondary colours** per course (with colour picker).
- **Section icons** (FontAwesome) with built-in icon picker.
- **Section images** via upload or URL (for Cards layout).
- **Per-section colour overrides**.
- **Configurable grid columns** (2, 3, or 4) for Cards layout.

### Progress Tracking

- Circular or linear progress indicators per section.
- Real-time progress updates on activity completion (no page reload).
- Automatic Kanban column sorting based on student progress.

### Animations

- Four entrance animation styles: Fade, Slide, Zoom, Bounce.
- Respects `prefers-reduced-motion` browser setting.
- Staggered animation delays for visual polish.

### Moodle Integration

- Full support for the reactive course editor (drag-and-drop).
- Course index sidebar support.
- Backup and restore support.
- Works with any Moodle theme (theme-agnostic CSS using custom properties).
- Section image file upload via Moodle file manager.
- In-place section name editing.

## Requirements

- Moodle 4.5 or later (up to Moodle 5.0).
- Activity completion enabled in the course for progress tracking features.

## Installation

1. Download the plugin ZIP file.
2. In Moodle, go to **Site administration > Plugins > Install plugins**.
3. Upload the ZIP file and follow the on-screen instructions.
4. Alternatively, extract the ZIP into `/course/format/letstudy/` and visit the admin
   notifications page.

## Configuration

### Site-wide defaults

Go to **Site administration > Plugins > Course formats > Letstudy Course Format** to configure:

- Default layout for new courses.
- Default brand and secondary colours.
- Default animation and progress settings.
- Whether activity indentation is allowed.

### Per-course settings

Edit any course and expand the **Course format** section to configure:

- Section layout (choose from 10 layouts).
- Number of columns (Cards layout only).
- Brand and secondary colours.
- Enable/disable animations and choose animation style.
- Enable/disable progress tracking and choose progress style.
- Show/hide section icons.

### Per-section settings

Edit any section to configure:

- Custom FontAwesome icon (with visual icon picker).
- Custom section colour override.
- Section background image (upload or URL, for Cards layout).

## Languages

- English (en)
- Greek (el)

## License

This plugin is licensed under the [GNU GPL v3 or later](https://www.gnu.org/copyleft/gpl.html).

## Credits

Developed by [Letstudy Group](https://plugins.letstudy.gr).
