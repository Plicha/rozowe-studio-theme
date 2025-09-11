(function(blocks, element, components, i18n, editor) {
    'use strict';

    var el = element.createElement;
    var Fragment = element.Fragment;
    var __ = i18n.__;
    var registerBlockType = blocks.registerBlockType;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var Placeholder = components.Placeholder;

    registerBlockType('rozowe-studio/contact-form', {
        title: __('Contact Form', 'rozowe-studio'),
        description: __('Display a Contact Form 7 form with custom styling.', 'rozowe-studio'),
        icon: 'email-alt',
        category: 'layout',
        keywords: [
            __('contact', 'rozowe-studio'),
            __('form', 'rozowe-studio'),
            __('cf7', 'rozowe-studio'),
        ],
        attributes: {
            shortcode: {
                type: 'string',
                default: '',
            },
            title: {
                type: 'string',
                default: '',
            },
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var shortcode = attributes.shortcode;
            var title = attributes.title;

            function onShortcodeChange(value) {
                setAttributes({ shortcode: value });
            }

            function onTitleChange(value) {
                setAttributes({ title: value });
            }

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Form Settings', 'rozowe-studio'), initialOpen: true },
                        el(TextControl, {
                            label: __('Contact Form 7 Shortcode', 'rozowe-studio'),
                            value: shortcode,
                            onChange: onShortcodeChange,
                            placeholder: __('[contact-form-7 id="123" title="Contact Form"]', 'rozowe-studio'),
                            help: __('Paste the Contact Form 7 shortcode here. Example: [contact-form-7 id="123" title="Contact Form"]', 'rozowe-studio')
                        }),
                        el(TextControl, {
                            label: __('Form Title', 'rozowe-studio'),
                            value: title,
                            onChange: onTitleChange,
                            placeholder: __('Enter form title...', 'rozowe-studio')
                        })
                    )
                ),
                el('div', { 
                    className: 'contact-form-block-editor'
                },
                    !shortcode ? el(Placeholder, {
                        icon: 'email-alt',
                        label: __('Contact Form', 'rozowe-studio'),
                        instructions: __('Paste your Contact Form 7 shortcode in the sidebar to display it here.', 'rozowe-studio')
                    }) : el('div', { className: 'contact-form-preview' },
                        title && el('h2', { className: 'contact-form-title' }, title),
                        el('div', { className: 'contact-form-wrapper' },
                            el('div', { className: 'contact-form-placeholder' },
                                el('p', {}, __('Contact Form 7 form will be displayed here:', 'rozowe-studio')),
                                el('strong', {}, shortcode)
                            )
                        )
                    )
                )
            );
        },

        save: function() {
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.i18n,
    window.wp.editor
);
