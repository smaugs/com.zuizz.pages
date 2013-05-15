(function ($) {
    /**
     * Navigation module implementation.
     *
     * @author Florian Gaechter, Veith Zäch
     * @namespace Tc.Module
     * @class Navigation
     * @extends Tc.Module
     */
    Tc.Module.Navigation = Tc.Module.extend({

        logoutButton: null,

        init: function ($ctx, sandbox, modId) {
            // call base constructor
            this._super($ctx, sandbox, modId);

            // dropdown aktivieren
            $('.dropdown-toggle', $ctx).dropdown();



            this.registerListener(sandbox);
        },
        registerListener: function (sandbox) {
            var $ctx = this.$ctx,
                self = this;

            // wenn targets definiert sind, über diese per js navigieren (ios)
            $('a', $ctx).click(function () {
                var target = $(this).data('target-url');

                if (target != undefined) {
                    window.location = target;
                    return false;
                }
            });

            self.logoutButton = $('.logout a', $ctx);
            self.logoutButton.on('click', function () {
                $.post('/?ZU_logout=true', function () {
                    window.location = '/';
                });
                return false;
            });

        },
        on: function (callback) {


            callback();
        }
    });
})(Tc.$);