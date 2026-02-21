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
 * Strings for component 'format_letstudy', language 'el'.
 *
 * @package    format_letstudy
 * @copyright  2026 LetStudy Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'LetStudy Course Formats';
$string['plugin_description'] = 'Ένα σύγχρονο, εξαιρετικά παραμετροποιήσιμο format μαθήματος με 10 μοναδικές προβολές (Κάρτες, Καρτέλες, Λίστα, Timeline, Μονοπάτι Μάθησης, Kanban, Metro Tiles, Χάρτης Περιπέτειας, 3D Βιβλιοθήκη, Τοίχος Polaroid), παρακολούθηση προόδου, animations και προσαρμόσιμα χρώματα. Από την LetStudy Group.';

// Section naming.
$string['sectionname'] = 'Ενότητα';
$string['section0name'] = 'Γενικά';
$string['newsection'] = 'Νέα ενότητα';
$string['hidefromothers'] = 'Απόκρυψη ενότητας';
$string['showfromothers'] = 'Εμφάνιση ενότητας';
$string['currentsection'] = 'Αυτή η ενότητα';
$string['page-course-view-letstudy'] = 'Οποιαδήποτε κύρια σελίδα μαθήματος σε LetStudy format';
$string['page-course-view-letstudy-x'] = 'Οποιαδήποτε σελίδα μαθήματος σε LetStudy format';

// Layout options.
$string['sectionlayout'] = 'Εμφάνιση ενοτήτων';
$string['sectionlayout_help'] = 'Επιλέξτε πώς εμφανίζονται οι ενότητες στους μαθητές:

- **Κάρτες / Πλέγμα**: Οι ενότητες εμφανίζονται ως κάρτες με flip animation. Κάντε κλικ σε μια κάρτα για να ανοίξετε την ενότητα. Ιδανικό για μαθήματα με εικόνες.
- **Καρτέλες**: Οι ενότητες εμφανίζονται ως οριζόντιες καρτέλες. Κατάλληλο για μαθήματα με λίγες ενότητες.
- **Μοντέρνα Λίστα**: Οι ενότητες εμφανίζονται σε κάθετη λίστα με μπάρα προόδου και links. Καθαρό και μινιμαλιστικό.
- **Timeline**: Οι ενότητες εμφανίζονται σε κάθετο timeline με κουκκίδες και περιεχόμενο. Κατάλληλο για χρονολογικά μαθήματα.
- **Μονοπάτι Μάθησης**: Οι ενότητες εμφανίζονται σαν μονοπάτι μάθησης με zigzag κόμβους. Δείχνει κατάσταση προόδου ανά βήμα (ολοκληρωμένο, σε εξέλιξη, εκκρεμεί). Ιδανικό για καθοδηγούμενα, σειριακά μαθήματα.
- **Πίνακας Kanban**: Οι ενότητες ταξινομούνται αυτόματα σε 3 στήλες (Εκκρεμεί, Σε εξέλιξη, Ολοκληρώθηκε) βάσει προόδου μαθητή. Ιδανικό για project-based μαθήματα.
- **Metro Tiles**: Πλακίδια εμπνευσμένα από Windows σε δυναμικό πλέγμα με διαφορετικά μεγέθη. Έντονα χρώματα, hover animations, σύγχρονο dashboard αίσθηση.
- **Χάρτης Περιπέτειας**: Ένας δρόμος σαν επιτραπέζιο παιχνίδι όπου οι ενότητες είναι στάσεις σε μια περιπέτεια. Δείχνει τρέχουσα θέση, σημαίες ολοκλήρωσης και τρόπαιο τερματισμού.
- **3D Βιβλιοθήκη**: Οι ενότητες εμφανίζονται ως βιβλία σε ξύλινο ράφι. Hover τραβάει το βιβλίο έξω με 3D προοπτική. Οπτικό, immersive και διασκεδαστικό.
- **Τοίχος Polaroid**: Οι ενότητες εμφανίζονται σαν φωτογραφίες polaroid καρφιτσωμένες σε τοίχο με ελαφριά κλίση. Hover τις ανασηκώνει. Δημιουργικό και καλλιτεχνικό.';
$string['layoutcards'] = 'Κάρτες / Πλέγμα';
$string['layouttabs'] = 'Καρτέλες';
$string['layoutlist'] = 'Μοντέρνα Λίστα';
$string['layouttimeline'] = 'Timeline';
$string['layoutpath'] = 'Μονοπάτι Μάθησης';
$string['layoutkanban'] = 'Πίνακας Kanban';
$string['layoutmetro'] = 'Metro Tiles';
$string['layoutmap'] = 'Χάρτης Περιπέτειας';
$string['layoutbookshelf'] = '3D Βιβλιοθήκη';
$string['layoutpolaroid'] = 'Τοίχος Polaroid';
$string['cardcolumns'] = 'Αριθμός στηλών (μόνο για Κάρτες)';
$string['cardcolumns_help'] = 'Πόσες στήλες καρτών θα εμφανίζονται στο πλέγμα. Ισχύει μόνο όταν η εμφάνιση είναι ρυθμισμένη σε "Κάρτες / Πλέγμα". Σε κινητές συσκευές, το πλέγμα προσαρμόζεται αυτόματα σε λιγότερες στήλες.';

