

function get_blox_element_rawhtml($content, $attrs){
	$tools = '<div class="blox_item_actions"> \
					<a href="javascript:;" class="icon-pencil action_edit"></a> \
					<a href="javascript:;" class="icon-copy action_clone"></a> \
					<a href="javascript:;" class="icon-times action_remove"></a> \
				</div>';
	return '<div class="blox_item blox_raw" '+($attrs!=undefined ? $attrs : '')+'> \
				<div class="blox_item_title"><i class="icon-unchecked"></i> Raw HTML and JS'+$tools+'</div> \
				<div class="blox_item_content" style="display:none;">'+($content!=undefined ? $content : '')+'</div> \
			</div>';
}


function parse_shortcode_rawhtml($content){
	$content = wp.shortcode.replace( 'blox_raw', $content, function(data){
		var attrs = '';
		jQuery.each(data.attrs.named, function(key, value){
			if( key=='title' && value!='undefined' ){
				attrs += key+'="'+value+'" ';
			}
			if( key=='animation' && value!='undefined' ){
				attrs += key+'="'+value+'" ';
			}
			if( key=='extra_class' && value!='undefined' ){
				attrs += key+'="'+value+'" ';
			}
		})
		return get_blox_element_rawhtml(data.content, attrs);
	});
	return $content;
}


function revert_shortcode_rawhtml($content){
	$content.find('.blox_raw').each(function(){
		attr = '';
		var temp_val = '';

        temp_val = jQuery(this).attr('title')+'';
        if( temp_val!='undefined' && temp_val!='' ){
            attr += ' title="'+temp_val+'"';
        }

        temp_val = jQuery(this).attr('animation')+'';
        if( temp_val!='undefined' && temp_val!='' ){
            attr += ' animation="'+temp_val+'"';
        }

        temp_val = jQuery(this).attr('extra_class')+'';
        if( temp_val!='undefined' && temp_val!='' ){
            attr += ' extra_class="'+temp_val+'"';
        }
		
		jQuery(this).replaceWith('[blox_raw'+attr+']'+jQuery(this).find('> .blox_item_content').html()+'[/blox_raw]');
	});
	return $content;
}


function add_event_blox_element_rawhtml(){
	
	jQuery('.blox_raw').each(function(){
		var $this = jQuery(this);
		
		$this.find('.blox_item_actions .action_edit').unbind('click')
			.click(function(){

				var form_element = [
                			{
                				type: 'input',
                				id: 'blox_option_title',
                				label: 'Title',
                				value: $this.attr('title')
                			},
                			{
                				type: 'textarea',
                				id: 'blox_option_rawhtml',
                				label: 'Content',
                				value: jQuery('<div/>').html($this.find('.blox_item_content').html()).text()
                			}
            			];

                show_blox_form('Edit Raw Element', form_element, function($form){
                    $this.attr('title', jQuery('#blox_option_title').val());
                    $this.find('.blox_item_content').html( jQuery('<div />').text(jQuery('#blox_option_rawhtml').val()).html() );
                },
	            {
	                target: $this,
                	extra_field: true
	            });
			});
			
		
		$this.find('.blox_item_actions .action_clone').unbind('click')
			.click(function(){
				$this.after($this.clone());
				add_event_blox_element_rawhtml();
			});
			
		$this.find('.blox_item_actions .action_remove').unbind('click')
			.click(function(){
				$this.remove();
			});
	});
	
}
