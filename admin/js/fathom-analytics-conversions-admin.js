(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(window).load(function () {
        $('#nf-drawer-content').on('DOMSubtreeModified', function () {
            $("input#fathom_analytics").prop("readonly", true);
        });
    });

    $(function () {
        let _site_id = $('#fac4wp-options_fac-site-id');
        $('.installed_tc_elsewhere input').on('change', function () {
            if (this.checked) {
                _site_id.prop('readonly', false);
            } else _site_id.prop('readonly', true);
        });
    });
})(jQuery);
