(function($) {
	'use strict';

	var WPSGScan = {
		scanId: null,
		totalThreats: 0,

		init: function() {
			$('#wpsg-start-scan').on('click', this.startScan.bind(this));
		},

		startScan: function() {
			var $btn = $('#wpsg-start-scan');
			$btn.prop('disabled', true).text(wpsgScan.i18n.scanning);

			$('#wpsg-scan-progress').show();
			$('#wpsg-scan-results').hide();
			$('#wpsg-scan-empty').hide();

			this.totalThreats = 0;
			this.runScanChunks();
		},

		runScanChunks: function() {
			var self = this;
			var isFirst = !this.scanId;

			$.ajax({
				url: wpsgScan.ajaxUrl,
				type: 'POST',
				data: {
					action: isFirst ? 'wpsg_start_scan' : 'wpsg_scan_chunk',
					nonce: wpsgScan.nonce,
					scan_id: this.scanId,
					offset: isFirst ? 0 : this.lastOffset
				},
				success: function(response) {
					if (response.success) {
						if (isFirst) {
							self.scanId = response.data.scan_id;
							self.lastOffset = response.data.scanned;
						} else {
							self.lastOffset = response.data.offset;
						}

						self.totalThreats += response.data.threats_found || 0;

						var percent = Math.round((response.data.offset / response.data.total_files) * 100);
						$('.wpsg-progress-fill').css('width', percent + '%');
						$('.wpsg-counts').text(
							response.data.offset + ' / ' + response.data.total_files + ' ' +
							(response.data.threats_found ? '(' + wpsgScan.i18n.threatsFound + ': ' + self.totalThreats + ')' : '')
						);

						if (response.data.has_more) {
							self.runScanChunks();
						} else {
							self.scanComplete();
						}
					} else {
						self.scanError(response.data && response.data.message ? response.data.message : wpsgScan.i18n.error);
					}
				},
				error: function() {
					self.scanError(wpsgScan.i18n.error);
				}
			});
		},

		scanComplete: function() {
			$('#wpsg-start-scan').prop('disabled', false).text(wpsgScan.i18n.startScan);
			$('.wpsg-status').text(wpsgScan.i18n.complete);
			$('.wpsg-progress-fill').css('width', '100%');

			this.loadResults();
		},

		scanError: function(message) {
			$('#wpsg-start-scan').prop('disabled', false).text(wpsgScan.i18n.startScan);
			$('.wpsg-status').text(message);
			alert(message);
		},

		loadResults: function() {
			var self = this;

			$.ajax({
				url: wpsgScan.ajaxUrl,
				type: 'GET',
				data: {
					action: 'wpsg_get_scan_results',
					nonce: wpsgScan.nonce,
					scan_id: this.scanId
				},
				success: function(response) {
					if (response.success && response.data.results) {
						if (response.data.results.length > 0) {
							$('#wpsg-scan-results').show();
							$('#wpsg-scan-empty').hide();

							var $summary = $('.wpsg-results-summary');
							$summary.removeClass('wpsg-clean');
							$summary.html(
								'<strong>' + wpsgScan.i18n.threatsFound + ':</strong> ' +
								response.data.results.length
							);

							var $tbody = $('#wpsg-results-table tbody');
							$tbody.empty();

							response.data.results.forEach(function(item) {
								var $tr = $('<tr>');
								$tr.append($('<td>').text(item.file_path));
								$tr.append($('<td>').text(item.signature_id));
								$tr.append($('<td>').addClass('wpsg-severity-' + item.severity).text(item.severity));
								$tr.append($('<td>').text(item.line_number || '-'));
								$tr.append($('<td>').addClass('wpsg-snippet').attr('title', item.snippet).text(item.snippet || ''));

								var $actions = $('<td>');
								if (item.file_path.indexOf('db:') !== 0 && item.file_path.indexOf('wp-includes') === -1 && item.file_path.indexOf('wp-admin') === -1) {
									$actions.append($('<button>').addClass('button button-small wpsg-quarantine').data('file', item.file_path).text('Karantina'));
								} else if (item.file_path.indexOf('wp-includes') !== -1 || item.file_path.indexOf('wp-admin') !== -1) {
									$actions.append($('<button>').addClass('button button-small wpsg-restore-core').data('path', item.file_path).text('Core Geri Yükle'));
								}
								$tr.append($actions);
								$tbody.append($tr);
							});

							$('.wpsg-quarantine').on('click', function() {
								var $btn = $(this);
								var file = $btn.data('file');
								if (!confirm('Bu dosyayı karantinaya almak istediğinize emin misiniz?')) return;
								$btn.prop('disabled', true);
								$.post(wpsgScan.ajaxUrl, {
									action: 'wpsg_quarantine_file',
									nonce: wpsgScan.nonce,
									file_path: file
								}).done(function(r) {
									if (r.success) {
										$btn.closest('tr').fadeOut();
									} else {
										alert(r.data && r.data.message ? r.data.message : 'Hata');
									}
								}).fail(function() {
									alert('Hata oluştu');
								}).always(function() {
									$btn.prop('disabled', false);
								});
							});

							$('.wpsg-restore-core').on('click', function() {
								var $btn = $(this);
								var path = $btn.data('path');
								if (!confirm('WordPress core dosyasını orijinal haliyle geri yüklemek istediğinize emin misiniz?')) return;
								$btn.prop('disabled', true);
								$.post(wpsgScan.ajaxUrl, {
									action: 'wpsg_restore_core',
									nonce: wpsgScan.nonce,
									file_path: path
								}).done(function(r) {
									if (r.success) {
										$btn.closest('tr').fadeOut();
									} else {
										alert(r.data && r.data.message ? r.data.message : 'Hata');
									}
								}).fail(function() {
									alert('Hata oluştu');
								}).always(function() {
									$btn.prop('disabled', false);
								});
							});
						} else {
							$('#wpsg-scan-results').hide();
							$('#wpsg-scan-empty').show();
						}
					}
				}
			});
		}
	};

	$(document).ready(function() {
		WPSGScan.init();
	});

})(jQuery);
