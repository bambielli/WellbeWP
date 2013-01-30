(function () {
    var a = tinymce.DOM;
    tinymce.create( 'tinymce.plugins.WooThemes_References', {
        mceTout: 0,
        init: function ( c, d ) { // c = editor instance, d = URL to current directory
        
        	c.addButton( 'WooThemes_References', {
                title: 'Insert Reference',
                image: d + '/img/icon.png',
                cmd: 'WooThemes_References_OpenDialog'
            });
        
            var e = this,
                h = c.getParam( 'wordpress_adv_toolbar', 'toolbar2' ),
                g = 0,
                f, b;
            c.onPostRender.add(function () {
                var i = c.controlManager.get(h);
                if (c.getParam("wordpress_adv_hidden", 1) && i) {
                    a.hide(i.id);
                    e._resizeIframe(c, h, 28)
                }
            });
            
            // Insert the reference into the content
        	c.addCommand( 'Woo_Reference', function ( a, tag ) {
        		tag = e._createImgTag( tag, d );
                c.execCommand( 'mceInsertContent', 0, tag );
            });
            
            // Add a command to open the thickbox.
            c.addCommand( 'WooThemes_References_OpenDialog', function () {
            	 jQuery.get( d + "/dialog.php", function( b ) {
	            	jQuery( '#woo-dialog' ).remove();
					jQuery( 'body' ).append( b );
					jQuery( '#woo-dialog' ).hide();
					var f=jQuery( window ).width();
					b=jQuery( window ).height();
					f=720<f?720:f;
					f-=80;
					b-=84;
				
					tb_show( 'Insert Reference', '#TB_inline?width=' + f + '&height=' + b + '&inlineId=woo-dialog' );
	            });
            });
            
            c.onBeforeExecCommand.add(function (p, m, s, l, j) {
                var v = tinymce.DOM,
                    k, i, r, u, t, q;
            });
            c.onInit.add(function (i) {
                i.onBeforeSetContent.add(function (j, k) {
                    if (k.content) {
                        k.content = k.content.replace(/<p>\s*<(p|div|ul|ol|dl|table|blockquote|h[1-6]|fieldset|pre|address)( [^>]*)?>/gi, "<$1$2>");
                        k.content = k.content.replace(/<\/(p|div|ul|ol|dl|table|blockquote|h[1-6]|fieldset|pre|address)>\s*<\/p>/gi, "</$1>")
                    }
                })
            });
            c.onSaveContent.add(function (i, j) {
                if ( typeof (switchEditors) == 'object' ) {
                    if (i.isHidden()) {
                        j.content = j.element.value
                    } else {
                        j.content = switchEditors.pre_wpautop( j.content )
                    }
                }
            });
            
            // Add listeners to handle references
			e._handleReference( c, d );
            
            c.onInit.add(function (i) {
                tinymce.dom.Event.add(i.getWin(), 'scroll', function ( j ) {
                    i.plugins.WooThemes_References._hideButtons()
                });
                tinymce.dom.Event.add(i.getBody(), 'dragstart', function ( j ) {
                    i.plugins.WooThemes_References._hideButtons()
                })
            });
            c.onBeforeExecCommand.add( function ( i, k, j, l ) {
                i.plugins.WooThemes_References._hideButtons();
            });
            c.onSaveContent.add( function ( i, j ) {
                i.plugins.WooThemes_References._hideButtons()
            });
            c.onMouseDown.add( function ( i, j ) {
                if ( j.target.nodeName != 'IMG' ) {
                    i.plugins.WooThemes_References._hideButtons()
                }
            });
        
        },
        
        createControl : function(n, cm) {
            return null;
        },
        
        getInfo: function () {
            return {
                longname: 'WooThemes References',
                author: 'WooThemes',
                authorurl: 'http://woothemes.com/',
                infourl: 'http://woothemes.com/',
                version: '1.0.0'
            }
        },
        _showButtons: function (f, d) {
            var g = tinyMCE.activeEditor,
                i, h, b, j = tinymce.DOM,
                e, c;
            b = g.dom.getViewPort(g.getWin());
            i = j.getPos(g.getContentAreaContainer());
            h = g.dom.getPos(f);
            e = Math.max(h.x - b.x, 0) + i.x;
            c = Math.max(h.y - b.y, 0) + i.y;
            j.setStyles(d, {
                top: c + 5 + 'px',
                left: e + 5 + 'px',
                display: 'block'
            });
            if ( this.mceTout ) {
                clearTimeout( this.mceTout )
            }
            this.mceTout = setTimeout(function () {
                g.plugins.WooThemes_References._hideButtons()
            }, 5000)
        },
        _hideButtons: function () {
            if ( ! this.mceTout ) {
                return
            }
            clearTimeout( this.mceTout );
            this.mceTout = 0
        },
        _resizeIframe: function ( c, e, b ) {
            var d = c.getContentAreaContainer().firstChild;
            a.setStyle( d, 'height', d.clientHeight + b );
            c.theme.deltaHeight += b
        },
        _handleReference: function (c, d) {
            var e;
            
            e = '<img src="' + d + '/img/trans.gif" alt="' + '$1' + '" class="mceWooReference mceItemNoResize" title="' + 'Reference Number $1' + '" />';
            c.onInit.add(function () {
                c.dom.loadCSS( d + '/css/content.css' )
            });
            c.onPostRender.add(function () {
                if ( c.theme.onResolveName ) {
                    c.theme.onResolveName.add(function (f, g) {
                        if ( g.node.nodeName == 'IMG' ) {
                            if (c.dom.hasClass( g.node, 'mceWooReference' ) ) {
                                g.name = 'wooreference'
                            }
                        }
                    })
                }
            });
            c.onBeforeSetContent.add(function (f, g) {
                if (g.content) {
                    g.content = g.content.replace(/<!--reference(.*?)?-->/g, e);
                    c.dom.loadCSS( d + '/css/content.css' )
                }
            });
            
            c.onPostProcess.add(function (f, g) {
                if (g.get) {
                    g.content = g.content.replace(/<img[^>]+>/g, function (i) {
                        if (i.indexOf( 'class="mceWooReference' ) !== -1 ) {
                            var h, j = (h = i.match(/alt="(.*?)"/)) ? h[1] : "";
                            i = '<!--reference' + j + '-->'
                        }
                        return i
                    })
                }
            });
            
            c.onNodeChange.add(function (g, f, h) {
                f.setActive( 'WooThemes_References', h.nodeName === 'IMG' && g.dom.hasClass( h, 'mceWooReference' ) );
            });
        }, 
        
        // Generate the image tag, used to render the icon in the post content of the tinyMCE editor, while preserving the HTML comment when in "HTML" view.
        // Called just before the content is inserted into tinyMCE.
        _createImgTag: function ( tag, d ) {
        	var e = '<img src="' + d + '/img/trans.gif" alt="' + '$1' + '" class="mceWooReference mceItemNoResize" title="' + 'Reference Number $1' + '" />';
        	var image = tag.replace(/<!--reference(.*?)-->/g, e );
        	return image;
        }
    });
    tinymce.PluginManager.add( 'WooThemes_References', tinymce.plugins.WooThemes_References );
})();