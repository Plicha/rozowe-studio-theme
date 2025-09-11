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
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var CheckboxControl = components.CheckboxControl;
    var URLInput = editor.URLInput;
    var InnerBlocks = blockEditor.InnerBlocks;

    registerBlockType('rozowe-studio/content-letter', {
        title: __('Content with Letter', 'rozowe-studio'),
        description: __('Full-width section with letter background, content and link.', 'rozowe-studio'),
        icon: 'text-page',
        category: 'layout',
        keywords: [
            __('content', 'rozowe-studio'),
            __('letter', 'rozowe-studio'),
            __('full-width', 'rozowe-studio'),
        ],
        attributes: {
            letter: {
                type: 'string',
                default: '',
            },
            content: {
                type: 'string',
                default: '',
            },
            linkUrl: {
                type: 'string',
                default: '',
            },
            linkText: {
                type: 'string',
                default: 'Zobacz więcej',
            },
            linkIcon: {
                type: 'string',
                default: '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.16699 10.5H15.8337M15.8337 10.5L12.5003 13.8333M15.8337 10.5L12.5003 7.16663" stroke="#221516" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            },
            darkBackground: {
                type: 'boolean',
                default: false,
            },
            contentOnRight: {
                type: 'boolean',
                default: false,
            },
            backgroundPositionCenter: {
                type: 'boolean',
                default: false,
            },
            backgroundFullWidth: {
                type: 'boolean',
                default: false,
            },
            backgroundImage: {
                type: 'object',
                default: null,
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

            function onRemoveBackgroundImage() {
                setAttributes({
                    backgroundImage: null
                });
            }

            function onLetterChange(value) {
                setAttributes({
                    letter: value
                });
            }

            function onContentChange(value) {
                setAttributes({
                    content: value
                });
            }

            function onLinkUrlChange(value) {
                setAttributes({
                    linkUrl: value
                });
            }

            function onLinkTextChange(value) {
                setAttributes({
                    linkText: value
                });
            }

            function onLinkIconChange(value) {
                setAttributes({
                    linkIcon: value
                });
            }

            function onDarkBackgroundChange(value) {
                setAttributes({
                    darkBackground: value
                });
            }

            function onContentOnRightChange(value) {
                setAttributes({
                    contentOnRight: value
                });
            }

            function onBackgroundPositionCenterChange(value) {
                setAttributes({
                    backgroundPositionCenter: value
                });
            }

            function onBackgroundFullWidthChange(value) {
                setAttributes({
                    backgroundFullWidth: value
                });
            }

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, {
                        title: __('Content Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(TextareaControl, {
                            label: __('Content', 'rozowe-studio'),
                            value: attributes.content,
                            onChange: onContentChange,
                            placeholder: __('Add your content here...', 'rozowe-studio'),
                            rows: 6,
                            help: __('Enter the main content for this block', 'rozowe-studio')
                        })
                    ),
                    el(PanelBody, {
                        title: __('Letter Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(TextControl, {
                            label: __('Letter', 'rozowe-studio'),
                            value: attributes.letter,
                            onChange: onLetterChange,
                            placeholder: __('Enter a letter...', 'rozowe-studio'),
                            help: __('This letter will be displayed as background decoration', 'rozowe-studio')
                        })
                    ),
                    el(PanelBody, {
                        title: __('Link Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(URLInput, {
                            label: __('Link URL', 'rozowe-studio'),
                            value: attributes.linkUrl,
                            onChange: onLinkUrlChange,
                            placeholder: __('Enter URL or select page...', 'rozowe-studio'),
                            autoFocus: false
                        }),
                        el(TextControl, {
                            label: __('Link Text', 'rozowe-studio'),
                            value: attributes.linkText,
                            onChange: onLinkTextChange,
                            placeholder: __('Enter link text...', 'rozowe-studio')
                        }),
                        el(TextareaControl, {
                            label: __('Link Icon (SVG)', 'rozowe-studio'),
                            value: attributes.linkIcon,
                            onChange: onLinkIconChange,
                            placeholder: __('Paste SVG code here...', 'rozowe-studio'),
                            rows: 3,
                            help: __('Paste the complete SVG code for the icon', 'rozowe-studio')
                        })
                    ),
                    el(PanelBody, {
                        title: __('Layout Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(CheckboxControl, {
                            label: __('Dark background', 'rozowe-studio'),
                            checked: attributes.darkBackground,
                            onChange: onDarkBackgroundChange,
                            help: __('Changes background to dark burgundy and text to white', 'rozowe-studio')
                        }),
                        el(CheckboxControl, {
                            label: __('Content column on the right', 'rozowe-studio'),
                            checked: attributes.contentOnRight,
                            onChange: onContentOnRightChange,
                            help: __('Moves content column to the right side', 'rozowe-studio')
                        }),
                        el(CheckboxControl, {
                            label: __('Background position - center', 'rozowe-studio'),
                            checked: attributes.backgroundPositionCenter,
                            onChange: onBackgroundPositionCenterChange,
                            help: __('Centers the background image position', 'rozowe-studio')
                        }),
                        el(CheckboxControl, {
                            label: __('Background full-width', 'rozowe-studio'),
                            checked: attributes.backgroundFullWidth,
                            onChange: onBackgroundFullWidthChange,
                            help: __('Makes background image full-width', 'rozowe-studio')
                        })
                    ),
                    el(PanelBody, {
                        title: __('Background Image', 'rozowe-studio'),
                        initialOpen: false
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
                    )
                ),
                // Dynamic editor view with current values
                el('div', {
                    className: 'content-letter-block-editor',
                    style: {
                        padding: '20px',
                        border: '1px dashed #ccc',
                        borderRadius: '4px',
                        backgroundColor: attributes.darkBackground ? '#3a040f' : '#e0dbd7',
                        color: attributes.darkBackground ? '#f4f5f1' : '#221516',
                        textAlign: 'center'
                    }
                },
                    el('div', {
                        style: {
                            marginBottom: '10px',
                            fontSize: '14px',
                            fontWeight: '500'
                        }
                    }, __('Content with Letter Block', 'rozowe-studio')),
                    
                    // Show letter if set
                    attributes.letter && el('div', {
                        style: {
                            fontSize: '48px',
                            fontWeight: 'bold',
                            marginBottom: '10px',
                            opacity: 0.3
                        }
                    }, attributes.letter),
                    
                    // Show current settings
                    el('div', {
                        style: {
                            fontSize: '12px',
                            opacity: 0.7,
                            marginBottom: '10px'
                        }
                    }, 
                        attributes.darkBackground ? __('Dark background', 'rozowe-studio') : __('Light background', 'rozowe-studio'),
                        attributes.contentOnRight && ' • ' + __('Content on right', 'rozowe-studio'),
                        attributes.backgroundPositionCenter && ' • ' + __('Center position', 'rozowe-studio'),
                        attributes.backgroundFullWidth && ' • ' + __('Full width', 'rozowe-studio')
                    ),
                    
                    // Show content preview if available
                    attributes.content && el('div', {
                        style: {
                            fontSize: '14px',
                            lineHeight: '1.5',
                            textAlign: 'left',
                            padding: '10px',
                            backgroundColor: 'rgba(255, 255, 255, 0.1)',
                            borderRadius: '4px',
                            marginTop: '10px'
                        },
                        dangerouslySetInnerHTML: { __html: attributes.content }
                    })
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
    window.wp.editor,
    window.wp.blockEditor
);
