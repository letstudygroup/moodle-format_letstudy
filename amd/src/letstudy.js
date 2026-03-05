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
 * Main Letstudy course format module.
 *
 * Handles tab switching and progress bar updates on activity completion.
 * Uses event delegation to work with Moodle's reactive course editor.
 *
 * @module     format_letstudy/letstudy
 * @copyright  2026 Letstudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Initialize the tabs functionality using event delegation.
 *
 * @param {HTMLElement} container The format container element.
 */
const initTabs = (container) => {
    // Event delegation for tab switching.
    container.addEventListener('click', (e) => {
        const tab = e.target.closest('.format-letstudy__tab');
        if (!tab) {
            return;
        }

        const target = tab.dataset.target;
        if (!target) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        // Deactivate all tabs.
        container.querySelectorAll('.format-letstudy__tab').forEach((t) => {
            t.classList.remove('format-letstudy__tab--active');
            t.setAttribute('aria-selected', 'false');
            t.setAttribute('tabindex', '-1');
        });

        // Deactivate all panels.
        container.querySelectorAll('.format-letstudy__tab-panel').forEach((p) => {
            p.classList.remove('format-letstudy__tab-panel--active');
        });

        // Activate clicked tab.
        tab.classList.add('format-letstudy__tab--active');
        tab.setAttribute('aria-selected', 'true');
        tab.setAttribute('tabindex', '0');

        // Activate corresponding panel.
        const panel = container.querySelector(
            '.format-letstudy__tab-panel[data-sectionnum="' + target + '"]'
        );
        if (panel) {
            panel.classList.add('format-letstudy__tab-panel--active');
        }
    });

    // Keyboard navigation for tabs.
    container.addEventListener('keydown', (e) => {
        const currentTab = e.target.closest('.format-letstudy__tab');
        if (!currentTab) {
            return;
        }
        const tabs = container.querySelectorAll('.format-letstudy__tab');
        const tabArray = Array.from(tabs);
        const currentIndex = tabArray.indexOf(currentTab);
        let nextIndex = -1;

        if (e.key === 'ArrowRight') {
            nextIndex = (currentIndex + 1) % tabArray.length;
        } else if (e.key === 'ArrowLeft') {
            nextIndex = (currentIndex - 1 + tabArray.length) % tabArray.length;
        } else if (e.key === 'Home') {
            nextIndex = 0;
        } else if (e.key === 'End') {
            nextIndex = tabArray.length - 1;
        }

        if (nextIndex >= 0) {
            e.preventDefault();
            tabArray[nextIndex].focus();
            tabArray[nextIndex].click();
        }
    });
};

/**
 * Update progress indicators after a completion toggle without page reload.
 * Finds the section containing the toggled activity and recalculates the progress.
 *
 * @param {HTMLElement} toggle The completion toggle button that was clicked.
 */
const updateProgress = (toggle) => {
    // Find the section containing this activity.
    const section = toggle.closest('[data-sectionnum], [data-number]');
    if (!section) {
        return;
    }
    const sectionNum = section.dataset.sectionnum || section.dataset.number;

    // Wait for Moodle AJAX to complete the toggle.
    setTimeout(() => {
        // Count completed/total in this section.
        const allToggles = section.querySelectorAll(
            '[data-action="toggle-manual-completion"], .togglecompletion'
        );
        let total = 0;
        let completed = 0;
        allToggles.forEach((btn) => {
            total++;
            // After toggle, Moodle updates data-toggletype: "manual:mark" = not done, "manual:undo" = done.
            if (btn.dataset.toggletype === 'manual:undo' ||
                btn.classList.contains('btn-subtle-success') ||
                btn.querySelector('.fa-check')) {
                completed++;
            }
        });

        if (total === 0) {
            return;
        }

        const percentage = Math.round((completed / total) * 100);

        // Update all progress indicators for this section number.
        const container = document.querySelector('.format-letstudy');
        if (!container) {
            return;
        }

        // Update linear progress bars.
        const fills = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__progress-bar-fill'
        );
        fills.forEach((fill) => {
            fill.style.width = percentage + '%';
        });

        // Update percentage labels.
        const labels = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__progress-label'
        );
        labels.forEach((label) => {
            label.textContent = percentage + '%';
        });

        // Update card progress.
        const cardLabels = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__card-progress .format-letstudy__progress-label'
        );
        cardLabels.forEach((label) => {
            label.textContent = percentage + '%';
        });

        // Update circular SVG progress.
        const circumference = 226.2;
        const offset = circumference - (circumference * percentage / 100);
        const svgFills = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__progress-fill'
        );
        svgFills.forEach((circle) => {
            circle.setAttribute('stroke-dashoffset', offset);
        });

        // Update progress text in circular indicators.
        const progressTexts = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__progress-text'
        );
        progressTexts.forEach((text) => {
            text.textContent = percentage + '%';
        });

        // Update tab badges.
        const tabBadges = container.querySelectorAll(
            '.format-letstudy__tab[data-target="' + sectionNum + '"] .format-letstudy__tab-badge'
        );
        tabBadges.forEach((badge) => {
            badge.textContent = percentage + '%';
        });

        // Update timeline badges.
        const timelineBadges = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__timeline-badge'
        );
        timelineBadges.forEach((badge) => {
            badge.textContent = percentage + '%';
        });

        // Update card back progress text.
        const backProgress = container.querySelectorAll(
            '[data-sectionnum="' + sectionNum + '"] .format-letstudy__card-back-progress'
        );
        backProgress.forEach((el) => {
            el.textContent = percentage + '%';
        });
    }, 1000);
};

