$(function(){
	var PAGE_LENGTH = 25;

	initDataTable();
	attachConfirmToDeleteLinks();

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
		return; // Ajax search disabled
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

	function initDataTable()
	{
		$("#clients_list").DataTable({
			pageLength: PAGE_LENGTH,
			columnDefs: [
				{orderable: false, targets: -1},
				{searchable: false, targets: [1, 2, 3, 4]}
			]
		});
	}

	function attachConfirmToDeleteLinks() {
		$(".delete-link").click(function(event) {
			event.preventDefault();
			var answer = confirm("Вы уверены?");
			if (answer === true) {
				location.href = $(this).attr("href");
			}
		});
	}
});