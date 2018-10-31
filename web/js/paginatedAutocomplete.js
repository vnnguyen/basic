$.widget("custom.paginatedAutocomplete", $.ui.autocomplete, {
    options: {
        minLength: 2,
        sourceUrl: '',
        pageSize: 10,
        source: function(request, response) {
            var self = this;
            $.ajax({
                url: this.options.sourceUrl,
                type: "GET",
                dataType: "json",
                data: {
                    per: self.options.pageSize,
                    term: request.term,
                    page: self._pageIndex
                },
                success: function(data) {
                    var items = data.data;
                    self._totalItems = data.total;
                    // Create a menu item for each of the items on the page.
                    response($.map(items, function(item) {
                        return {
                            label: item.text,
                            value: item.value
                        }
                    }));
                }
            });
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        }
    },
    search: function(value, event) {
        // Start a fresh search; Hide pagination panel and reset to 1st page.
        this._pageIndex = 0;
        $.ui.autocomplete.prototype.search.call(this, value, event);
    },
    _renderItem: function(ul, item) {

        return $("<li>").attr("data-value", item.value).attr("data-id", item.id).data("item.autocomplete", item).append(item.html).appendTo(ul);
    },
    select: function(item) {
        var self = this;
        // Apply the item's label to the autocomplete textbox.
        this._value(item.label);
        // Keep track of the selected item.
        self._previousSelectedItem = item;
    },
    close: function(event) {
        // Close the pagination panel when the selection pop-up is closed.
        // this._paginationContainerElement.css('display', 'none');
        // $.ui.autocomplete.prototype.close.call(this, event);
    },
    _previousSelectedItem: null,
    _totalPages: null,
    _totalItems: null,
    _pageIndex: 0,
    _create: function() {
        var self = this;
        // Create the DOM structure to support the paginated autocomplete.
        this.element.after("<div class='ui-autocomplete-pagination-results'></div>");
        this._resultsContainerElement = this.element.next("div.ui-autocomplete-pagination-results");
        this._resultsContainerElement.append("<div style='display:none; padding-top: 5px; padding-left: 5px; position: relative; min-width:320px; z-index: 101; left: 0px !important; top: -30px !important' class='ui-autocomplete-pagination-container'>" + "<button type='button' class='previous-page' style='width: 25px; height: 25px;float: left;margin-right: 4px; padding-left: 5px;'><i class='fas fa-angle-left' style='font-size: 18px;'></i></button>" + "<button type='button' class='next-page' style='width: 25px; height: 25px;float: left;margin-right: 4px;padding-left: 5px;'><i class='fas fa-angle-right' style='font-size: 18px;'></i></button>" + "<div style='float:left; width:65%;' class='ui-autocomplete-pagination-details'>" + "Showing 1-10 of 1000 items.</div>" + "</div>");
        this._paginationContainerElement = this._resultsContainerElement.children("div.ui-autocomplete-pagination-container");
        this._nextPageElement = this._paginationContainerElement.find("button.next-page");
        this._previousPageElement = this._paginationContainerElement.find("button.previous-page");
        this._paginationDetailsElement = this._paginationContainerElement.find("div.ui-autocomplete-pagination-details");
        this._nextPageElement.button({
            text: false,
            icons: {
                primary: "fas fa-arrow-right"
            }
        });
        this._previousPageElement.button({
            text: false,
            icons: {
                primary: "fas fa-arrow-left"
            }
        });
        // Append the menu items (and related content) to the specified element.
        if (this.options.appendTo !== null) {
            this._paginationContainerElement.appendTo(this._resultsContainerElement);
            this._resultsContainerElement.appendTo(this.options.appendTo);
            this.options.appendTo = this._resultsContainerElement;
        } else {
            this.options.appendTo = this._resultsContainerElement;
        }
        // Hide default JQuery Autocomplete details (want to use our own blurb).
        $(this.element).next("span.ui-helper-hidden-accessible").css("display", "none");
        // Event handler(s) for the next/previous pagination buttons.
        this._on(this._nextPageElement, {
            click: this._nextPage
        });
        this._on(this._previousPageElement, {
            click: this._previousPage
        });
        // Event handler(s) for the autocomplete textbox.
        this._on(this.element, {
            blur: function(event) {
                // When losing focus hide the pagination panel
                this._pageIndex = 0;
            },
            paginatedautocompleteopen: function(event) {
                // Autocomplete menu is now visible.
                // Update pagination information.
                var self = this,
                    paginationFrom = null,
                    paginationTo = null,
                    menuOffset = this.menu.element.offset();
                self._totalPages = Math.ceil(self._totalItems / self.options.pageSize);
                paginationFrom = self._pageIndex * self.options.pageSize + 1;
                paginationTo = ((self._pageIndex * self.options.pageSize) + self.options.pageSize);
                if (this._paginationContainerElement.find('#wrap_page').length > 0) {
                    var wrap = this._paginationContainerElement.find('#wrap_page');
                    $(wrap).empty();
                } else {
                    $('<div id="wrap_page">').insertAfter(this._previousPageElement)
                }
                for (var i = 1; i <= this._totalPages  ; i++) {
                    if (this._paginationContainerElement.find('.page-'+i) .length == 0) {
                        var btn_html = '<button data-page="'+i+'" type="button" class="page-'+i+'" style="width: 25px; height: 25px;float: left;margin-right: 4px; padding-left: 3px;">'+i+'</button>';
                        $('#wrap_page').append($(btn_html));
                        self._on($('.page-'+i), {
                            click: this._toPage
                        });
                    }

                }
                if (paginationTo > self._totalItems) {
                    paginationTo = self._totalItems;
                }
                // Align the pagination container with the menu.
                this._paginationContainerElement.offset({
                    top: menuOffset.top,
                    left: menuOffset.left
                });
                // Modify the list generated by the autocomplete so that it appears below the pagination controls.
                this._resultsContainerElement.find("ul").prependTo(".ui-autocomplete-pagination-results").css({
                    "padding-top": "60px",
                    "min-width": "300px",
                    "z-index": "100"
                });
                this._paginationDetailsElement.html("Showing " + paginationFrom.toString() + " to " + paginationTo.toString() + " of " + self._totalItems.toString() + " items.");
            }
        });
        // Event handler(s) for the pagination panel.
        this._on(this._paginationContainerElement, {
            mousedown: function(event) {
                // The following will prevent the pagination panel and selection menu from losing focus (and disappearing).
                // Prevent moving focus out of the text field
                event.preventDefault();
                // IE doesn't prevent moving focus even with event.preventDefault()
                // so we set a flag to know when we should ignore the blur event
                this.cancelBlur = true;
                this._delay(function() {
                    delete this.cancelBlur;
                });
            }
        });
        // Now we're going to let the default _create() to do it's thing. This will create the autocomplete pop-up selection menu.
        $.ui.autocomplete.prototype._create.call(this);
        // Event handler(s) for the autocomplete pop-up selection menu.
        this._on(this.menu.element, {
            menuselect: function(event, ui) {
                var item = ui.item.data("ui-autocomplete-item"); // Get the selected item.
                this.select(item);;
            }
        });
    },
    _toPage: function(e) {
        console.log($(e.currentTarget).data('page'));
        this._pageIndex = $(e.currentTarget).data('page') - 1;
        $.ui.autocomplete.prototype._search.call(this, this.term);
    },
    _nextPage: function(event) {
        if (this._pageIndex < this._totalPages - 1) {
            this._pageIndex++;
            $.ui.autocomplete.prototype._search.call(this, this.term);
        }
    },
    _previousPage: function(event) {
        if (this._pageIndex > 0) {
            this._pageIndex--;
            $.ui.autocomplete.prototype._search.call(this, this.term);
        }
    },
    _change: function(event) {
        // Clear the textbox if an item wasn't selected from the menu.
        if (((this.selectedItem === null) && (this._previousSelectedItem === null)) || (this.selectedItem === null) && (this._previousSelectedItem !== null) && (this._previousSelectedItem.label !== this._value())) {
            // Clear values.
            // this._value("");
            this._paginationContainerElement.css('display', 'none');
            $.ui.autocomplete.prototype.close.call(this, event);
        }
        $.ui.autocomplete.prototype._change.call(this, event);
        // Clear the textbox and closes the menu if an item wasn't selected from the menu but was clicked somewhere.
    },
    _destroy: function() {
        this._paginationContainerElement.remove();
        this._resultsContainerElement.remove();
        $.ui.autocomplete.prototype._destroy.call(this);
    },
    __response: function(content) {
        var self = this;
        self._totalItemsOnPage = content.length;
        self._paginationContainerElement.css('display', self._totalItemsOnPage > 0 ? 'block' : 'none');
        $.ui.autocomplete.prototype.__response.call(this, content);
    }
});
$.widget.bridge("paginatedAutocomplete", $.custom.paginatedAutocomplete);