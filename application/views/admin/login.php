<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<script>
(function () {
  try {
    var saved = localStorage.getItem('portfolio-theme');
    var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
  } catch (e) { /* localStorage unavailable, default light theme applies */ }
})();
</script>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login</title>
<link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
<style>
.login-shell { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
.login-panel { width: min(420px, 100%); padding: 36px; position: relative; }
.login-panel .theme-toggle { position: absolute; top: 20px; right: 20px; }
.login-title { font-size: 30px; letter-spacing: -.02em; margin: 14px 0 26px; }
.login-hint { font-size: 11px; color: var(--muted); margin-top: 20px; }
</style>
</head>
<body>
<main class="login-shell">
  <section class="panel login-panel">
    <button type="button" class="theme-toggle" data-theme-toggle aria-pressed="false" aria-label="Aktifkan mode gelap" title="Aktifkan mode gelap">
      <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg>
      <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" hidden><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path></svg>
    </button>
    <div class="eyebrow brand-font">Portfolio admin</div>
    <h1 class="login-title brand-font">Welcome back.</h1>
    <?php if ($error): ?><div class="flash flash--error" role="alert"><?= html_escape($error) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('admin/login') ?>">
      <div class="field">
        <label for="login-username">USERNAME</label>
        <input id="login-username" name="username" required autofocus autocomplete="username">
      </div>
      <div class="field" style="margin-top:15px">
        <label for="login-password">PASSWORD</label>
        <input id="login-password" name="password" type="password" required autocomplete="current-password">
      </div>
      <button class="submit" type="submit" style="width:100%;margin-top:24px">Sign in ↗</button>
    </form>
    <p class="login-hint">Default lokal: admin / admin123</p>
  </section>
</main>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
</body>
</html>
