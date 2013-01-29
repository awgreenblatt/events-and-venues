window.VenueListView = Backbone.View.extend({
	tagName: 'ul',

	events: {
		"click .new": "newVenue"
	},

	initialize:function() {
		this.template = _.template(EVUI.tpl.get('venue-list-head'));
        this.model.bind("reset", this.render, this);
        var that = this;
        this.model.bind("add", function(venue) {
            jQuery(that.el).append(new VenueListItemView({model: venue}).render().el);
        });
    },

    render: function(eventName) {
        this.$el.html(this.template());
        _.each(this.model.models, function(venue) {
            this.$el.append(new VenueListItemView({model:venue}).render().el);
        }, this);
        return this;
    },

	newVenue: function() {
		app.navigate("venues/new", true, true);
	}
});

window.VenueListItemView = Backbone.View.extend({
	tagName: 'li',

    initialize: function() {
        this.template = _.template(EVUI.tpl.get('venue-list-item'));
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },

    render: function(eventName) {
        jQuery(this.el).html(this.template(this.model.toJSON()));
        return this;
    }
});
