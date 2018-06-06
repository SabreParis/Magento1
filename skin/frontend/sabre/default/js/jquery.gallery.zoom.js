jQuery.fn.extend({
    jGalleryZoom : function(opts){
        var eGallery = this;
        var _gallery = {};
        var _optsDefault = {};
        _optsDefault.selectors = {};
        /* Init Vars */
        _optsDefault.selectors.fullImageAnchor = 'jgz-full-link';
        _optsDefault.selectors.fullImage = 'jgz-full-image';
        _optsDefault.selectors.thumbnails = 'jgz-thumbnails';
        _optsDefault.selectors.thumbnailsItemAnchor = 'jgz-thumbnails-item-link';
        _optsDefault.selectors.thumbnailsItemImage = 'jgz-thumbnails-item-image';
        _optsDefault.selectors.zoomImg = 'zoomImg';
        
        opts.selectors.fullImageAnchor = opts.selectors.fullImageAnchor != null ? opts.selectors.fullImageAnchor : _optsDefault.selectors.fullImageAnchor;
        opts.selectors.fullImage = opts.selectors.fullImage != null ? opts.selectors.fullImage : _optsDefault.selectors.fullImage;
        opts.selectors.thumbnails = opts.selectors.thumbnails != null ? opts.selectors.thumbnails : _optsDefault.selectors.thumbnails;
        opts.selectors.thumbnailsItemAnchor = opts.selectors.thumbnailsItemAnchor != null ? opts.selectors.thumbnailsItemAnchor : _optsDefault.selectors.thumbnailsItemAnchor;
        opts.selectors.thumbnailsItemImage = opts.selectors.thumbnailsItemImage != null ? opts.selectors.thumbnailsItemImage : _optsDefault.selectors.thumbnailsItemImage;
        opts.selectors.zoomImg = opts.selectors.zoomImg != null ? opts.selectors.zoomImg : _optsDefault.selectors.zoomImg;
        
        _gallery.fullImageAnchor = eGallery.find('.'+opts.selectors.fullImageAnchor);
        _gallery.fullImage = eGallery.find('.'+opts.selectors.fullImage);
        _gallery.thumbnails = eGallery.find('.'+opts.selectors.thumbnails);
        _gallery.thumbnailsItemAnchor = eGallery.find('.'+opts.selectors.thumbnailsItemAnchor);
        _gallery.thumbnailsItemImage = eGallery.find('.'+opts.selectors.thumbnailsItemImage);
        
        if(_gallery.thumbnailsItemAnchor.length >0){
            _gallery.thumbnailsItemAnchor.click(function(){
                var _thumbItemAnc = jQuery(this);
                var _thumbItemImg = _thumbItemAnc.find('.'+opts.selectors.thumbnailsItemImage);
                var _thumbItemImgSrc = _thumbItemAnc.attr('href');
                
                /* add&remove classes */
                _gallery.thumbnailsItemAnchor.removeClass('active');
                _thumbItemAnc.addClass('active');
                
                /* add&remove classes */
                _gallery.thumbnailsItemImage.removeClass('active');
                _thumbItemImg.addClass('active');
                
                _gallery.fullImageAnchor.attr('href', _thumbItemImgSrc);
                _gallery.fullImage.attr('src', _thumbItemImgSrc);
                
                /* Réinit zoom */
                _gallery.fullImageAnchor.html(_gallery.fullImage);
                _gallery.fullImageAnchor.zoom();
                
                return false;
            });
        }
        _gallery.fullImageAnchor.zoom();
    }
});
   
