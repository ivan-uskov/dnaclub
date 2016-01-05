$(function(){
	var isProcessing = false;
	var sidebarSearchInput = $("#sidebar_search_input");
	sidebarSearchInput.on("input", function(event) {
		processSearchQuery();
	});
	$("#sidebar_search_submit").click(function(event) {
		event.preventDefault();
		processSearchQuery();
	});

	function processSearchQuery() {
		return; // Search disabled
		if (isProcessing) {
			return;
		}
		isProcessing = true;
		$.post(
				"/clients-search-ajax",
				{
					"query": sidebarSearchInput.val()
				}
		).complete(function(response) {
			$("#table_body").empty().html(response.responseText);
		}).always(function(){
			isProcessing = false;
		});
	}
});