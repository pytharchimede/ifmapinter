  <!-- ================= FOOTER ================= -->
  <?php $settings = null;
    try {
        $settings = db()->query('SELECT * FROM settings WHERE id=1')->fetch();
    } catch (Throwable $e) {
    } ?>
  <footer id="contact">
      <div class="footer-inner">
          <div>
              <h3><?= htmlspecialchars(t('footer.contact.title') ?? 'Contact') ?></h3>
              <p><strong><?= htmlspecialchars(t('footer.email.label') ?? 'Email') ?> :</strong> <?= htmlspecialchars($settings['contact_email'] ?? 'contact@ifmap.ci') ?></p>
              <p><strong><?= htmlspecialchars(t('footer.phone.label') ?? 'Téléphone') ?> :</strong> <?= htmlspecialchars($settings['contact_phone'] ?? '+225 01 71 31 85 11') ?></p>
              <p><strong><?= htmlspecialchars(t('footer.address.label') ?? 'Adresse') ?> :</strong> <?= htmlspecialchars($settings['contact_address'] ?? 'Abidjan, Côte d’Ivoire') ?></p>
              <div class="footer-map" aria-label="Carte localisation IFMAP">
                  <iframe title="Carte IFMAP" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Niangon+Lubafrique+Yopougon+Abidjan+Cote+d'Ivoire&output=embed"></iframe>
              </div>
              <button type="button" class="footer-map-btn" onclick="openMapModal()"><?= htmlspecialchars(t('footer.map.btn') ?? 'Voir la carte') ?></button>
          </div>
          <div>
              <h3><?= htmlspecialchars(t('footer.links.title') ?? 'Liens') ?></h3>
              <ul>
                  <li><a href="<?= htmlspecialchars($settings['link_programmes'] ?? base_url('programmes')) ?>"><?= htmlspecialchars(t('nav.programmes') ?? 'Programmes') ?></a></li>
                  <li><a href="<?= htmlspecialchars($settings['link_formations'] ?? base_url('formations')) ?>"><?= htmlspecialchars(t('nav.formations') ?? 'Formations') ?></a></li>
                  <li><a href="<?= htmlspecialchars($settings['link_actualites'] ?? base_url('actualites')) ?>"><?= htmlspecialchars(t('nav.news') ?? 'Actualités') ?></a></li>
                  <li><a href="<?= htmlspecialchars($settings['link_partenaires'] ?? base_url('partenaires')) ?>"><?= htmlspecialchars(t('nav.partners') ?? 'Partenaires') ?></a></li>
              </ul>
          </div>
          <div>
              <h3><?= htmlspecialchars(t('footer.follow.title') ?? 'Suivez-nous') ?></h3>
              <ul>
                  <li><a href="<?= htmlspecialchars($settings['social_facebook'] ?? '#') ?>" target="_blank" rel="noopener">Facebook</a></li>
                  <li><a href="<?= htmlspecialchars($settings['social_linkedin'] ?? '#') ?>" target="_blank" rel="noopener">LinkedIn</a></li>
                  <li><a href="<?= htmlspecialchars($settings['social_youtube'] ?? '#') ?>" target="_blank" rel="noopener">YouTube</a></li>
              </ul>
          </div>
          <div>
              <h3><?= htmlspecialchars(t('footer.newsletter.title') ?? 'Newsletter') ?></h3>
              <p><?= htmlspecialchars($settings['newsletter_text'] ?? (t('footer.newsletter.text') ?? 'Recevez nos dernières actualités et événements.')) ?></p>
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
      // Map modal
      function openMapModal() {
          const m = document.getElementById('map-modal');
          if (!m) return;
          m.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
      }

      function closeMapModal() {
          const m = document.getElementById('map-modal');
          if (!m) return;
          m.setAttribute('aria-hidden', 'true');
          document.body.style.overflow = '';
      }
      document.addEventListener('keydown', e => {
          if (e.key === 'Escape') closeMapModal();
      });
  </script>

  <!-- Map Modal -->
  <div id="map-modal" class="modal" aria-hidden="true">
      <div class="modal-backdrop" onclick="closeMapModal()"></div>
      <div class="modal-dialog">
          <button class="modal-close" onclick="closeMapModal()" aria-label="Fermer">×</button>
          <div class="modal-content">
              <h2><?= htmlspecialchars(t('footer.map.title') ?? 'Accès & Carte') ?></h2>
              <div style="height:340px;border-radius:12px;overflow:hidden;margin-top:.6rem;background:#eef2f7;">
                  <iframe title="Carte IFMAP" width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Niangon+Lubafrique+Yopougon+Abidjan+Cote+d'Ivoire&output=embed"></iframe>
              </div>
          </div>
      </div>
  </div>

  </body>

  </html>