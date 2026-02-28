/**
 * Özelleştirici config: shortcode sayfasında PHP (wp_localize_script) ile EMC_CONFIG zaten verilir.
 * Bu script sadece handle olarak kullanılıyor; config doğrudan EMC_CONFIG ile gelir.
 */
(function() {
	// EMC_CONFIG PHP'den wp_localize_script ile enjekte edilir; yoksa REST'ten çekilebilir (fallback).
	if (typeof window.EMC_CONFIG !== 'undefined') return;
	var url = typeof EMC_REST_CONFIG_URL !== 'undefined' ? EMC_REST_CONFIG_URL : '';
	if (!url) return;
	fetch(url).then(function(r) { return r.json(); }).then(function(data) { window.EMC_CONFIG = data; }).catch(function() { window.EMC_CONFIG = { error: true }; });
})();
