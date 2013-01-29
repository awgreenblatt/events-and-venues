var AppRouter = Backbone.Router.extend({
	routes: {
		"": "listVenues",
		"venues/new": "newVenue",
		"venues/:id": "venueDetails"
	},

	initialize: function() {
	},

	listVenues: function(callback) {
		if (!this.venueList) {
			this.venueList = new VenueCollection();
			var that = this;
			this.venueList.fetch({
				success: function() {
					jQuery('.ev-venues-list').html(new VenueListView({model: that.venueList}).render().el);
					if (callback) callback();
				}
			});
		} else {
			if (callback) callback();
		}
	},

	newVenue: function() {
		var that = this;
		this.listVenues(function() {
			that.showView('.ev-venue-details', new VenueDetailsView({model: new Venue()}));
		});
	},

	venueDetails: function(id) {
		var that = this;

		this.listVenues(function() {
			var venue = that.venueList.get(id);
			if (venue != null) {
				that.showView('.ev-venue-details', new VenueDetailsView({model: venue}));
			}
		});
	}
});

jQuery(document).ready(function() {
	EVUI.tpl.loadTemplates(['venue-list-head', 'venue-list-item', 'venue-details'], function() {
		app = new AppRouter();
		Backbone.history.start();
	});
});
