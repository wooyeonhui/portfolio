/**
 * This file contains the global functions
 * @since 1.0
 */
 ;(function($, window, document, undefined){
    "user strict";

    if ( !$().piMedia )
    {
        $.fn.piMedia = function (opts)
        {
            var $self = $(this),
                defaults = {
                    append      : '',
                    typeGet     : 'url', // one of in [url, id]
                    multiple    : false,
                    singleImage : false,
                    showImage   : true,
                    title       : 'My Photos',
                    type        : 'image',
                    button      : 'Select',
                    init        : function () {}, // callback init
                    select      : function () {}
                },
                options = $.extend($self.data(), defaults),
                $button = $('.pi-btn-upload', $self),
                $trigger = $('.pi-list-image', $self),
                $listIDs = $('.pi-list-id', $self),
                $showImage = $('<ul class="pi-show-image"></ul>');

            options = $.extend(options, opts);
            if (!$self.data('isCallMedia')) {
                $self.data('isCallMedia', true)
            }
            else {
                return false;
            }
            var piUpload = {
                $el: $self,
                media: null,
                init: function () {
                    var _this = this;

                    _this.createHTML();
                    _this.setMedia();
                    _this.events();
                },
                createHTML: function () {
                    var _this = this;

                    if (!$('.pi-show-image', $self).length) {
                        $button.after($showImage);
                    }
                    _this.$showImage = $('.pi-show-image', $self);

                },
                events: function () {
                    var _this = this;

                    _this.media.on('select', function () {
                        var selection  = _this.media.state().get('selection'),
                            aListImage = $trigger.val(),
                            aListID    = $listIDs.val();

                        aListImage = aListImage != null ? aListImage.split(",") : aListImage;
                        aListID    = aListID != null ? aListID.split(",") : aListID;
                        // Check empty value

                        if (aListImage == null || aListImage == '') {
                            aListImage = [];
                        }
                        if (aListID == null || aListID == '') {
                            aListID = [];
                        }
                        // Check is change or add
                        if (options.multiple) {
                            if (_this.index == -1) { // event add image
                                selection.each (function (attachment, id) {
                                    attachment = attachment.toJSON();
                                    aListImage.push(attachment.url);
                                    aListID.push(attachment.id)
                                });
                            }
                            else { // event change image

                                selection.each(function (attachment, id) {
                                    attachment = attachment.toJSON();
                                    if (id ==0) {
                                        aListImage[_this.index] = attachment.url;
                                        aListID[_this.index] = attachment.id;
                                    }
                                    else {
                                        aListImage.splice(_this.index, 0, attachment.url);
                                        aListID.splice(_this.index, 0, attachment.id);
                                    }
                                })
                            }
                        }
                        else {
                            selection.each (function (attachment, id) {
                                attachment = attachment.toJSON();
                                aListImage = attachment.url;
                            });
                        }
                        $listIDs.val(aListID);
                        $trigger.val(aListImage).trigger('change', {changeByMedia: true})
                    });

                    $button.click( function (event, data) {

                        event.preventDefault();
                        _this.media.open();

                        if (data && typeof data.index != 'undefined') {
                            _this.index = data.index;
                        }
                        else {
                            _this.index = -1;
                        }
                    });

                    $trigger.change( function (event, data) {

                        var listImage = $(this).val();

                        try {
                            listImage = listImage.split(",");
                        }
                        catch(e) {}

                        if (typeof listImage == 'object' && listImage != null) {
                            var listIDs = $listIDs.val();
                                listIDs = listIDs.split(",");

                            _this.$showImage.empty();
                            $.each(listImage, function (index, url) {
                                var $itemImage = $('<li class="pi-item-image" style="display: inline-block;"><img src="" alt="" style="width:75px; height:75px;"/><div class="pi-media-controls"><i class="pi-media-edit" title="Edit"></i><i class="pi-media-remove" title="Remove"></i></div></div></li>');

                                if (listIDs != undefined && listIDs[index] != undefined) {
                                    $itemImage.attr('data-id', listIDs[index]);
                                }
                                $('img', $itemImage).attr('src', url);
                                _this.$showImage.append($itemImage);
                            });
                        }
                    })

                    $self.delegate('.pi-media-edit', 'click', function(event) {
                        event.stopPropagation();
                        event.preventDefault();

                        var $target = $(event.target).hasClass('pi-item-image') ? $(event.target) : $(event.target).closest('.pi-item-image'),
                            index = $target.index();

                        $button.trigger('click', {index: index});

                    });

                    $self.delegate('.pi-media-remove', 'click', function (event) {
                        event.stopPropagation();
                        event.preventDefault();

                        var $target = $(event.target).hasClass('pi-item-image') ? $(event.target) : $(event.target).closest('.pi-item-image'),
                            index = $target.index(),
                            listID = ($listIDs.val()).split(","),
                            listImage = ($trigger.val()).split(",");

                            listID.splice(index, 1);
                            listImage.splice(index, 1);
                            $listIDs.val(listID);
                            $trigger.val(listImage).trigger('change');
                    });
                },
                setMedia: function () {
                    var _this = this;

                    _this.media = wp.media({
                        title: options.title,
                        button: {
                            text: options.button
                        },
                        multiple: options.multiple
                    })

                }
            };

            return piUpload.init();
        }
    }

    if ( !$().piAudio )
    {
        $.fn.piAudio = function(){
            $(this).click(function(event)
            {
                event.preventDefault();
                var file_frame, $this = $(this);
                // If the media frame already exists, reopen it.
                if ( file_frame ) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: jQuery( this ).data( 'uploader_title' ),
                    button: {
                        text: jQuery( this ).data( 'uploader_button_text' ),
                    },
                    library:{
                        type: 'audio'
                    }
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    $this.parent().find(".pi-insert-audio").val(attachment.url);
                });

                // Finally, open the modal
                file_frame.open();
            })
        }
    }

    if ( !$().piGetListsOfMailchimp )
    {
        $.fn.piGetListsOfMailchimp = function()
        {
            var $self = $(this), _oLists, _oData = {},  _list="", _name="", _val="";
            if ( $self.attr("id") && $self.attr("id").search("__i__") == -1 )
            {

                $self.click( function()
                {
                    _val = $self.prev().val();

                    if ( _val != "" )
                    {
                        $.ajax(
                            {
                                url: ajaxurl,
                                type: "POST",
                                data: {action: 'pi_mailchimp_get_lists', api_key: _val},
                                success: function(data, textStatus, jqXHR)
                                {
                                    _oData = JSON.parse(data);
                                    if ( _oData.type=="error" )
                                    {
                                        alert(_oData.msg);
                                    }else{
                                        _oLists = JSON.parse(_oData.data);

                                        $.each(_oLists, function(_id, _name)
                                        {
                                            _list += '<option value="'+_id+'">'+_name+'</option>';
                                        })
                                        $self.closest(".wrapper").next().find("#pi_mailchimp_lists").html(_list);
                                    }
                                }

                            });
                    }else{
                        alert("Please enter maichimp api key!");
                    }

                    return false;
                });
            }
        }
    }

    $(document).ready(function(){
        if ( typeof  wp.media === 'function' )
        {
            $(".pi-single-upload").piMedia({typeGet: 'url'});
            $(".pi-multiple-upload").piMedia({multiple: true});
            $(".pi-audio-upload").piAudio();
        }
    })

    $(window).load(function(){
        $.windowLoad = true;
    })
 })(jQuery, window, document);