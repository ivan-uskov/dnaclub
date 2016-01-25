$(function(){
	var PAGE_LENGTH = 25;

	initDatePicker();
	attachConfirmToDeleteLinks();
	initDataTable();

	function initDatePicker() {
		$('.datepicker').datepicker({
			format: 'dd.mm.yyyy',
			weekStart: 1,
			autoclose: true,
			language: 'ru'
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

	function initDataTable() {
		$("#disease_histories_list").DataTable({
			pageLength: PAGE_LENGTH,
			order: [[ 0, "desc" ]],
			bFilter: false,
			columnDefs: [
				{orderable: false, targets: -1},
				{orderable: false, targets: -2}
			]
		});
	}
});