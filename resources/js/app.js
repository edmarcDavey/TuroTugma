import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// initialize admin teacher form helpers (handles AJAX-inserted fragments)
import './admin-teacher-form';
