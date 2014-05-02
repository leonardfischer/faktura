window.addEvent('domready', function() {
    // Drop-Down Button script.
    $$('div.container')[0].addEvent('click', function(ev) {
	    if (ev.target.match('button.dropdown-toggle')) {
            ev.target.getParent().toggleClass('open');
	    }
    });

    // Invoice/Customer/Supplier-save script.
    if ($('save-button')) {
        $('save-button').addEvent('click', function () {
            var data = {},
                message = $('form-message')
                    .removeClass('alert-success')
                    .removeClass('alert-danger')
                    .removeClass('hidden')
                    .setStyle('backgroundColor', '#aaa')
                    .setStyle('color', '#fff')
                    .set('html', Faktura.get('form.loading-message'));

            this.addClass('disabled');

            $$('input,textarea,select').each(function (el) {
                data[el.get('name')] = el.get('value');

                if (el.get('type') == 'checkbox') {
	                // This is used for "allowance".
                    data[el.get('name')] = (el.checked ? 2 : 0);
                }
            });

            if (! Faktura.has('form.ajax_url')) {
                alert('No ajax URL defined!');
                return;
            }

            new Request.JSON({
                url: Faktura.get('form.ajax_url'),
                data: data,
                onSuccess: function(json){
                    var property;

                    this.removeClass('disabled');

                    message
                        .setStyle('backgroundColor', null)
                        .setStyle('color', null);

                    $$('.form-group.has-error').removeClass('has-error');
                    $$('.help-inline:not(.hidden)').addClass('hidden');

                    if (json.success) {
                        message.addClass('alert-success').set('text', Faktura.get('form.success-message'));

	                    if (json.data !== null && json.data.clean() != '') {
		                    document.location.href = json.data;
	                    }
                    } else {
                        message.addClass('alert-danger').set('text', json.message);

	                    if (json.data.hasOwnProperty('_external')) {
		                    for (property in json.data._external) {
			                    if (json.data._external.hasOwnProperty(property)) {
				                    $$('.form-group[data-property="' + property + '"]')[0]
					                    .addClass('has-error')
					                    .getElement('span.help-inline')
					                    .removeClass('hidden').set('text', json.data._external[property]);
			                    }
		                    }
	                    } else {
	                        for (property in json.data) {
	                            if (json.data.hasOwnProperty(property)) {
	                                $$('.form-group[data-property="' + property + '"]')[0]
	                                    .addClass('has-error')
	                                    .getElement('span.help-inline')
	                                    .removeClass('hidden').set('text', json.data[property]);
	                            }
	                        }
	                    }
                    }

                }.bind(this)}).send();
        });
    }

    if (Faktura.get('form.name') !== null) {
        $(Faktura.get('form.name')).addEvent('keyup:relay(input.money)', function () {
            this.set('value', this.get('value').replace(/[^,\.\d]*/g, ''))
        });
    }

    // Focus on the first input / textarea!
    var first_field = $$('input,textarea');

    if (first_field.length > 0) {
        first_field[0].focus();
    }

	$$('select.searchable').each(function (el) {
		new Searchable(el);
	});

	// Once the DOM is ready, trigger the "responsive_popup" function.
	ModalPopup.initialize().responsive();

	window.addEventListener('resize', ModalPopup.responsive);
});

