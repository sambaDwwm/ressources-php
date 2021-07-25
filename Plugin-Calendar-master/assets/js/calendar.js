

$(document).ready(function(){



	$("#submit_modal").click(function(){
		var ajaxData = {
			action : 'add_calendar',
			param : {
					number_person : $("#number_person").val(),
					name : $("#name").val(),
					activity : $("#activity").val(),
					date : $("#date").val(),
					hour_start : $("#hour_start").val(),
					hour_end : $("#hour_end").val(),
					location : $("#location").val(),

				}
		}
		$.post(ajaxurl, ajaxData);
	});

	var ajaxEvent = {
		action : 'get_all_event',
		param : 'toto'
	}
	$.post(ajaxurl, ajaxEvent, function(response){
		var events = JSON.parse(response);
		var evts = [];
		for (var i = 0; i < events.length; i++) {
			evts.push({
				title : events[i].activity,
				start : events[i].date + 'T' + events[i].hour_start,
				end : events[i].date + 'T' + events[i].hour_end,

			});
		}

		$('#calendar').fullCalendar({
			header : {
				left : 'prev,next',
				center : 'title',
				right : 'month,agendaWeek, listWeek'
			},
			defaultView : 'month',
			locale: 'fr',
			events  : evts


	    });
	});


});
