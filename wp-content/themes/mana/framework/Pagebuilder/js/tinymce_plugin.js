(function() {

	if( typeof tinymce!=='undefined' ){

		if( typeof(tinymce.majorVersion)!=='undefined' && parseInt(tinymce.majorVersion)<4 ){

			tinymce.create( "tinymce.plugins.themeton_shortcode", {
					init: function(ed,e) { },
					createControl:function(d,e)
					{

						var ed = tinymce.activeEditor;

						if(d=="themeton_shortcode"){

							d=e.createMenuButton( "themeton_shortcode",{
								title: "Themeton Shortcode",
								icons: false
								});

								var a=this;d.onRenderMenu.add(function(c,b){

									
									a.addImmediate(b, "Button", '[blox_button text="" link="#" target="_self" style="default" color="#dd3333" icon="icon-thumbs-up-alt" size="medium" align="left" /]' );
									a.addImmediate(b, "Icon", '[blox_icon style="blox_elem_icon_circle" color="#1e73be" icon="icon-thumbs-up-alt" size="48" align="left"]' );
									a.addImmediate(b, "Iconic List", '[blox_list title="Title" icon="icon-thumbs-up-alt" color="#1e73be"]<ul><li>item 1</li><li>item 2</li></ul>[/blox_list]' );

									b.addSeparator();

									a.addImmediate(b, "Blog", '[blox_blog title="" style="regular" categories="all" content="both" count="10" pager="yes" filter="no" overlay="permalink" order="default"/]' );

									b.addSeparator();

									c=b.addMenu({title: "Media Shortcodes"});
											a.addImmediate(c, "Audio", '[blox_audio title="" type="url" url="" color="#000000" /]' );
											a.addImmediate(c, "Video", '[blox_audio title="" type="url" url="" color="#000000" /]' );

								});
							return d

						} 

						return null
					},
					addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				}
			);

			tinymce.PluginManager.add( "themeton_shortcode", tinymce.plugins.themeton_shortcode);

		}
		else{

			tinymce.PluginManager.add('themeton_shortcode', function(editor, url) {

				editor.addButton('themeton_shortcode', {
		            type: 'menubutton',
		            text: 'TT Shortcode',
		            icon: false,
		            menu: [
	                	{ text: "Button", onclick: function(){
	                		editor.insertContent('[blox_button text="" link="#" target="_self" style="default" color="#dd3333" icon="icon-thumbs-up-alt" size="medium" align="left" /]');
	                	}},
	                	{ text: "Icon", onclick: function(){
	                		editor.insertContent('[blox_icon style="blox_elem_icon_circle" color="#1e73be" icon="icon-thumbs-up-alt" size="48" align="left"]');
	                	}},
	                	{ text: "Iconic List", onclick: function(){
	                		editor.insertContent('[blox_list title="Title" icon="icon-thumbs-up-alt" color="#1e73be"]<ul><li>item 1</li><li>item 2</li></ul>[/blox_list]');
	                	}},
	                	{ text: "Blog", onclick: function(){
	                		editor.insertContent('[blox_blog title="" style="regular" categories="all" content="both" count="10" pager="yes" filter="no" overlay="permalink" order="default"/]');
	                	}},
	                	{ 
	                		text: "Media Shortcodes", onclick: function(){},
	                		menu: [
	                			{ text: "Audio", onclick: function(){
			                		editor.insertContent('[blox_audio title="" type="url" url="" color="#000000" /]');
			                	}},
			                	{ text: "Video", onclick: function(){
			                		editor.insertContent('[blox_audio title="" type="url" url="" color="#000000" /]');
			                	}}
	                		]
	                	}
		            ]
		            
		        });
			});

		}

	}

})();