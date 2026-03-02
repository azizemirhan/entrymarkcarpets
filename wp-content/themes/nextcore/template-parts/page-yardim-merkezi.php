<?php
/**
 * Yardım Merkezi sayfası — premium, detaylı tasarım
 *
 * @package nextcore
 */

$ym_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_ym_' . $key, $default );
};
$eyebrow  = $ym_get( 'eyebrow', 'Destek' );
$subtitle = $ym_get( 'subtitle', 'Sipariş, teslimat, ödeme ve daha fazlası hakkında sorularınızın yanıtları burada.' );

$categories = array(
	array( 'icon' => 'cart', 'title' => 'Sipariş', 'desc' => 'Sipariş verme, izleme ve değişiklik', 'count' => 8, 'color' => 'navy' ),
	array( 'icon' => 'card', 'title' => 'Ödeme', 'desc' => 'Ödeme yöntemleri ve faturalama', 'count' => 5, 'color' => 'gold' ),
	array( 'icon' => 'truck', 'title' => 'Teslimat', 'desc' => 'Kargo, süre ve takip', 'count' => 7, 'color' => 'green' ),
	array( 'icon' => 'refresh', 'title' => 'İade & Değişim', 'desc' => 'İade koşulları ve süreçleri', 'count' => 6, 'color' => 'teal' ),
	array( 'icon' => 'palette', 'title' => 'Tasarım', 'desc' => 'Özel tasarım ve özelleştirme', 'count' => 9, 'color' => 'purple' ),
	array( 'icon' => 'settings', 'title' => 'Teknik', 'desc' => 'Site kullanımı ve teknik destek', 'count' => 4, 'color' => 'slate' ),
);

$popular = array(
	array( 'q' => 'Siparişim ne zaman teslim edilir?', 'cat' => 'Teslimat' ),
	array( 'q' => 'Nasıl özel paspas tasarlayabilirim?', 'cat' => 'Tasarım' ),
	array( 'q' => 'Ödeme seçenekleri nelerdir?', 'cat' => 'Ödeme' ),
	array( 'q' => 'İade koşulları nelerdir?', 'cat' => 'İade & Değişim' ),
	array( 'q' => 'Siparişimi nasıl takip edebilirim?', 'cat' => 'Sipariş' ),
);

