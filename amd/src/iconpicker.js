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
 * Icon picker modal for Letstudy course format.
 *
 * Provides a visual icon browser for selecting FontAwesome icons
 * in section settings, replacing manual class name input.
 *
 * @module     format_letstudy/iconpicker
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {get_strings as getStrings} from 'core/str';

const ICONS = [
    // Education & Learning
    'fa-book', 'fa-graduation-cap', 'fa-pencil', 'fa-university', 'fa-bookmark',
    'fa-archive', 'fa-file-text', 'fa-file-pdf-o', 'fa-file-word-o', 'fa-newspaper-o',
    // Science & Technology
    'fa-flask', 'fa-code', 'fa-laptop', 'fa-desktop', 'fa-mobile',
    'fa-database', 'fa-terminal', 'fa-cogs', 'fa-cog', 'fa-microchip',
    // Creative
    'fa-paint-brush', 'fa-camera', 'fa-film', 'fa-music', 'fa-magic',
    'fa-picture-o', 'fa-video-camera', 'fa-microphone',
    // Communication
    'fa-envelope', 'fa-comment', 'fa-comments', 'fa-bell', 'fa-bullhorn',
    'fa-paper-plane', 'fa-rss', 'fa-phone', 'fa-fax',
    // Objects & Tools
    'fa-wrench', 'fa-key', 'fa-lock', 'fa-unlock', 'fa-shield', 'fa-trophy',
    'fa-star', 'fa-heart', 'fa-flag', 'fa-paperclip', 'fa-link',
    // People & Places
    'fa-user', 'fa-users', 'fa-globe', 'fa-home', 'fa-map-marker',
    'fa-building', 'fa-industry', 'fa-sitemap',
    // Nature & Environment
    'fa-leaf', 'fa-tree', 'fa-sun-o', 'fa-moon-o', 'fa-cloud',
    'fa-bolt', 'fa-fire', 'fa-tint', 'fa-snowflake-o', 'fa-paw',
    // Health & Safety
    'fa-medkit', 'fa-stethoscope', 'fa-heartbeat', 'fa-eye',
    'fa-ambulance', 'fa-plus-square', 'fa-wheelchair',
    // Navigation & Status
    'fa-check-circle', 'fa-info-circle', 'fa-question-circle',
    'fa-exclamation-triangle', 'fa-arrow-right', 'fa-lightbulb-o',
    'fa-thumbs-up', 'fa-thumbs-down',
    // Charts & Data
    'fa-bar-chart', 'fa-pie-chart', 'fa-line-chart', 'fa-calculator',
    'fa-tasks', 'fa-list', 'fa-th', 'fa-table',
    // Fun & Games
    'fa-gamepad', 'fa-puzzle-piece', 'fa-rocket', 'fa-diamond',
    'fa-cube', 'fa-cubes', 'fa-futbol-o', 'fa-bicycle',
    // Commerce
    'fa-shopping-cart', 'fa-money', 'fa-credit-card',
    // Transport
    'fa-car', 'fa-plane', 'fa-ship', 'fa-truck',
    // Law & Governance
    'fa-balance-scale', 'fa-gavel', 'fa-certificate',
    'fa-handshake-o', 'fa-briefcase',
    // Time
    'fa-calendar', 'fa-clock-o', 'fa-hourglass', 'fa-history',
    // Misc
    'fa-bullseye', 'fa-folder', 'fa-folder-open', 'fa-search',
    'fa-random', 'fa-refresh', 'fa-share-alt', 'fa-qrcode',
    'fa-map', 'fa-compass', 'fa-binoculars', 'fa-language',
];

/**
 * Show the icon picker modal.
 *
 * @param {HTMLInputElement} field The input field to set the selected icon value on.
 * @param {HTMLElement} preview The preview element to update.
 * @param {Object} strings The translated strings object.
 */
