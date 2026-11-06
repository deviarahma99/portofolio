<?php defined('BASEPATH') OR exit('No direct script access allowed');

$profile = $profile ?: array(
    'name' => 'Cerdizan',
    'role' => 'Software Engineer',
    'headline' => 'Membangun software yang terasa sederhana, cepat, dan berdampak.',
    'bio' => 'Saya merancang dan membangun produk digital dari ide hingga rilis.',
    'location' => 'Indonesia',
    'email' => 'hello@cerdizan.dev',
    'availability' => 'Open for selected projects',
    'linkedin_url' => '#',
    'github_url' => '#',
    'avatar_url' => ''
);

$avatar_raw = isset($profile['avatar_url']) ? trim($profile['avatar_url']) : '';
$avatar_url = '';
if ($avatar_raw !== '') {
    if (preg_match('#^https?://#i', $avatar_raw)) {
        if (filter_var($avatar_raw, FILTER_VALIDATE_URL)) {
            $avatar_url = $avatar_raw;
        }
    } elseif (is_file(FCPATH . ltrim($avatar_raw, '/'))) {
        $avatar_url = base_url(ltrim($avatar_raw, '/'));
    }
}
$name_parts = preg_split('/\s+/', trim($profile['name']));
$initials = strtoupper(substr($name_parts[0], 0, 1) . (count($name_parts) > 1 ? substr($name_parts[count($name_parts) - 1], 0, 1) : ''));
$logo_initial = strtoupper(substr($name_parts[0], 0, 1));

$skills = $skills ?: array();
$projects = $projects ?: array();

$skills_count = count($skills);
$projects_count = count($projects);

$categories = array();
foreach ($skills as $skill) {
    $categories[$skill['category']] = TRUE;
}
$categories_count = count($categories);

$tech_tokens = array();
foreach ($projects as $project) {
    foreach (preg_split('/,\s*/', trim($project['tech_stack'])) as $token) {
        if ($token !== '') {
            $tech_tokens[strtolower($token)] = TRUE;
        }
    }
}
$tech_count = count($tech_tokens);

