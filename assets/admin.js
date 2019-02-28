jQuery(document).ready(function($) {

    console.log(ncam_options);
    var $clean_admin_menu = $('<input name="'+ ncam_options.option_name +'" type="hidden" />');

    // Calc marked eyes
    function recordResult() {
        menu_disabled = {
            menu: [],
            sub_menu: []
        };

        $('#adminmenu > li > span.after').each(function(index, el) {
            if( $(this).hasClass('hide') ) {
                menu_disabled.menu.push( $(this).parent().children('a').attr('href') );
            }
        });

        $('#adminmenu>li>ul span.after').each(function(index, el) {
            if( $(this).hasClass('hide') ) {
                var parent = $(this).parent().parent().parent().children('a').attr('href');
                var obj = $(this).parent().children('a').attr('href');

                menu_disabled.sub_menu.push({parent: parent, obj: obj});
            }
        });

        $clean_admin_menu.val( JSON.stringify(menu_disabled) );
    }

    function deleteEyes() {
        $('#adminmenu li').each(function() {
            $(this).find('.dashicons-hidden').remove();
        });
    }

    function drowEyes() {
        $('#adminmenu li').each(function() {
            if( 'collapse-menu' !== $(this).attr('id') &&
                ! $(this).hasClass( 'wp-menu-separator' ) &&
                ! $(this).hasClass( 'hide-if-no-customize' ) )
            {
                $(this).append( '<span class="after dashicons dashicons-hidden"></span>' );
            }
        });
    }

    function fillEyes() {
        $.each(ncam_options.menu, function(index, val) {
            $('a[href="'+val+'"]:first').parent().children('.after').addClass('hide');
        });

        $.each(ncam_options.sub_menu, function(index, val) {
            $('a[href="'+val.obj+'"]:last').parent().children('.after').addClass('hide');
        });
    }

    $('.factory-control-clean_admin_menu_value input[type="checkbox"]').on('change', function (e) {
        if( $(this).is(':checked') ) {
            drowEyes();
            fillEyes();
        }
        else {
            deleteEyes();
        }
    }).trigger('change');

    $('.wbcr-factory-content').find('form').append($clean_admin_menu);

    $('#adminmenu').on('click', 'span.after', function() {
        $(this).toggleClass('hide');

        recordResult();
        return false;
    });

    recordResult();
});
