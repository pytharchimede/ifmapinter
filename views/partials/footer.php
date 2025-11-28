  <!-- ================= FOOTER ================= -->
  <?php $settings = null;
    try {
        $settings = db()->query('SELECT * FROM settings WHERE id=1')->fetch();
    } catch (Throwable $e) {
    } ?>
  <footer id="contact">
      <div class="footer-inner">
          <div>
              <h3>Contact</h3>
              <p>Email : <?= htmlspecialchars($settings['contact_email'] ?? 'contact@ifmap.ci') ?></p>
              <p>Téléphone : <?= htmlspecialchars($settings['contact_phone'] ?? '+225 01 71 31 85 11') ?></p>
              <p>Adresse : <?= htmlspecialchars($settings['contact_address'] ?? 'Abidjan, Côte d’Ivoire') ?></p>
          </div>
          <div>
              <h3>Liens</h3>
              <ul>
                  <li><a href="<?= htmlspecialchars($settings['link_programmes'] ?? base_url('#programmes')) ?>">Programmes</a></li>
                  <li><a href="<?= htmlspecialchars($settings['link_formations'] ?? base_url('#formations')) ?>">Formations</a></li>
                  <li><a href="<?= htmlspecialchars($settings['link_actualites'] ?? base_url('#news')) ?>">Actualités</a></li>
                  <li><a href="<?= htmlspecialchars($settings['link_partenaires'] ?? base_url('#partenaires')) ?>">Partenaires</a></li>
              </ul>
          </div>
          <div>
              <h3>Suivez-nous</h3>
              <ul>
                  <li><a href="<?= htmlspecialchars($settings['social_facebook'] ?? '#') ?>" target="_blank" rel="noopener">Facebook</a></li>
                  <li><a href="<?= htmlspecialchars($settings['social_linkedin'] ?? '#') ?>" target="_blank" rel="noopener">LinkedIn</a></li>
                  <li><a href="<?= htmlspecialchars($settings['social_youtube'] ?? '#') ?>" target="_blank" rel="noopener">YouTube</a></li>
              </ul>
          </div>
          <div>
              <h3>Newsletter</h3>
              <p><?= htmlspecialchars($settings['newsletter_text'] ?? 'Recevez nos dernières actualités et événements.') ?></p>
              <div class="newsletter">
                  <form id="newsletter-form" method="post" action="<?= base_url('newsletter/subscribe') ?>" onsubmit="return false;">
                      <?= csrf_field() ?>
                      <input type="email" name="email" placeholder="Votre email" required>
                      <button type="submit">S’inscrire</button>
                  </form>
                  <div id="newsletter-msg" style="margin-top:6px;font-size:.8rem;"></div>
              </div>
          </div>
      </div>
      <div class="copyright">© 2025 IFMAP – Tous droits réservés</div>
  </footer>

  <!-- JS -->
  <?php $appJsV = @filemtime(__DIR__ . '/../../assets/js/app.js') ?: time(); ?>
  <script src="<?= base_url('assets/js/app.js?v=' . $appJsV) ?>"></script>
  <script>
      (function() {
          const f = document.getElementById('newsletter-form');
          if (!f) return;
          const msg = document.getElementById('newsletter-msg');
          f.addEventListener('submit', function() {
              const fd = new FormData(f);
              fetch(f.action, {
                      method: 'POST',
                      body: fd
                  })
                  .then(r => r.json())
                  .then(d => {
                      msg.textContent = d.message || '';
                      msg.style.color = d.ok ? '#16a34a' : '#dc2626';
                      if (d.ok) {
                          f.reset();
                      }
                  })
                  .catch(() => {
                      msg.textContent = 'Erreur réseau';
                      msg.style.color = '#dc2626';
                  });
          });
      })();
  </script>

  </body>

  </html>