// This pager class can be used for tables, filled by a controller + model.
var Pager = new Class({
	options: {
		exclude:[],
		selection: false
	},
	initialize: function (options) {
		this.options = options;

		this.options.current_page = 1;

		this.page_data = {};

		// Render the pager.
		this.options.pager.map(function(el) {
			var i;

			el.grab(new Element('li').grab(new Element('a.pager.pager-prev', {href:'#', html:'&laquo;'})));

			for (i = 1; i <= this.options.max_page; i += 1) {
				el.grab(new Element('li').grab(new Element('a.pager.pager-page', {href:'#', html:i, "data-page":i})));
			}

			el.grab(new Element('li').grab(new Element('a.pager.pager-next', {href:'#', html:'&raquo;'})));

		}.bind(this));

		// Attach the observer.
		this.options.pager.invoke('addEvent', 'click', function (ev) {
			ev.preventDefault();

			var clicked_el = ev.target,
				clicked_li = clicked_el.getParent('li'),
				clicked_pager = clicked_el.getParent('ul.pagination');

			if (! clicked_pager.hasClass('disabled') && ! clicked_li.hasClass('disabled') && ! clicked_li.hasClass('active')) {
				if (clicked_el.hasClass('pager-page')) {
					this.goto(clicked_el.get('data-page'));
				} else if (clicked_el.hasClass('pager-next')) {
					this.next();
				} else if (clicked_el.hasClass('pager-prev')) {
					this.previous();
				}
			}
		}.bind(this));

		this.handle_pager_classes();
		this.get_current_page_data();
	},

	goto: function (page) {
		this.options.current_page = parseInt(page);

		this.get_current_page_data();
	},

	next: function () {
		if (this.options.current_page < this.options.max_page) {
			this.options.current_page += 1;

			this.get_current_page_data();
		}
	},

	previous: function () {
		if (this.options.current_page > 1) {
			this.options.current_page -= 1;

			this.get_current_page_data();
		}
	},

	get_current_page_data: function () {
		if (typeof this.page_data[this.options.current_page] != 'undefined') {
			this.render_current_page();
		} else {
			new Request.JSON({
				url: '?',
				data: {page:this.options.current_page, exclude:this.options.exclude},
				onSuccess: function(json){
					var i, tbody = this.options.target.getElement('tbody').set('html', '');

					if (json.success) {
						this.page_data[this.options.current_page] = json.data;

						this.render_current_page();
					} else {
						tbody.grab(new Element('tr').grab(new Element('td.text-danger', {colspan:6, html:'<i class="mr5 fa fa-exclamation-circle"></i>' + json.message})));
					}

				}.bind(this)}).send();
		}
	},

	render_current_page: function () {
		var i, i2, tbody = this.options.target.getElement('tbody').set('html', ''), row;

		for (i in this.page_data[this.options.current_page]) {
			if (this.page_data[this.options.current_page].hasOwnProperty(i)) {
				row = new Element('tr');

				if (this.page_data[this.options.current_page][i].hasOwnProperty('_id')) {
					row.set('data-id', this.page_data[this.options.current_page][i]['_id']);

					if (this.options.selection && this.options.selection == this.page_data[this.options.current_page][i]['_id']) {
						row.addClass('success');
					}
				}

				for (i2 in this.page_data[this.options.current_page][i]) {
					if (this.page_data[this.options.current_page][i].hasOwnProperty(i2) && i2.substr(0, 1) !== '_') {
						row.grab(new Element('td', {html:this.page_data[this.options.current_page][i][i2], 'data-prop':i2}))
					}
				}

				tbody.grab(row);
			}
		}

		this.handle_pager_classes();
	},

	handle_pager_classes: function () {
		if (this.options.items === 0) {
			return;
		}

		this.options.pager.map(function(el) {
			el.getElements('li').invoke('removeClass', 'disabled').invoke('removeClass', 'active');

			el.getElement('li a[data-page="' + this.options.current_page + '"]').getParent('li').addClass('active');

			if (this.options.current_page == 1) {
				el.getElement('li a.pager-prev').getParent('li').addClass('disabled');
			}

			if (this.options.current_page == this.options.max_page) {
				el.getElement('li a.pager-next').getParent('li').addClass('disabled');
			}
		}.bind(this));
	}
});

