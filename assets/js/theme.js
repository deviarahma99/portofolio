(function () {
  'use strict';

  var STORAGE_KEY = 'portfolio-theme';
  var root = document.documentElement;

  function labelFor(theme) {
    return theme === 'dark' ? 'Aktifkan mode terang' : 'Aktifkan mode gelap';
  }

  function paintToggles(theme) {
    var buttons = document.querySelectorAll('[data-theme-toggle]');
    for (var i = 0; i < buttons.length; i++) {
      var btn = buttons[i];
      var isDark = theme === 'dark';
      var label = labelFor(theme);
      btn.setAttribute('aria-pressed', String(isDark));
      btn.setAttribute('aria-label', label);
      btn.setAttribute('title', label);
      var sun = btn.querySelector('.icon-sun');
      var moon = btn.querySelector('.icon-moon');
      if (sun) sun.hidden = isDark;
      if (moon) moon.hidden = !isDark;
    }
  }

  function setTheme(theme, persist) {
    root.setAttribute('data-theme', theme);
    if (persist) {
      try { localStorage.setItem(STORAGE_KEY, theme); } catch (e) { /* storage unavailable */ }
    }
    paintToggles(theme);
  }

  document.addEventListener('DOMContentLoaded', function () {
    paintToggles(root.getAttribute('data-theme') || 'light');

    var buttons = document.querySelectorAll('[data-theme-toggle]');
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].addEventListener('click', function () {
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        setTheme(next, true);
      });
    }
  });

  if (window.matchMedia) {
    var media = window.matchMedia('(prefers-color-scheme: dark)');
    var onSystemChange = function (e) {
      var stored = null;
      try { stored = localStorage.getItem(STORAGE_KEY); } catch (err) { /* storage unavailable */ }
      if (!stored) setTheme(e.matches ? 'dark' : 'light', false);
    };
    if (media.addEventListener) media.addEventListener('change', onSystemChange);
    else if (media.addListener) media.addListener(onSystemChange);
  }
})();
