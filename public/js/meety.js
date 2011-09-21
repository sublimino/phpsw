$(function() {
	$('a.attendance-button').click(function(e) {
		var href = $(this).attr('href');
		if (href.match(/attend/)) {
			e.preventDefault();

			var that = this;
			var username = $(this).attr('data-username');
			var users = $.map($('#event-attendees').children(), function(value, index) {
				return $(value).text();
			});

			$.post(href, function(data) {
				var status = href.match(/\/(\d+)$/)[1];
				$(that).parent().children().removeClass('primary');
				$(that).addClass('primary');

				if (status == 0) {
					users = _.without(users, username);
				} else {
					if (_.indexOf(users, username) == -1) {
						users.push(username);
						users.sort();
					}
				}

				$('#event-attendees').empty();
				$.each(users, function(i, val) {
					$('#event-attendees').append($('<li />').text(val));
				});
			});
		}
	});
});