$faqs = array(
	'Sipariş' => array(
		array( 'q' => 'Online sipariş nasıl verilir?', 'a' => 'Ürünleri galeri veya tasarım araçlarımızdan seçin, boyut ve malzeme tercihlerinizi belirleyin, sepetinize ekleyin ve ödeme sayfasından siparişinizi tamamlayın. İsterseniz önce fiyat teklifi alabilirsiniz.' ),
		array( 'q' => 'Siparişimi nasıl iptal edebilirim?', 'a' => 'Üretim başlamadan önce siparişinizi iptal edebilirsiniz. Müşteri hizmetlerimizle iletişime geçin; sipariş durumuna göre iptal işlemi 1–2 iş günü içinde tamamlanır.' ),
		array( 'q' => 'Minimum sipariş miktarı var mı?', 'a' => 'Özel halı ve paspas üretimimizde genelde minimum sipariş miktarı bulunmamaktadır. Toplu siparişlerde ise özel fiyatlandırma uygulanabilir.' ),
	),
	'Ödeme' => array(
		array( 'q' => 'Hangi ödeme yöntemlerini kabul ediyorsunuz?', 'a' => 'Kredi kartı, banka havalesi ve kapıda ödeme seçeneklerimiz mevcuttur. Kurumsal siparişlerde vadeli ödeme imkanı sunulabilir.' ),
		array( 'q' => 'Fatura alabilir miyim?', 'a' => 'Evet. Sipariş sırasında fatura bilgilerinizi girebilir veya sipariş sonrası müşteri hizmetlerimizden fatura talep edebilirsiniz.' ),
	),
	'Teslimat' => array(
		array( 'q' => 'Teslimat süresi ne kadar?', 'a' => 'Standart üretim süresi 5–10 iş günüdür. Özel tasarımlar ve büyük boyutlarda süre değişebilir. Sipariş onayında tahmini teslimat tarihi belirtilir.' ),
		array( 'q' => 'Kargo ücreti ne kadardır?', 'a' => 'Kargo ücreti sipariş tutarı, boyut ve teslimat adresine göre hesaplanır. Belirli tutarın üzerindeki siparişlerde ücretsiz kargo uygulanabilir.' ),
		array( 'q' => 'Siparişimi nasıl takip ederim?', 'a' => 'Kargo bilgisi e-posta ile paylaşılır. Takip numarası ile kargo firmasının sitesinden anlık takip yapabilirsiniz.' ),
	),
	'İade & Değişim' => array(
		array( 'q' => 'İade politikası nedir?', 'a' => 'Üretime geçmemiş siparişlerde tam iade yapılır. Üretilmiş ürünlerde, kusur veya hatalı üretim durumunda değişim veya iade sağlanır.' ),
		array( 'q' => 'Ürün değişimi yapılabilir mi?', 'a' => 'Üretim öncesi boyut ve malzeme değişikliği mümkündür. Üretim sonrası değişim, hata veya kusur durumunda uygulanır.' ),
	),
);
?>
<div class="yardim-merkezi-page">
	<section class="ym-hero">
		<div class="ym-hero-bg">
			<div class="ym-hero-glow1"></div>
			<div class="ym-hero-glow2"></div>
			<div class="ym-hero-pattern"></div>
		</div>
		<div class="ym-hero-content">
			<div class="ym-hero-eyebrow"><span></span><span class="ym-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
			<h1 class="ym-hero-title"><em>Yardım</em> Merkezi</h1>
			<p class="ym-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
			<div class="ym-search-wrap">
				<div class="ym-search-inner">
					<svg class="ym-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
					<input type="search" class="ym-search-input" placeholder="Sorunuzu burada arayın..." aria-label="Yardım ara">
					<button type="button" class="ym-search-btn">Ara</button>
				</div>
			</div>
		</div>
		<div class="ym-stats">
			<div class="ym-stat"><span class="ym-stat-num">7/24</span><span class="ym-stat-label">Destek</span></div>
			<div class="ym-stat-div"></div>
			<div class="ym-stat"><span class="ym-stat-num">&lt;48h</span><span class="ym-stat-label">Yanıt süresi</span></div>
			<div class="ym-stat-div"></div>
			<div class="ym-stat"><span class="ym-stat-num">50+</span><span class="ym-stat-label">SSS</span></div>
		</div>
	</section>

	<section class="ym-section ym-categories">
		<div class="ym-container">
			<h2 class="ym-section-title">Kategoriler</h2>
			<p class="ym-section-desc">Konuya göre yardım makalelerine göz atın</p>
			<div class="ym-cat-grid">
				<?php foreach ( $categories as $cat ) : ?>
				<a href="#faq-<?php echo esc_attr( sanitize_title( $cat['title'] ) ); ?>" class="ym-cat-card ym-cat--<?php echo esc_attr( $cat['color'] ); ?>">
					<div class="ym-cat-icon">
						<?php
						if ( 'cart' === $cat['icon'] ) :
							?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg><?php
						elseif ( 'card' === $cat['icon'] ) :
							?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg><?php
						elseif ( 'truck' === $cat['icon'] ) :
							?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 3h5v13h-5"/><path d="M14 16H9a1 1 0 0 1-1-1v-4H1"/><path d="M5 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M16 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg><?php
						elseif ( 'refresh' === $cat['icon'] ) :
							?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M21 21v-5h-5"/></svg><?php
						elseif ( 'palette' === $cat['icon'] ) :
							?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.9 0 1.75-.2 2.5-.5"/></svg><?php
						else :
							?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg><?php
						endif;
						?>
					</div>
					<div class="ym-cat-text">
						<h3 class="ym-cat-title"><?php echo esc_html( $cat['title'] ); ?></h3>
						<p class="ym-cat-desc"><?php echo esc_html( $cat['desc'] ); ?></p>
					</div>
					<span class="ym-cat-badge"><?php echo absint( $cat['count'] ); ?></span>
					<span class="ym-cat-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ym-section ym-popular">
		<div class="ym-container">
			<h2 class="ym-section-title">Sık Sorulan Sorular</h2>
			<p class="ym-section-desc">En çok merak edilen konular</p>
			<div class="ym-popular-grid">
				<?php foreach ( $popular as $p ) : ?>
				<a href="#faq-list" class="ym-pop-card" data-search="<?php echo esc_attr( strtolower( $p['q'] ) ); ?>">
					<span class="ym-pop-cat"><?php echo esc_html( $p['cat'] ); ?></span>
					<h4 class="ym-pop-q"><?php echo esc_html( $p['q'] ); ?></h4>
					<span class="ym-pop-link">Cevabı gör <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ym-section ym-faq" id="faq-list">
		<div class="ym-container">
			<h2 class="ym-section-title">Tüm SSS</h2>
			<p class="ym-section-desc">Kategorilere göre sık sorulan sorular</p>
			<div class="ym-faq-list">
				<?php foreach ( $faqs as $cat_title => $items ) : ?>
				<div class="ym-faq-group" id="faq-<?php echo esc_attr( sanitize_title( $cat_title ) ); ?>">
					<h3 class="ym-faq-group-title"><?php echo esc_html( $cat_title ); ?></h3>
					<div class="ym-accordion">
						<?php foreach ( $items as $item ) : ?>
						<div class="ym-accordion-item">
							<button type="button" class="ym-accordion-trigger" aria-expanded="false" aria-controls="ym-acc-<?php echo esc_attr( sanitize_title( $item['q'] ) ); ?>" id="ym-trigger-<?php echo esc_attr( sanitize_title( $item['q'] ) ); ?>">
								<span><?php echo esc_html( $item['q'] ); ?></span>
								<svg class="ym-accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
							</button>
							<div class="ym-accordion-panel" id="ym-acc-<?php echo esc_attr( sanitize_title( $item['q'] ) ); ?>" role="region" aria-labelledby="ym-trigger-<?php echo esc_attr( sanitize_title( $item['q'] ) ); ?>" hidden>
								<div class="ym-accordion-content">
									<p><?php echo esc_html( $item['a'] ); ?></p>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ym-section ym-contact">
		<div class="ym-contact-card">
			<div class="ym-contact-glow"></div>
			<div class="ym-contact-inner">
				<div class="ym-contact-icon">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
				</div>
				<h2 class="ym-contact-title">Hala cevabını bulamadınız mı?</h2>
				<p class="ym-contact-desc">Ekibimiz size yardımcı olmak için burada. Bize ulaşın, en kısa sürede yanıt verelim.</p>
				<div class="ym-contact-actions">
					<a href="<?php echo esc_url( home_url( '/iletisim/' ) ); ?>" class="ym-contact-btn ym-contact-btn--primary">İletişime Geç</a>
					<a href="mailto:info@entrymarkcarpets.com" class="ym-contact-btn ym-contact-btn--outline">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>
						info@entrymarkcarpets.com
					</a>
				</div>
				<div class="ym-contact-meta">
					<span>Ortalama yanıt süresi: <strong>24 saat içinde</strong></span>
				</div>
			</div>
		</div>
	</section>

	<div class="ym-back">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ym-back-btn">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
			Ana Sayfaya Dön
		</a>
	</div>
