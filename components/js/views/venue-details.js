window.VenueDetailsView = Backbone.View.extend({
	initialize: function() {
		this.template = _.template(EVUI.tpl.get('venue-details'));
		this.model.bind("change", this.render, this);
	},

	render: function(eventName) {
		jQuery(this.el).html(this.template(this.model.toJSON()));
		return this;
	},

	events: {
		"click .save": "saveVenue",
		"click .delete": "deleteVenue",
		"click .close": "closeVenue"
	},

	saveVenue: function() {
		this.model.on("invalid", function(model, error) {
			alert(error);
		});

		this.model.set({
			name: jQuery('#venue_name').val(),
			address1: jQuery('#venue_address1').val(),
			address2: jQuery('#venue_address2').val(),
			city: jQuery('#venue_city').val(),
			state: jQuery('#venue_state').val(),
			zip: jQuery('#venue_zip').val()
		});

		if (this.model.isNew()) {
			var that = this;
			app.venueList.create(this.model, {
				success: function(model, response) {
					app.navigate('venues/' + model.id, {trigger: false});
				},
				wait: true
			});
		} else {
			this.model.save(null, {wait: true});
		}

		return false;
	},

	closeVenue: function() {
		app.navigate('', {trigger: false});
		this.close();
	},

	deleteVenue: function() {
		var that = this;

		jQuery.getJSON('/rest/venues/user_can/', { "cap": "delete_venues" },
			function(data, status, xhr) {
				var can_delete = data.msg;
				if (can_delete) {
					that.model.destroy({
						success: function() {
							app.navigate('', {trigger: false});
							that.close();
						}
					});
				} else {
					alert('You do not have permission to delete venues');
				}
			});
		return false;
	}
});
