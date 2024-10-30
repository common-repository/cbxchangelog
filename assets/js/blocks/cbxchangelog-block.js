'use strict';

(function (blocks, element, components, editor, ServerSideRender, blockEditor) {
    var el                = element.createElement,
        registerBlockType = blocks.registerBlockType,

        //InspectorControls = editor.InspectorControls,
        InspectorControls = blockEditor.InspectorControls,

        //ServerSideRender  = components.ServerSideRender,

        RangeControl      = components.RangeControl,
        Panel             = components.Panel,
        PanelBody         = components.PanelBody,
        PanelRow          = components.PanelRow,
        TextControl       = components.TextControl,
        //NumberControl     = components.NumberControl,
        TextareaControl   = components.TextareaControl,
        CheckboxControl   = components.CheckboxControl,
        RadioControl      = components.RadioControl,
        SelectControl     = components.SelectControl,
        ToggleControl     = components.ToggleControl,
        //ColorPicker = components.ColorPalette,
        //ColorPicker = components.ColorPicker,
        //ColorPicker = components.ColorIndicator,
        PanelColorPicker  = editor.PanelColorSettings,
        DateTimePicker    = components.DateTimePicker,
        HorizontalRule    = components.HorizontalRule,
        ExternalLink      = components.ExternalLink;

    //var MediaUpload = wp.editor.MediaUpload;

    var iconEl = el('svg', {width: 24, height: 24},
        el('path', {
            fill: "#212120", d: "M22.9,3.2H8.1C7.5,3.2,7,2.4,7,1.6C7,0.7,7.5,0,8.1,0h14.8C23.5,0,24,0.7,24,1.6\n" +
                "\t\tC24,2.4,23.5,3.2,22.9,3.2L22.9,3.2z"
        }),
        el('path', {
            fill: "#212120",
            d: "M22.9,16.7H8.1C7.5,16.7,7,16,7,15.1c0-0.8,0.5-1.6,1.1-1.6h14.8c0.6,0,1.1,0.7,1.1,1.6\n" +
                "\t\t\tC24,15.9,23.5,16.7,22.9,16.7L22.9,16.7z"
        }),
        el('path', {
            fill: "#212120", d: "M14,22.8V7.2C14,6.6,14.7,6,15.5,6C16.3,6,17,6.5,17,7.2v15.6c0,0.6-0.7,1.2-1.5,1.2\n" +
                "\t\t\tC14.7,24,14,23.5,14,22.8L14,22.8z"
        }),

        el('path', {fill: "#212120", d: "M0,2.1C0,0.9,0.9,0,2,0s2,0.9,2,2.1S3.1,4.2,2,4.2S0,3.3,0,2.1z"}),
        el('path', {fill: "#212120", d: "M0,12c0-1.2,0.9-2.1,2-2.1s2,0.9,2,2.1s-0.9,2.1-2,2.1S0,13.2,0,12z"}),
        el('path', {fill: "#212120", d: "M0,21.9c0-1.2,0.9-2.1,2-2.1s2,0.9,2,2.1S3.1,24,2,24S0,23.1,0,21.9z"}),
    );

    registerBlockType('codeboxr/cbxchangelog-block', {
        title   : cbxchangelog_block.block_title,
        icon    : iconEl,
        category: cbxchangelog_block.block_category,

        edit: function (props) {
            return [
                el(ServerSideRender, {
                    block     : 'codeboxr/cbxchangelog-block',
                    attributes: props.attributes
                }),
                el(InspectorControls, {},
                    // 1st Panel – Form Settings
                    el(PanelBody, {title: cbxchangelog_block.general_settings.heading, initialOpen: true},
                        el(TextControl, {
                            label   : cbxchangelog_block.general_settings.title,
                            onChange: (value) => {
                                props.setAttributes({
                                    title: value
                                });
                            },
                            value   : props.attributes.title
                        }),
                        el(TextControl, {
                            label   : cbxchangelog_block.general_settings.id,
                            onChange: (value) => {
                                props.setAttributes({
                                    id: parseInt(value)
                                });
                            },
                            type    : 'number',
                            value   : Number(props.attributes.id)
                        }),
                        el(TextControl, {
                            label   : cbxchangelog_block.general_settings.release,
                            onChange: (value) => {
                                props.setAttributes({
                                    release: parseInt(value)
                                });
                            },
                            type    : 'number',
                            value   : Number(props.attributes.release)
                        }),
                        el(ToggleControl,
                            {
                                label   : cbxchangelog_block.general_settings.show_label,
                                onChange: (value) => {
                                    props.setAttributes({show_label: value});
                                },
                                type    : 'number',
                                checked : props.attributes.show_label
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxchangelog_block.general_settings.show_date,
                                onChange: (value) => {
                                    props.setAttributes({show_date: value});
                                },
                                checked : props.attributes.show_date
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxchangelog_block.general_settings.show_url,
                                onChange: (value) => {
                                    props.setAttributes({show_url: value});
                                },
                                checked : props.attributes.show_url
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxchangelog_block.general_settings.relative_date,
                                onChange: (value) => {
                                    props.setAttributes({relative_date: value});
                                },
                                checked : props.attributes.relative_date
                            }
                        ),
                        el(SelectControl, {
                            label   : cbxchangelog_block.general_settings.layout,
                            options : cbxchangelog_block.general_settings.layout_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    layout: value
                                });
                            },
                            value   : props.attributes.layout
                        }),
                        el(SelectControl, {
                            label   : cbxchangelog_block.general_settings.order,
                            options : cbxchangelog_block.general_settings.order_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    order: value
                                });
                            },
                            value   : props.attributes.order
                        }),
                        el(SelectControl, {
                            label   : cbxchangelog_block.general_settings.orderby,
                            options : cbxchangelog_block.general_settings.orderby_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    orderby: value
                                });
                            },
                            value   : props.attributes.orderby
                        })
                    )
                )

            ];
        },
        // We're going to be rendering in PHP, so save() can just return null.
        save: function () {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.editor,
    window.wp.serverSideRender,
    window.wp.blockEditor
));