</div>

<script>
(function() {
	var triggers = document.querySelectorAll('.ym-accordion-trigger');
	triggers.forEach(function(btn) {
		btn.addEventListener('click', function() {
			var expanded = this.getAttribute('aria-expanded') === 'true';
			var panel = document.getElementById(this.getAttribute('aria-controls'));
			var accordion = this.closest('.ym-accordion');
			if (accordion) {
				accordion.querySelectorAll('.ym-accordion-trigger').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });
				accordion.querySelectorAll('.ym-accordion-panel').forEach(function(p) { p.hidden = true; });
			}
			if (!expanded && panel) {
				this.setAttribute('aria-expanded', 'true');
				panel.hidden = false;
			}
		});
	});
	var searchInput = document.querySelector('.ym-search-input');
	var searchBtn = document.querySelector('.ym-search-btn');
	if (searchInput && searchBtn) {
		searchBtn.addEventListener('click', function() {
			var q = searchInput.value.trim();
			if (q) {
				var cards = document.querySelectorAll('.ym-pop-card');
				cards.forEach(function(c) {
					c.style.display = c.dataset.search && c.dataset.search.indexOf(q.toLowerCase()) !== -1 ? 'block' : 'none';
				});
				document.getElementById('faq-list') && document.getElementById('faq-list').scrollIntoView({ behavior: 'smooth' });
			}
		});
	}
})();
</script>
