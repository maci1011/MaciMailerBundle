
(function($){

var maciMailer = function (options) {

	var _defaults = {},

		subscribers = false,
		_last_action,

		mailId, mail,
		selected_sub,
		recipients, selected_re,
		sent

	;

	_obj = {

	resetMail: function(id) {
		$.ajax({
			type: 'POST',
			data: {
				'data': {
					'edit': {
						'section': 'mails',
						'entity': 'mail',
						'id': mailId,
						'data': {
							'resetData': null
						}
					}
				}
			},
			url: '/mcm/ajax',
			success: function(d,s,x) {
				alert('success!');
			}
		});
	},

	getMail: function(id) {
		if (!_obj.checkData(id)) {
			return false;
		}
		$.ajax({
			type: 'POST',
			data: {
				'data': {
					'item': {
						'section': 'mails',
						'entity': 'mail',
						'id': mailId
					}
				}
			},
			url: '/mcm/ajax',
			// type: 'GET',
			// data: {},
			// url: '/mcm/view/mails/mail/show/' + mailId,
			success: function(d,s,x) {
				mail = d.data.item;
				recipients = [];
				sent = [];
				if (mail.data && mail.data.recipients) {
					for (var i = 0; i < mail.data.recipients.length; i++) {
						if (mail.data.recipients[i]['sent'] == 'false') {
							recipients[recipients.length] = parseInt(mail.data.recipients[i]['id']);
						} else {
							sent[sent.length] = parseInt(mail.data.recipients[i]['id']);
						}
					}
				}
				_obj.showMenu();
				_last_action = window.location.hash;
				_obj.showLastAction();
			}
		});
	},

	saveData: function() {
		var list = [];
		for (var i = 0; i < subscribers.length; i++) {
			if (recipients.includes(subscribers[i].id)) {
				list[list.length] = {
					'id': subscribers[i].id,
					'sent': false
				};
			} else if (sent.includes(subscribers[i].id)) {
				list[list.length] = _obj.getRecipient(subscribers[i].id);
			}
		}
		var data = mail.data;
		data.recipients = list;
		mail.data = data;
		$.ajax({
			type: 'POST',
			data: {
				'data': {
					'edit': {
						'section': 'mails',
						'entity': 'mail',
						'id': mailId,
						'data': {
							'setData': [mail.data]
						}
					}
				}
			},
			url: '/mcm/ajax',
			success: function(d,s,x) {
				console.log('Recipients Saved!');
			}
		});
	},

	sendNextMail: function(id) {
		$.ajax({
			type: 'POST',
			data: {
				'id': mailId
			},
			url: '/mailer/send-next',
			success: function(d,s,x) {
				var i = _obj.getRecipientIndex(d.id);
				if (i === false) {
					i = mail.data.recipients.length;
				}
				var data = mail.data;
				data.recipients[i] = d.data;
				mail.data = data;
				recipients.splice(recipients.indexOf(d.id), 1);
				sent[sent.length] = d.id;

				console.log('A Mail has been Sended!');
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
		
		$('#actions').html('');
		$('#content').html('');
	},

	getSubribers: function() {
		subscribers = [];
		$.ajax({
			type: 'POST',
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
				} else {
					$('#content').html('');
					$('<h3/>').text('No Subscribers!').appendTo('#content');
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

		$('<a/>').attr('class', 'nav-link').attr('href', '#sents')
		.text('Sents Mail').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			_obj.showSents();
		}).wrap('<li/>').parent().attr('class', 'nav-item');

		/*
		$('<a/>').attr('class', 'nav-link').attr('href', '#resetData')
		.text('Reset Mail').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			if (confirm('Sure?')) {
				_obj.resetMail();
			}
		}).wrap('<li/>').parent().attr('class', 'nav-item');
		*/

		$('<h3/>').text('Mail').appendTo('#actions');
		var mailUl = $('<ul/>').attr('class', 'navbar-nav').appendTo('#actions');
		mailUl.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');

		$('<span/>').html('Id: <b>' + mail.id + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Name: <b>' + mail.name + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Subject: <b>' + mail.subject + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<span/>').html('Recipients List Length: <b>' + recipients.length + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
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
		} else {
			$('a[href="#subscribers"]').click();
		}
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

	showSendMail: function() {
		$('a[href="#sendMail"]').parent().addClass('active');
		_last_action = "#sendMail";
		$('#content').html('');
		$('<h3/>').text('Send Mail').appendTo('#content');
		$('<button/>').appendTo('#content').click(function(e) {
			e.preventDefault();
			_obj.sendNextMail();
		}).text('Send Mails').attr('class', 'btn btn-success ml-auto mt-3');
	},

	showSents: function() {
		$('a[href="#sents"]').parent().addClass('active');
		_last_action = "#sents";
		$('#content').html('');
		if (!sent.length) {
			$('<h3/>').text('Mail did not sent.').appendTo('#content');
			return;
		}
		$('<h3/>').text('Send List').appendTo('#content');
		var ul = $('<ul/>').attr('class', 'navbar-nav ml-auto').appendTo('#content');
		ul.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');
		for (var i = 0; i < subscribers.length; i++) {
			if (!sent.includes(subscribers[i].id)) {
				continue;
			}
			$('<a/>').attr('class', 'nav-link').attr('href', '#')
			.text(subscribers[i].name).appendTo(ul).click(function(e) {
				e.preventDefault();
				if (sent.includes(parseInt($(this).prop('id')))) {
					$(this).text(_obj.getRecipientData(parseInt($(this).prop('id'))));
				}
			}).mouseenter(function (e) {
				$(this).text(subscribers[parseInt($(this).prop('index'))].mail);
			}).mouseleave(function (e) {
				$(this).text(subscribers[parseInt($(this).prop('index'))].name);
			}).prop('index', i).prop('id', subscribers[i].id).wrap('<li/>').parent().attr('class', 'nav-item' + (sent.includes(subscribers[i].id) ? ' sent' : ''));
		}
		$('<button/>').appendTo('#content').click(function(e) {
			e.preventDefault();
			_obj.removeSubribers();
		}).text('Remove Subscribers').attr('class', 'btn btn-success ml-auto mt-3');
	},

	addSubribers: function() {
		for (var i = 0; i < selected_sub.length; i++) {
			recipients[recipients.length] = selected_sub[i];
		}
		selected_sub = [];
		_obj.saveData();
		_obj.showSubribers();
		_obj.showMenu();
	},

	removeSubribers: function() {
		for (var i = 0; i < selected_re.length; i++) {
			recipients.splice(recipients.indexOf(selected_re[i]), 1);
		}
		selected_re = [];
		_obj.saveData();
		_obj.showRecipients();
		_obj.showMenu();
	},

	getRecipientIndex: function(id) {
		for (var i = mail.data.recipients.length - 1; i >= 0; i--) {
			if (mail.data.recipients[i]['id'] == id) {
				return i;
			}
		}
		return false;
	},

	getRecipient: function(id) {
		var i = _obj.getRecipientIndex(id);
		if (i) return mail.data.recipients[i];
		return false;
	},

	getRecipientData: function(id) {
		var i = _obj.getRecipient(id);
		if (i) return i['sent'];
		return false;
	}

	}; // end _obj

	// Play!
	_obj.getMail($('#mail').attr('data-id'));

	return _obj;

}


$(document).ready(function(e) {

	maciMailer();

});

})(jQuery);
