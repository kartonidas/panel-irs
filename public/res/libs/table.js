(function($) {
	var AjaxTable = function(element, option) {
		var defaults = {};
		this.option = $.extend({}, defaults, option);
		this.$element = $(element);
		this.$table = $(element).find("TABLE TBODY");
		this.$pagination = $(element).find("DIV.ajax-pagination");
        
        if (this.option.init != undefined && this.option.init) {
            this.init();
        }
	};

	AjaxTable.prototype = {
		init: function() {
			this.getData();
		},
		refresh: function() {
			this.getData();
		},
        export: function(button) {
            $(button).addClass("disabled");
            
			data = {};
    		data.params = this.$element.attr("data-params");
            data.sort = this.$element.attr("data-sort") + "," + this.$element.attr("data-order");
            
            var url = this.$element.attr("data-export-url");
            if (url == undefined || !url) {
                console.log("Empty export URL!");
                return false;
            }
            
			if(this.$element.attr("data-filter-form") != undefined)
			{
				var filterForm = this.$element.attr("data-filter-form");
				if($("FORM#" + filterForm).length)
					data.filterquery = $("FORM#" + filterForm).serialize();
			}

			$.ajax({
    			headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                dataType: "json",
                type: "get",
                data: data,
                success: function(ret) {
                	if(ret.url != undefined && ret.url)
                        window.location.href = ret.url;
                        
                    $(button).removeClass("disabled");
                },
                error: function() {
                    $(button).removeClass("disabled");
                }
			});
		},
		getData: function() {
			this.$element.addClass("loading");

			var $this = this;

			data = {};
    		data.page = this.$element.attr("data-page");
    		data.toprecords = this.$element.attr("data-toprecords");
    		data.params = this.$element.attr("data-params");
            data.sort = this.$element.attr("data-sort") + "," + this.$element.attr("data-order");
            
            var url = this.$element.attr("data-url");
            if (url == undefined || !url) {
                console.log("Empty source URL!");
                return false;
            }

			var callback = this.$element.attr("data-callback");
            var beforeCallback = this.$element.attr("data-before-callback");
            
            if(beforeCallback != undefined)
			{
                var tmp = beforeCallback.split(".");
                var cObject = tmp[0];
                var cFunction = tmp[1];
                
                if(typeof window[cObject][cFunction] == "function")
				    window[cObject][cFunction]($this.$table);
            }

			if(this.$element.attr("data-filter-form") != undefined)
			{
				var filterForm = this.$element.attr("data-filter-form");
				if($("FORM#" + filterForm).length)
					data.filterquery = $("FORM#" + filterForm).serialize();
			}

			$.ajax({
    			headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                dataType: "json",
                type: "get",
                data: data,
                success: function(ret) {
                	if(ret.table != undefined)
                	{
                		$this.$table.html(ret.table);
                		if(ret.paginator != undefined)
                			$this.$pagination.html(ret.paginator);
            			$this.$element.removeClass("loading");

						if(callback != undefined)
				        {
				            var tmp = callback.split(".");
				            var cObject = tmp[0];
				            var cFunction = tmp[1];

				            if(typeof window[cObject][cFunction] == "function")
				                window[cObject][cFunction](ret, $this.$table);
				        }
                	}
                }
			});
		}
	}

	$.fn.ajaxTable = function(option) {
		var arg = arguments, options = typeof option == "object" && option;

		return this.each(function() {
			var $this = $(this), data = $this.data('ajaxTable');

			if (!data) $this.data('ajaxTable', (data = new AjaxTable(this, options)));
			if (typeof option === 'string') {
				if (arg.length > 1)
					data[option].apply(data, Array.prototype.slice.call(arg, 1));
				else
					data[option]();
			}
		});
	};
})(jQuery);