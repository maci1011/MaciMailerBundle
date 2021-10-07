
(function($){

var maciMailerImport = function (options) {

	var form, mails, index

	_obj = {

	saveSubscriber: function(data) {
		if(!data['mail']) {
			return;
		}
		if(!data['country']) {
			data['country'] = form.find("#import_country").val();
		}
		if(!data['locale']) {
			data['locale'] = form.find("#import_locale").val();
		}
		if(!data['sex']) {
			data['sex'] = form.find("#import_sex_1").val();
		}
		$.ajax({
			type: 'POST',
			data: {
				'data': {
					'new': {
						'section': 'mails',
						'entity': 'subscriber',
						'data': data
					}
				}
			},
			url: '/mcm/ajax',
			success: function(d,s,x) {
				if (index < mails.length) {
					_obj.saveNextSubscriber();
				}
				else {
					form.find("#import_submit").show();
					form.find("#import_data").val("End.");
				}
			},
			error: function(d,s,x) {
				form.find("#import_submit").show();
				form.find("#import_data").val("Error.");
			}
		});
	},

	saveNextSubscriber: function(_form) {
		_obj.saveSubscriber({ 'mail': mails[index].trim() });
		index++;
		form.find("#import_data").val("Importing: " + index + " of " + mails.length);
	},

	importTxt: function() {
		mails = form.find("#import_data").val().split("\n");
		index = 0;
		_obj.saveNextSubscriber();
	},

	importXml: function() {
		data = $(form.find("#import_data").val()).find('Fornitori1');
		list = [];
		data.each(function(i, row) {
			row_data = {};
			$(row).children().each(function(j, field) {
				row_data[field['nodeName']] = $(field).text();
			});
			list[i] = row_data;
		});
		console.log(list);
	},

	set: function(_form) {
		form = _form;
		form.find("#import_submit").click(function(e) {
			e.preventDefault();
			form.find("#import_submit").hide();
			// _obj.importTxt();
			_obj.importXml();
		});
	},

	foo: function() {}

	}; // end _obj

	// Play!
	_obj.set($('#import_form'));

	return _obj;

}


$(document).ready(function(e) {

	maciMailerImport();

});

})(jQuery);
