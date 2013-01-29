var EVUI = {
    init: function() {
        Backbone.View.prototype.close = function() {
            if (this.beforeClose) {
                this.beforeClose();
            }

            this.remove();
            this.unbind();
        };

		Backbone.Router.prototype.showView = function (selector, view) {
	        if (this.currentView)
				this.currentView.close();
			jQuery(selector).html(view.render().el);
			this.currentView = view;
			return view;
		};

		Backbone.Model.prototype.parse = function(response) {
			if (response.hasOwnProperty('object'))
				return response.object;
			else
				return response;
		};

		Backbone.Collection.prototype.parse = function(response) {
			if (response.hasOwnProperty('objects'))
				return response.objects;
			else
				return response;
		};
    },

    tpl: {
        // Hash of preloaded templates for the app
        templates: {},

        // Recursively pre-load all the templates for the app.
        // this implementation should be changed in a production environment.
        // All the template files should be concatenated in a single file.
        loadTemplates: function(names, callback) {
            var that = this;

            var loadTemplate = function(index) {
                var name = names[index];
                jQuery.get(EVUIEnv.tpl_dir + name + '.html', function(data) {
                    that.templates[name] = data;
                    index++;
                    if (index < names.length) {
                        loadTemplate(index);
                    } else {
                        callback();
                    }
                });
            }

            loadTemplate(0);
        },

        get: function(name) {
            return this.templates[name];
        }
    }
};

EVUI.init();
