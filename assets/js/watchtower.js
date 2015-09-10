$(document).ready(function() {
	$(function () {
		// TOOLTIP HOVERING
		$("[rel='tooltip']").tooltip({
			'html': true,
			'container': 'body'
		});

		// CLICKABLE DATATABLE ROWS
		$(".linkrow").click(function() {
			window.location.href = $(this).data("href");
		});
	});
});