;
(function ($) {
    $.aYaAutoload = function (settings) {
        var _this = this;
        this.settings = $.extend({}, $.aYaAutoload.defaultSettings, settings);
        this.scroller = this.settings.scroller;
        this.container = this.settings.container;
        this.pagesUrl = this.settings.pagesUrl;
        this.currentPageId = this.settings.currentPageId;
        this.tolerate = !this.settings.tolerate || this.settings.tolerate > 1 ? $.aYaAutoload.defaultSettings.tolerate : this.settings.tolerate;
        this.settings.onInit(_this);
        this.bindAutoload();
        if (this.settings.fireFirstPageLoad) {
            this.fireLoadingPage();
        }
    };


    /**
     * 
     * @param {type} elem
     * @returns {undefined}
     */
    $.aYaAutoload.prototype.bindAutoload = function () {
        var _autoloadInst = this;
        if(typeof _autoloadInst.settings.lazyLoadingHandler === 'function'){
            _autoloadInst.settings.lazyLoadingHandler(_autoloadInst);
        }
        _autoloadInst.bindDefaultAutoload();
    };
    /**
     * 
     * @param {type} elem
     * @returns {undefined}
     */
    $.aYaAutoload.prototype.bindDefaultAutoload = function () {
        var _autoloadInst = this;
        _autoloadInst.scroller.on('scroll', function () {
            if ((typeof _autoloadInst.pagesUrl[_autoloadInst.currentPageId] === 'undefined')) {
                _autoloadInst.container.unbind('scroll');
                return;
            }
            //console.log($(this).scrollLeft(), $(this).innerWidth(), $(this)[0].scrollWidth);
            if (($(this).scrollLeft() + $(this).innerWidth()) >= ($(this)[0].scrollWidth - ($(this)[0].scrollWidth * _autoloadInst.tolerate))) {

                _autoloadInst.fireLoadingPage();

            }
        });
    };
    /**
     * 
     * @param {type} elem
     * @returns {undefined}
     */
    $.aYaAutoload.prototype.fireLoadingPage = function () {
        var _autoloadInst = this;
        if (!_autoloadInst.waitingResponse && (typeof _autoloadInst.pagesUrl[_autoloadInst.currentPageId] !== 'undefined')) {
            _autoloadInst.settings.beforeRequest(_autoloadInst);
            _autoloadInst.waitingResponse = 1;
            $.ajax({
                url: _autoloadInst.pagesUrl[_autoloadInst.currentPageId],
                success: function (response) {
                    _autoloadInst.settings.beforeSuccessResponse(_autoloadInst, response);
                    
                    _autoloadInst.settings.onSuccessResponseProcess(_autoloadInst, response);
                    
                    
                    _autoloadInst.waitingResponse = 0;
                    _autoloadInst.currentPageId++;
                    _autoloadInst.settings.afterSuccessResponse(_autoloadInst, response);
                },
                error: function (e1, e2, e3) {
                    console.log(e1, e2, e3);
                    _autoloadInst.waitingResponse = 0;
                }
            });
        }
    };

    /**
     * Default settings
     */
    $.aYaAutoload.defaultSettings = {
        scroller: null,
        container: null,
        pagesUrl: null,
        currentPageId: 0,
        waitingResponse: 0,
        tolerate: 0.2,
        fireFirstPageLoad: true,
        lazyLoadingHandler: null,
        onInit: function (ui) {

        },
        beforeRequest: function (ui) {

        },
        beforeSuccessResponse: function (ui, response) {

        },
        onSuccessResponseProcess: function (ui, response) {
            ui.container.append(response);
        },
        afterSuccessResponse: function (ui, response) {

        }
    };

})(jQuery);