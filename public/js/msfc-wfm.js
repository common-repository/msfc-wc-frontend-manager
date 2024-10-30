(function ($) {
	"use strict";

    jQuery(document).ready(function($){
    
        //Alert Dismiss
        if($(".msfc-wfm-alert.alert-msfc-fadeout.alert").length > 0) {
            setTimeout(function () {
                $(".msfc-wfm-alert.alert-msfc-fadeout.alert").remove();
            }, 6000);
        }

        //Dismiss alert
        $('.msfc-wfm-alert.alert-dismissible .close').click(function(){
            $(".msfc-wfm-alert").remove();
        });


        /****
         * Single Product Image Upload
         * *****/
        //add product image on validation time
        var product_thumbnail_url = $("#product_thumbnail_url").val();
        if( product_thumbnail_url ){
            $('#product_thumb_img').html( '<img src="'+product_thumbnail_url+'" alt="Product Image"/>' );
            $('#product-single-image').addClass('image-drop-bg');
        }

        //Product Single Image or Media Upload
        $('#product-single-image').click(function(event){
            event.preventDefault();

            var targetContainer = $(this);
            // If the media frame already exists, reopen it.
            if ( frame ) {
                frame.open();
                return false;
            }

            // Create a new media frame
            var frame = wp.media({
                title: "Upload Product Image",
                button:{
                    text: "Insert Image"
                },
                multiple: false
            });

            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                
                // Send the attachment id to our hidden input
                $('#product_thumbnail_id').val(attachment.id);

                // Send the attachment URL to our custom image input field.
                $('#product_thumb_img').html( '<img src="'+attachment.sizes.thumbnail.url+'" alt="Product Image"/>' );
                $('#product_thumbnail_url').val( attachment.sizes.thumbnail.url );

                //add class to hide text normaly
                $(targetContainer).addClass('image-drop-bg');
            });

            frame.open();
        });
        //End Product WP Media Uploader



        /****
         * Product Gallery Images Upload
         * *****/
        //add product image on validation time
        var product_image_gallery_url = $("#product_image_gallery_url").val();
        product_image_gallery_url = product_image_gallery_url ? product_image_gallery_url.split(',') : [];
        for( var gurlItem in product_image_gallery_url ){
            var single_product_image_gallery_url = product_image_gallery_url[gurlItem];
            $('#product_gallery_img').append( '<img src="'+single_product_image_gallery_url+'" alt="Product Image"/>' );
            $('#product-gallery-images').addClass('image-drop-bg');
        }

        //Product Single Image or Media Upload
        $('#product-gallery-images').click(function(event){
            event.preventDefault();

            var targetContainer = $(this);
            // If the media frame already exists, reopen it.
            if ( gframe ) {
                gframe.open();
                return false;
            }

            // Create a new media frame
            var gframe = wp.media({
                title: "Upload Product Image",
                button:{
                    text: "Insert Image"
                },
                multiple: true
            });

            gframe.on('select', function(){
                $('#product_gallery_img').html('');
                var galleryImageIds = [];
                var galleryImageUrls = [];
                var attachments = gframe.state().get('selection').toJSON();
                
                for(var singleItem in attachments){
                    var attachment = attachments[singleItem];
                    galleryImageIds.push(attachment.id);
                    galleryImageUrls.push(attachment.sizes.thumbnail.url);
                    $('#product_gallery_img').append( '<img src="'+attachment.sizes.thumbnail.url+'" alt="Product Gallery Image"/>' );
                    
                }
                // Send the attachment ids to our hidden input
                $('#product_image_gallery').val(galleryImageIds.join(','));
                $('#product_image_gallery_url').val( galleryImageUrls.join(',') );

                //add class to hide text normaly
                $(targetContainer).addClass('image-drop-bg');
            });

            gframe.open();
        });
        //End Product WP Media Uploader

        //Remove Error Message
        $('.is-invalid').change(function(){
            $(this).removeClass('.is-invalid');
        });

        //Product Details Thumb Preview
        $('.msfc-wfm-thumb-gl-item-img').click(function(){
            $('.msfc-wfm-thumb-gl-item-img').removeClass('active');
            var msfcBigImage = $(this).attr('attr-big-image');
            $('#msfc-wfm-big-preview-src').attr('src', msfcBigImage);
            $(this).addClass('active');
        });


        //Active Class on navigation
        const currentUrl = window.location.href;
        let msfcWfmNavigation = document.querySelectorAll('.msfc-wfm-left-navigation li a');
        msfcWfmNavigation.forEach(function(item){
            if(item.href === currentUrl){
                item.closest('li').classList.add('is-active');
            }
        })

    });
}(jQuery));	