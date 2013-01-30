var wooDialogHelper = {

    needsPreview: false,
    setUpButtons: function () {
        var a = this;
        jQuery( "#woo-btn-cancel").click(function () {
            a.closeDialog()
        });
        jQuery( "#woo-btn-insert").click(function () {
            a.insertAction()
        });
    },
    
    initPreviewAction: function () {
    	var a = this;
    	
    	jQuery( '#woo-options ul > li' ).click( function ( e ) {
    		jQuery( '.selected' ).removeClass( 'selected' );
    		jQuery( this ).addClass( 'selected' ).find( '.reference-selector' ).attr( 'checked', 'checked' );
    		var previewData = jQuery( this ).find( '.reference-preview-data' ).html();
			jQuery( '#woo-preview .preview-data' ).html( previewData );
    	});
    	
		jQuery( '.reference-selector' ).change( function ( e ) {
			jQuery( '.selected' ).removeClass( 'selected' );
			var previewData = jQuery( this ).parent().find( '.reference-preview-data' ).html();
			jQuery( '#woo-preview .preview-data' ).html( previewData );
			jQuery( this ).parents( 'li' ).addClass( 'selected' );
		});
    },

    insertAction: function () {
        if ( jQuery( '.reference-selector:checked' ).length ) {
        	var selected_id = jQuery( '.reference-selector:checked' ).val();
        	var tag = '<!--reference' + selected_id + '-->';
        	tinyMCE.activeEditor.execCommand( 'Woo_Reference', false, tag );
        	this.closeDialog();
        }
    },

    closeDialog: function () {
        this.needsPreview = false;
        tb_remove();
        jQuery( "#woo-dialog").remove();
    }

};

wooDialogHelper.setUpButtons();
wooDialogHelper.initPreviewAction();