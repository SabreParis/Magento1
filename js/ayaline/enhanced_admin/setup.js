/**
 * created : 2013
 *
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

window.Ayaline = window.Ayaline || {};
window.Ayaline.EnhancedAdmin = window.Ayaline.EnhancedAdmin || {};

varienGridAction.run = function (link, confirm) {
    var row, file, type, additionalUrl = '';

    row = link.up('tr');
    file = row.select('select[name="file"]');
    additionalUrl += (file.length == 1) ? 'file/' + file[0].value + '/' : '';
    type = row.select('select[name="type"]');
    additionalUrl += (type.length == 1) ? 'type/' + type[0].value + '/' : '';

    if (confirm && link.title) {
        if (window.confirm(link.title)) {
            setLocation(link.href + additionalUrl);
        }
    } else {
        setLocation(link.href + additionalUrl);
    }

    return false;
};

varienGridAction.getFileContent = function (link) {
    var html = '', params = {}, row, file, type;

    row = link.up('tr');
    file = row.select('select[name="file"]');
    type = row.select('select[name="type"]');

    if (file.length === 1) {
        params.file = file[0].value;
    }
    if (type.length === 1) {
        params.type = type[0].value;
    }

    $('file-container').update(html);

    new Ajax.Request(link.href, {
        parameters: params,
        onSuccess: function (response) {
            html = response.responseText;
            $('file-container').update(html);
        },
        onFailure: function () {
            $('file-container').update('');
        }
    });

    return false;
};

var setSelectTypeStyle = function setSelectTypeStyle(grid, row) {
    row.select('select[name="type"]').each(function (elem) {
        elem.setStyle({ width: '100px' });
    });
};


window.Ayaline.EnhancedAdmin.Actions = Class.create({

    dialogWindowIsClosed: false,
    dialogWindow: null,
    dialogWindowId: new Template('ayaline-enhanced-admin-actions-window', new RegExp('(^|.|\\r|\\n)({{\\s*(\\w+)\\s*}})', "")),
    overlayShowEffectOptions: null,
    overlayHideEffectOptions: null,
    _content: '',

    initialize: function () {
        this.dialogWindowId = this.dialogWindowId.evaluate({ type: this._type });
    },

    _getActions: function (url) {
        new Ajax.Request(url, {
            asynchronous: false,
            method: 'POST',
            onSuccess: function (response) {
                this._content = response.responseText;
            }.bind(this)
        });

        return this._content;
    },

    open: function (url) {
        if ($(this.dialogWindowId) && typeof(Windows) != 'undefined') {
            Windows.focus(this.dialogWindowId);
            return;
        }
        this.dialogWindowIsClosed = false;
        this.overlayShowEffectOptions = Windows.overlayShowEffectOptions;
        this.overlayHideEffectOptions = Windows.overlayHideEffectOptions;
        Windows.overlayShowEffectOptions = {duration: 0};
        Windows.overlayHideEffectOptions = {duration: 0};
        this._getActions(url);

        this.dialogWindow = Dialog.info(this._content, {
            draggable: true,
            resizable: false,
            closable: true,
            className: "magento",
            windowClassName: "popup-window",
            title: '',
            width: 700,
//            height: 250,
            zIndex: 1000,
            recenterAuto: false,
            hideEffect: Element.hide,
            showEffect: Element.show,
            id: this.dialogWindowId,
            onClose: this.closeForm.bind(this)
        });
        this._content.evalScripts.bind(this._content).defer();
    },

    closeForm: function (window) {
        if (!window) {
            window = this.dialogWindow;
        }
        if (window && !this.dialogWindowIsClosed) {
            this.dialogWindowIsClosed = true;
            window.close();
            Windows.overlayShowEffectOptions = this.overlayShowEffectOptions;
            Windows.overlayHideEffectOptions = this.overlayHideEffectOptions;
        }
    }

});

var setupActions = new Ayaline.EnhancedAdmin.Actions();