/**
 * Initialize progress watcher - updates progress without page reload.
 */
const initProgressWatcher = () => {
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest(
            '[data-action="toggle-manual-completion"], .togglecompletion'
        );
        if (!toggle) {
            return;
        }
        updateProgress(toggle);
    });
};

/**
 * Determine the completion state of a single activity element.
 *
 * @param {HTMLElement} activityEl The .activity element.
 * @return {string} 'complete', 'inprogress', or 'pending'.
 */
const getActivityState = (activityEl) => {
    // Manual completion: toggle button present.
    const toggle = activityEl.querySelector(
        '[data-action="toggle-manual-completion"], .togglecompletion'
    );
    if (toggle) {
        // "manual:undo" means activity IS complete (button would undo it).
        if (toggle.dataset.toggletype === 'manual:undo') {
            return 'complete';
        }
        // Green success button also means complete.
        if (toggle.classList.contains('btn-subtle-success') || toggle.querySelector('.fa-check')) {
            return 'complete';
        }
        // Has toggle but not complete → in progress (student has started interacting).
        return 'pending';
    }

    // Auto-completion indicators (badges/icons set by Moodle).
    if (activityEl.querySelector('.completion_complete, .completion-complete')) {
        return 'complete';
    }
    if (activityEl.querySelector('.completion_incomplete, .completion-incomplete')) {
        return 'pending';
    }

    // Check for data attribute completion state.
    const completionState = activityEl.dataset.completionState || activityEl.dataset.completion;
    if (completionState === '1' || completionState === 'complete') {
        return 'complete';
    }

    // No completion tracking at all → pending.
    return 'pending';
};

/**
 * Initialize activity-level Kanban on single section pages.
 * Moves activity elements from the standard list into kanban columns.
 * Uses retry mechanism because Moodle 5.0 reactive components render activities after page load.
 *
 * @param {HTMLElement} container The format container element.
 */
