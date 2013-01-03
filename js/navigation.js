(function ($) {
    /**
     * Navigation module implementation.
     *
     * @author Florian Gaechter
     * @namespace Tc.Module
     * @class Navigation
     * @extends Tc.Module
     */
    Tc.Module.Navigation = Tc.Module.extend({

        logoutButton: null,

        init: function ($ctx, sandbox, modId) {
            // call base constructor
            this._super($ctx, sandbox, modId);

            $('.dropdown-toggle',$ctx).dropdown() ;

            $('a',$ctx).click(function(){
                var target = $(this).data('target');
                if(target != undefined){
                    window.location(target);
                    return false;
                }

            });
        },

        on: function (callback) {
            var $ctx = this.$ctx,
                self = this;

            self.logoutButton = $('.logout a', $ctx);

            self.logoutButton.on('click', function () {
                $.post('/?ZU_logout=true', function () {
                    window.location = '/';
                });
                return false;
            });

            callback();
        }
    });
})(Tc.$);