// Progress options.
$string['showprogress'] = 'Εμφάνιση προόδου ενοτήτων';
$string['showprogress_help'] = 'Εμφανίζει έναν δείκτη προόδου σε κάθε ενότητα που δείχνει πόσες δραστηριότητες έχει ολοκληρώσει ο μαθητής. Απαιτεί να είναι ενεργοποιημένη η ολοκλήρωση δραστηριοτήτων στο μάθημα.';
$string['progressstyle'] = 'Στυλ δείκτη προόδου';
$string['progressstyle_help'] = 'Επιλέξτε το οπτικό στυλ του δείκτη προόδου. Ισχύει μόνο όταν η "Εμφάνιση προόδου" είναι ενεργή. "Κυκλικό" δείχνει ένα δαχτυλίδι, "Γραμμική μπάρα" δείχνει μια οριζόντια μπάρα.';
$string['progresscircular'] = 'Κυκλικό';
$string['progresslinear'] = 'Γραμμική μπάρα';
$string['progressof'] = '{$a->completed} από {$a->total} ολοκληρώθηκαν';

// Color options.
$string['brandcolor'] = 'Κύριο χρώμα';
$string['brandcolor_help'] = 'Το κύριο χρώμα που χρησιμοποιείται σε όλα τα layouts για κεφαλίδες, εικονίδια, μπάρες προόδου και εφέ. Εισάγετε κωδικό HEX (π.χ. #1E88E5). Αυτό το χρώμα επηρεάζει όλες τις προβολές.';
$string['secondarycolor'] = 'Δευτερεύον χρώμα';
$string['secondarycolor_help'] = 'Το δευτερεύον χρώμα που χρησιμοποιείται για gradients, εφέ hover και οπτικό βάθος. Συνεργάζεται με το κύριο χρώμα. Εισάγετε κωδικό HEX (π.χ. #FF6D00).';

// Animation options.
$string['enableanimations'] = 'Ενεργοποίηση animations';
$string['enableanimations_help'] = 'Όταν είναι ενεργά, οι ενότητες θα εμφανίζονται με animation κατά τη φόρτωση της σελίδας (π.χ. fade in, slide up). Ισχύει σε όλες τις προβολές. Χρήστες με ρύθμιση "prefers-reduced-motion" δεν θα βλέπουν animations.';
$string['animationstyle'] = 'Στυλ animation';
$string['animationstyle_help'] = 'Επιλέξτε το animation εισόδου για τις ενότητες κατά τη φόρτωση. Ισχύει μόνο όταν τα animations είναι ενεργά.';
$string['animfade'] = 'Σταδιακή εμφάνιση';
$string['animslide'] = 'Ολίσθηση προς τα πάνω';
$string['animzoom'] = 'Μεγέθυνση';
$string['animbounce'] = 'Αναπήδηση';

// Section options (per-section settings).
$string['showsectionicon'] = 'Εμφάνιση εικονιδίων ενοτήτων';
$string['showsectionicon_help'] = 'Εμφανίζει ένα εικονίδιο δίπλα στον τίτλο κάθε ενότητας. Τα εικονίδια εκχωρούνται αυτόματα αλλά μπορούν να προσαρμοστούν ανά ενότητα. Ισχύει σε όλες τις προβολές.';
$string['sectionicon'] = 'Προσαρμοσμένο εικονίδιο (FontAwesome)';
$string['sectionicon_help'] = 'Εισάγετε μια κλάση εικονιδίου FontAwesome ή χρησιμοποιήστε το κουμπί "Browse" για οπτική επιλογή. Αφήστε κενό για το προεπιλεγμένο εικονίδιο.';
$string['sectioncolor'] = 'Προσαρμοσμένο χρώμα ενότητας';
$string['sectioncolor_help'] = 'Αντικαθιστά το κύριο χρώμα μόνο για αυτήν την ενότητα. Εισάγετε κωδικό HEX (π.χ. #E91E63). Αφήστε κενό για να χρησιμοποιηθεί το κύριο χρώμα του μαθήματος.';
$string['sectionimage'] = 'Εικόνα φόντου κάρτας';
$string['sectionimage_help'] = 'Ορίστε μια εικόνα φόντου για την κάρτα αυτής της ενότητας. Μπορείτε να ανεβάσετε εικόνα ή να εισάγετε URL. Ισχύει μόνο σε εμφάνιση "Κάρτες / Πλέγμα". Προτεινόμενο μέγεθος: 600x400px.';
$string['sectionimage_upload'] = 'Ανέβασμα εικόνας κάρτας';
$string['sectionimage_upload_help'] = 'Ανεβάστε μια εικόνα για χρήση ως φόντο στην κάρτα αυτής της ενότητας. Ισχύει μόνο σε εμφάνιση "Κάρτες / Πλέγμα". Προτεινόμενο μέγεθος: 600x400px. Αν υπάρχει και ανεβασμένη εικόνα και URL, η ανεβασμένη εικόνα έχει προτεραιότητα.';
$string['sectionimage_url'] = 'Ή εισάγετε URL εικόνας (εναλλακτικά)';
$string['clicktoopen'] = 'Κάντε κλικ για άνοιγμα';
$string['activities'] = '{$a} δραστηριότητες';