const initActivityKanban = (container) => {
    const kanbanBoard = container.querySelector('[data-region="letstudy-activity-kanban"]');
    if (!kanbanBoard) {
        return;
    }

    const colPending = kanbanBoard.querySelector('[data-kanban-col="pending"]');
    const colInprogress = kanbanBoard.querySelector('[data-kanban-col="inprogress"]');
    const colComplete = kanbanBoard.querySelector('[data-kanban-col="complete"]');

    if (!colPending || !colInprogress || !colComplete) {
        return;
    }

    /**
     * Find activities in the section using broad selectors.
     * @return {NodeList} The activity elements found.
     */
    const findActivities = () => {
        return container.querySelectorAll(
            '.format-letstudy__sections--single .activity[data-for="cmitem"], ' +
            '.format-letstudy__sections--single .activity[data-id], ' +
            '.format-letstudy__sections--single li.activity'
        );
    };

    /**
     * Find the cmlist container.
     * @return {HTMLElement|null} The cmlist element.
     */
    const findCmList = () => {
        return container.querySelector(
            '.format-letstudy__sections--single [data-for="cmlist"], ' +
            '.format-letstudy__sections--single .section .content ul'
        );
    };

    /**
     * Sort all activities into kanban columns.
     * @param {NodeList} activities The activity elements.
     */
    const sortActivities = (activities) => {
        let pendingCount = 0;
        let inprogressCount = 0;
        let completeCount = 0;

        activities.forEach((activity) => {
            const state = getActivityState(activity);

            // Create a card wrapper for the activity.
            let card = activity.closest('.format-letstudy__kanban-activity-card');
            if (!card) {
                card = document.createElement('div');
                card.className = 'format-letstudy__kanban-activity-card format-letstudy__kanban-card';
                activity.parentNode.insertBefore(card, activity);
                card.appendChild(activity);
            }

            // Remove column-state classes.
            card.classList.remove(
                'format-letstudy__kanban-activity-card--pending',
                'format-letstudy__kanban-activity-card--inprogress',
                'format-letstudy__kanban-activity-card--complete'
            );
            card.classList.add('format-letstudy__kanban-activity-card--' + state);

            // Move card to appropriate column.
            if (state === 'complete') {
                colComplete.appendChild(card);
                completeCount++;
            } else if (state === 'inprogress') {
                colInprogress.appendChild(card);
                inprogressCount++;
            } else {
                colPending.appendChild(card);
                pendingCount++;
            }
        });

        // Update counts.
        const countPending = kanbanBoard.querySelector('[data-kanban-count="pending"]');
        const countInprogress = kanbanBoard.querySelector('[data-kanban-count="inprogress"]');
        const countComplete = kanbanBoard.querySelector('[data-kanban-count="complete"]');
        if (countPending) {
            countPending.textContent = pendingCount;
        }
        if (countInprogress) {
            countInprogress.textContent = inprogressCount;
        }
        if (countComplete) {
            countComplete.textContent = completeCount;
        }
    };

    /**
     * Set up the kanban board once activities are found.
     * @param {NodeList} activities The activity elements.
     */
    const setupKanban = (activities) => {
        // Initial sort.
        sortActivities(activities);

        // Hide the original cmlist container (now empty).
        const cmList = findCmList();
        if (cmList) {
            cmList.style.display = 'none';
        }

        // Watch for completion toggles and re-sort after Moodle AJAX completes.
        kanbanBoard.addEventListener('click', (e) => {
            const toggle = e.target.closest(
                '[data-action="toggle-manual-completion"], .togglecompletion'
            );
            if (toggle) {
                setTimeout(() => {
                    const freshActivities = findActivities();
                    sortActivities(freshActivities);
                }, 1200);
            }
        });

        // Watch for Moodle reactive state changes (MutationObserver on activities).
        const completionObserver = new MutationObserver(() => {
            const freshActivities = findActivities();
            sortActivities(freshActivities);
        });
        activities.forEach((activity) => {
            completionObserver.observe(activity, {
                attributes: true,
                attributeFilter: ['data-completion', 'data-completionstate', 'class'],
                subtree: true,
            });
        });
    };

    // Try to find activities immediately.
    let activities = findActivities();
    if (activities.length > 0) {
        setupKanban(activities);
        return;
    }

    // Activities not yet rendered (Moodle reactive components render async).
    // Watch the section list container for children being added.
    const sectionList = container.querySelector('.format-letstudy__sections--single');
    if (!sectionList) {
        return;
    }

    let retryCount = 0;
    const maxRetries = 30;

    const retryFind = () => {
        activities = findActivities();
        if (activities.length > 0) {
            setupKanban(activities);
            return;
        }
        retryCount++;
        if (retryCount < maxRetries) {
            setTimeout(retryFind, 300);
        }
    };

    // Also use MutationObserver for faster detection.
    const domObserver = new MutationObserver(() => {
        activities = findActivities();
        if (activities.length > 0) {
            domObserver.disconnect();
            setupKanban(activities);
        }
    });
    domObserver.observe(sectionList, {childList: true, subtree: true});

    // Start retry as fallback.
    setTimeout(retryFind, 300);
};

/**
 * Initialize the Letstudy course format.
 *
 * @param {string} layout The section layout name passed from PHP.
 */
export const init = (layout) => {
    // Ensure DOM is ready.
    const run = () => {
        const container = document.querySelector('.format-letstudy');
        if (!container) {
            return;
        }

        // Use PHP-provided layout, fallback to data-attribute, fallback to class detection.
        const activeLayout = layout
            || container.dataset.layout
            || (container.className.match(
                /format-letstudy--(cards|tabs|list|timeline|path|kanban|metro|map|bookshelf|polaroid)/
            ) || [])[1]
            || 'cards';

        if (activeLayout === 'tabs') {
            initTabs(container);
        }

        // Activity-level Kanban on single section pages.
        // Always call - the function self-checks for the kanban board element.
        initActivityKanban(container);

        // Watch for completion changes to auto-update progress.
        initProgressWatcher();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
};
