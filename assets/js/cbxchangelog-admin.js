(function ($) {
    'use strict';

    function cbxchangelog_copyStringToClipboard(str) {
        // Create new element
        var el   = document.createElement('textarea');
        // Set value (string to be copied)
        el.value = str;
        // Set non-editable to avoid focus and move outside of view
        el.setAttribute('readonly', '');
        el.style = {position: 'absolute', left: '-9999px'};
        document.body.appendChild(el);
        // Select text inside element
        el.select();
        // Copy text to clipboard
        document.execCommand('copy');
        // Remove temporary element
        document.body.removeChild(el);
    }

    $(document.body).ready(function ($) {

        //console.log('hi there');


        var awn_options = {
            labels: {
                tip          : cbxchangelog_admin.awn_options.tip,
                info         : cbxchangelog_admin.awn_options.info,
                success      : cbxchangelog_admin.awn_options.success,
                warning      : cbxchangelog_admin.awn_options.warning,
                alert        : cbxchangelog_admin.awn_options.alert,
                async        : cbxchangelog_admin.awn_options.async,
                confirm      : cbxchangelog_admin.awn_options.confirm,
                confirmOk    : cbxchangelog_admin.awn_options.confirmOk,
                confirmCancel: cbxchangelog_admin.awn_options.confirmCancel
            }
        };

        //click to copy shortcode
        $('.cbxballon_ctp').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            cbxchangelog_copyStringToClipboard($this.prev('.cbxshortcode').text());

            $this.attr('aria-label', cbxchangelog_admin.copycmds.copied_tip);

            window.setTimeout(function () {
                $this.attr('aria-label', cbxchangelog_admin.copycmds.copy_tip);
            }, 1000);
        });


        //$('.cbxchangelog_colorpicker').wpColorPicker();

        /*$('.setting-color-picker-wrapper').each(function (index, element){
          var $color_field_wrap = $(element);
          var $color_field = $color_field_wrap.find('.setting-color-picker');
          var $color_field_fire = $color_field_wrap.find('.setting-color-picker-fire');

          var $current_color = $color_field_fire.data('current-color');

          // Simple example, see optional options for more configuration.
          const pickr = Pickr.create({
            el: $color_field_fire[0],
            theme: 'classic', // or 'monolith', or 'nano'
            default: $current_color,

            swatches: [
              'rgba(244, 67, 54, 1)',
              'rgba(233, 30, 99, 0.95)',
              'rgba(156, 39, 176, 0.9)',
              'rgba(103, 58, 183, 0.85)',
              'rgba(63, 81, 181, 0.8)',
              'rgba(33, 150, 243, 0.75)',
              'rgba(3, 169, 244, 0.7)',
              'rgba(0, 188, 212, 0.7)',
              'rgba(0, 150, 136, 0.75)',
              'rgba(76, 175, 80, 0.8)',
              'rgba(139, 195, 74, 0.85)',
              'rgba(205, 220, 57, 0.9)',
              'rgba(255, 235, 59, 0.95)',
              'rgba(255, 193, 7, 1)'
            ],

            components: {

              // Main components
              preview: true,
              opacity: true,
              hue: true,

              // Input / output Options
              interaction: {
                hex: true,
                rgba: false,
                hsla: false,
                hsva: false,
                cmyk: false,
                input: true,
                clear: true,
                save: true
              }
            },
            i18n: cbxchangelog_admin.pickr_i18n
          });

          pickr.on('init', instance => {
            //console.log('Event: "init"', instance);
          }).on('hide', instance => {
            //console.log('Event: "hide"', instance);
          }).on('show', (color, instance) => {
            //console.log('Event: "show"', color, instance);
          }).on('save', (color, instance) => {
            //console.log(color.toHEXA().toString());
            //console.log(color);

            if(color !== null){
              $color_field_fire.data('current-color', color.toHEXA().toString());
              $color_field.val(color.toHEXA().toString());
            }
            else{
              $color_field_fire.data('current-color', '');
              $color_field.val('');
            }


            //console.log(instance);
            //console.log(color.toHEXA());
            //console.log(color.toHEX);
          }).on('clear', instance => {
            //console.log('Event: "clear"', instance);



          }).on('change', (color, source, instance) => {
            //console.log('Event: "change"', color, source, instance);

          }).on('changestop', (source, instance) => {
            //console.log('Event: "changestop"', source, instance);
          }).on('cancel', instance => {
            //console.log('Event: "cancel"', instance);
          }).on('swatchselect', (color, instance) => {
            //console.log('Event: "swatchselect"', color, instance);
          });

        });*/

        //attach datepicker
        $('#cbxchangelog_wrapper').on('click', '.cbxchangelog_datepicker', function (e) {
            //$(this).datetimepicker({
            $(this).datepicker({
                dateFormat: 'yy-mm-dd',
                //timeFormat:"HH:mm:ss",
                showOn: 'focus'
            }).focus();
        });


        $('#cbxchangelog_wrapper').on('click', '.toggle-release', function (e) {
            e.preventDefault();

            var $this = $(this);
            $this.toggleClass('cbx-icon-up');
            $this.toggleClass('cbx-icon-down');
            var $parent = $this.closest('.cbxchangelog_release');
            $parent.find('.release-content').toggle();
        });

        //sorting, add/remove etc


        var $release_template = $('#release_template').html();
        var $feature_template = $('#feature_template').html();
        Mustache.parse($release_template);   // optional, speeds up future uses
        Mustache.parse($feature_template);   // optional, speeds up future uses

        //sorting single feature
        $('#cbxchangelog_wrapper .release-feature-wrap').each(function (index, element) {

            var $element = $(element);

            //sort sponsor item
            $element.sortable({
                group            : 'feature_wrap_' + index,
                nested           : false,
                vertical         : true,
                horizontal       : false,
                pullPlaceholder  : true,
                handle           : '.move-feature',
                placeholder      : 'feature_placeholder',
                itemSelector     : 'p.feature',
                containerSelector: $element,
            });

        });

        $('#cbxchangelog_metabox').on('click', 'a.cbxchangelog_add_release', function (e) {
            e.preventDefault();

            var $this    = $(this);
            var $counter = Number($this.attr('data-counter'));

            var rendered = Mustache.render($release_template, {increment: $counter, incrementplus: ($counter + 1)});
            $('#cbxchangelog_wrapper').prepend(rendered);

            $counter++;
            $this.attr('data-counter', $counter);

            //sorting single feature
            $('#cbxchangelog_wrapper .release-feature-wrap').each(function (index, element) {

                var $element = $(element);

                //console.log($element);

                //sort sponsor item
                $element.sortable({
                    group            : 'feature_wrap_' + index,
                    nested           : false,
                    vertical         : true,
                    horizontal       : false,
                    pullPlaceholder  : true,
                    handle           : '.move-feature',
                    placeholder      : 'feature_placeholder',
                    itemSelector     : 'p.feature',
                    containerSelector: $element,
                });

            });

        });

        //remove any release
        $('#cbxchangelog_wrapper').on('click', '.trash-release', function (e) {
            e.preventDefault();

            var $this = $(this);

            var notifier = new AWN(awn_options);

            var onCancel = () => {
            };

            var onOk = () => {

                $this.closest('.cbxchangelog_release').fadeOut('slow', function () {
                    $(this).remove();
                });
            };

            notifier.confirm(
                cbxchangelog_admin.deleteconfirm_desc,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxchangelog_admin.deleteconfirm
                    }
                }
            );

        });

        //add new feature
        $('#cbxchangelog_wrapper').on('click', '.add-feature', function (e) {
            e.preventDefault();

            var $this    = $(this);
            var $parent  = $this.parents('.release-feature-wrap');
            var $counter = $parent.data('boxincrement');

            var rendered = Mustache.render($feature_template, {increment: $counter});
            $parent.prepend(rendered);

        });

        //remove any feature
        $('#cbxchangelog_wrapper').on('click', '.trash-feature', function (e) {
            e.preventDefault();

            var $this = $(this);

            var $parent_feature_wrap = $this.closest('.release-feature-wrap');

            if ($parent_feature_wrap.find('.feature').length > 1) {
                //delete if there are more than one features, otherwise edit or delete the total release.

                var notifier = new AWN(awn_options);

                var onCancel = () => {
                };

                var onOk = () => {

                    $this.closest('.feature').fadeOut('slow', function () {
                        $(this).remove();
                    });
                };

                notifier.confirm(
                    cbxchangelog_admin.deleteconfirm_desc,
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: cbxchangelog_admin.deleteconfirm
                        }
                    }
                );


            } else {

                new AWN(awn_options).alert(cbxchangelog_admin.deletelastitem);
            }


        });

        //sorting releases
        $('#cbxchangelog_wrapper').sortable({
            group            : 'cbxchangelog_releases',
            nested           : false,
            vertical         : true,
            horizontal       : false,
            pullPlaceholder  : true,
            handle           : '.move-release',
            placeholder      : 'cbxchangelog_release_placeholder',
            itemSelector     : 'div.cbxchangelog_release',
            containerSelector: 'div#cbxchangelog_wrapper'
        });

    });

})(jQuery);