// Privacy.
$string['privacy:metadata'] = 'Το plugin LetStudy format δεν αποθηκεύει προσωπικά δεδομένα.';

// Admin settings (site-wide defaults).
$string['indentation'] = 'Να επιτρέπεται η εσοχή στη σελίδα μαθήματος';
$string['indentation_help'] = 'Να επιτρέπεται στους εκπαιδευτές να κάνουν εσοχή σε δραστηριότητες και πόρους στη σελίδα μαθήματος.';
$string['defaultlayout'] = 'Προεπιλεγμένη εμφάνιση';
$string['defaultlayout_desc'] = 'Η εμφάνιση που θα χρησιμοποιούν τα νέα μαθήματα εξ ορισμού. Οι εκπαιδευτές μπορούν να την αλλάξουν ανά μάθημα.';
$string['defaultcolumns'] = 'Προεπιλεγμένος αριθμός στηλών';
$string['defaultcolumns_desc'] = 'Προεπιλεγμένος αριθμός στηλών για την εμφάνιση Κάρτες / Πλέγμα. Ισχύει μόνο όταν είναι επιλεγμένες οι Κάρτες.';
$string['defaultbrandcolor'] = 'Προεπιλεγμένο κύριο χρώμα';
$string['defaultbrandcolor_desc'] = 'Προεπιλεγμένο κύριο χρώμα (HEX) για νέα μαθήματα. Οι εκπαιδευτές μπορούν να το αλλάξουν ανά μάθημα.';
$string['defaultsecondarycolor'] = 'Προεπιλεγμένο δευτερεύον χρώμα';
$string['defaultsecondarycolor_desc'] = 'Προεπιλεγμένο δευτερεύον χρώμα (HEX) για νέα μαθήματα. Οι εκπαιδευτές μπορούν να το αλλάξουν ανά μάθημα.';
$string['defaultanimations'] = 'Ενεργοποίηση animations εξ ορισμού';
$string['defaultanimations_desc'] = 'Αν τα animations εισόδου θα είναι ενεργά εξ ορισμού στα νέα μαθήματα.';
$string['defaultanimstyle'] = 'Προεπιλεγμένο στυλ animation';
$string['defaultanimstyle_desc'] = 'Προεπιλεγμένο animation εισόδου για νέα μαθήματα. Ισχύει μόνο όταν τα animations είναι ενεργά.';
$string['defaultprogress'] = 'Εμφάνιση προόδου εξ ορισμού';
$string['defaultprogress_desc'] = 'Αν η πρόοδος ολοκλήρωσης ενοτήτων θα εμφανίζεται εξ ορισμού στα νέα μαθήματα.';
$string['defaultprogressstyle'] = 'Προεπιλεγμένο στυλ προόδου';
$string['defaultprogressstyle_desc'] = 'Προεπιλεγμένο στυλ δείκτη προόδου για νέα μαθήματα. Ισχύει μόνο όταν η πρόοδος είναι ενεργή.';

// Kanban board strings.
$string['kanban_pending'] = 'Εκκρεμεί';
$string['kanban_inprogress'] = 'Σε εξέλιξη';
$string['kanban_complete'] = 'Ολοκληρώθηκε';
$string['kanban_empty'] = 'Καμία ενότητα εδώ';

// Icon picker strings.
$string['iconpicker_title'] = 'Επιλογή Εικονιδίου';
$string['iconpicker_search'] = 'Αναζήτηση εικονιδίων...';
$string['iconpicker_browse'] = 'Αναζήτηση';
