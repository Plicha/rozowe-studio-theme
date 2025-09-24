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
    var Placeholder = components.Placeholder;
    var CheckboxControl = components.CheckboxControl;

    registerBlockType('rozowe-studio/photo-gallery', {
        title: __('Photo Gallery', 'rozowe-studio'),
        description: __('Display photos in alternating layout with lightbox gallery.', 'rozowe-studio'),
        icon: 'format-gallery',
        category: 'design',
        keywords: [
            __('gallery', 'rozowe-studio'),
            __('photos', 'rozowe-studio'),
            __('images', 'rozowe-studio'),
            __('lightbox', 'rozowe-studio'),
        ],
        attributes: {
            images: {
                type: 'array',
                default: [],
            },
            threeColumnLayout: {
                type: 'boolean',
                default: false,
            },
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function onSelectImages(media) {
                var selectedImages = media.map(function(item) {
                    return {
                        id: item.id,
                        url: item.url,
                        alt: item.alt || '',
                        title: item.title || ''
                    };
                });
                
                // If we already have images, add new ones to existing list
                if (attributes.images.length > 0) {
                    var existingImages = attributes.images;
                    var newImages = selectedImages.filter(function(newImg) {
                        return !existingImages.some(function(existingImg) {
                            return existingImg.id === newImg.id;
                        });
                    });
                    setAttributes({
                        images: existingImages.concat(newImages)
                    });
                } else {
                    setAttributes({
                        images: selectedImages
                    });
                }
            }

            function onRemoveImage(index) {
                var newImages = attributes.images.filter(function(_, i) {
                    return i !== index;
                });
                setAttributes({
                    images: newImages
                });
            }

            function onReorderImages(fromIndex, toIndex) {
                var newImages = [...attributes.images];
                var movedImage = newImages.splice(fromIndex, 1)[0];
                newImages.splice(toIndex, 0, movedImage);
                setAttributes({
                    images: newImages
                });
            }

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, {
                        title: __('Gallery Settings', 'rozowe-studio'),
                        initialOpen: true
                    },
                        el(MediaUploadCheck, {},
                            el(MediaUpload, {
                                onSelect: onSelectImages,
                                allowedTypes: ['image'],
                                multiple: true,
                                render: function(obj) {
                                    return el(Button, {
                                        className: 'button button-primary',
                                        onClick: obj.open
                                    }, attributes.images.length > 0 ? __('Add More Images', 'rozowe-studio') : __('Select Images', 'rozowe-studio'));
                                }
                            })
                        ),
                        attributes.images.length > 0 && el(Button, {
                            onClick: function() {
                                setAttributes({ images: [] });
                            },
                            isLink: true,
                            isDestructive: true
                        }, __('Clear All Images', 'rozowe-studio')),
                        el(CheckboxControl, {
                            label: __('Three column layout', 'rozowe-studio'),
                            checked: attributes.threeColumnLayout,
                            onChange: function(value) {
                                setAttributes({ threeColumnLayout: value });
                            }
                        })
                    )
                ),
                el('div', {
                    className: 'photo-gallery-block photo-gallery-block-editor photo-gallery-fullwidth'
                },
                    attributes.images.length === 0 ? 
                        el(Placeholder, {
                            icon: 'format-gallery',
                            label: __('Photo Gallery', 'rozowe-studio'),
                            instructions: __('Select images to create a photo gallery with alternating layout.', 'rozowe-studio')
                        },
                            el(MediaUploadCheck, {},
                                el(MediaUpload, {
                                    onSelect: onSelectImages,
                                    allowedTypes: ['image'],
                                    multiple: true,
                                    render: function(obj) {
                                        return el(Button, {
                                            className: 'button button-primary',
                                            onClick: obj.open
                                        }, __('Select Images', 'rozowe-studio'));
                                    }
                                })
                            )
                        ) :
                        el('div', {
                            className: 'photo-gallery-container' + (attributes.threeColumnLayout ? ' photo-gallery-three-column' : '')
                        },
                            attributes.images.map(function(image, index) {
                                var rowNumber, isOddRow, isFirstInRow, itemClass = 'photo-gallery-item';
                                
                                if (attributes.threeColumnLayout) {
                                    // Three column layout logic
                                    rowNumber = Math.floor(index / 3) + 1;
                                    isOddRow = rowNumber % 2 === 1;
                                    var positionInRow = index % 3;
                                    
                                    if (isOddRow) {
                                        // Odd rows: 3 equal items
                                        itemClass += ' photo-gallery-item-three-equal';
                                    } else {
                                        // Even rows: 2 equal items
                                        if (positionInRow < 2) {
                                            itemClass += ' photo-gallery-item-two-equal';
                                        } else {
                                            // Skip the third item in even rows
                                            return null;
                                        }
                                    }
                                } else {
                                    // Original two column layout logic
                                    rowNumber = Math.floor(index / 2) + 1;
                                    isOddRow = rowNumber % 2 === 1;
                                    isFirstInRow = index % 2 === 0;
                                    
                                    if (isFirstInRow) {
                                        itemClass += isOddRow ? ' photo-gallery-item-small' : ' photo-gallery-item-large';
                                    } else {
                                        itemClass += isOddRow ? ' photo-gallery-item-large' : ' photo-gallery-item-small';
                                    }
                                }

                                return el('div', {
                                    key: image.id || index,
                                    className: itemClass
                                },
                                    el('div', {
                                        className: 'photo-gallery-item-wrapper'
                                    },
                                        el('img', {
                                            src: image.url,
                                            alt: image.alt || '',
                                            style: { width: '100%', height: 'auto' }
                                        }),
                                        el('div', {
                                            className: 'photo-gallery-item-actions'
                                        },
                                            el(Button, {
                                                onClick: function() {
                                                    onRemoveImage(index);
                                                },
                                                isLink: true,
                                                isDestructive: true,
                                                className: 'photo-gallery-remove-btn'
                                            }, __('Remove', 'rozowe-studio'))
                                        )
                                    )
                                );
                            })
                        )
                )
            );
        },

        save: function() {
            return null; // Dynamic block, rendered server-side
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