// This class can be used with "select" fields.
var Searchable = new Class({
	initialize: function (el) {
		this.element = (typeOf(el) === 'string') ? $(el) : el;
		this.selection = this.element.getSelected();
		this.minlength = 3;
		this.container = new Element('div.searchable-plugin');

		// If no item could be found, we just display "no data".
		if (this.selection.length === 0) {
			this.container
				.grab(new Element('button.dropdown-toggle.btn.btn-block.btn-default', {type: 'button'})
					.grab(new Element('span', {text: Faktura.get('searchable.no-data-message')})));
			this.element.setStyle('display', 'none').grab(this.container, 'after');

			return;
		}

		this.toggler = new Element('button.dropdown-toggle', {type:'button', class:'btn btn-block btn-default', 'data-toggle':'dropdown'})
			.grab(new Element('i.fa.fa-search'))
			.grab(new Element('span', {text:this.selection.get('text')}))
			.addEvent('click', function (ev) {
				if (this.container.toggleClass('open').hasClass('open')) {
					this.popup.getElement('input.filter').focus();
				}
			}.bind(this));

		var list_filter = new Element('input.filter.form-control.input-sm').addEvent('keyup:pause(350)', this.filter_list.bind(this));

		if (Faktura.has('searchable.min_length') && Faktura.has('searchable.placeholder')) {
			this.minlength = parseInt(Faktura.get('searchable.min_length'));
			list_filter.set('placeholder', Faktura.get('searchable.placeholder').replace(':minlength', this.minlength));
		}

		this.popup = new Element('ul.dropdown-menu')
			.grab(new Element('li').grab(list_filter));

		this.element.getElements('option').each(function(el) {
			this.popup.grab(new Element('li.searchable', {'data-search':el.get('text').toLowerCase().clean()}).grab(new Element('a', {href:'#', 'data-value':el.get('value'), text:el.get('text')})));
		}.bind(this));

		this.container
			.grab(this.toggler)
			.grab(this.popup);

		this.popup.addEvent('click', this.select_item.bind(this));

		this.element.setStyle('display', 'none').grab(this.container, 'after');
	},

	filter_list: function (ev) {
		var el = ev.target,
			value = el.get('value').toLowerCase();

		if (value.clean() == '') {
			this.popup.getElements('li.searchable').invoke('removeClass', 'hidden');
		} else if (value.length >= this.minlength) {
			this.popup.getElements('li.searchable').invoke('addClass', 'hidden');
			this.popup.getElements('li.searchable[data-search*="' + value + '"]').invoke('removeClass', 'hidden');
		}
	},

	select_item: function (ev) {
		var el = ev.target;

		if (el.match('li.searchable a')) {
			// Remove the old selection.
			this.selection.erase('selected');

			// Set the new selection.
			this.element.set('value', el.getAttribute('data-value'));
			el.set("selected", "selected");

			// Write the new selection on the button.
			this.toggler.getElement('span').set('text', el.get('text'));

			// Close the popup.
			this.container.removeClass('open');
		}

		// Prevent the default behaviour (jump to top, adding a "#" to the address).
		ev.preventDefault();
	}
});

// This class can be used in addition to a Pageable table.
var AjaxTableSearch = new Class({
	options: {
		exclude:[],
		selection:false
	},
	initialize: function (searchfield, table, options) {
		this.searchfield = searchfield;
		this.table = table;
		this.original_table = this.table.clone();
		this.options = options;

		this.options.cols = this.table.getElements('thead th').length;

		this.searchfield.addEvent('keyup:pause(350)', this.trigger_search.bind(this));
	},

	trigger_search: function () {
		var search = this.searchfield.get('value').clean();

		if (search.length < this.options.minlength) {
			this.restore_table();
		} else {
			this.perform_search(search);
		}
	},

	perform_search: function (search) {
		var tbody = this.table.getElement('tbody').set('html', this.options.loading_label);

		this.disable_pager();

		new Request.JSON({
			url: this.options.url,
			data: {search: search, filter: this.options.filter, exclude:this.options.exclude},
			onSuccess: function (json) {
				var i, i2, row;

				tbody.set('html', '');

				if (json.success) {
					if (json.data == null || json.data.length == 0) {
						tbody.grab(new Element('tr').grab(new Element('td.text-primary', {colspan: this.options.cols, html: this.options.nothing_found_label})));
					} else {
						for (i in json.data) {
							if (json.data.hasOwnProperty(i)) {

								row = new Element('tr');

								if (json.data[i].hasOwnProperty('_id')) {
									row.set('data-id', json.data[i]['_id']);

									if (this.options.selection && this.options.selection == json.data[i]['_id']) {
										row.addClass('success');
									}
								}

								for (i2 in json.data[i]) {
									if (json.data[i].hasOwnProperty(i2) && i2.substr(0, 1) !== '_') {
										row.grab(new Element('td', {html:json.data[i][i2], 'data-prop':i2}));
									}
								}

								tbody.grab(row);
							}
						}
					}
				} else {
					tbody.grab(new Element('tr').grab(new Element('td.text-danger', {colspan: this.options.cols, html: json.message})));
				}
			}.bind(this)}).send();
	},

	restore_table: function () {
		// This should be used to display the "normal" table including the pager.
		this.enable_pager();

		if (this.options.pager) {
			this.options.pager.get_current_page_data();
		} else {
			var original_parts = this.original_table.clone().getChildren(), i;

			this.table.set('html', '');

			// This is a fallback...
			for (i in original_parts) {
				if (original_parts.hasOwnProperty(i) && typeOf(original_parts[i]) === 'element') {
					this.table.grab(original_parts[i]);
				}
			}

			// In case the selection has been reset, we update the visual representation.
			if (this.options.selection) {
				this.table.getElements('tr.success').invoke('removeClass', 'success');
				this.table.getElement('tr[data-id="' + this.options.selection + '"]').addClass('success');
			}
		}
	},

	disable_pager: function () {
		if (this.options.pager) {
			this.options.pager.options.pager.invoke('addClass', 'disabled');
		}
	},

	enable_pager: function () {
		if (this.options.pager) {
			this.options.pager.options.pager.invoke('removeClass', 'disabled');
		}
	}
});