const showModal = (field, preview, strings) => {
    // Create backdrop.
    const backdrop = document.createElement('div');
    backdrop.className = 'ls-iconpicker-backdrop';

    // Create modal.
    const modal = document.createElement('div');
    modal.className = 'ls-iconpicker-modal';

    // Build icon grid HTML.
    let gridHtml = '';
    ICONS.forEach((icon) => {
        gridHtml += '<button type="button" class="ls-iconpicker-item" data-icon="' + icon + '" title="' + icon + '">'
            + '<i class="fa ' + icon + '"></i>'
            + '<span>' + icon.replace('fa-', '') + '</span>'
            + '</button>';
    });

    modal.innerHTML =
        '<div class="ls-iconpicker-header">'
        + '<h5 class="ls-iconpicker-title">' + strings.title + '</h5>'
        + '<button type="button" class="ls-iconpicker-close">&times;</button>'
        + '</div>'
        + '<div class="ls-iconpicker-search-wrap">'
        + '<input type="text" class="form-control ls-iconpicker-search" placeholder="' + strings.search + '">'
        + '</div>'
        + '<div class="ls-iconpicker-grid">'
        + gridHtml
        + '</div>';

    document.body.appendChild(backdrop);
    document.body.appendChild(modal);

    const searchInput = modal.querySelector('.ls-iconpicker-search');

    // Focus search.
    setTimeout(() => {
        searchInput.focus();
    }, 100);

    /**
     * Close and remove the modal.
     */
    const closeModal = () => {
        backdrop.remove();
        modal.remove();
        document.removeEventListener('keydown', escHandler);
    };

    /**
     * Handle Escape key.
     * @param {KeyboardEvent} e
     */
    const escHandler = (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    };
    document.addEventListener('keydown', escHandler);

    // Close on backdrop click.
    backdrop.addEventListener('click', closeModal);

    // Close button.
    modal.querySelector('.ls-iconpicker-close').addEventListener('click', closeModal);

    // Icon click.
    modal.addEventListener('click', (e) => {
        const item = e.target.closest('.ls-iconpicker-item');
        if (!item) {
            return;
        }
        field.value = item.dataset.icon;
        field.dispatchEvent(new Event('change', {bubbles: true}));
        if (preview) {
            preview.innerHTML = '<i class="fa ' + item.dataset.icon + '"></i>';
        }
        closeModal();
    });

    // Search filter.
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        modal.querySelectorAll('.ls-iconpicker-item').forEach((item) => {
            item.style.display = item.dataset.icon.includes(query) ? '' : 'none';
        });
    });
};

/**
 * Initialize the icon picker for the section edit form.
 */
export const init = async() => {
    const field = document.getElementById('id_sectionicon');
    if (!field) {
        return;
    }

    // Fetch translatable strings from Moodle.
    const fetchedStrings = await getStrings([
        {key: 'iconpicker_title', component: 'format_letstudy'},
        {key: 'iconpicker_search', component: 'format_letstudy'},
        {key: 'iconpicker_browse', component: 'format_letstudy'},
    ]);
    const strings = {
        title: fetchedStrings[0],
        search: fetchedStrings[1],
        browse: fetchedStrings[2],
    };

    // Create browse button.
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-outline-secondary';
    btn.innerHTML = '<i class="fa fa-th" aria-hidden="true"></i> ' + strings.browse;
    btn.style.marginLeft = '8px';
    btn.style.verticalAlign = 'middle';

    // Create preview.
    const preview = document.createElement('span');
    preview.className = 'ls-iconpicker-preview';
    preview.style.marginLeft = '8px';
    preview.style.fontSize = '1.5rem';
    preview.style.verticalAlign = 'middle';

    /**
     * Update the icon preview.
     */
    const updatePreview = () => {
        if (field.value && field.value.trim()) {
            preview.innerHTML = '<i class="fa ' + field.value.trim() + '"></i>';
        } else {
            preview.innerHTML = '';
        }
    };
    updatePreview();
    field.addEventListener('input', updatePreview);
    field.addEventListener('change', updatePreview);

    // Insert after field.
    const container = field.closest('.felement') || field.parentNode;
    container.appendChild(btn);
    container.appendChild(preview);

    // Open modal on click.
    btn.addEventListener('click', () => {
        showModal(field, preview, strings);
    });
};
