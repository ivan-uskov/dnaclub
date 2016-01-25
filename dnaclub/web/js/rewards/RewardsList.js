$(function()
{
	var PAGE_LENGTH = 25;

	initDatePicker();
	attachConfirmToDeleteLinks();
	initDataTable();

	function initDatePicker()
	{
		$('.datepicker').datepicker({
			format: 'dd.mm.yyyy',
			weekStart: 1,
			autoclose: true,
			language: 'ru'
		});
	}

	function attachConfirmToDeleteLinks()
	{
		$(".delete-link").click(function(event)
		{
			event.preventDefault();
			var answer = confirm("Вы уверены?");
			if (answer === true)
			{
				location.href = $(this).attr("href");
			}
		});
	}

	function initDataTable()
	{
		if ($("#template_mode").val() == "rewards")
		{
			$("#rewards_list").DataTable({
				pageLength: PAGE_LENGTH,
				order: [[0, "asc"]],
				columnDefs: [
					{orderable: false, targets: [-1, -2, -3]},
					{searchable: false, targets: [-1, -2, -3]},
					{type: 'date-ru', targets: [1]},
					{type: 'formatted-num', targets: [2, 3]}
				]
			});
		}
		else
		{
			$("#rewards_list").DataTable({
				pageLength: PAGE_LENGTH,
				order: [[0, "desc"]],
				columnDefs: [
					{orderable: false, targets: [-1, -2, -3]},
					{searchable: false, targets: [-1, -2, -3]},
					{type: 'date-ru', targets: [0]},
					{type: 'formatted-num', targets: [1, 2]}
				]
			});
		}
	}
});