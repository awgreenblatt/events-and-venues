window.Venue = Backbone.Model.extend({
	urlRoot: "/rest/venues/",

    defaults: {
		"id": null,
		"name": null,
		"address1": null,
		"address2": null,
		"city": null,
		"state": null,
		"zip": null
	},

	validate: function(attrs, options) {
		/*
		 * This is used when validating a response from the server.  If it returns an error,
		 * create a Backbone validationError with the first error.
		 */
		if (attrs.hasOwnProperty('status') && attrs.status === 'ERROR') {
			for (var err_type in attrs.errors) {
				return attrs.errors[err_type][0];
			}
		}
	},

	url: function() {
		/*
		 * Override the default method to ensure that we always end with a slash.
		 * When specifying an individual model with an id, by default it won't have a slash at the end.
		 */
		var u = Backbone.Model.prototype.url.apply(this);
		if (u.charAt(u.length-1) != '/')
			u += '/';
		return u;
    },
});

window.VenueCollection = Backbone.Collection.extend({
	model: Venue,
    url: "/rest/venues/",
});

