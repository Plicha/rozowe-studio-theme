(function(blocks, element, components, i18n, editor, blockEditor) {
    'use strict';

    var el = element.createElement;
    var Fragment = element.Fragment;
    var __ = i18n.__;
    var registerBlockType = blocks.registerBlockType;
    var MediaUpload = editor.MediaUpload;
    var MediaUploadCheck = editor.MediaUploadCheck;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var Button = components.Button;
    var InnerBlocks = blockEditor.InnerBlocks;

    registerBlockType('rozowe-studio/story-frames', {
        title: __('Story Frames', 'rozowe-studio'),
        description: __('Two-column layout with text content and image.', 'rozowe-studio'),
        icon: 'format-gallery',
        category: 'layout',
        keywords: [
            __('story', 'rozowe-studio'),
            __('frames', 'rozowe-studio'),
            __('two-column', 'rozowe-studio'),
        ],
        attributes: {
            blockId: {
                type: 'string',
                default: '',
            },
            image: {
                type: 'object',
                default: null,
            },
            imageAlt: {
                type: 'string',
                default: '',
            },
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function onSelectImage(media) {
                setAttributes({
                    image: media
                });
            }

            function onRemoveImage() {
                setAttributes({
                    image: null
                });
            }

            function onBlockIdChange(value) {
                setAttributes({
                    blockId: value
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
                            placeholder: __('story', 'rozowe-studio'),
                            help: __('Optional: Add an ID to this block for anchor links (e.g., #story)', 'rozowe-studio')
                        })
                    ),
                    el(PanelBody, {
                        title: __('Image Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(MediaUploadCheck, {},
                            el(MediaUpload, {
                                onSelect: onSelectImage,
                                allowedTypes: ['image'],
                                value: attributes.image ? attributes.image.id : '',
                                render: function(obj) {
                                    return el(Button, {
                                        className: attributes.image ? 'editor-post-featured-image__preview' : 'editor-post-featured-image__toggle',
                                        onClick: obj.open
                                    }, !attributes.image ? __('Set Image', 'rozowe-studio') : 
                                        el('img', {
                                            src: attributes.image.sizes.medium ? attributes.image.sizes.medium.url : attributes.image.url,
                                            alt: attributes.image.alt || ''
                                        })
                                    );
                                }
                            })
                        ),
                        attributes.image && el(Button, {
                            onClick: onRemoveImage,
                            isLink: true,
                            isDestructive: true
                        }, __('Remove Image', 'rozowe-studio'))
                    )
                ),
                el('div', {
                    className: 'story-frames-block story-frames-block-editor'
                },
                    el('div', {
                        className: 'container'
                    },
                        el('div', {
                            className: 'grid'
                        },
                            el('div', {
                                className: 'grid-col-5'
                            },
                                el('div', {
                                    className: 'story-frames-content-wrapper'
                                },
                                    el('div', {
                                        className: 'story-frames-content'
                                    },
                                        el(InnerBlocks, {
                                            allowedBlocks: [
                                                'core/heading',
                                                'core/paragraph', 
                                                'core/list',
                                                'core/quote',
                                                'core/button',
                                                'core/separator'
                                            ],
                                            template: [
                                                ['core/heading', { level: 2, placeholder: 'Add a heading...' }],
                                                ['core/paragraph', { placeholder: 'Add your content here...' }]
                                            ],
                                            templateLock: false
                                        })
                                    )
                                )
                            ),
                            el('div', {
                                className: 'grid-col-1'
                            }),
                            el('div', {
                                className: 'grid-col-6'
                            },
                                el('div', {
                                    className: 'story-frames-image'
                                },
                                    attributes.image ? 
                                        el('img', {
                                            src: attributes.image.url,
                                            alt: attributes.image.alt || '',
                                        }) :
                                        el('div', {
                                            className: 'story-frames-placeholder'
                                        }, __('Add an image', 'rozowe-studio'))
                                )
                            )
                        )
                    )
                )
            );
        },

        save: function() {
            return el('div', {
                className: 'story-frames-block'
            },
                el('div', {
                    className: 'container'
                },
                    el('div', {
                        className: 'grid'
                    },
                        el('div', {
                            className: 'grid-col-5'
                        },
                            el('div', {
                                className: 'story-frames-content-wrapper'
                            },
                                el('div', {
                                    className: 'story-frames-content'
                                },
                                    el(InnerBlocks.Content)
                                )
                            )
                        ),
                        el('div', {
                            className: 'grid-col-1'
                        }),
                        el('div', {
                            className: 'grid-col-6'
                        },
                            el('div', {
                                className: 'story-frames-image'
                            })
                        )
                    )
                )
            );
        }
    });

})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.i18n,
    window.wp.editor,
    window.wp.blockEditor
);
