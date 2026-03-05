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
 * Main class for the Letstudy course format.
 *
 * @package    format_letstudy
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/course/format/lib.php');

use core\output\inplace_editable;

/**
 * Letstudy course format class.
 *
 * A highly configurable course format with 10 visual layouts (Cards, Tabs, List,
 * Timeline, Path, Kanban, Metro, Map, Bookshelf, Polaroid), section progress
 * tracking, animations, and customizable colors.
 *
 * @package    format_letstudy
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_letstudy extends core_courseformat\base {
    /**
     * Returns true if this course format uses sections.
     *
     * @return bool
     */
    public function uses_sections() {
        return true;
    }

    /**
     * Returns true if this course format uses the course index.
     *
     * @return bool
     */
    public function uses_course_index() {
        return true;
    }

    /**
     * Returns whether this format uses indentation.
     *
     * @return bool
     */
    public function uses_indentation(): bool {
        return (get_config('format_letstudy', 'indentation')) ? true : false;
    }

    /**
     * Returns the display name of the given section.
     *
     * @param int|stdClass $section Section object from database or just field section.section
     * @return string Display name that the course format prefers
     */
    public function get_section_name($section) {
        $section = $this->get_section($section);
        if ((string)$section->name !== '') {
            return format_string(
                $section->name,
                true,
                ['context' => context_course::instance($this->courseid)]
            );
        } else {
            return $this->get_default_section_name($section);
        }
    }

    /**
     * Returns the default section name.
     *
     * @param int|stdClass $section Section object from database or just field course_sections section
     * @return string The default value for the section name.
     */
    public function get_default_section_name($section) {
        $section = $this->get_section($section);
        if ($section->sectionnum == 0) {
            return get_string('section0name', 'format_letstudy');
        }
        return get_string('newsection', 'format_letstudy');
    }

    /**
     * Generate the title for this section page.
     *
     * @return string the page title
     */
    public function page_title(): string {
        return get_string('sectionoutline');
    }

    /**
     * The URL to use for the specified course (with section).
     *
     * @param int|stdClass $section Section object from database or just field course_sections.section
     * @param array $options options for view URL
     * @return moodle_url
     */
    public function get_view_url($section, $options = []) {
        $course = $this->get_course();
        if (array_key_exists('sr', $options) && !is_null($options['sr'])) {
            $sectionno = $options['sr'];
        } else if (is_object($section)) {
            $sectionno = $section->section;
        } else {
            $sectionno = $section;
        }
        if ((!empty($options['navigation']) || array_key_exists('sr', $options)) && $sectionno !== null) {
            $sectioninfo = $this->get_section($sectionno);
            return new moodle_url('/course/section.php', ['id' => $sectioninfo->id]);
        }
        return new moodle_url('/course/view.php', ['id' => $course->id]);
    }

    /**
     * Returns the information about the ajax support.
     *
     * @return stdClass
     */
    public function supports_ajax() {
        $ajaxsupport = new stdClass();
        $ajaxsupport->capable = true;
        return $ajaxsupport;
    }

    /**
     * Returns true to indicate this format supports the reactive course editor components.
     *
     * @return bool
     */
    public function supports_components() {
        return true;
    }

    /**
     * Loads all of the course sections into the navigation.
     *
     * @param global_navigation $navigation
     * @param navigation_node $node The course node within the navigation
     * @return void
     */
    public function extend_course_navigation($navigation, navigation_node $node) {
        global $PAGE;
        if ($navigation->includesectionnum === false) {
            $selectedsection = optional_param('section', null, PARAM_INT);
            if (
                $selectedsection !== null && (!defined('AJAX_SCRIPT') || AJAX_SCRIPT == '0') &&
                    $PAGE->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            ) {
                $navigation->includesectionnum = $selectedsection;
            }
        }
        parent::extend_course_navigation($navigation, $node);

        $modinfo = get_fast_modinfo($this->get_course());
        $sections = $modinfo->get_sections();
        if (!isset($sections[0])) {
            $section = $modinfo->get_section_info(0);
            $generalsection = $node->get($section->id, navigation_node::TYPE_SECTION);
            if ($generalsection) {
                $generalsection->remove();
            }
        }
    }

    /**
     * Custom action after section has been moved in AJAX mode.
     *
     * @return array This will be passed in ajax response
     */
    public function ajax_section_move() {
        global $PAGE;
        $titles = [];
        $course = $this->get_course();
        $modinfo = get_fast_modinfo($course);
        $renderer = $this->get_renderer($PAGE);
        if ($renderer && ($sections = $modinfo->get_section_info_all())) {
            foreach ($sections as $number => $section) {
                $titles[$number] = $renderer->section_title($section, $course);
            }
        }
        return ['sectiontitles' => $titles, 'action' => 'move'];
    }

    /**
     * Returns the list of blocks to be automatically added for the newly created course.
     *
     * @return array of default blocks
     */
    public function get_default_blocks() {
        return [
            BLOCK_POS_LEFT => [],
            BLOCK_POS_RIGHT => [],
        ];
    }

    /**
     * Definitions of the additional options that this course format uses for the course.
     *
     * @param bool $foreditform
     * @return array of options
     */
    public function course_format_options($foreditform = false) {
        static $courseformatoptions = false;
        if ($courseformatoptions === false) {
            $courseconfig = get_config('moodlecourse');
            $courseformatoptions = [
                'hiddensections' => [
                    'default' => $courseconfig->hiddensections ?? 1,
                    'type' => PARAM_INT,
                ],
                'coursedisplay' => [
                    'default' => $courseconfig->coursedisplay ?? COURSE_DISPLAY_SINGLEPAGE,
                    'type' => PARAM_INT,
                ],
                'sectionlayout' => [
                    'default' => 'cards',
                    'type' => PARAM_ALPHA,
                ],
                'cardcolumns' => [
                    'default' => 3,
                    'type' => PARAM_INT,
                ],
                'showprogress' => [
                    'default' => 1,
                    'type' => PARAM_INT,
                ],
                'progressstyle' => [
                    'default' => 'circular',
                    'type' => PARAM_ALPHA,
                ],
                'brandcolor' => [
                    'default' => '#1E88E5',
                    'type' => PARAM_RAW,
                ],
                'secondarycolor' => [
                    'default' => '#FF6D00',
                    'type' => PARAM_RAW,
                ],
                'enableanimations' => [
                    'default' => 1,
                    'type' => PARAM_INT,
                ],
                'animationstyle' => [
                    'default' => 'fade',
                    'type' => PARAM_ALPHA,
                ],
                'showsectionicon' => [
                    'default' => 1,
                    'type' => PARAM_INT,
                ],
            ];
        }
        if ($foreditform && !isset($courseformatoptions['sectionlayout']['label'])) {
            $courseformatoptionsedit = [
                'hiddensections' => [
                    'label' => new lang_string('hiddensections'),
                    'help' => 'hiddensections',
                    'help_component' => 'moodle',
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            0 => new lang_string('hiddensectionscollapsed'),
                            1 => new lang_string('hiddensectionsinvisible'),
                        ],
                    ],
                ],
                'coursedisplay' => [
                    'label' => new lang_string('coursedisplay'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            COURSE_DISPLAY_SINGLEPAGE => new lang_string('coursedisplay_single'),
                            COURSE_DISPLAY_MULTIPAGE => new lang_string('coursedisplay_multi'),
                        ],
                    ],
                    'help' => 'coursedisplay',
                    'help_component' => 'moodle',
                ],
                'sectionlayout' => [
                    'label' => get_string('sectionlayout', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            'cards' => get_string('layoutcards', 'format_letstudy'),
                            'tabs' => get_string('layouttabs', 'format_letstudy'),
                            'list' => get_string('layoutlist', 'format_letstudy'),
                            'timeline' => get_string('layouttimeline', 'format_letstudy'),
                            'path' => get_string('layoutpath', 'format_letstudy'),
                            'kanban' => get_string('layoutkanban', 'format_letstudy'),
                            'metro' => get_string('layoutmetro', 'format_letstudy'),
                            'map' => get_string('layoutmap', 'format_letstudy'),
                            'bookshelf' => get_string('layoutbookshelf', 'format_letstudy'),
                            'polaroid' => get_string('layoutpolaroid', 'format_letstudy'),
                        ],
                    ],
                    'help' => 'sectionlayout',
                    'help_component' => 'format_letstudy',
                ],
                'cardcolumns' => [
                    'label' => get_string('cardcolumns', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            2 => '2',
                            3 => '3',
                            4 => '4',
                        ],
                    ],
                    'help' => 'cardcolumns',
                    'help_component' => 'format_letstudy',
                ],
                'showprogress' => [
                    'label' => get_string('showprogress', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            1 => get_string('yes'),
                            0 => get_string('no'),
                        ],
                    ],
                    'help' => 'showprogress',
                    'help_component' => 'format_letstudy',
                ],
                'progressstyle' => [
                    'label' => get_string('progressstyle', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            'circular' => get_string('progresscircular', 'format_letstudy'),
                            'linear' => get_string('progresslinear', 'format_letstudy'),
                        ],
                    ],
                    'help' => 'progressstyle',
                    'help_component' => 'format_letstudy',
                ],
                'brandcolor' => [
                    'label' => get_string('brandcolor', 'format_letstudy'),
                    'element_type' => 'text',
                    'element_attributes' => [
                        ['size' => 7, 'maxlength' => 7],
                    ],
                    'help' => 'brandcolor',
                    'help_component' => 'format_letstudy',
                ],
                'secondarycolor' => [
                    'label' => get_string('secondarycolor', 'format_letstudy'),
                    'element_type' => 'text',
                    'element_attributes' => [
                        ['size' => 7, 'maxlength' => 7],
                    ],
                    'help' => 'secondarycolor',
                    'help_component' => 'format_letstudy',
                ],
                'enableanimations' => [
                    'label' => get_string('enableanimations', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            1 => get_string('yes'),
                            0 => get_string('no'),
                        ],
                    ],
                    'help' => 'enableanimations',
                    'help_component' => 'format_letstudy',
                ],
                'animationstyle' => [
                    'label' => get_string('animationstyle', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            'fade' => get_string('animfade', 'format_letstudy'),
                            'slide' => get_string('animslide', 'format_letstudy'),
                            'zoom' => get_string('animzoom', 'format_letstudy'),
                            'bounce' => get_string('animbounce', 'format_letstudy'),
                        ],
                    ],
                    'help' => 'animationstyle',
                    'help_component' => 'format_letstudy',
                ],
                'showsectionicon' => [
                    'label' => get_string('showsectionicon', 'format_letstudy'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            1 => get_string('yes'),
                            0 => get_string('no'),
                        ],
                    ],
                    'help' => 'showsectionicon',
                    'help_component' => 'format_letstudy',
                ],
            ];
            $courseformatoptions = array_merge_recursive($courseformatoptions, $courseformatoptionsedit);
        }
        return $courseformatoptions;
    }

    /**
     * Definitions of the additional options that this course format uses for sections.
     *
     * @param bool $foreditform
     * @return array
     */
    public function section_format_options($foreditform = false) {
        static $sectionformatoptions = false;
        if ($sectionformatoptions === false) {
            $sectionformatoptions = [
                'sectionicon' => [
                    'default' => '',
                    'type' => PARAM_TEXT,
                ],
                'sectioncolor' => [
                    'default' => '',
                    'type' => PARAM_RAW,
                ],
                'sectionimage' => [
                    'default' => '',
                    'type' => PARAM_RAW,
                ],
            ];
        }
        if ($foreditform && !isset($sectionformatoptions['sectionicon']['label'])) {
            $sectionformatoptionsedit = [
                'sectionicon' => [
                    'label' => get_string('sectionicon', 'format_letstudy'),
                    'element_type' => 'text',
                    'element_attributes' => [
                        ['size' => 30, 'placeholder' => 'fa-book'],
                    ],
                    'help' => 'sectionicon',
                    'help_component' => 'format_letstudy',
                ],
                'sectioncolor' => [
                    'label' => get_string('sectioncolor', 'format_letstudy'),
                    'element_type' => 'text',
                    'element_attributes' => [
                        ['size' => 7, 'maxlength' => 7, 'placeholder' => '#FF6D00'],
                    ],
                ],
                'sectionimage' => [
                    'label' => get_string('sectionimage_url', 'format_letstudy'),
                    'element_type' => 'text',
                    'element_attributes' => [
                        ['size' => 60, 'placeholder' => 'https://example.com/image.jpg'],
                    ],
                    'help' => 'sectionimage',
                    'help_component' => 'format_letstudy',
                ],
            ];
            $sectionformatoptions = array_merge_recursive($sectionformatoptions, $sectionformatoptionsedit);
        }
        return $sectionformatoptions;
    }

    /**
     * Adds format options elements to the course/section edit form.
     *
     * @param MoodleQuickForm $mform form the elements are added to.
     * @param bool $forsection 'true' if this is a section edit form, 'false' if this is course edit form.
     * @return array array of references to the added form elements.
     */
    public function create_edit_form_elements(&$mform, $forsection = false) {
        global $COURSE, $PAGE;
        $elements = parent::create_edit_form_elements($mform, $forsection);

        if (!$forsection && (empty($COURSE->id) || $COURSE->id == SITEID)) {
            $courseconfig = get_config('moodlecourse');
            $max = (int)$courseconfig->maxsections;
            $element = $mform->addElement('select', 'numsections', get_string('numberweeks'), range(0, $max ?: 52));
            $mform->setType('numsections', PARAM_INT);
            if (is_null($mform->getElementValue('numsections'))) {
                $mform->setDefault('numsections', $courseconfig->numsections);
            }
            array_unshift($elements, $element);
        }

        // Add conditional visibility rules for course-level settings.
        if (!$forsection) {
            // Number of columns only applies to Cards layout.
            $mform->hideIf('cardcolumns', 'sectionlayout', 'neq', 'cards');

            // Animation style only shows when animations are enabled.
            $mform->hideIf('animationstyle', 'enableanimations', 'eq', 0);

            // Progress style only shows when progress is enabled.
            $mform->hideIf('progressstyle', 'showprogress', 'eq', 0);

            // Add HTML5 color picker next to color text fields.
            $PAGE->requires->js_amd_inline("
                require([], function() {
                    var fields = ['brandcolor', 'secondarycolor'];
                    fields.forEach(function(name) {
                        var input = document.getElementById('id_' + name);
                        if (!input) return;
                        var picker = document.createElement('input');
                        picker.type = 'color';
                        picker.value = input.value || '#1E88E5';
                        picker.style.cssText = 'width:44px;height:36px;padding:2px;' +
                            'border:1px solid #ced4da;border-radius:6px;' +
                            'cursor:pointer;margin-left:8px;vertical-align:middle';
                        picker.addEventListener('input', function() { input.value = this.value; });
                        input.addEventListener('input', function() { try { picker.value = this.value; } catch(e) {} });
                        input.parentNode.insertBefore(picker, input.nextSibling);
                    });
                });
            ");
        }

        // Section-level: add file upload for section image + icon picker.
        if ($forsection) {
            global $PAGE;

            // Load icon picker AMD module.
            $PAGE->requires->js_call_amd('format_letstudy/iconpicker', 'init');

            // Add filemanager for section image upload.
            $sectionid = optional_param('id', 0, PARAM_INT);
            $context = context_course::instance($this->courseid);

            $fileoptions = [
                'maxfiles' => 1,
                'accepted_types' => ['image'],
                'subdirs' => 0,
            ];

            $draftitemid = file_get_submitted_draft_itemid('sectionimage_filemanager');
            file_prepare_draft_area(
                $draftitemid,
                $context->id,
                'format_letstudy',
                'sectionimage',
                $sectionid,
                $fileoptions
            );

            $element = $mform->createElement(
                'filemanager',
                'sectionimage_filemanager',
                get_string('sectionimage_upload', 'format_letstudy'),
                null,
                $fileoptions
            );

            // Insert the filemanager before the URL text field if it exists.
            if ($mform->elementExists('sectionimage')) {
                $mform->insertElementBefore($element, 'sectionimage');
            } else {
                $mform->addElement($element);
            }
            $mform->setDefault('sectionimage_filemanager', $draftitemid);
            $mform->addHelpButton('sectionimage_filemanager', 'sectionimage_upload', 'format_letstudy');
            array_unshift($elements, $element);
        }

        return $elements;
    }

    /**
     * Updates format options for a course.
     *
     * @param stdClass|array $data return value from moodleform::get_data() or array with data
     * @param stdClass $oldcourse if this function is called from update_course()
     * @return bool whether there were any changes to the options values
     */
    public function update_course_format_options($data, $oldcourse = null) {
        $data = (array)$data;
        if ($oldcourse !== null) {
            $oldcourse = (array)$oldcourse;
            $options = $this->course_format_options();
            foreach ($options as $key => $unused) {
                if (!array_key_exists($key, $data)) {
                    if (array_key_exists($key, $oldcourse)) {
                        $data[$key] = $oldcourse[$key];
                    }
                }
            }
        }
        return $this->update_format_options($data);
    }

    /**
     * Updates format options for a section.
     *
     * Handles file uploads from the section image filemanager.
     *
     * @param stdClass|array $data return value from moodleform::get_data() or array with data
     * @return bool whether there were any changes to the options values
     */
    public function update_section_format_options($data) {
        $data = (array)$data;

        // Handle section image file upload.
        if (isset($data['sectionimage_filemanager'])) {
            $sectionid = $data['id'] ?? 0;
            $context = context_course::instance($this->courseid);

            $fileoptions = [
                'maxfiles' => 1,
                'accepted_types' => ['image'],
                'subdirs' => 0,
            ];

            file_save_draft_area_files(
                $data['sectionimage_filemanager'],
                $context->id,
                'format_letstudy',
                'sectionimage',
                $sectionid,
                $fileoptions
            );
        }

        return parent::update_section_format_options($data);
    }

    /**
     * Whether this format allows to delete sections.
     *
     * @param int|stdClass|section_info $section
     * @return bool
     */
    public function can_delete_section($section) {
        return true;
    }

    /**
     * Indicates whether the course format supports the creation of a news forum.
     *
     * @return bool
     */
    public function supports_news() {
        return true;
    }

    /**
     * Returns whether this course format allows the activity to have "triple visibility state".
     *
     * @param stdClass|cm_info $cm course module
     * @param stdClass|section_info $section section where this module is located
     * @return bool
     */
    public function allow_stealth_module_visibility($cm, $section) {
        return !$section->section || $section->visible;
    }

    /**
     * Callback used in WS core_course_edit_section when teacher performs an AJAX action on a section.
     *
     * @param section_info|stdClass $section
     * @param string $action
     * @param int $sr
     * @return null|array any data for the Javascript post-processor
     */
    public function section_action($section, $action, $sr) {
        global $PAGE;

        if ($section->section && ($action === 'setmarker' || $action === 'removemarker')) {
            require_capability('moodle/course:setcurrentsection', context_course::instance($this->courseid));
            course_set_marker($this->courseid, ($action === 'setmarker') ? $section->section : 0);
            return null;
        }

        $rv = parent::section_action($section, $action, $sr);
        $renderer = $PAGE->get_renderer('format_letstudy');

        if (!($section instanceof section_info)) {
            $modinfo = course_modinfo::instance($this->courseid);
            $section = $modinfo->get_section_info($section->section);
        }
        $elementclass = $this->get_output_classname('content\\section\\availability');
        $availability = new $elementclass($this, $section);

        $rv['section_availability'] = $renderer->render($availability);
        return $rv;
    }

    /**
     * Return the plugin configs for external functions.
     *
     * @return array the list of configuration settings
     */
    public function get_config_for_external() {
        $formatoptions = $this->get_format_options();
        $formatoptions['indentation'] = get_config('format_letstudy', 'indentation');
        return $formatoptions;
    }

    /**
     * Get the required javascript files for the course format.
     *
     * @return array The list of javascript files required by the course format.
     */
    public function get_required_jsfiles(): array {
        return [];
    }
}

