<?php defined('BASEPATH') OR exit('No direct script access allowed');

$admin_name = $this->session->userdata('admin_name');

$avatar_raw = isset($profile['avatar_url']) ? trim($profile['avatar_url']) : '';
$avatar_display_url = '';
if ($avatar_raw !== '') {
    if (preg_match('#^https?://#i', $avatar_raw)) {
        if (filter_var($avatar_raw, FILTER_VALIDATE_URL)) {
            $avatar_display_url = $avatar_raw;
        }
    } elseif (is_file(FCPATH . ltrim($avatar_raw, '/'))) {
        $avatar_display_url = base_url(ltrim($avatar_raw, '/'));
    }
}
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
<title>Dashboard - <?= html_escape($profile['name']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
<div class="admin-shell">
  <aside class="sidebar">
    <a class="brand brand-font" href="<?= base_url() ?>" target="_blank">
      <span class="brand-mark"><?= html_escape(strtoupper(substr($profile['name'], 0, 1))) ?></span>
      <?= html_escape($profile['name']) ?>
    </a>
    <nav class="side-nav">
      <a class="active" href="#overview">Overview</a>
      <a href="#profile">Profile</a>
      <a href="#skills">Skills</a>
      <a href="#projects">Projects</a>
      <a href="#messages">Messages</a>
    </nav>
    <div class="side-bottom">
      <button type="button" class="theme-toggle" data-theme-toggle aria-pressed="false" aria-label="Aktifkan mode gelap" title="Aktifkan mode gelap">
        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg>
        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" hidden><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path></svg>
      </button>
      <span>ADMIN CONSOLE</span>
      <a href="<?= site_url('admin/logout') ?>">Sign out ↗</a>
    </div>
  </aside>

  <main class="content">
    <div class="topline">
      <div>
        <div class="eyebrow">Portfolio control room</div>
        <h1>Good to see you, <?= html_escape($admin_name) ?>.</h1>
      </div>
      <div class="topline-actions">
        <a class="logout" href="<?= base_url() ?>" target="_blank">View website ↗</a>
      </div>
    </div>

    <?php if ($flash): ?><div class="flash"><?= html_escape($flash) ?></div><?php endif; ?>
    <?php if (!empty($flash_error)): ?><div class="flash flash--error" role="alert"><?= html_escape($flash_error) ?></div><?php endif; ?>

    <div class="stats" id="overview">
      <div class="stat"><b><?= count($projects) ?></b><span>Projects published</span></div>
      <div class="stat"><b><?= count($skills) ?></b><span>Skills curated</span></div>
      <div class="stat"><b><?= count($messages) ?></b><span>Inbox messages</span></div>
    </div>

    <section class="panel" id="profile">
      <h2>Profile &amp; positioning</h2>
      <form method="post" action="<?= site_url('admin/profile') ?>" enctype="multipart/form-data">
        <div class="field full">
          <label>FOTO PROFILE</label>
          <div class="avatar-upload-row">
            <div class="avatar-frame">
              <?php if ($avatar_display_url): ?>
                <img class="avatar-preview" id="avatar-photo-preview" src="<?= html_escape($avatar_display_url) ?>" alt="Foto profil saat ini" onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'avatar-preview avatar-preview--empty',id:'avatar-photo-preview',textContent:'Gambar gagal dimuat'}))">
              <?php else: ?>
                <div class="avatar-preview avatar-preview--empty avatar-preview--initial" id="avatar-photo-preview"><?= html_escape(strtoupper(substr($profile['name'], 0, 1))) ?></div>
              <?php endif; ?>
            </div>
            <div class="avatar-upload-actions">
              <div class="avatar-upload-buttons">
                <label class="btn-ghost" for="avatar-file-input">Pilih Foto</label>
                <input type="file" id="avatar-file-input" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only-file">
                <?php if ($avatar_display_url): ?>
                  <label class="btn-danger">
                    <input type="checkbox" name="remove_avatar" id="remove-avatar-input" value="1"> Hapus Foto
                  </label>
                <?php endif; ?>
              </div>
              <p class="hint">Format JPG, PNG, atau WEBP. Ukuran maksimal 2 MB.</p>
              <p class="hint" id="avatar-file-name"></p>
            </div>
          </div>
          <details class="avatar-url-fallback">
            <summary class="hint">Atau gunakan URL gambar eksternal</summary>
            <input type="text" inputmode="url" name="avatar_url" value="<?= html_escape($profile['avatar_url']) ?>" placeholder="https://..." maxlength="255" style="margin-top:8px">
            <p class="hint">Kosongkan atau abaikan field ini jika Anda memakai foto hasil upload di atas.</p>
          </details>
        </div>
        <div class="form-grid" style="margin-top:16px">
          <div class="field"><label>NAME</label><input name="name" value="<?= html_escape($profile['name']) ?>" maxlength="100" required></div>
          <div class="field"><label>ROLE</label><input name="role" value="<?= html_escape($profile['role']) ?>" maxlength="150" required></div>
          <div class="field full"><label>HEADLINE</label><input name="headline" value="<?= html_escape($profile['headline']) ?>" maxlength="255" required></div>
          <div class="field full"><label>BIO</label><textarea name="bio"><?= html_escape($profile['bio']) ?></textarea></div>
          <div class="field"><label>LOCATION</label><input name="location" value="<?= html_escape($profile['location']) ?>" maxlength="120"></div>
          <div class="field"><label>EMAIL</label><input type="email" name="email" value="<?= html_escape($profile['email']) ?>" maxlength="150"></div>
          <div class="field"><label>AVAILABILITY</label><input name="availability" value="<?= html_escape($profile['availability']) ?>" maxlength="120"></div>
          <div class="field"><label>LINKEDIN URL</label><input type="url" name="linkedin_url" value="<?= html_escape($profile['linkedin_url']) ?>" maxlength="255"></div>
          <div class="field"><label>GITHUB URL</label><input type="url" name="github_url" value="<?= html_escape($profile['github_url']) ?>" maxlength="255"></div>
        </div>
        <button class="submit" type="submit">Save profile</button>
      </form>
    </section>

    <div class="split">
      <section class="panel" id="skills">
        <h2>Skills</h2>
        <form method="post" action="<?= site_url('admin/skill') ?>">
          <div class="field"><label>NAME</label><input name="name" required maxlength="100" placeholder="TypeScript"></div>
          <div class="form-grid" style="margin-top:13px">
            <div class="field"><label>CATEGORY</label><input name="category" required maxlength="80" placeholder="Frontend"></div>
            <div class="field"><label>LEVEL 0-100</label><input name="level" type="number" min="0" max="100" value="80"></div>
          </div>
          <div class="field" style="margin-top:13px"><label>SORT ORDER</label><input name="sort_order" type="number" value="10"></div>
          <button class="submit" type="submit">Add skill</button>
        </form>

        <?php foreach ($skills as $skill): $edit_id = 'skill-edit-' . $skill['id']; ?>
          <div class="record">
            <div class="record-head">
              <div>
                <h3><?= html_escape($skill['name']) ?></h3>
                <p><?= html_escape($skill['category']) ?> · <?= (int) $skill['level'] ?>% · urutan <?= (int) $skill['sort_order'] ?></p>
              </div>
              <div class="record-actions">
                <label class="btn-ghost" for="<?= $edit_id ?>">Edit</label>
                <a class="btn-danger" href="<?= site_url('admin/skill/delete/' . $skill['id']) ?>" data-confirm="Hapus skill &quot;<?= html_escape(addslashes($skill['name'])) ?>&quot;?">Delete</a>
              </div>
            </div>
            <input type="checkbox" id="<?= $edit_id ?>" class="edit-toggle">
            <form class="edit-form" method="post" action="<?= site_url('admin/skill/save/' . $skill['id']) ?>">
              <div class="field"><label>NAME</label><input name="name" value="<?= html_escape($skill['name']) ?>" required maxlength="100"></div>
              <div class="form-grid">
                <div class="field"><label>CATEGORY</label><input name="category" value="<?= html_escape($skill['category']) ?>" required maxlength="80"></div>
                <div class="field"><label>LEVEL 0-100</label><input name="level" type="number" min="0" max="100" value="<?= (int) $skill['level'] ?>"></div>
              </div>
              <div class="field"><label>SORT ORDER</label><input name="sort_order" type="number" value="<?= (int) $skill['sort_order'] ?>"></div>
              <button class="submit" type="submit">Save changes</button>
            </form>
          </div>
        <?php endforeach; ?>
      </section>

      <section class="panel" id="projects">
        <h2>Projects</h2>
        <form method="post" action="<?= site_url('admin/project') ?>">
          <div class="field"><label>TITLE</label><input name="title" required maxlength="150" placeholder="New product"></div>
          <div class="field" style="margin-top:13px"><label>SUMMARY</label><textarea name="summary" required></textarea></div>
          <div class="form-grid" style="margin-top:13px">
            <div class="field"><label>TECH STACK</label><input name="tech_stack" maxlength="255" placeholder="PHP, MySQL"></div>
            <div class="field"><label>YEAR</label><input name="year" type="number" value="<?= date('Y') ?>"></div>
            <div class="field full"><label>IMAGE URL</label><input type="url" name="image_url" id="new-project-image" data-preview-input="new-project-image-preview" placeholder="https://..." maxlength="255"></div>
            <div class="field full"><label>PROJECT URL</label><input type="url" name="project_url" placeholder="https://..." maxlength="255"></div>
          </div>
          <div class="image-preview image-preview--empty" id="new-project-image-preview" style="margin-top:13px">Belum ada gambar</div>
          <label class="checkbox-field"><input type="checkbox" name="featured" value="1"> Featured project</label>
          <button class="submit" type="submit">Add project</button>
        </form>

        <?php foreach ($projects as $project): $edit_id = 'project-edit-' . $project['id']; ?>
          <div class="record">
            <div class="record-head">
              <?php if (!empty($project['image_url'])): ?>
                <img class="record-thumb" src="<?= html_escape($project['image_url']) ?>" alt="Thumbnail <?= html_escape($project['title']) ?>" onerror="this.style.visibility='hidden'">
              <?php endif; ?>
              <div style="flex:1">
                <h3><?= html_escape($project['title']) ?></h3>
                <p><?= html_escape($project['year']) ?> · <?= html_escape($project['tech_stack']) ?><?= $project['featured'] ? ' · Featured' : '' ?></p>
              </div>
              <div class="record-actions">
                <label class="btn-ghost" for="<?= $edit_id ?>">Edit</label>
                <a class="btn-danger" href="<?= site_url('admin/project/delete/' . $project['id']) ?>" data-confirm="Hapus project &quot;<?= html_escape(addslashes($project['title'])) ?>&quot;?">Delete</a>
              </div>
            </div>
            <input type="checkbox" id="<?= $edit_id ?>" class="edit-toggle">
            <form class="edit-form" method="post" action="<?= site_url('admin/project/save/' . $project['id']) ?>">
              <div class="field"><label>TITLE</label><input name="title" value="<?= html_escape($project['title']) ?>" required maxlength="150"></div>
              <div class="field"><label>SUMMARY</label><textarea name="summary" required><?= html_escape($project['summary']) ?></textarea></div>
              <div class="form-grid">
                <div class="field"><label>TECH STACK</label><input name="tech_stack" value="<?= html_escape($project['tech_stack']) ?>" maxlength="255"></div>
                <div class="field"><label>YEAR</label><input name="year" type="number" value="<?= (int) $project['year'] ?>"></div>
                <div class="field full">
                  <label>IMAGE URL</label>
                  <input type="url" name="image_url" id="<?= $edit_id ?>-image" data-preview-input="<?= $edit_id ?>-image-preview" value="<?= html_escape($project['image_url']) ?>" maxlength="255">
                </div>
                <div class="field full"><label>PROJECT URL</label><input type="url" name="project_url" value="<?= html_escape($project['project_url']) ?>" maxlength="255"></div>
              </div>
              <?php if (!empty($project['image_url'])): ?>
                <img class="image-preview" id="<?= $edit_id ?>-image-preview" src="<?= html_escape($project['image_url']) ?>" alt="Preview gambar <?= html_escape($project['title']) ?>" onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'image-preview image-preview--empty',textContent:'Gambar gagal dimuat'}))">
              <?php else: ?>
                <div class="image-preview image-preview--empty" id="<?= $edit_id ?>-image-preview">Belum ada gambar</div>
              <?php endif; ?>
              <label class="checkbox-field"><input type="checkbox" name="featured" value="1" <?= $project['featured'] ? 'checked' : '' ?>> Featured project</label>
              <button class="submit" type="submit">Save changes</button>
            </form>
          </div>
        <?php endforeach; ?>
      </section>
    </div>

    <section class="panel" id="messages">
      <h2>Inbox</h2>
      <?php if (!$messages): ?>
        <p class="hint">Belum ada pesan masuk.</p>
      <?php endif; ?>
      <?php foreach ($messages as $message): ?>
        <div class="message" data-message-id="<?= (int) $message['id'] ?>">
          <div class="message-head">
            <div>
              <strong><?= html_escape($message['name']) ?></strong> · <a href="mailto:<?= html_escape($message['email']) ?>"><?= html_escape($message['email']) ?></a><br>
              <small><?= html_escape($message['created_at']) ?></small>
            </div>
            <button type="button" class="message-mark" data-mark-read aria-pressed="false">Tandai dibaca</button>
          </div>
          <p><?= nl2br(html_escape($message['message'])) ?></p>
        </div>
      <?php endforeach; ?>
      <?php if ($messages): ?><p class="hint">Status "dibaca" tersimpan lokal di browser ini saja.</p><?php endif; ?>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script>
