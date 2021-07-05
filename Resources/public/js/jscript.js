
(function($){

var maciMailer = function (options) {

	var _defaults = {},

		subscribers = false,
		_last_action = false,

		mailId, mail,
		selected_sub,
		recipients, selected_re,
		sent,
		_index

	;

	_obj = {

	getMail: function(id) {
		if (!_obj.checkData(id)) {
			return false;
		}
		$.ajax({
			type: 'GET',
			data: {},
			url: '/mcm/view/mails/mail/show/' + mailId,
			success: function(d,s,x) {
				mail = d.item;
				recipients = mail.data.recipients ? mail.data.recipients : [];
				sent = mail.data.sent ? mail.data.sent : [];
				if (mail.data.index) {
					_index = mail.data.index;
				}
				_obj.showMenu();
				_obj.showLastAction();
			}
		});
	},

	checkData: function(id) {
		_obj.resetMailData();
		id = parseInt(id);
		if (id < 1) {
			return false;
		}
		mailId = id;
		if (subscribers === false || !subscribers.length) {
			_obj.getSubribers();
			return false;
		}
		return true;
	},

	resetMailData: function() {
		mailId = false;
		mail = false;
		selected_sub = [];
		recipients = [];
		selected_re = [];
		sent = [];
		_index = -1;
		
		$('#actions').html('');
		$('#content').html('');
	},

	getSubribers: function() {
		$.ajax({
			type: 'GET',
			data: {
				'data': {
					'list': {
						'section': 'mails',
						'entity': 'subscriber'
					}
				}
			},
			url: '/mcm/ajax',
			success: function(d,s,x) {
				if (Array.isArray(d.data.list) && d.data.list.length) {
					subscribers = d.data.list;
					_obj.getMail(mailId);
				}
			}
		});
		// $.ajax({
		// 	type: 'GET',
		// 	data: {},
		// 	url: '/mcm/view/mails/subscriber/list',
		// 	success: function(d,s,x) {
		// 		if (Array.isArray(d.list) && d.list.length) {
		// 			subscribers = d.list;
		// 			_obj.getMail(mailId);
		// 		}
		// 	}
		// });
	},

	showSubribers: function() {
		$('a[href="#subscribers"]').parent().addClass('active');
		_last_action = "#subscribers";
		$('#content').html('');
		$('<h3/>').text('Subscribers List').appendTo('#content');
		var ul = $('<ul/>').attr('class', 'navbar-nav ml-auto').appendTo('#content');
		ul.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');
		var tot = 0;
		for (var i = 0; i < subscribers.length; i++) {
			if (recipients.includes(subscribers[i].id) || sent.includes(subscribers[i].id)) {
				continue;
			}
			tot += 1;
			$('<a/>').attr('class', 'nav-link').attr('href', '#')
			.text(subscribers[i].name).appendTo(ul).click(function(e) {
				e.preventDefault();
				$(this).parent().toggleClass('active');
				if ($(this).parent().hasClass('active')) {
					selected_sub[selected_sub.length] = parseInt($(this).prop('id'));
				} else {
					selected_sub.splice(selected_sub.indexOf($(this).prop('id')), 1);
				}
			}).mouseenter(function (e) {
				$(this).text(subscribers[parseInt($(this).prop('index'))].mail);
			}).mouseleave(function (e) {
				$(this).text(subscribers[parseInt($(this).prop('index'))].name);
			}).prop('index', i).prop('id', subscribers[i].id).wrap('<li/>').parent().attr('class', 'nav-item' + (selected_sub.includes(subscribers[i].id) ? ' active' : ''));
		}
		if (tot === 0) {
			$('#content').html('');
			$('<h3/>').text('No Subscribers.').appendTo('#content');
			return;
		}
		$('<button/>').appendTo('#content').click(function(e) {
			e.preventDefault();
			_obj.addSubribers();
		}).text('Add Subscribers').attr('class', 'btn btn-success ml-auto mt-3');
	},

	addSubribers: function() {
		for (var i = 0; i < selected_sub.length; i++) {
			recipients[recipients.length] = selected_sub[i];
		}
		mail.data.recipients = recipients;
		selected_sub = [];
		_obj.showSubribers();
		_obj.showMenu();
	},

	showMenu: function() {
		$('#actions').html('');

		$('<h3/>').text('Actions').appendTo('#actions');
		var actionsUl = $('<ul/>').attr('class', 'navbar-nav').appendTo('#actions');
		actionsUl.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');

		$('<a/>').attr('class', 'nav-link').attr('href', '#subscribers')
		.text('Subscribers').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			_obj.showSubribers();
		}).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<a/>').attr('class', 'nav-link').attr('href', '#recipients')
		.text('Recipients').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			_obj.showRecipients();
		}).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<a/>').attr('class', 'nav-link').attr('href', '#sendMail')
		.text('Send Mail').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			_obj.showSendMail();
		}).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<h3/>').text('Mail').appendTo('#actions');
		var mailUl = $('<ul/>').attr('class', 'navbar-nav').appendTo('#actions');
		mailUl.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');

		$('<span/>').html('Id: <b>' + mail.id + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Name: <b>' + mail.name + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Subject: <b>' + mail.subject + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<span/>').html('Recipients List Length: <b>' + recipients.length + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Index Position: <b>' + _index + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Sent List Length: <b>' + sent.length + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<a/>').attr('class', 'nav-link').attr('href', '/mailer/show/' + mail.token).attr('target', '_black')
		.html('<i class="fas fa-eye"></i> Show Template').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
	},

	showLastAction: function() {
		if (_last_action == "#subscribers") {
			$('a[href="#subscribers"]').click();
		} else if (_last_action == "#recipients") {
			$('a[href="#recipients"]').click();
		} else if (_last_action == "#sendMail") {
			$('a[href="#sendMail"]').click();
		}
		$('a[href="#subscribers"]').click();
	},

	showRecipients: function() {
		$('a[href="#recipients"]').parent().addClass('active');
		_last_action = "#recipients";
		$('#content').html('');
		if (!recipients.length) {
			$('<h3/>').text('No Recipients.').appendTo('#content');
			return;
		}
		$('<h3/>').text('Send List').appendTo('#content');
		var ul = $('<ul/>').attr('class', 'navbar-nav ml-auto').appendTo('#content');
		ul.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');
		for (var i = 0; i < subscribers.length; i++) {
			if (!recipients.includes(subscribers[i].id)) {
				continue;
			}
			$('<a/>').attr('class', 'nav-link').attr('href', '#')
			.text(subscribers[i].name).appendTo(ul).click(function(e) {
				e.preventDefault();
				$(this).parent().toggleClass('active');
				if ($(this).parent().hasClass('active')) {
					selected_re[selected_re.length] = parseInt($(this).prop('id'));
				} else {
					selected_re.splice(selected_re.indexOf($(this).prop('id')), 1);
				}
			}).mouseenter(function (e) {
				$(this).text(subscribers[parseInt($(this).prop('index'))].mail);
			}).mouseleave(function (e) {
				$(this).text(subscribers[parseInt($(this).prop('index'))].name);
			}).prop('index', i).prop('id', subscribers[i].id).wrap('<li/>').parent().attr('class', 'nav-item' + (selected_re.includes(subscribers[i].id) ? ' active' : ''));
		}
		$('<button/>').appendTo('#content').click(function(e) {
			e.preventDefault();
			_obj.removeSubribers();
		}).text('Remove Subscribers').attr('class', 'btn btn-success ml-auto mt-3');
	},

	removeSubribers: function() {
		for (var i = 0; i < selected_re.length; i++) {
			recipients.splice(recipients.indexOf(selected_re[i]), 1);
		}
		mail.data.recipients = recipients;
		selected_re = [];
		_obj.showRecipients();
		_obj.showMenu();
	},

	showSendMail: function() {
		$('a[href="#sendMail"]').parent().addClass('active');
		_last_action = "#sendMail";
		$('#content').html('');
		$('<h3/>').text('Send Mail').appendTo('#content');
	}

	}; // _obj

	_obj.getMail($('#mail').attr('data-id'));

	return _obj;

}


$(document).ready(function(e) {

	maciMailer();

});

})(jQuery);
