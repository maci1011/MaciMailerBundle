
(function($){

var maciMailer = function (options) {

	var _defaults = {},

		mailId, mail, subscribers, sendList, addList

	;

	_obj = {

	getMail: function(id) {
		id = parseInt(id);
		if (id < 1) {
			return false;
		}
		mailId = id;
		_obj.getMailData();
	},

	getMailData: function() {
		$.ajax({
			type: 'GET',
			data: {},
			url: '/mcm/view/mails/mail/show/' + mailId,
			success: function(d,s,x) {
				mail = d.item;
				_obj.showMenu();
				_obj.getSubribersList();
			}
		});
	},

	showMenu: function() {
		$('#actions').html('');

		$('<h3/>').text('Actions').appendTo('#actions');
		var actionsUl = $('<ul/>').attr('class', 'navbar-nav').appendTo('#actions');
		actionsUl.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');

		$('<a/>').attr('class', 'nav-link').attr('href', '#subscribers')
		.text('Subscribers').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			_obj.showSubribersList();
		}).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<a/>').attr('class', 'nav-link').attr('href', '#sendList')
		.text('Send List').appendTo(actionsUl).click(function(e) {
			actionsUl.children().removeClass('active');
			_obj.showSendList();
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

		$('<span/>').html('Send List Length: <b>' + mail.data.sendList.length + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
		$('<span/>').html('Index Position: <b>' + mail.data.toIndex + '</b>').attr('class', 'navbar-text').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');

		$('<a/>').attr('class', 'nav-link').attr('href', '/mailer/show/' + mail.token).attr('target', '_black')
		.html('<i class="fas fa-eye"></i> Show Template').appendTo(mailUl).wrap('<li/>').parent().attr('class', 'nav-item');
	},

	showSendMail: function() {
		$('a[href="#sendMail"]').parent().addClass('active');
		$('#content').html('');
		$('<h3/>').text('Send Mail').appendTo('#content');
	},

	showSendList: function() {
		$('a[href="#sendList"]').parent().addClass('active');
		$('#content').html('');
		$('<h3/>').text('Send List').appendTo('#content');
		var ul = $('<ul/>').attr('class', 'navbar-nav ml-auto').appendTo('#content');
		ul.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');
		for (var i = 0; i < subscribers.length; i++) {
			if (!sendList.includes(subscribers[i].id)) {
				continue;
			}
			$('<a/>').attr('class', 'nav-link').attr('href', '#')
			.text(subscribers[i].name).appendTo(ul).click(function(e) {
				e.preventDefault();
				addList[$(this).prop('index')][2] = $(this).parent().toggleClass('active').hasClass('active');
			}).prop('index', i).wrap('<li/>').parent().attr('class', 'nav-item' + (addList[i][2] ? ' active' : ''));
		}
		$('<button/>').appendTo('#content').click(function(e) {
			e.preventDefault();
			_obj.removeSubribers();
		}).text('Remove Subribers').attr('class', 'btn btn-success ml-auto mt-3');
	},

	removeSubribers: function() {
		for (var i = 0; i < addList.length; i++) {
			if (addList[i][2] && sendList.includes(addList[i][0])) {
				sendList.splice(sendList.indexOf(addList[i][0]), 1);
			}
			addList[i][2] = false;
		}
		mail.data.sendList = sendList;
		_obj.showSendList();
		_obj.showMenu();
	},

	getSubribersList: function() {
		subscribers = [];
		sendList = [];
		addList = [];
		$.ajax({
			type: 'GET',
			data: {},
			url: '/mcm/view/mails/subscriber/list',
			success: function(d,s,x) {
				subscribers = d.list;
				for (var i = 0; i < subscribers.length; i++) {
					addList[i] = [subscribers[i].id, sendList.includes(subscribers[i].id), false];
				}
				_obj.showSubribersList();
			}
		});
	},

	showSubribersList: function() {
		$('a[href="#subscribers"]').parent().addClass('active');
		$('#content').html('');
		$('<h3/>').text('Subribers List').appendTo('#content');
		var ul = $('<ul/>').attr('class', 'navbar-nav ml-auto').appendTo('#content');
		ul.wrap('<nav/>').parent().attr('class', 'navbar navbar-light py-0');
		for (var i = 0; i < subscribers.length; i++) {
			if (sendList.includes(subscribers[i].id)) {
				continue;
			}
			$('<a/>').attr('class', 'nav-link').attr('href', '#')
			.text(subscribers[i].name).appendTo(ul).click(function(e) {
				e.preventDefault();
				addList[$(this).prop('index')][1] = $(this).parent().toggleClass('active').hasClass('active');
			}).prop('index', i).wrap('<li/>').parent().attr('class', 'nav-item' + (addList[i][1] ? ' active' : ''));
		}
		$('<button/>').appendTo('#content').click(function(e) {
			e.preventDefault();
			_obj.addSubribers();
		}).text('Add Subribers').attr('class', 'btn btn-success ml-auto mt-3');
	},

	addSubribers: function() {
		for (var i = 0; i < addList.length; i++) {
			if (addList[i][1] && !sendList.includes(addList[i][0])) {
				sendList[sendList.length] = addList[i][0];
			}
			addList[i][1] = false;
		}
		mail.data.sendList = sendList;
		_obj.showSubribersList();
		_obj.showMenu();
	}

	}; // _obj

	_obj.getMail($('#mail').attr('data-id'));

	return _obj;

}


$(document).ready(function(e) {

	maciMailer();

});

})(jQuery);
