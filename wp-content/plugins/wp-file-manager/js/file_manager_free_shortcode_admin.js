jQuery(document).ready(function () {
    var security_key = fmfparams.nonce;
    var fmlang = fmfparams.lang;
    var ajaxurl = fmfparams.ajaxurl;
    jQuery('#wp_file_manager').elfinder({
        url: ajaxurl,
        customData: {
            action: 'mk_file_folder_manager',
            _wpnonce: security_key
        },
        uploadMaxChunkSize: 1048576000000,
        defaultView: 'list',
        height: 500,
        lang: fmlang,
        /* Start */
        handlers: {
            /* Upload */
            upload: function (event, instance) {
                if (fmfparams.fm_enable_media_upload) {
                    var filepaths = [];
                    var uploadedFiles = event.data.added;
                    for (i in uploadedFiles) {
                        var file = uploadedFiles[i];
                        filepaths.push(file.url);
                    }
                    if (filepaths != '') {
                        var data = {
                            'action': 'mk_file_folder_manager_media_upload',
                            'uploadefiles': filepaths,
							'_wpnonce': security_key
                        };
                        jQuery.post(ajaxurl, data, function (response) {

                        });
                    }
                }
            },
        },

        commandsOptions: {
            edit: {

                mimes: [],

                editors: [{

                    mimes: ['text/plain', 'text/html', 'text/javascript', 'text/css', 'text/x-php', 'application/x-php'],

                    load: function (textarea) {
                        var mimeType = this.file.mime;
                        var filename = this.file.name;
                        // CodeMirror configure
                        editor = CodeMirror.fromTextArea(textarea, {
                            //mode: 'css',
                            indentUnit: 4,
                            lineNumbers: true,
                            theme: "3024-day",
                            viewportMargin: Infinity,
                            lineWrapping: true,
                            //gutters: ["CodeMirror-lint-markers"],
                            lint: true
                        });
                        return editor;


                    },
                    close: function (textarea, instance) {
                        this.myCodeMirror = null;
                    },

                    save: function (textarea, editor) {
                        jQuery(textarea).val(editor.getValue());
                    }

                }]
            },

        }

        /* END */
    }).elfinder('instance');
});