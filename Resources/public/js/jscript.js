
(function($){

var maciMailer = function (options) {

	var _defaults = {},

		mailId, mailData, subscribers

	;

	_obj = {

	getMailData: function() {
		$.ajax({
			type: 'GET',
			data: {},
			url: '/mcm/view/mails/mail/show/' + mailId,
			success: function(d,s,x) {
				mailData = d.item;
				$('#actions').html('');
				$('<h3/>').text('Mail: ' + d.item.id).appendTo('#actions');
				$('<span/>').text('Name: ' + d.item.name).appendTo('#actions');
				$('<span/>').text('Subject: ' + d.item.subject).appendTo('#actions');
				$('<span/>').text('Index Position: ' + d.item.data.toIndex).appendTo('#actions');
				$('<span/>').text('Send List Length: ' + d.item.data.sendList.length).appendTo('#actions');
			}
		});
	},

	getSubribersList: function() {
		$.ajax({
			type: 'GET',
			data: {},
			url: '/mcm/view/mails/subscriber/list',
			success: function(d,s,x) {
				$('#content').html('');
				$('<h3/>').text('Subribers List').appendTo('#content');
				var list = $('<ul/>').appendTo('#content');
				for (var i = 0; i < d.list.length; i++) {
					$('<span/>').text(d.list[i].name).appendTo(list)
						.wrap('<li/>').parent().attr('data-id', d.list[i].id);
				}
				subscribers = d.list;
			}
		});
	}

	}; // _obj

	mailId = $('#mail').attr('data-id');
	_obj.getMailData();
	_obj.getSubribersList();

	return _obj;

}


$(document).ready(function(e) {

	maciMailer();

});

})(jQuery);
