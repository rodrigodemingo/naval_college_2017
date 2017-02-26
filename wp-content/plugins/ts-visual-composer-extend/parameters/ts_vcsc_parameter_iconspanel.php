<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_IconsPanel')) {
        class TS_Parameter_IconsPanel {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('icons_panel', array(&$this, 'iconspanel_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('icons_panel', array(&$this, 'iconspanel_settings_field'));
				}
            }        
            function iconspanel_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_EXTENSIONS;
                $dependency     	= vc_generate_dependencies_attributes($settings);
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
                $default			= isset($settings['default']) ? $settings['default'] : '';
				$visual				= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector;
				$parameters			= isset($settings['settings']) ? $settings['settings'] : array();
				// Extract Custom Icon Picker Settings
				$icons_type			= isset($parameters['type']) ? $parameters['type'] : "extensions";
				if ($icons_type == "extensions") {
					$icons_source	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_List_Icons_Compliant;
				} else if ($icons_type == "rating") {
					$icons_source	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_RatingScaleIconsCompliant;
				} else if ($icons_type == "hovereffect") {
					$icons_source	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_HoverEffectsIconsSelectionCompliant;
				} else if ($icons_type == "navigator") {
					$icons_source	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_NavigatorIconsCompliant;
				} else if ($icons_type == "timeline") {
					$icons_source	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_TimelineDateTimeCompliant;
				} else {
					$icons_source   = isset($parameters['source']) ? $parameters['source'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_List_Icons_Compliant;
				}
				$icons_empty		= isset($parameters['emptyIcon']) ? $parameters['emptyIcon'] : "true";
				if ($icons_empty == true) {
					$icons_empty	= "true";
				} else if ($icons_empty == false) {
					$icons_empty	= "false";
				}
				$icons_transparent 	= isset($parameters['emptyIconValue']) ? $parameters['emptyIconValue'] : "";
				$icons_search		= isset($parameters['hasSearch']) ? $parameters['hasSearch'] : "true";
				if ($icons_search == true) {
					$icons_search	= "true";
				} else if ($icons_search == false) {
					$icons_search	= "false";
				}				
				$icons_pagination	= isset($parameters['iconsPerPage']) ? $parameters['iconsPerPage'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorPager;
				// Other Settings
                $url            	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath;
				$randomizer			= mt_rand(999999, 9999999);
                $output         	= '';
				// Icon Picker Output
                if (($visual == "true") || ($override == "true")) {
					$output .= '<div id="ts-font-icons-picker-parent-' . $randomizer . '" class="ts-font-icons-picker-parent">';
						// Icon Picker
						if (($value == "") && ($default != "")) {
							$value	= $default;
						}
                        $output .= '<div id="ts-font-icons-picker-' . $param_name . '" class="ts-visual-selector ts-font-icons-picker" data-value="' . $value . '" data-theme="inverted" data-empty="' . $icons_empty . '" data-transparent="' . $icons_transparent . '" data-search="' . $icons_search . '" data-pagecount="' . $icons_pagination . '">';
							$iconGroups 			= array();
							$output .= '<select id="' . $param_name . '" name="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" value="' . $value . '">';
								// Add Empty Placeholder
								if ($icons_empty == "true") {
									if (($value == "") || ($value == "transparent")) {
										$output .= '<option value="" selected="selected"></option>';
									} else {
										$output .= '<option value=""></option>';
									}
								}
								// Add Built-In Fonts (based on provided Source)              
								foreach ($icons_source as $group => $icons) {
									if (!is_array($icons) || !is_array(current($icons))) {
										$font		= "";
									} else {									
										$font		= str_replace("(", "", esc_attr($group));
										$font		= str_replace(")", "", $font);
									}
									if (($font != "") && (!in_array($font, $iconGroups))) {
										$output .= '<optgroup label="' . $font . '">';
									}									
									if (!is_array($icons) || !is_array(current($icons))) {
										$class_key      = key($icons);
										$class_group    = explode('-', esc_attr($class_key));
										if (($class_group[0] != "dashicons") && ($class_group[0] != "transparent")) {
											if ($value == esc_attr($class_key)) {
												$output .= '<option value="' . esc_attr($class_key) . '" selected="selected">' . esc_attr($class_key) . '</option>';
											} else {
												$output .= '<option value="' . esc_attr($class_key) . '">' . esc_attr($class_key) . '</option>';
											}
										} else {
											if ($value == esc_attr($class_key)) {
												$output .= '<option value="' . esc_attr($class_key) . '" selected="selected">' . esc_attr($class_key) . '</option>';
											} else {
												$output .= '<option value="' . esc_attr($class_key) . '">' . esc_attr($class_key) . '</option>';
											}
										}
									} else {
										foreach ($icons as $key => $label) {
											$class_key      = key($label);
											$class_group    = explode('-', esc_attr($class_key));
											$font           = str_replace("(", "", strtolower(strtolower(esc_attr($group))));
											$font           = str_replace(")", "", strtolower($font));
											if (($class_group[0] != "dashicons") && ($class_group[0] != "transparent")) {
												if ($value == esc_attr($class_key)) {
													$output .= '<option value="' . esc_attr($class_key) . '" selected="selected">' . esc_attr($class_key) . '</option>';
												} else {
													$output .= '<option value="' . esc_attr($class_key) . '">' . esc_attr($class_key) . '</option>';
												}
											} else {
												if ($value == esc_attr($class_key)) {
													$output .= '<option value="' . esc_attr($class_key) . '" selected="selected">' . esc_attr($class_key) . '</option>';
												} else {
													$output .= '<option value="' . esc_attr($class_key) . '">' . esc_attr($class_key) . '</option>';
												}
											}
										}
									}									
									if (($font != "") && (!in_array($font, $iconGroups))) {
										$output .= '</optgroup>';
										array_push($iconGroups, $font);
									}
								}
								// Add Custom Upload Font
								if ((get_option('ts_vcsc_extend_settings_tinymceCustom', 0) == 1) && ($custom == "true")) {                       
									foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Icons_Compliant_Custom as $group => $icons) {
										if (!is_array($icons) || !is_array(current($icons))) {
											$class_key      = key($icons);
											$class_group    = explode('-', esc_attr($class_key));
											if ($value == esc_attr($class_key)) {
												$output .= '<option value="' . esc_attr($class_key) . '" selected="selected">' . esc_attr($class_key) . '</option>';
											} else {
												$output .= '<option value="' . esc_attr($class_key) . '">' . esc_attr($class_key) . '</option>';
											}
										} else {
											foreach ($icons as $key => $label) {
												$class_key      = key($label);
												$class_group    = explode('-', esc_attr($class_key));
												$font           = str_replace("(", "", strtolower(strtolower(esc_attr($group))));
												$font           = str_replace(")", "", strtolower($font));
												if ($value == esc_attr($class_key)) {
													$output .= '<option value="' . esc_attr($class_key) . '" selected="selected">' . esc_attr($class_key) . '</option>';
												} else {
													$output .= '<option value="' . esc_attr($class_key) . '">' . esc_attr($class_key) . '</option>';
												}
											}
										}
									}                            
								}
							$output .= '</select>';
                        $output .= '</div>';
                    $output .= '</div>';
                } else {
					$output .= '<div id="ts-font-icons-manual-parent-' . $randomizer . '" class="ts-font-icons-manual-parent ts-settings-parameter-gradient-grey">';
						$previewURL = site_url() . '/wp-admin/admin.php?page=TS_VCSC_Previews';			
						$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="text" value="' . $value . '"/>';
						$output .= '<a href="' . $previewURL . '" target="_blank">' . __( "Find Icon Class Name", "ts_visual_composer_extend" ) . '</a>';
					$output .= '</div>';
                }
                return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_IconsPanel')) {
        $TS_Parameter_IconsPanel = new TS_Parameter_IconsPanel();
    }
?>