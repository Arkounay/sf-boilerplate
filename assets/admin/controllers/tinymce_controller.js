import { Controller } from '@hotwired/stimulus';
import tinymce from 'tinymce';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/media';
import 'tinymce/plugins/nonbreaking';
import 'tinymce/plugins/directionality';
import 'tinymce/plugins/autoresize';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';
import '../tinymce/tinymce_fr';
import 'tinymce/icons/default';
import 'tinymce/skins/ui/tinymce-5/skin.min.css';

export default class extends Controller {

    static values = {
        triggerChange: Boolean,
        type: String
    }

    connect() {
        function fileBrowser(callback, value, meta) {

            const type = meta.filetype;
            let cmsURL = "/admin/manager/?conf=default&module=tiny";
            if (cmsURL.indexOf("?") < 0) {
                cmsURL = cmsURL + "?type=" + type;
            }
            else {
                cmsURL = cmsURL + "&type=" + type;
            }

            const windowManagerCSS = '<style>' +
                '.tox-dialog {max-width: 100%!important; width:97.5%!important; overflow: hidden; height:95%!important; border-radius:0.25em;}' +
                '.tox-dialog__body { padding: 0!important; }' +
                '.tox .tox-dialog__body-content { max-height: none; }' +
                '.tox .tox-form__group { height: 100% }' +
                '.tox-dialog__body-content > div { height: 100%; overflow:hidden}' +
                '</style > ';

            window.tinymceCallBackURL = '';
            window.tinymceWindowManager = tinymce.activeEditor.windowManager;

            tinymceWindowManager.open({
                title: 'File Manager',
                body: {
                    type: 'panel',
                    items: [{
                        type: 'htmlpanel',
                        html: windowManagerCSS + '<iframe src="' + cmsURL + '" style="width:100%; height:100%"></iframe>'
                    }]
                },
                buttons: [],
                onClose: function () {
                    if (tinymceCallBackURL !== '')
                        callback(encodeURI(tinymceCallBackURL), {}); //to set selected file path
                }
            });
        }

        this.element.style.visibility = 'hidden';

        let options = {
            target: this.element,
            skin: false,
            plugins: 'advlist autolink link emoticons image lists charmap anchor code fullscreen media nonbreaking autoresize directionality',
            convert_urls: false,
            width: "auto",
            statusbar: false,
            image_dimensions: false,
            toolbar: "undo redo | emoticons | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image  | removeformat | code", // styleselect
            extended_valid_elements: "div[*],meta[*],span[*]",
            menubar: false,
            min_height: 200,
            valid_elements: '*[*]',
            remove_script_host: false,
            content_css: '/admin/tinymce_content.min.css',
            cleanup: false,
            contextmenu: false,
            entity_encoding: "raw",
            language: "fr_FR",
            browser_spellcheck: true,
            document_base_url: window.location.origin,
            branding: false,
            // style_formats:[{ title: 'Titre (h2)', format: 'h2' }, { title: 'Titre (h3)', format: 'h3' }, { title: 'Paragraph', format: 'p' }],
            autoresize_overflow_padding: 10,
            autoresize_bottom_margin: 0,
            file_picker_callback: fileBrowser,
            content_style: 'body { background-color: white; padding-left: 10px; padding-top: 0; padding-right: 10px; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;}',
            setup: (editor) => {
                this.editor = editor;
                editor.on('focus', function() {
                    $(editor.contentAreaContainer.offsetParent).addClass('focus');
                });
                editor.on('blur', function() {
                    $(editor.contentAreaContainer.offsetParent).removeClass('focus');
                });
                if (this.triggerChangeValue) {
                    editor.on('change', () => {
                        tinymce.triggerSave();
                        this.element.dispatchEvent(new Event('change'));
                    })
                    editor.on('keyup', () => {
                        tinymce.triggerSave();
                        this.element.dispatchEvent(new Event('keyup'));
                    })
                }
            },
        };

        switch (this.typeValue) {
            case 'large':
                Object.assign(options, {min_height: 500, autoresize_overflow_padding: 25, autoresize_bottom_margin: 0});
                break;
            case 'raw':
                Object.assign(options, {forced_root_block: false})
                break;
        }
        if (this.tmpFix !== undefined && this.previousId === this.element.id) {
            this.element.name = this.tmpFix;
        }
        tinymce.init(options);
    }

    disconnect() {
        if (this.editor !== undefined) {
            this.tmpFix = this.element.name;
            this.previousId = this.element.id;
            this.editor.destroy();
        }
    }

}