// This class should be used for modal popups.
var ModalPopup = {
	initialize: function () {
		this.overlay = $('overlay');
		this.popup = $('popup');
		this.popup_content = $('popup-content');

		this.popup.addEvent('click:relay(.popup-close)', ModalPopup.close.bind(this));

		return this;
	},

	is_open: function () {
		return (! (this.overlay.hasClass('hidden') && this.popup.hasClass('hidden')));
	},

	toggle: function () {
		if (this.is_open()) {
			this.close.call(this);
		} else {
			this.open.call(this);
		}
	},

	open: function () {
		if (Faktura.get('modal.blur')) {
			$('main-container').removeClass('blur-off').addClass('blur');
		}

		document.body.addClass('modal-open');

		this.overlay.removeClass('hidden');
		this.popup.removeClass('hidden');

		return this;
	},

	close: function () {
		if (Faktura.get('modal.blur')) {
			$('main-container').addClass('blur-off').removeClass('blur');
		}

		document.body.removeClass('modal-open');

		this.overlay.addClass('hidden');
		this.popup.addClass('hidden');

		return this;
	},

	execute_javascript: function () {
		var scripts = this.popup_content.getElements('script'),
			i;

		if (scripts.length > 0) {
			for (i in scripts) {
				if (scripts.hasOwnProperty(i)) {
					Browser.exec(scripts[i].innerHTML);
				}
			}
		}

		return this;
	},

	grab: function (element) {
		this.popup_content.set('html', '').grab(element);

		return this;
	},

	load_html: function (url, data, callback) {
		new Request.HTML({
			url: url,
			data: data,
			evalScripts: false,
			onSuccess: function (tree, elements, html, responseJavaScript) {
				this.popup_content.set('html', html);

				if (typeOf(callback) === 'function') {
					callback.call(this, responseJavaScript);
				}
			}.bind(this)
		}).send();

		return this;
	},

	load_json: function (url, data, callback) {
		new Request.JSON({
			url: url,
			data: data,
			onSuccess: function (json) {
				if (json.success) {
					this.popup_content.set('html', json.data);
				} else {
					this.popup_content.set('html', json.message);
				}

				if (typeOf(callback) === 'function') {
					callback.call(this);
				}
			}.bind(this),
			onFailure: function (text) {
				this.popup_content.set('html', text.responseText);
				if (typeOf(callback) === 'function') {
					callback.call(this);
				}
			}.bind(this)
		}).send();

		return this;
	},

	responsive: function () {
		var width = window.getSize().x;

		if (width >= 950) {
			this.popup.set('style', null);
		} else if (width > 650 && width < 950) {
			this.popup.set('styles', {
				width: 600,
				marginLeft: -300,
				left: '50%'
			});
		} else {
			this.popup.set('styles', {
				width: '100%',
				marginLeft: 0,
				left: 0
			});
		}

		return this;
	}
};