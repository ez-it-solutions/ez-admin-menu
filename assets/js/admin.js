(function ($) {
  'use strict';

  function applyStoredTheme($root) {
    var stored = window.localStorage ? window.localStorage.getItem('EAMAdminTheme') : null;
    if (!stored) {
      return;
    }
    $root.removeClass('eam-theme-light eam-theme-dark');
    if (stored === 'dark') {
      $root.addClass('eam-theme-dark');
    } else {
      $root.addClass('eam-theme-light');
    }
  }

  $(function () {
    var $root = $('#eam-admin-root');
    if (!$root.length) {
      return;
    }

    applyStoredTheme($root);

    $('#eam-theme-toggle').on('click', function () {
      var isDark = $root.hasClass('eam-theme-dark');
      $root.toggleClass('eam-theme-dark', !isDark);
      $root.toggleClass('eam-theme-light', isDark);

      if (window.localStorage) {
        window.localStorage.setItem('EAMAdminTheme', !isDark ? 'dark' : 'light');
      }
    });
  });
})(jQuery);