$has_linkedin = !empty($profile['linkedin_url']) && $profile['linkedin_url'] !== '#';
$has_github = !empty($profile['github_url']) && $profile['github_url'] !== '#';
?>
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
<title><?= html_escape($profile['name']) ?> - <?= html_escape($profile['role']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/css/portfolio.css') ?>">
</head>
<body>

<header class="site-header">
  <div class="container">
    <nav class="site-nav">
      <a class="brand" href="<?= base_url() ?>">
        <span class="brand-mark"><?= html_escape($logo_initial) ?></span>
        <span><?= html_escape($profile['name']) ?></span>
      </a>
      <input type="checkbox" id="nav-toggle" class="nav-toggle-input">
      <div class="nav-links">
        <a href="#about">About</a>
        <a href="#skills">Skills</a>
        <a href="#projects">Projects</a>
        <a href="#contact">Contact</a>
      </div>
      <div class="nav-actions">
        <button type="button" class="theme-toggle" data-theme-toggle aria-pressed="false" aria-label="Aktifkan mode gelap" title="Aktifkan mode gelap">
          <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg>
          <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" hidden><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path></svg>
        </button>
        <a class="button button-small" href="#contact">Let's Talk <span aria-hidden="true">↗</span></a>
        <label for="nav-toggle" class="nav-toggle-btn" aria-label="Buka menu navigasi">
          <span></span><span></span><span></span>
        </label>
      </div>
    </nav>
  </div>
</header>

<main>
  <section class="container hero" id="hero">
    <div class="hero-content">
      <div class="eyebrow"><span class="pulse-dot" aria-hidden="true"></span> <?= html_escape($profile['availability']) ?></div>
      <span class="hero-kicker"><?= html_escape($profile['name']) ?> · <?= html_escape($profile['role']) ?></span>
      <h1><?= html_escape($profile['headline']) ?></h1>
      <p class="hero-copy">Saya menerjemahkan masalah kompleks menjadi pengalaman digital yang terasa jernih, berbasis di <?= html_escape($profile['location']) ?>.</p>
      <div class="actions">
        <a class="button dark" href="#projects">View My Work <span aria-hidden="true">↓</span></a>
        <a class="button" href="#contact">Contact Me</a>
      </div>
      <div class="stat-strip">
        <div><b><?= (int) $projects_count ?>+</b><span>Projects</span></div>
        <div><b><?= (int) $skills_count ?>+</b><span>Skills</span></div>
        <div><b><?= (int) $categories_count ?>+</b><span>Disiplin</span></div>
        <div><b><?= (int) $tech_count ?>+</b><span>Teknologi</span></div>
      </div>
    </div>
    <div class="hero-art">
      <div class="hero-orbit orbit-one"></div>
      <div class="signature-badge" aria-hidden="true"><?= html_escape($initials) ?></div>
      <div class="portrait-wrap">
        <?php if ($avatar_url): ?>
          <img class="portrait" src="<?= html_escape($avatar_url) ?>" alt="Foto profil <?= html_escape($profile['name']) ?>">
        <?php else: ?>
          <div class="portrait portrait-fallback" role="img" aria-label="Inisial <?= html_escape($profile['name']) ?>"><?= html_escape($initials) ?></div>
        <?php endif; ?>
        <span class="portrait-badge">Hello, I build things <b>✦</b></span>
      </div>
      <div class="art-caption">
        <strong>Build with intention.</strong>
        <span>product / systems / craft</span>
      </div>
    </div>
  </section>

  <section class="section reveal" id="about">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow">01 / About</div>
          <h2>Teknologi yang<br>punya alasan.</h2>
        </div>
        <p class="section-intro">Bukan hanya membuat sesuatu bekerja. Saya peduli bagaimana produk dipahami, digunakan, dan bertahan.</p>
      </div>
      <div class="about-grid">
        <p class="about-copy"><?= nl2br(html_escape($profile['bio'])) ?></p>
        <div class="meta-list">
          <div class="meta-row"><span>Based in</span><strong><?= html_escape($profile['location']) ?></strong></div>
          <div class="meta-row"><span>Focus</span><strong>Web products &amp; systems</strong></div>
          <div class="meta-row"><span>Status</span><strong><?= html_escape($profile['availability']) ?></strong></div>
          <div class="meta-row"><span>Say hello</span><strong><?= html_escape($profile['email']) ?></strong></div>
        </div>
      </div>
    </div>
  </section>

  <section class="section reveal" id="skills">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow">02 / Capabilities</div>
          <h2>Tools of the trade.</h2>
        </div>
        <p class="section-intro">Stack hanyalah alat. Yang penting adalah keputusan yang dibuat dengan alat tersebut.</p>
      </div>
      <div class="skills-grid">
        <?php foreach ($skills as $skill): $level = min(100, max(0, (int) $skill['level'])); ?>
          <div class="skill">
            <small><?= html_escape($skill['category']) ?></small>
            <h3><?= html_escape($skill['name']) ?></h3>
            <div class="skill-bar-row">
              <div class="skill-bar" role="progressbar" aria-valuenow="<?= $level ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Level <?= html_escape($skill['name']) ?>">
                <i style="width:<?= $level ?>%"></i>
              </div>
              <span class="skill-level"><?= $level ?>%</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section reveal" id="projects">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow">03 / Selected work</div>
          <h2>Things I've shipped.</h2>
        </div>
        <p class="section-intro">Beberapa proyek yang merepresentasikan cara saya berpikir: fokus, terukur, dan dekat dengan kebutuhan nyata.</p>
      </div>
      <div class="project-grid">
        <?php foreach ($projects as $project):
          $image_url = trim($project['image_url']);
          $has_image = (bool) filter_var($image_url, FILTER_VALIDATE_URL);
          $project_url = trim($project['project_url']);
          $has_link = !empty($project_url) && $project_url !== '#';
        ?>
          <article class="project">
            <?php if ($has_image): ?>
              <img class="project-image" src="<?= html_escape($image_url) ?>" alt="Tampilan project <?= html_escape($project['title']) ?>" loading="lazy">
            <?php else: ?>
              <div class="project-image project-image--empty">
                <span class="project-image-fallback" aria-hidden="true"><?= html_escape(strtoupper(substr($project['title'], 0, 1))) ?></span>
              </div>
            <?php endif; ?>
            <div class="project-body">
              <span class="project-year"><?= html_escape($project['year']) ?> / <?= html_escape($project['tech_stack']) ?></span>
              <h3><?= html_escape($project['title']) ?></h3>
              <p><?= html_escape($project['summary']) ?></p>
              <?php if ($has_link): ?>
                <a class="project-foot" href="<?= html_escape($project_url) ?>" target="_blank" rel="noopener noreferrer">View case study ↗</a>
              <?php else: ?>
                <span class="project-foot project-foot--muted">Detail belum tersedia</span>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="contact-band" id="contact">
    <div class="container contact-grid">
      <div>
        <div class="eyebrow">04 / Start a conversation</div>
        <h2>Punya masalah yang ingin diurai?</h2>
        <p>Ceritakan konteksnya. Kita mulai dari percakapan yang baik, lalu lihat apa yang bisa dibangun.</p>
        <?php if ($has_linkedin || $has_github): ?>
          <p class="social-links">
            <?php if ($has_linkedin): ?><a href="<?= html_escape($profile['linkedin_url']) ?>" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a><?php endif; ?>
            <?php if ($has_github): ?><a href="<?= html_escape($profile['github_url']) ?>" target="_blank" rel="noopener noreferrer">GitHub ↗</a><?php endif; ?>
          </p>
        <?php endif; ?>
      </div>
      <div>
        <?php if ($message_sent): ?><div class="alert" role="status"><?= html_escape($message_sent) ?></div><?php endif; ?>
        <form class="contact-form" action="<?= site_url('contact') ?>" method="post" novalidate>
          <div>
            <label class="field-label" for="contact-name">Nama</label>
            <input id="contact-name" name="name" placeholder="Nama Anda" maxlength="100" required>
          </div>
          <div>
            <label class="field-label" for="contact-email">Email</label>
            <input id="contact-email" type="email" name="email" placeholder="Email Anda" maxlength="150" required>
          </div>
          <div>
            <label class="field-label" for="contact-message">Pesan</label>
            <textarea id="contact-message" name="message" placeholder="Apa yang sedang Anda bangun?" maxlength="2000" required></textarea>
          </div>
          <button class="button" type="submit">Kirim pesan <span aria-hidden="true">↗</span></button>
        </form>
      </div>
    </div>
  </section>
</main>

<footer class="container">
  <span>&copy; <?= date('Y') ?> <?= html_escape($profile['name']) ?></span>
  <?php if ($has_linkedin || $has_github): ?>
    <span class="social-links">
      <?php if ($has_linkedin): ?><a href="<?= html_escape($profile['linkedin_url']) ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a><?php endif; ?>
      <?php if ($has_github): ?><a href="<?= html_escape($profile['github_url']) ?>" target="_blank" rel="noopener noreferrer">GitHub</a><?php endif; ?>
    </span>
  <?php endif; ?>
  <span>Designed &amp; built with care.</span>
</footer>

<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script>
(function () {
  var items = document.querySelectorAll('.reveal');
  if (!items.length) return;

  if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    items.forEach(function (el) { el.classList.add('is-visible'); });
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

  items.forEach(function (el) { observer.observe(el); });
})();
</script>
</body>
</html>
