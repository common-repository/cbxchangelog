;(function ($) {
    'use strict';

    $(document).ready(function () {
        var cbxchangelog_awn_options = {
            labels: {
                tip: cbxchangelog_setting.awn_options.tip,
                info: cbxchangelog_setting.awn_options.info,
                success: cbxchangelog_setting.awn_options.success,
                warning: cbxchangelog_setting.awn_options.warning,
                alert: cbxchangelog_setting.awn_options.alert,
                async: cbxchangelog_setting.awn_options.async,
                confirm: cbxchangelog_setting.awn_options.confirm,
                confirmOk: cbxchangelog_setting.awn_options.confirmOk,
                confirmCancel: cbxchangelog_setting.awn_options.confirmCancel
            }
        };

        $('.setting-color-picker-wrapper').each(function (index, element) {
            //console.log(element);

            var $color_field_wrap = $(element);
            //console.log($color_field);
            var $color_field = $color_field_wrap.find('.setting-color-picker');
            var $color_field_fire = $color_field_wrap.find('.setting-color-picker-fire');

            var $current_color = $color_field_fire.data('current-color');
            //var $default_color = $color_field_fire.data('default-color');


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
                i18n: cbxchangelog_setting.pickr_i18n
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

                if (color !== null) {
                    $color_field_fire.data('current-color', color.toHEXA().toString());
                    $color_field.val(color.toHEXA().toString());
                } else {
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

        });


        //add chooser
        $('.selecttwo-select-wrapper').each(function (index, element) {
            $('.selecttwo-select').select2({
                placeholder: cbxchangelog_setting.please_select,
                allowClear: false,
                theme: 'default select2-container--cbx'
                //dropdownParent: $(element)
            });
        });


        var $setting_page = $('#cbxchangelog-setting');
        var $setting_nav = $setting_page.find('.setting-tabs-nav');
        var activetab = '';
        if (typeof (localStorage) !== 'undefined') {
            activetab = localStorage.getItem('cbxchangelogactivetab');
        }

        //if url has section id as hash then set it as active or override the current local storage value
        if (window.location.hash) {
            if ($(window.location.hash).hasClass('global_setting_group')) {
                activetab = window.location.hash;
                if (typeof (localStorage) !== 'undefined') {
                    localStorage.setItem('cbxchangelogactivetab', activetab);
                }
            }
        }


        function setting_nav_change($tab_id) {
            if ($tab_id === null) {
                return;
            }

            $tab_id = $tab_id.replace('#', '');

            $setting_nav.find('a').removeClass('active');
            $('#' + $tab_id + '-tab').addClass('active');


            var clicked_group = '#' + $tab_id;

            if (typeof (localStorage) !== 'undefined') {
                localStorage.setItem('cbxchangelogactivetab', clicked_group);
            }

            $('.global_setting_group').hide();
            $(clicked_group).fadeIn();

            //load the
            if(clicked_group === '#cbxchangelog_tools'){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxchangelog_setting.ajaxurl,
                    data: {
                        action: 'cbxchangelog_settings_reset_load',
                        security: cbxchangelog_setting.nonce
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        $('#cbxchangelog_resetinfo_wrap').html(data.html);
                    }//end of success
                });//end of ajax
            }
        }

        //click on inidividual nav
        $setting_nav.on('click', 'a', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $tab_id = $this.data('tabid');

            $('.setting-select-nav').val($tab_id);
            $('.setting-select-nav').trigger('change');
        });

        $('.setting-select-nav').on('change', function (e) {
            var $this = $(this);
            var $tab_id = $this.val();

            setting_nav_change($tab_id);
        });

        //set default
        if(activetab === null){
            activetab = $('.setting-tabs-nav').find('a.active').attr('href');
        }

        //console.log(activetab);

        if (activetab !== null) {
            var activetab_whash = activetab.replace('#', '');

            $('.setting-select-nav').val(activetab_whash);
            $('.setting-select-nav').trigger('change');
        }
        else{
            //console.log('hi there change');
        }


        $('.wpsa-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();

                self.prev('.wpsa-url').val(attachment.url);
            });

            // Finally, open the modal
            file_frame.open();
        });

        //make the subheading single row
        $('.setting_heading').each(function (index, element) {
            var $element = $(element);
            var $element_parent = $element.parent('td');
            $element_parent.attr('colspan', 2);
            $element_parent.prev('th').remove();
            $element_parent.parent('tr').removeAttr('class');
            $element_parent.parent('tr').addClass('global_setting_heading_section');
        });


        $('.setting_subheading').each(function (index, element) {
            var $element = $(element);
            var $element_parent = $element.parent('td');
            $element_parent.attr('colspan', 2);
            $element_parent.prev('th').remove();
            $element_parent.parent('tr').removeAttr('class');
            $element_parent.parent('tr').addClass('global_setting_subheading_section');
        });


        $('.global_setting_group').each(function (index, element) {
            var $element = $(element);

            $element.find('.submit_setting').removeClass('button-primary').addClass('primary');

            var $form_table = $element.find('.form-table');
            $form_table.prev('h2').remove();

            var $i = 0;
            $form_table.find('tr').each(function (index2, element) {
                var $tr = $(element);

                if (!$tr.hasClass('global_setting_heading_section')) {
                    $tr.addClass('global_setting_common_section');
                    $tr.addClass('global_setting_common_section_' + $i);
                } else {
                    $i++;
                    $tr.addClass('global_setting_heading_section_' + $i);
                    $tr.attr('data-counter', $i);
                    $tr.attr('data-is-closed', 0);
                }
            });

            $('#global_setting_group_actions').show();
            $('#global_setting_group_actions').on('click', '.global_setting_group_action', function (event) {
                event.preventDefault();

                $form_table.find('.setting_heading').trigger('click');
            });


            $form_table.on('click', '.setting_heading', function (evt) {
                evt.preventDefault();

                var $this = $(this);
                var $parent = $this.closest('.global_setting_heading_section');
                var $counter = Number($parent.data('counter'));
                var $is_closed = Number($parent.data('is-closed'));

                if ($is_closed === 0) {
                    $parent.data('is-closed', 1);
                    $parent.addClass('global_setting_heading_section_closed');
                    $('.global_setting_common_section_' + $counter).hide();
                } else {
                    $parent.data('is-closed', 0);
                    $parent.removeClass('global_setting_heading_section_closed');
                    $('.global_setting_common_section_' + $counter).show();
                }
            });
        });

        //var adjustment_photo;
        /*$('.multicheck_fields_sortable').sortable({
            vertical: true,
            handle: '.multicheck_field_handle',
            containerSelector: '.multicheck_fields',
            itemSelector: '.multicheck_field',
            placeholder: 'multicheck_field_placeholder'
        });*/


        $(".checkbox_fields_check_actions").on(
            "click",
            ".checkbox_fields_check_action_call",
            function (e) {
                e.preventDefault();

                var $this = $(this);
                $this
                    .parent()
                    .next(".checkbox_fields")
                    .find(":checkbox")
                    .prop("checked", true);
            }
        );

        $(".checkbox_fields_check_actions").on(
            "click",
            ".checkbox_fields_check_action_ucall",
            function (e) {
                e.preventDefault();

                var $this = $(this);
                $this
                    .parent()
                    .next(".checkbox_fields")
                    .find(":checkbox")
                    .prop("checked", false);
            }
        );

        $('.checkbox_fields_sortable').sortable({
            vertical: true,
            handle: '.checkbox_field_handle',
            containerSelector: '.checkbox_fields',
            itemSelector: '.checkbox_field',
            placeholder: 'checkbox_field_placeholder'
        });



        //one click save setting for the current tab
        $('#save_settings').on('click', function (e) {
            e.preventDefault();

            var $setting_nav = $('.setting-tabs-nav');

            var $current_tab = $setting_nav.find('.active');
            var $tab_id = $current_tab.data('tabid');
            $('#' + $tab_id).find('.submit_setting').trigger('click');
        });

        $("#cbxchangelog_resetinfo_wrap").on(
            "click",
            ".cbxchangelog_setting_options_check_action_call",
            function (e) {
                e.preventDefault();

                var $this = $(this);
                $("#cbxchangelog_resetinfo_wrap").find(":checkbox").prop("checked", true);
            }
        );

        $("#cbxchangelog_resetinfo_wrap").on(
            "click",
            ".cbxchangelog_setting_options_check_action_ucall",
            function (e) {
                e.preventDefault();

                var $this = $(this);
                $("#cbxchangelog_resetinfo_wrap").find(":checkbox").prop("checked", false);
            }
        );


        //reset click
        $('#reset_data_trigger').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);

            var notifier = new AWN(cbxchangelog_awn_options);

            var onCancel = () => {
            };

            var onOk = () => {
                $this.attr('disabled', true);


                $this.hide();


                let  $reset_tables = $('.reset_tables:checkbox:checked').map(function() {
                    return this.value;
                }).get();

                let  $reset_options = $('.reset_options:checkbox:checked').map(function() {
                    return this.value;
                }).get();

                //send ajax request to reset plugin
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxchangelog_setting.ajaxurl,
                    data: {
                        action: 'cbxchangelog_settings_reset',
                        security: cbxchangelog_setting.nonce,
                        reset_tables : $reset_tables,
                        reset_options : $reset_options
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        window.location.href = data.url;

                    }//end of success
                });//end of ajax
            };

            notifier.confirm(
                cbxchangelog_setting.are_you_sure_delete_desc,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxchangelog_setting.are_you_sure_global
                    }
                }
            );
        });//end click #reset_data_trigger
    });
})(jQuery);
//settings