/**
 * Implements callback inplace_editable() allowing to edit values in-place.
 *
 * @param string $itemtype
 * @param int $itemid
 * @param mixed $newvalue
 * @return inplace_editable
 */
function format_letstudy_inplace_editable($itemtype, $itemid, $newvalue) {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/course/lib.php');
    if ($itemtype === 'sectionname' || $itemtype === 'sectionnamenl') {
        $section = $DB->get_record_sql(
            'SELECT s.* FROM {course_sections} s JOIN {course} c ON s.course = c.id WHERE s.id = ? AND c.format = ?',
            [$itemid, 'letstudy'],
            MUST_EXIST
        );
        return course_get_format($section->course)->inplace_editable_update_section_name($section, $itemtype, $newvalue);
    }
}

/**
 * Serves files for the Letstudy course format plugin.
 *
 * Handles section background images uploaded via the section edit form.
 *
 * @param stdClass $course Course object
 * @param stdClass $cm Course module object (unused)
 * @param context $context Course context
 * @param string $filearea File area name
 * @param array $args Extra path arguments
 * @param bool $forcedownload Force download instead of inline display
 * @param array $options Additional options
 * @return void|false
 */
function format_letstudy_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    if ($filearea !== 'sectionimage') {
        return false;
    }

    require_course_login($course, true, null, false);

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'format_letstudy', $filearea, $itemid, $filepath, $filename);

    if (!$file) {
        return false;
    }

    send_stored_file($file, 86400, 0, $forcedownload, $options);
}
