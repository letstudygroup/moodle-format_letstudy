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
 * Settings for format_letstudy.
 *
 * @package    format_letstudy
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // Indentation.
    $settings->add(new admin_setting_configcheckbox(
        'format_letstudy/indentation',
        new lang_string('indentation', 'format_letstudy'),
        new lang_string('indentation_help', 'format_letstudy'),
        1
    ));

    // Default layout.
    $settings->add(new admin_setting_configselect(
        'format_letstudy/defaultlayout',
        new lang_string('defaultlayout', 'format_letstudy'),
        new lang_string('defaultlayout_desc', 'format_letstudy'),
        'cards',
        [
            'cards' => new lang_string('layoutcards', 'format_letstudy'),
            'tabs' => new lang_string('layouttabs', 'format_letstudy'),
            'list' => new lang_string('layoutlist', 'format_letstudy'),
            'timeline' => new lang_string('layouttimeline', 'format_letstudy'),
            'path' => new lang_string('layoutpath', 'format_letstudy'),
            'kanban' => new lang_string('layoutkanban', 'format_letstudy'),
            'metro' => new lang_string('layoutmetro', 'format_letstudy'),
            'map' => new lang_string('layoutmap', 'format_letstudy'),
            'bookshelf' => new lang_string('layoutbookshelf', 'format_letstudy'),
            'polaroid' => new lang_string('layoutpolaroid', 'format_letstudy'),
        ]
    ));

    // Default columns.
    $settings->add(new admin_setting_configselect(
        'format_letstudy/defaultcolumns',
        new lang_string('defaultcolumns', 'format_letstudy'),
        new lang_string('defaultcolumns_desc', 'format_letstudy'),
        '3',
        ['2' => '2', '3' => '3', '4' => '4']
    ));

    // Default brand colour.
    $settings->add(new admin_setting_configcolourpicker(
        'format_letstudy/defaultbrandcolor',
        new lang_string('defaultbrandcolor', 'format_letstudy'),
        new lang_string('defaultbrandcolor_desc', 'format_letstudy'),
        '#1E88E5'
    ));

    // Default secondary colour.
    $settings->add(new admin_setting_configcolourpicker(
        'format_letstudy/defaultsecondarycolor',
        new lang_string('defaultsecondarycolor', 'format_letstudy'),
        new lang_string('defaultsecondarycolor_desc', 'format_letstudy'),
        '#FF6D00'
    ));

    // Default animations.
    $settings->add(new admin_setting_configcheckbox(
        'format_letstudy/defaultanimations',
        new lang_string('defaultanimations', 'format_letstudy'),
        new lang_string('defaultanimations_desc', 'format_letstudy'),
        1
    ));

    // Default animation style.
    $settings->add(new admin_setting_configselect(
        'format_letstudy/defaultanimstyle',
        new lang_string('defaultanimstyle', 'format_letstudy'),
        new lang_string('defaultanimstyle_desc', 'format_letstudy'),
        'fade',
        [
            'fade' => new lang_string('animfade', 'format_letstudy'),
            'slide' => new lang_string('animslide', 'format_letstudy'),
            'zoom' => new lang_string('animzoom', 'format_letstudy'),
            'bounce' => new lang_string('animbounce', 'format_letstudy'),
        ]
    ));

    // Default progress.
    $settings->add(new admin_setting_configcheckbox(
        'format_letstudy/defaultprogress',
        new lang_string('defaultprogress', 'format_letstudy'),
        new lang_string('defaultprogress_desc', 'format_letstudy'),
        1
    ));

    // Default progress style.
    $settings->add(new admin_setting_configselect(
        'format_letstudy/defaultprogressstyle',
        new lang_string('defaultprogressstyle', 'format_letstudy'),
        new lang_string('defaultprogressstyle_desc', 'format_letstudy'),
        'circular',
        [
            'circular' => new lang_string('progresscircular', 'format_letstudy'),
            'linear' => new lang_string('progresslinear', 'format_letstudy'),
        ]
    ));
}
