<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	
    // TS Lightbox Gallery (Deprecated)
	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                          => __( "TS Lightbox Gallery (Deprecated)", "ts_visual_composer_extend" ),
		"base"                          => "TS-VCSC-Lightbox-Gallery",
		"icon" 	                        => "ts-composer-element-icon-lightbox-gallery",
		"category"                      => __( 'Deprecated', "ts_visual_composer_extend" ),
		"description"                   => __("Place multiple images in a lightbox element", "ts_visual_composer_extend"),
		"admin_enqueue_js"              => "",
		"admin_enqueue_css"             => "",
		"deprecated" 					=> "2.3.0",
		"content_element"				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseDeprecatedElements == "true" ? true : false,
		"params"                        => array(
			// Gallery Content
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_1",
				"seperator"				=> "Gallery Content",
			),
			array(
				"type"                  => "attach_images",
				"heading"               => __( "Select Images", "ts_visual_composer_extend" ),
				"param_name"            => "content_images",
				"value"                 => "",
				"admin_label"           => true,
				"description"           => __( "Select the images for your gallery overlay; move images to arrange order in which to display.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "content_type", 'value' => 'gallery' )
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Preview Image Source", "ts_visual_composer_extend" ),
				"param_name"            => "content_images_size",
				"width"                 => 150,
				"holder"				=> "custom",
				"value"                 => array(
					__( 'Medium Size Image', "ts_visual_composer_extend" )			=> "medium",
					__( 'Large Size Image', "ts_visual_composer_extend" )			=> "large",
					__( 'Full Size Image', "ts_visual_composer_extend" )			=> "full",
				),
				"admin_label"           => false,
				"description"           => __( "Select which image size based on WordPress settings should be used for the preview image.", "ts_visual_composer_extend" ),
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Lightbox Image Source", "ts_visual_composer_extend" ),
				"param_name"            => "lightbox_size",
				"width"                 => 150,
				"holder"				=> "custom",
				"value"                 => array(
					__( 'Full Size Image', "ts_visual_composer_extend" )			=> "full",
					__( 'Large Size Image', "ts_visual_composer_extend" )			=> "large",
					__( 'Medium Size Image', "ts_visual_composer_extend" )		=> "medium",
				),
				"admin_label"           => false,
				"description"           => __( "Select which image size based on WordPress settings should be used for the lightbox image.", "ts_visual_composer_extend" ),
			),
			array(
				"type"                  => "exploded_textarea",
				"heading"               => __( "Image Titles", "ts_visual_composer_extend" ),
				"param_name"            => "content_images_titles",
				"value"                 => "",
				"description"           => __( "Enter titles for images; separate by line break; use an empty line for image without title.", "ts_visual_composer_extend" ),
			),
			// Gallery Info
			array(
				"type"                  => "textfield",
				"heading"               => __( "Gallery Title", "ts_visual_composer_extend" ),
				"param_name"            => "content_title",
				"value"                 => "",
				"description"           => __( "Enter a title for the gallery itself; leave empty if you don't want to show a title.", "ts_visual_composer_extend" ),
				"group" 				=> "Gallery Info",
			),
			array(
				"type"		            => "textarea_html",
				"heading"               => __( "Gallery Description", "ts_visual_composer_extend" ),
				"param_name"            => "content",
				"value"                 => "",
				"admin_label"           => false,
				"description"           => __( "Create a detailed description / summary for the gallery.", "ts_visual_composer_extend" ),
				"group" 				=> "Gallery Info",
			),
			// Display Settings
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_2",
				"seperator"				=> "Display Settings",
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Display Style", "ts_visual_composer_extend" ),
				"param_name"            => "content_style",
				"width"                 => 150,
				"value"                 => array(
					__( 'Auto Grid of all Images', "ts_visual_composer_extend" )		=> "grid",
					__( 'First Image Only', "ts_visual_composer_extend" )				=> "first",
					__( 'Single Custom Image', "ts_visual_composer_extend" )			=> "image",
				),
				"description"           => __( "Select how the lightbox should be previewed on your page.", "ts_visual_composer_extend" ),
				"dependency"            => ""
			),
			array(
				"type"                  => "attach_image",
				"heading"               => __( "Select Image", "ts_visual_composer_extend" ),
				"param_name"            => "content_trigger_image",
				"value"                 => "",
				"description"           => __( "Select the trigger image for lightbox gallery.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "content_style", 'value' => 'image' )
			),
			array(
				"type"                  => "textfield",
				"heading"               => __( "Enter TITLE Attribute", "ts_visual_composer_extend" ),
				"param_name"            => "content_trigger_title",
				"value"                 => "",
				"admin_label"           => true,
				"description"           => __( "Enter a title for the image that triggers the lightbox.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "content_style", 'value' => 'image' )
			),
			array(
				"type"                  => "textfield",
				"heading"               => __( "Grid Break Points", "ts_visual_composer_extend" ),
				"param_name"            => "data_grid_breaks",
				"value"                 => "240,480,720,960",
				"description"           => __( "Define the break points (columns) for the grid based on available screen size; separate by comma.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "content_style", 'value' => 'grid' )
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Grid Space", "ts_visual_composer_extend" ),
				"param_name"            => "data_grid_space",
				"value"                 => "2",
				"min"                   => "0",
				"max"                   => "20",
				"step"                  => "1",
				"unit"                  => 'px',
				"description"           => __( "Define the space between images in grid.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "content_style", 'value' => 'grid' )
			),
			array(
				"type"              	=> "switch_button",
				"heading"			    => __( "Maintain Image Order", "ts_visual_composer_extend" ),
				"param_name"		    => "data_grid_order",
				"value"				    => "false",
				"description"		    => __( "Switch the toggle to keep original image order in grid; it is adviced to have the plugin determine order for best layout.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "content_style", 'value' => 'grid' )
			),
			// Lightbox Settings
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_3",
				"seperator"				=> "Lightbox Settings",
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"              	=> "switch_button",
				"heading"			    => __( "Open on Pageload", "ts_visual_composer_extend" ),
				"param_name"		    => "lightbox_pageload",
				"value"				    => "false",
				"description"		    => __( "Switch the toggle if you want automatically open the lightbox gallery on page load.", "ts_visual_composer_extend" ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Thumbnail Position", "ts_visual_composer_extend" ),
				"param_name"            => "thumbnail_position",
				"width"                 => 150,
				"value"                 => array(
					__( 'Bottom', "ts_visual_composer_extend" )       => "bottom",
					__( 'Top', "ts_visual_composer_extend" )          => "top",
					__( 'Left', "ts_visual_composer_extend" )         => "left",
					__( 'Right', "ts_visual_composer_extend" )        => "right",
					__( 'None', "ts_visual_composer_extend" )         => "0",
				),
				"admin_label"           => true,
				"description"           => __( "Select the position of the thumbnails in the lightbox.", "ts_visual_composer_extend" ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Thumbnail Height", "ts_visual_composer_extend" ),
				"param_name"            => "thumbnail_height",
				"value"                 => "100",
				"min"                   => "50",
				"max"                   => "200",
				"step"                  => "1",
				"unit"                  => 'px',
				"description"           => __( "Define the height of the thumbnails in the lightbox.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "thumbnail_position", 'value' => array('bottom', 'top', 'left', 'right') ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Transition Effect", "ts_visual_composer_extend" ),
				"param_name"            => "lightbox_effect",
				"width"                 => 150,
				"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Animations,
				"default" 				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Default_Animation,
				"std" 					=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Default_Animation,
				"admin_label"           => true,
				"description"           => __( "Select the transition effect to be used for each image in the lightbox.", "ts_visual_composer_extend" ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"              	=> "switch_button",
				"heading"			    => __( "Autoplay Lightbox", "ts_visual_composer_extend" ),
				"param_name"		    => "lightbox_autoplay",
				"value"				    => "false",
				"description"		    => __( "Switch the toggle if you want start an autoplay of the gallery once opened.", "ts_visual_composer_extend" ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Autoplay Speed", "ts_visual_composer_extend" ),
				"param_name"            => "lightbox_speed",
				"value"                 => "5000",
				"min"                   => "1000",
				"max"                   => "20000",
				"step"                  => "100",
				"unit"                  => 'ms',
				"description"           => __( "Define the speed at which autoplay should rotate between images.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "lightbox_autoplay", 'value' => 'true' ),
				"group" 				=> "Lightbox Settings",
			),
			
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Backlight Effect", "ts_visual_composer_extend" ),
				"param_name"            => "lightbox_backlight",
				"width"                 => 150,
				"value"                 => array(
					__( 'Auto Color', "ts_visual_composer_extend" )       											=> "auto",
					__( 'Custom Color', "ts_visual_composer_extend" )     											=> "custom",
					__( 'Transparent Backlight', "ts_visual_composer_extend" )     	=> "hideit",
				),
				"admin_label"           => true,
				"description"           => __( "Select the backlight effect for the gallery images.", "ts_visual_composer_extend" ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"                  => "colorpicker",
				"heading"               => __( "Custom Backlight Color", "ts_visual_composer_extend" ),
				"param_name"            => "lightbox_backlight_color",
				"value"                 => "#ffffff",
				"description"           => __( "Define the backlight color for the gallery images.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "lightbox_backlight", 'value' => 'custom' ),
				"group" 				=> "Lightbox Settings",
			),
			array(
				"type"              	=> "switch_button",
				"heading"			    => __( "Social Share Buttons", "ts_visual_composer_extend" ),
				"param_name"		    => "lightbox_social",
				"value"				    => "true",
				"description"		    => __( "Switch the toggle if you want show social share buttons with deeplinking for each image.", "ts_visual_composer_extend" ),
				"group" 				=> "Lightbox Settings",
			),
			// Other Settings
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_4",
				"seperator"				=> "Other Settings",
				"group" 				=> "Other Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Margin: Top", "ts_visual_composer_extend" ),
				"param_name"            => "margin_top",
				"value"                 => "0",
				"min"                   => "0",
				"max"                   => "200",
				"step"                  => "1",
				"unit"                  => 'px',
				"description"           => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
				"group" 				=> "Other Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Margin: Bottom", "ts_visual_composer_extend" ),
				"param_name"            => "margin_bottom",
				"value"                 => "0",
				"min"                   => "0",
				"max"                   => "200",
				"step"                  => "1",
				"unit"                  => 'px',
				"description"           => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
				"group" 				=> "Other Settings",
			),
			array(
				"type"                  => "textfield",
				"heading"               => __( "Define ID Name", "ts_visual_composer_extend" ),
				"param_name"            => "el_id",
				"value"                 => "",
				"description"           => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
				"group" 				=> "Other Settings",
			),
			array(
				"type"                  => "tag_editor",
				"heading"           	=> __( "Extra Class Names", "ts_visual_composer_extend" ),
				"param_name"            => "el_class",
				"value"                 => "",
				"description"      		=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
				"group" 				=> "Other Settings",
			),
		)
	);
	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	}
?>