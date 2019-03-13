/*
 * Scripts for the Shortcodes controller.
 */
+function ($) { "use strict";

    var BlogShortcodes = function () {

        var defaultOptions = {
            size: 'large'
        };

        this.clickRecord = function (recordId) {
            var newPopup = $('<a />');

            newPopup.popup($.extend({}, defaultOptions, {
                handler: 'onUpdateForm',
                extraData: {
                    'record_id': recordId
                }
            }));
        };

        this.createRecord = function () {
            var newPopup = $('<a />');
            newPopup.popup($.extend({},defaultOptions, {
                handler: 'onCreateForm'
            }));
        };

    };

    $.blogShortcodes = new BlogShortcodes;

}(window.jQuery);