<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains the content output class for the Letstudy course format.
 *
 * @package    format_letstudy
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_letstudy\output\courseformat;

use core_courseformat\output\local\content as content_base;
use renderer_base;
use completion_info;

/**
 * Class to render the course content for the Letstudy format.
 *
 * @package    format_letstudy
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class content extends content_base {
    /**
     * @var bool Letstudy format has add section after each section.
     */
    protected $hasaddsection = true;

    /**
     * Get the name of the template to use for this templatable.
     *
     * @param \renderer_base $renderer The renderer requesting the template name
     * @return string
     */
    public function get_template_name(\renderer_base $renderer): string {
        return 'format_letstudy/local/content';
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return \stdClass data context for a mustache template
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $USER;

        // Get format options BEFORE parent export to determine display mode.
        $format = $this->format;
        $course = $format->get_course();
        $formatoptions = $format->get_format_options();

        // Load AMD modules (pass layout to JS for reliable detection).
        $PAGE->requires->js_call_amd('format_letstudy/mutations', 'init');
        $PAGE->requires->js_call_amd('format_letstudy/letstudy', 'init', [
            $formatoptions['sectionlayout'] ?? 'cards',
        ]);

        // Always force single-page display so section data includes activities (cmlist).
        // Our format handles navigation (cards/list link to section pages, tabs/timeline
        // show activities inline). Without this, sections render as summary-only (empty).
        $originaldisplay = $course->coursedisplay ?? COURSE_DISPLAY_SINGLEPAGE;
        $course->coursedisplay = COURSE_DISPLAY_SINGLEPAGE;

        // Get core template data (uses coursedisplay to decide section content depth).
        $data = parent::export_for_template($output);

        // Restore original display setting.
        $course->coursedisplay = $originaldisplay;

        // Add format settings to template data.
        $data->sectionlayout = $formatoptions['sectionlayout'] ?? 'cards';
        $data->cardcolumns = $formatoptions['cardcolumns'] ?? 3;
        $data->showprogress = !empty($formatoptions['showprogress']);
        $data->progressstyle = $formatoptions['progressstyle'] ?? 'circular';
        $data->brandcolor = $formatoptions['brandcolor'] ?? '#1E88E5';
        $data->secondarycolor = $formatoptions['secondarycolor'] ?? '#FF6D00';
        $data->enableanimations = !empty($formatoptions['enableanimations']);
        $data->animationstyle = $formatoptions['animationstyle'] ?? 'fade';
        $data->showsectionicon = !empty($formatoptions['showsectionicon']);
        // Layout booleans for template.
        $data->islayoutcards = ($data->sectionlayout === 'cards');
        $data->islayouttabs = ($data->sectionlayout === 'tabs');
        $data->islayoutlist = ($data->sectionlayout === 'list');
        $data->islayouttimeline = ($data->sectionlayout === 'timeline');
        $data->islayoutpath = ($data->sectionlayout === 'path');
        $data->islayoutkanban = ($data->sectionlayout === 'kanban');
        $data->islayoutmetro = ($data->sectionlayout === 'metro');
        $data->islayoutmap = ($data->sectionlayout === 'map');
        $data->islayoutbookshelf = ($data->sectionlayout === 'bookshelf');
        $data->islayoutpolaroid = ($data->sectionlayout === 'polaroid');

        // Editing mode flag.
        $data->isediting = $PAGE->user_is_editing();

        // Progress style booleans.
        $data->iscircular = ($data->progressstyle === 'circular');
        $data->islinear = ($data->progressstyle === 'linear');

        // Calculate progress for each section.
        // Always calculate for kanban (needs progress to determine column placement).
        if ($data->showprogress || $data->islayoutkanban) {
            $completioninfo = new completion_info($course);
            $modinfo = get_fast_modinfo($course);

            if (!empty($data->sections)) {
                foreach ($data->sections as &$sectiondata) {
                    $sectionnum = $sectiondata->num ?? 0;
                    if ($sectionnum == 0) {
                        continue;
                    }
                    $sectioninfo = $modinfo->get_section_info($sectionnum);
                    if (!$sectioninfo) {
                        continue;
                    }
                    $sectionmods = $modinfo->sections[$sectionnum] ?? [];
                    $total = 0;
                    $completed = 0;
                    foreach ($sectionmods as $cmid) {
                        $cm = $modinfo->get_cm($cmid);
                        if ($cm->completion != COMPLETION_TRACKING_NONE) {
                            $total++;
                            $completiondata = $completioninfo->get_data($cm, true, $USER->id);
                            if (
                                $completiondata->completionstate == COMPLETION_COMPLETE ||
                                $completiondata->completionstate == COMPLETION_COMPLETE_PASS
                            ) {
                                $completed++;
                            }
                        }
                    }
                    $sectiondata->progress_total = $total;
                    $sectiondata->progress_completed = $completed;
                    $sectiondata->progress_percentage = ($total > 0) ? round(($completed / $total) * 100) : 0;
                    $sectiondata->progress_has_items = ($total > 0);
                    $sectiondata->progress_text = get_string('progressof', 'format_letstudy', [
                        'completed' => $completed,
                        'total' => $total,
                    ]);
                    // SVG dashoffset for circular progress (circumference = 2*pi*36 ≈ 226.2).
                    $circumference = 226.2;
                    $sectiondata->progress_dashoffset = $circumference - ($circumference * $sectiondata->progress_percentage / 100);
                    $sectiondata->progress_circumference = $circumference;
                    // Status booleans for path layout.
                    $sectiondata->progress_complete = ($total > 0 && $sectiondata->progress_percentage == 100);
                    $sectiondata->progress_inprogress = ($total > 0 && $completed > 0 && $sectiondata->progress_percentage < 100);
                    $sectiondata->progress_pending = ($total == 0 || $completed == 0);
                }
                unset($sectiondata);
            }
        }

        // Separate section 0 (General) from other sections and add icons/colors/urls.
        $generalsection = null;
        $coursesections = [];
        $modinfo = get_fast_modinfo($course);
        if (!empty($data->sections)) {
            $defaulticons = ['fa-book', 'fa-flask', 'fa-graduation-cap', 'fa-pencil', 'fa-star',
                'fa-lightbulb-o', 'fa-cogs', 'fa-trophy', 'fa-puzzle-piece', 'fa-rocket'];
            $firstfound = false;
            foreach ($data->sections as &$sectiondata) {
                $sectionnum = $sectiondata->num ?? 0;
                if ($sectionnum == 0) {
                    $generalsection = $sectiondata;
                    continue;
                }

                // Section format options (icon, color, image).
                $sectionoptions = $format->get_format_options($sectionnum);
                $sectiondata->sectionicon = !empty($sectionoptions['sectionicon'])
                    ? $sectionoptions['sectionicon']
                    : ($defaulticons[($sectionnum - 1) % count($defaulticons)] ?? 'fa-folder');
                $sectiondata->sectioncolor = !empty($sectionoptions['sectioncolor'])
                    ? $sectionoptions['sectioncolor']
                    : '';
                $sectiondata->hassectioncolor = !empty($sectiondata->sectioncolor);
                // Section image: check uploaded file first, then URL fallback.
                $coursecontext = \context_course::instance($course->id);
                $fs = get_file_storage();
                $sectioninfoobj = $modinfo->get_section_info($sectionnum);
                $imgfiles = $fs->get_area_files(
                    $coursecontext->id,
                    'format_letstudy',
                    'sectionimage',
                    $sectioninfoobj->id,
                    'sortorder, id',
                    false
                );
                if ($imgfiles) {
                    $imgfile = reset($imgfiles);
                    $sectiondata->sectionimage = \moodle_url::make_pluginfile_url(
                        $coursecontext->id,
                        'format_letstudy',
                        'sectionimage',
                        $sectioninfoobj->id,
                        $imgfile->get_filepath(),
                        $imgfile->get_filename()
                    )->out(false);
                    $sectiondata->hassectionimage = true;
                } else if (!empty($sectionoptions['sectionimage'])) {
                    $sectiondata->sectionimage = $sectionoptions['sectionimage'];
                    $sectiondata->hassectionimage = true;
                } else {
                    $sectiondata->sectionimage = '';
                    $sectiondata->hassectionimage = false;
                }

                // Section URL (link to section page).
                $sectioninfo = $modinfo->get_section_info($sectionnum);
                if ($sectioninfo) {
                    $sectiondata->sectionurl = (new \moodle_url('/course/section.php', ['id' => $sectioninfo->id]))->out(false);
                } else {
                    $sectiondata->sectionurl = '#';
                }

                // Section title.
                $sectiondata->sectiontitle = $format->get_section_name($sectionnum);

                // Activity count.
                $sectionmods = $modinfo->sections[$sectionnum] ?? [];
                $visiblecount = 0;
                foreach ($sectionmods as $cmid) {
                    $cm = $modinfo->get_cm($cmid);
                    if ($cm->uservisible) {
                        $visiblecount++;
                    }
                }
                $sectiondata->activitycount = $visiblecount;

                $sectiondata->sectionindex = $sectionnum - 1;
                $sectiondata->isfirstsection = !$firstfound;
                $firstfound = true;
                $coursesections[] = $sectiondata;
            }
            unset($sectiondata);
        }

        // Set separated sections.
        $data->initialsection = $generalsection;
        $data->sections = $coursesections;

        // Group sections for kanban layout.
        if ($data->islayoutkanban) {
            $data->kanban_pending = [];
            $data->kanban_inprogress = [];
            $data->kanban_complete = [];
            foreach ($coursesections as $section) {
                if (!empty($section->progress_complete)) {
                    $data->kanban_complete[] = $section;
                } else if (!empty($section->progress_inprogress)) {
                    $data->kanban_inprogress[] = $section;
                } else {
                    $data->kanban_pending[] = $section;
                }
            }
            $data->has_kanban_pending = !empty($data->kanban_pending);
            $data->has_kanban_inprogress = !empty($data->kanban_inprogress);
            $data->has_kanban_complete = !empty($data->kanban_complete);
            $data->kanban_pending_count = count($data->kanban_pending);
            $data->kanban_inprogress_count = count($data->kanban_inprogress);
            $data->kanban_complete_count = count($data->kanban_complete);
        }

        return $data;
    }
}