(function () {
  document.querySelectorAll('[data-preview-input]').forEach(function (input) {
    var target = document.getElementById(input.dataset.previewInput);
    if (!target) return;
    input.addEventListener('input', function () {
      var url = input.value.trim();
      if (!url) {
        target.removeAttribute('src');
        return;
      }
      if (target.tagName === 'IMG') {
        target.src = url;
      }
    });
  });

  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (event) {
      if (!window.confirm(el.getAttribute('data-confirm'))) {
        event.preventDefault();
      }
    });
  });

  var avatarInput = document.getElementById('avatar-file-input');
  if (avatarInput) {
    var MAX_BYTES = 2 * 1024 * 1024;
    var ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    var preview = document.getElementById('avatar-photo-preview');
    var fileNameLabel = document.getElementById('avatar-file-name');
    var removeCheckbox = document.getElementById('remove-avatar-input');

    avatarInput.addEventListener('change', function () {
      var file = avatarInput.files && avatarInput.files[0];
      if (!file) return;

      if (ALLOWED_TYPES.indexOf(file.type) === -1) {
        window.alert('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
        avatarInput.value = '';
        return;
      }
      if (file.size > MAX_BYTES) {
        window.alert('Ukuran file melebihi batas maksimal 2 MB.');
        avatarInput.value = '';
        return;
      }

      if (fileNameLabel) fileNameLabel.textContent = 'Dipilih: ' + file.name;
      if (removeCheckbox) removeCheckbox.checked = false;

      var reader = new FileReader();
      reader.onload = function (event) {
        if (preview && preview.tagName === 'IMG') {
          preview.src = event.target.result;
        } else if (preview) {
          var img = document.createElement('img');
          img.className = 'avatar-preview';
          img.id = 'avatar-photo-preview';
          img.alt = 'Preview foto baru';
          img.src = event.target.result;
          preview.replaceWith(img);
          preview = img;
        }
      };
      reader.readAsDataURL(file);
    });
  }

  var READ_KEY = 'admin-read-messages';
  var readIds = [];
  try { readIds = JSON.parse(localStorage.getItem(READ_KEY) || '[]'); } catch (e) { readIds = []; }

  function paintMessage(el, id) {
    var isRead = readIds.indexOf(id) !== -1;
    var btn = el.querySelector('[data-mark-read]');
    el.classList.toggle('is-read', isRead);
    btn.setAttribute('aria-pressed', String(isRead));
    btn.textContent = isRead ? 'Sudah dibaca' : 'Tandai dibaca';
  }

  document.querySelectorAll('.message[data-message-id]').forEach(function (el) {
    var id = el.getAttribute('data-message-id');
    paintMessage(el, id);
    el.querySelector('[data-mark-read]').addEventListener('click', function () {
      var idx = readIds.indexOf(id);
      if (idx === -1) { readIds.push(id); } else { readIds.splice(idx, 1); }
      try { localStorage.setItem(READ_KEY, JSON.stringify(readIds)); } catch (e) { /* storage unavailable */ }
      paintMessage(el, id);
    });
  });
})();
</script>
</body>
</html>
