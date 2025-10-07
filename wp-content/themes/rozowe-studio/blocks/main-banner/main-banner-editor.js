(function(blocks, element, components, i18n, editor) {
    'use strict';

    var el = element.createElement;
    var Fragment = element.Fragment;
    var __ = i18n.__;
    var registerBlockType = blocks.registerBlockType;
    var MediaUpload = editor.MediaUpload;
    var MediaUploadCheck = editor.MediaUploadCheck;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;
    var Button = components.Button;
    var Placeholder = components.Placeholder;

    registerBlockType('rozowe-studio/main-banner', {
        title: __('Main Banner', 'rozowe-studio'),
        description: __('Full-screen banner with background image and center content.', 'rozowe-studio'),
        icon: 'format-image',
        category: 'layout',
        keywords: [
            __('banner', 'rozowe-studio'),
            __('hero', 'rozowe-studio'),
            __('fullscreen', 'rozowe-studio'),
        ],
        attributes: {
            blockId: {
                type: 'string',
                default: '',
            },
            backgroundImage: {
                type: 'object',
                default: null,
            },
            centerImage: {
                type: 'object',
                default: null,
            },
            centerImageAlt: {
                type: 'string',
                default: '',
            },
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function onSelectBackgroundImage(media) {
                setAttributes({
                    backgroundImage: media
                });
            }

            function onSelectCenterImage(media) {
                setAttributes({
                    centerImage: media
                });
            }

            function onRemoveBackgroundImage() {
                setAttributes({
                    backgroundImage: null
                });
            }

            function onBlockIdChange(value) {
                setAttributes({
                    blockId: value
                });
            }

            function onRemoveCenterImage() {
                setAttributes({
                    centerImage: null
                });
            }

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, {
                        title: __('Block Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(components.TextControl, {
                            label: __('Block ID', 'rozowe-studio'),
                            value: attributes.blockId,
                            onChange: onBlockIdChange,
                            placeholder: __('hero', 'rozowe-studio'),
                            help: __('Optional: Add an ID to this block for anchor links (e.g., #hero)', 'rozowe-studio')
                        })
                    ),
                    el(PanelBody, {
                        title: __('Background Image', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(MediaUploadCheck, {},
                            el(MediaUpload, {
                                onSelect: onSelectBackgroundImage,
                                allowedTypes: ['image'],
                                value: attributes.backgroundImage ? attributes.backgroundImage.id : '',
                                render: function(obj) {
                                    return el(Button, {
                                        className: attributes.backgroundImage ? 'editor-post-featured-image__preview' : 'editor-post-featured-image__toggle',
                                        onClick: obj.open
                                    }, !attributes.backgroundImage ? __('Set Background Image', 'rozowe-studio') : 
                                        el('img', {
                                            src: attributes.backgroundImage.sizes.medium ? attributes.backgroundImage.sizes.medium.url : attributes.backgroundImage.url,
                                            alt: attributes.backgroundImage.alt || ''
                                        })
                                    );
                                }
                            })
                        ),
                        attributes.backgroundImage && el(Button, {
                            onClick: onRemoveBackgroundImage,
                            isLink: true,
                            isDestructive: true
                        }, __('Remove Background Image', 'rozowe-studio'))
                    ),
                    el(PanelBody, {
                        title: __('Center Image', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(MediaUploadCheck, {},
                            el(MediaUpload, {
                                onSelect: onSelectCenterImage,
                                allowedTypes: ['image'],
                                value: attributes.centerImage ? attributes.centerImage.id : '',
                                render: function(obj) {
                                    return el(Button, {
                                        className: attributes.centerImage ? 'editor-post-featured-image__preview' : 'editor-post-featured-image__toggle',
                                        onClick: obj.open
                                    }, !attributes.centerImage ? __('Set Center Image', 'rozowe-studio') : 
                                        el('img', {
                                            src: attributes.centerImage.sizes.medium ? attributes.centerImage.sizes.medium.url : attributes.centerImage.url,
                                            alt: attributes.centerImage.alt || ''
                                        })
                                    );
                                }
                            })
                        ),
                        attributes.centerImage && el(Button, {
                            onClick: onRemoveCenterImage,
                            isLink: true,
                            isDestructive: true
                        }, __('Remove Center Image', 'rozowe-studio'))
                    )
                ),
                el('div', {
                    className: 'main-banner-block main-banner-block-editor',
                    style: attributes.backgroundImage ? {
                        backgroundImage: 'url(' + attributes.backgroundImage.url + ')'
                    } : {}
                },
                    el('div', {
                        className: 'main-banner-content'
                    },
                        attributes.centerImage ? 
                            el('img', {
                                src: attributes.centerImage.url,
                                alt: attributes.centerImage.alt || '',
                                className: 'main-banner-center-image'
                            }) :
                            el(Placeholder, {
                                icon: 'format-image',
                                label: __('Main Banner', 'rozowe-studio'),
                                instructions: __('Add a background image and center image to create your banner.', 'rozowe-studio')
                            })
                    )
                )
            );
        },

        save: function() {
            // Server-side rendering
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