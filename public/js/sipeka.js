/* SIPEKA — interaksi kecil (tanpa dependensi selain Bootstrap) */
(function () {
  'use strict';

  // ---- Unggah foto: pratinjau + validasi sisi klien (server tetap memvalidasi ulang) ----
  document.querySelectorAll('[data-photo-input]').forEach(function (input) {
    var max = parseInt(input.dataset.maxFiles || '3', 10);
    var maxBytes = 5 * 1024 * 1024;
    var allowed = ['image/jpeg', 'image/png', 'image/webp'];
    var zone = input.closest('[data-photo-zone]');
    var previews = zone ? zone.querySelector('[data-photo-previews]') : null;
    var msg = zone ? zone.querySelector('[data-photo-message]') : null;
    var dt = new DataTransfer();

    function show(text) { if (msg) { msg.textContent = text || ''; msg.classList.toggle('d-none', !text); } }

    function render() {
      if (!previews) return;
      previews.innerHTML = '';
      Array.prototype.forEach.call(dt.files, function (file, i) {
        var item = document.createElement('div');
        item.className = 'item';
        var img = document.createElement('img');
        img.alt = file.name;
        img.src = URL.createObjectURL(file);
        var name = document.createElement('div');
        name.className = 'name';
        name.textContent = file.name;
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sp-danger btn-sm w-100 rounded-0';
        btn.innerHTML = '<i class="bi bi-x-lg"></i> Hapus';
        btn.addEventListener('click', function () {
          var next = new DataTransfer();
          Array.prototype.forEach.call(dt.files, function (f, j) { if (j !== i) next.items.add(f); });
          dt = next; input.files = dt.files; render();
        });
        item.appendChild(img); item.appendChild(name); item.appendChild(btn);
        previews.appendChild(item);
      });
    }

    function addFiles(list) {
      show('');
      Array.prototype.forEach.call(list, function (file) {
        if (dt.files.length >= max) { show('Maksimal ' + max + ' foto.'); return; }
        if (allowed.indexOf(file.type) === -1) { show('Format foto harus JPG, PNG, atau WebP.'); return; }
        if (file.size > maxBytes) { show('Ukuran setiap foto maksimal 5 MB.'); return; }
        dt.items.add(file);
      });
      input.files = dt.files;
      render();
    }

    input.addEventListener('change', function () {
      var chosen = Array.prototype.slice.call(input.files);
      input.files = dt.files; // reset ke daftar valid, lalu tambahkan pilihan baru
      addFiles(chosen);
    });

    var drop = zone ? zone.querySelector('[data-photo-drop]') : null;
    if (drop) {
      ['dragenter', 'dragover'].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('is-over'); }); });
      ['dragleave', 'drop'].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('is-over'); }); });
      drop.addEventListener('drop', function (e) { addFiles(e.dataTransfer.files); });
      drop.addEventListener('click', function () { input.click(); });
      drop.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });
    }
  });

  // ---- Salin kode laporan ----
  document.querySelectorAll('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = btn.dataset.copy;
      var done = function () {
        var old = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> Tersalin';
        setTimeout(function () { btn.innerHTML = old; }, 1800);
      };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(done);
      } else {
        var ta = document.createElement('textarea');
        ta.value = text; document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); done(); } catch (e) {}
        document.body.removeChild(ta);
      }
    });
  });

  // ---- Cegah kirim ganda ----
  document.querySelectorAll('form[data-once]').forEach(function (form) {
    form.addEventListener('submit', function () {
      var b = form.querySelector('[type=submit]');
      if (b) { b.disabled = true; b.dataset.label = b.innerHTML; b.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...'; }
    });
  });
})();
