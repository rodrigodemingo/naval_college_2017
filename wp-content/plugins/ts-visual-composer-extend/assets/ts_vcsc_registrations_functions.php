<?php
	// Function to Create Custom Image Sizes
	// -------------------------------------
	if (!function_exists('TS_VCSC_CreateCustomImageSize')){
		function TS_VCSC_CreateCustomImageSize($id, $width, $height) {
			if (function_exists('wpb_getImageBySize')) {
				$return						= array();
				$image 						= wpb_getImageBySize(array('attach_id' => $id, 'thumb_size' => $width . 'x' . $height, 'class' => ''));
				$thumbnail					= $image['thumbnail'];
				preg_match('/(src=["\'](.*?)["\'])/', $thumbnail, $match);
				$thumbnail 					= preg_split('/["\']/', $match[0]);
				$filename 					= basename($thumbnail[1]);
				$filename 					= substr($filename, 0, strrpos($filename, "."));				
				if (strpos($filename, $width . 'x' . $height) !== false) {
					$filewidth				= $width;
					$fileheight				= $height;
				} else {
					$filewidth				= $image['p_img_large'][1];
					$fileheight				= $image['p_img_large'][2];
				}
				unset($image);
				array_push($return, $thumbnail[1]);
				array_push($return, $filewidth);
				array_push($return, $fileheight);
				array_push($return, true);
				return $return;
			} else {
				return wp_get_attachment_image_src($id, array($width,$height));
			}
		}
	}
	
	// Function to Retrieve Image MetaData
	// -----------------------------------
	if (!function_exists('TS_VCSC_GetImageMetaData')){
		function TS_VCSC_GetImageMetaData($img_id) {
			$image_array					= array();
			if ($img_id != "") {
				$image_data 				= get_post($img_id);
				$image_array['alt']			= get_post_meta($img_id, '_wp_attachment_image_alt', true);
				$image_array['caption']		= $image_data->post_excerpt;
				$image_array['content']		= $image_data->post_content;
				$image_array['title']		= $image_data->post_title;
				$image_array['type']		= $image_data->post_mime_type;
			}
			return $image_array;
		}
	}
	
	// Function to Parse File Paths
	// ----------------------------
	if (!function_exists('TS_VCSC_GetPathInfo')){
		function TS_VCSC_GetPathInfo($filepath) {
			preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $m);
			if ($m[1]) $ret['dirname']		= $m[1];
			if ($m[2]) $ret['basename']		= $m[2];
			if ($m[5]) $ret['extension']	= $m[5];
			if ($m[3]) $ret['filename']		= $m[3];
			return $ret;
		}
	}

	// Function to Compare + Check Version Numbers
	// -------------------------------------------
	if (!function_exists('TS_VCSC_VersionCompare')){
		function TS_VCSC_VersionCompare($a, $b) {
			//Compare two sets of versions, where major/minor/etc. releases are separated by dots. 
			//Returns 0 if both are equal, 1 if A > B, and -1 if B < A.
			$a = trim($a);
			$b = trim($b);
			$a = preg_replace("/[^0-9.]/", "", $a);
			$b = preg_replace("/[^0-9.]/", "", $b);
			$a = explode(".", TS_VCSC_CustomSTRrTrim($a, ".0")); //Split version into pieces and remove trailing .0 
			$b = explode(".", TS_VCSC_CustomSTRrTrim($b, ".0")); //Split version into pieces and remove trailing .0 
			//Iterate over each piece of A 
			foreach ($a as $depth => $aVal) {
				if (isset($b[$depth])) {
				//If B matches A to this depth, compare the values 
					if ($aVal > $b[$depth]) {
						return 1; //Return A > B
						//break;
					} else if ($aVal < $b[$depth]) {
						return -1; //Return B > A
						//break;
					}
				//An equal result is inconclusive at this point 
				} else  {
					//If B does not match A to this depth, then A comes after B in sort order 
					return 1; //so return A > B
					//break;
				} 
			} 
			//At this point, we know that to the depth that A and B extend to, they are equivalent. 
			//Either the loop ended because A is shorter than B, or both are equal. 
			return (count($a) < count($b)) ? -1 : 0; 
		}
	}
	
	// Function to Trim trailing .0 from Version Numbers
	// -------------------------------------------------
	if (!function_exists('TS_VCSC_CustomSTRrTrim')){
		function TS_VCSC_CustomSTRrTrim($message, $strip) {
			$lines = explode($strip, $message); 
			$last  = ''; 
			do { 
				$last = array_pop($lines); 
			} while (empty($last) && (count($lines)));
			return implode($strip, array_merge($lines, array($last))); 
		}
	}
	
	// Function to Compare + Check WordPress Versions
	// ----------------------------------------------
	if (!function_exists('TS_VCSC_WordPressCheckup')) {
		function TS_VCSC_WordPressCheckup($version = '3.8') {
			global $wp_version;		
			if (version_compare($wp_version, $version, '>=')) {
				return "true";
			} else {
				return "false";
			}
		}
	}
	
	// Function to Check if Currently Editing Page + Post
	// --------------------------------------------------
	if (!function_exists('TS_VCSC_IsEditPagePost')){
		function TS_VCSC_IsEditPagePost($new_edit = null){
			global $pagenow, $typenow;
			$frontend = TS_VCSC_CheckFrontEndEditor();
			if (function_exists('vc_is_inline')){
				$vc_is_inline = vc_is_inline();
				if ((vc_is_inline() == false) && (vc_is_inline() != '') && (vc_is_inline() != true) && (!is_admin())) {
					return false;
				} else if ((vc_is_inline() == true) && (vc_is_inline() != '') && (vc_is_inline() != true) && (!is_admin())) {
					return true;
				} else if (((vc_is_inline() == NULL) || (vc_is_inline() == '')) && (!is_admin())) {
					if ($frontend == true) {
						$vc_is_inline = true;
						return true;
					} else {
						$vc_is_inline = false;
						return false;
					}
				}
			} else {
				$vc_is_inline = false;
				if (!is_admin()) return false;
			}
			if (($frontend == true) && (!is_admin())) {
				return true;
			} else if ($new_edit == "edit") {
				return in_array($pagenow, array('post.php'));
			} else if ($new_edit == "new") {
				return in_array($pagenow, array('post-new.php'));
			} else if ($vc_is_inline == true) {
				return true;
			} else {
				return in_array($pagenow, array('post.php', 'post-new.php'));
			}
		}
	}
	
	// Function to Check for VC Frontend Editor
	// ----------------------------------------
	if (!function_exists('TS_VCSC_CheckFrontEndEditor')){
		function TS_VCSC_CheckFrontEndEditor() {
			$request 		= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			if (strpos($request, "admin-ajax.php") !== false) {
				$finalurl	= $_SERVER["HTTP_REFERER"];
			} else {
				$finalurl	= $request;
			}
			if ((strpos($finalurl, "vc_editable=true") !== false) || (strpos($finalurl, "vc_action=vc_inline") !== false)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
    // Function to check Current User Role
    // -----------------------------------
    if (!function_exists('TS_VCSC_CheckUserRole')){
        function TS_VCSC_CheckUserRole($roles, $user_id = NULL) {
            // Get user by ID, else get current user
            if ($user_id) {
                $user = get_userdata($user_id);
            } else {
                $user = wp_get_current_user();
            }
            // No user found, return
            if (empty($user)) {
                return false;
            }
            // Append administrator to roles, if necessary
            if (!in_array('administrator', $roles)) {
                $roles[] = 'administrator';
            }
            // Loop through user roles
            foreach ($user->roles as $role) {
                // Does user have role
                if (in_array($role, $roles)) {
                    return true;
                }
            }
            // User not in roles
            return false;
        }
    }
	if (!function_exists('TS_VCSC_CheckCurrentUserRoles')){
		function TS_VCSC_CheckCurrentUserRoles($role, $user_id = null) {
			if (is_numeric($user_id)) {
				$user = get_userdata($user_id);
			} else {
				$user = wp_get_current_user();
			}			 
			if (empty($user)) {
				return false;
			}
			return in_array($role, (array) $user->roles);
		}
	}
		
	// Function to Retrieve Categories for Custom Post
	// -----------------------------------------------
	if (!function_exists('TS_VCSC_GetCategoriesCustomPost')){
		function TS_VCSC_GetCategoriesCustomPost($id = false, $tcat = 'category') {
			$categories = get_the_terms( $id, $tcat );
			if (!$categories) {
				$categories = array();
			}	
			$categories = array_values( $categories );	
			foreach ( array_keys( $categories ) as $key ) {
				_make_cat_compat( $categories[$key] );
			}	
			return apply_filters('get_the_categories', $categories);
		}
	}
	
    // Function to retrieve Attachment ID from Link
    // --------------------------------------------
    if (!function_exists('TS_VCSC_GetAttachmentIDFromLink')){
        function TS_VCSC_GetAttachmentIDFromLink ($image_src) {
	    global $wpdb;
	    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	    $id = $wpdb->get_var($query);
	    return $id;
        }
    }
	
	// Function to Migrate Tooltip Settings
	// ------------------------------------
    if (!function_exists('TS_VCSC_TooltipMigrateStyle')){
        function TS_VCSC_TooltipMigrateStyle ($tooltip_style) {
			if (($tooltip_style == "") || ($tooltip_style == "ts-simptip-style-black") || ($tooltip_style == "tooltipster-black")) {
				$tooltip_style			= "tooltipster-black";
			} else if (($tooltip_style == "ts-simptip-style-gray") || ($tooltip_style == "tooltipster-gray")) {
				$tooltip_style			= "tooltipster-gray";
			} else if (($tooltip_style == "ts-simptip-style-green") || ($tooltip_style == "tooltipster-green")) {
				$tooltip_style			= "tooltipster-green";
			} else if (($tooltip_style == "ts-simptip-style-blue") || ($tooltip_style == "tooltipster-blue")) {
				$tooltip_style			= "tooltipster-blue";
			} else if (($tooltip_style == "ts-simptip-style-red") || ($tooltip_style == "tooltipster-red")) {
				$tooltip_style			= "tooltipster-red";
			} else if (($tooltip_style == "ts-simptip-style-orange") || ($tooltip_style == "tooltipster-orange")) {
				$tooltip_style			= "tooltipster-orange";
			} else if (($tooltip_style == "ts-simptip-style-yellow") || ($tooltip_style == "tooltipster-yellow")) {
				$tooltip_style			= "tooltipster-yellow";
			} else if (($tooltip_style == "ts-simptip-style-purple") || ($tooltip_style == "tooltipster-purple")) {
				$tooltip_style			= "tooltipster-purple";
			} else if (($tooltip_style == "ts-simptip-style-pink") || ($tooltip_style == "tooltipster-pink")) {
				$tooltip_style			= "tooltipster-pink";
			} else if (($tooltip_style == "ts-simptip-style-white") || ($tooltip_style == "tooltipster-white")) {
				$tooltip_style			= "tooltipster-white";
			} else if (($tooltip_style == "ts-simptip-style-custom") || ($tooltip_style == "tooltipster-custom")) {
				$tooltip_style			= "tooltipster-custom";
			}
			return $tooltip_style;
        }
    }
   if (!function_exists('TS_VCSC_TooltipMigratePosition')){
        function TS_VCSC_TooltipMigratePosition ($tooltip_position) {
			if (($tooltip_position == "ts-simptip-position-top") || ($tooltip_position == "top")) {
				$tooltip_position		= "top";
			} else if (($tooltip_position == "ts-simptip-position-left") || ($tooltip_position == "left")) {
				$tooltip_position		= "left";
			} else if (($tooltip_position == "ts-simptip-position-right") || ($tooltip_position == "right")) {
				$tooltip_position		= "right";
			} else if (($tooltip_position == "ts-simptip-position-bottom") || ($tooltip_position == "bottom")) {
				$tooltip_position		= "bottom";
			}
			return $tooltip_position;
        }
    }
    
    // Function to extract String in Between Strings
    // ---------------------------------------------
    if (!function_exists('TS_VCSC_GetStringBetween')){
        function TS_VCSC_GetStringBetween ($string, $start, $finish) {
            $string = " " . $string;
            $position = strpos($string, $start);
            if ($position == 0) return "";
            $position += strlen($start);
            $length = strpos($string, $finish, $position) - $position;
            return substr($string, $position, $length);
        }
    }
	if (!function_exists('TS_VCSC_GetContentsBetween')){
		function TS_VCSC_GetContentsBetween($str, $startDelimiter, $endDelimiter) {
			$contents = array();
			$startDelimiterLength = strlen($startDelimiter);
			$endDelimiterLength = strlen($endDelimiter);
			$startFrom = $contentStart = $contentEnd = 0;
			while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
			  $contentStart += $startDelimiterLength;
			  $contentEnd = strpos($str, $endDelimiter, $contentStart);
			  if (false === $contentEnd) {
				break;
			  }
			  $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
			  $startFrom = $contentEnd + $endDelimiterLength;
			}  
			return $contents;
		}
	}
    
    // Function to retrieve Current Post Type
    // --------------------------------------
    if (!function_exists('TS_VCSC_GetCurrentPostType')){
        function TS_VCSC_GetCurrentPostType() {
            global $post, $typenow, $current_screen;
            if ($post && $post->post_type) {
                // We have a post so we can just get the post type from that
                return $post->post_type;		
            } else if ($typenow) {
                // Check the global $typenow
                return $typenow;
            } else if ($current_screen && $current_screen->post_type) {
                // Check the global $current_screen Object
                return $current_screen->post_type;	
            } else if (isset($_REQUEST['post_type'])) {
                // Check the Post Type QueryString
                return sanitize_key($_REQUEST['post_type']);
			} else if (empty($typenow) && !empty($_GET['post'])) {
				// Try to get via get_post(); Attempt A
				$post 		= get_post($_GET['post']);
				$typenow 	= $post->post_type;
				return $typenow;
			} else if (empty($typenow) && !empty($_POST['post_ID'])) {
				// Try to get via get_post(); Attempt B
				$post 		= get_post($_POST['post_ID']);
				$typenow 	= $post->post_type;
				return $typenow;
			} else if (function_exists('get_current_screen')) {
				// Try to get via get_current_screen()
				$current 	= get_current_screen();
				if (isset($current) && ($current != false) && ($current->post_type)) {
					return $current->post_type;
				} else {
					return null;
				}
			}
            // We Do Not Know The Post Type!!!
            return null;
        }
    }
    
    // Functions to retrieve Video Thumbnails
    // --------------------------------------
    if (!function_exists('TS_VCSC_VideoImage_Youtube')){
        function TS_VCSC_VideoImage_Youtube($url){
            // Get image from video URL
            $urls 		= parse_url($url);
            $imgPath 	= '';
            if ((isset($urls['host'])) && ($urls['host'] == 'youtu.be')) {
                //Expect the URL to be http://youtu.be/abcd, where abcd is the video ID
                $imgPath = ltrim($urls['path'],'/');
            } else if ((isset($urls['path'])) && (strpos($urls['path'], 'embed') == 1)) {
                // Expect the URL to be http://www.youtube.com/embed/abcd
				$imgPath = explode('/', $urls['path']);
                $imgPath = end($imgPath);
            } else if (strpos($url, '/') === false) { 
                //Expect the URL to be abcd only
                $imgPath = $url;
            } else {
                //Expect the URL to be http://www.youtube.com/watch?v=abcd
                parse_str($urls['query']);
                $imgPath = $v;
            }
            return "https://img.youtube.com/vi/" . $imgPath . "/hqdefault.jpg";
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Youtube')){
        function TS_VCSC_VideoID_Youtube($url){
            // Get image from video URL
            $urls 		= parse_url($url);
            $imgPath 	= '';
            if ((isset($urls['host'])) && ($urls['host'] == 'youtu.be')) {
                //Expect the URL to be http://youtu.be/abcd, where abcd is the video ID
                $imgPath = ltrim($urls['path'], '/');
            } else if ((isset($urls['path'])) && (strpos($urls['path'], 'embed') == 1)) {
                // Expect the URL to be http://www.youtube.com/embed/abcd
				$imgPath = explode('/', $urls['path']);
                $imgPath = end($imgPath);
            } else if (strpos($url, '/') === false) { 
                //Expect the URL to be abcd only
                $imgPath = $url;
            } else {
                //Expect the URL to be http://www.youtube.com/watch?v=abcd
                parse_str($urls['query']);
                $imgPath = $v;
            }
            return $imgPath;
        }
    }
    if (!function_exists('TS_VCSC_PlaylistID_Youtube')){
        function TS_VCSC_PlaylistID_Youtube($url){
            $urls           = parse_url($url);
            $imgPath 	    = '';
            if (strpos($url, '/') === false) {
                $imgPath    = $url;
            } else {
                parse_str($urls['query'], $q);
                $imgPath    = $q['list'];
            }
            return $imgPath;
        }
    }
    if (!function_exists('TS_VCSC_VideoImage_Vimeo')){
        function TS_VCSC_VideoImage_Vimeo($url){
            $image_url = parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')) {
                $path = trim(TS_VCSC_retrieveExternalData("http://vimeo.com/api/v2/video/" . substr($image_url['path'], 1) . ".php"));
                $hash = @unserialize($path);
                if (isset($hash[0]["thumbnail_large"])) {
                    return $hash[0]["thumbnail_large"];
                } else {
                    return '';
                }
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Vimeo')){
        function TS_VCSC_VideoID_Vimeo($url){
            $image_url = parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')) {
				if (isset($image_url['path'])) {
					return substr($image_url['path'], 1);
				} else {
					return '';
				}
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoImage_Motion')){
        function TS_VCSC_VideoImage_Motion($url){
            $image_url 	= parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.dailymotion.com' || $image_url['host'] == 'dailymotion.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                $parts 	= explode('_', $parts[2]);
                return "https://www.dailymotion.com/thumbnail/video/" . $parts[0];
            } else if ((isset($image_url['host'])) && ($image_url['host'] == 'dai.ly')) {
                $imgPath = ltrim($image_url['path'],'/');
                return "https://www.dailymotion.com/thumbnail/video/" . $imgPath;
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Motion')){
        function TS_VCSC_VideoID_Motion($url){
            $image_url 	= parse_url($url);
            $imgPath 	= '';
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.dailymotion.com' || $image_url['host'] == 'dailymotion.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                $parts 	= explode('_', $parts[2]);
                return $parts[0];
            } else if ((isset($image_url['host'])) && ($image_url['host'] == 'dai.ly')) {
                $imgPath = ltrim($image_url['path'],'/');
                return $imgPath;
            } else {
                return $imgPath;
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoImage_Facebook')){
        function TS_VCSC_VideoImage_Facebook($url){
            $image_url 	= parse_url($url);			
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.facebook.com' || $image_url['host'] == 'facebook.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                return "https://graph.facebook.com/" . $parts[3] . "/picture";
			} else if (($url != "") && (!preg_match('#[^0-9]#', $url))) {
				return "https://graph.facebook.com/" . $url . "/picture";
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Facebook')){
        function TS_VCSC_VideoID_Facebook($url){
            $image_url 	= parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.facebook.com' || $image_url['host'] == 'facebook.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                return $parts[3];
            } else {
                return $url;
            }
        }
    }
	
	// Function to Minify HTML/JS/CSS Code
	// ------------------------------------
	if (!function_exists('TS_VCSC_MinifyHTML')) {
		function TS_VCSC_MinifyHTML($input) {
			if (trim($input) === "") return $input;
			// Remove extra white-space(s) between HTML Attribute(s)
			/*$input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
				return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
			}, str_replace("\r", "", $input));*/		
			$input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', 'TS_VCSC_CleanUpHTML', str_replace("\r", "", $input));	
			// Minify Inline CSS Declaration(s)
			if(strpos($input, ' style=') !== false) {
				/*$input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
					return '<' . $matches[1] . ' style=' . $matches[2] . TS_VCSC_MinifyCSS($matches[3]) . $matches[2];
				}, $input);*/
				$input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', 'TS_VCSC_CleanUpSTYLE', $input);
			}
			return preg_replace(
				array(
					// t = text
					// o = tag open
					// c = tag close
					// Keep important white-space(s) after self-closing HTML tag(s)
					'#<(img|input)(>| .*?>)#s',
					// Remove a line break and two or more white-space(s) between tag(s)
					'#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
					'#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
					'#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
					'#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
					'#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
					'#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
					'#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
					'#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
					// Remove HTML comment(s) except IE comment(s)
					'#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
				),
				array(
					'<$1$2</$1>',
					'$1$2$3',
					'$1$2$3',
					'$1$2$3$4$5',
					'$1$2$3$4$5$6$7',
					'$1$2$3',
					'<$1$2',
					'$1 ',
					'$1',
					""
				),
			$input);
		}
	}
	if (!function_exists('TS_VCSC_CleanUpHTML')) {
		function TS_VCSC_CleanUpHTML($matches) {
			return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
		}
	}
	if (!function_exists('TS_VCSC_CleanUpSTYLE')) {
		function TS_VCSC_CleanUpSTYLE($matches) {
			return '<' . $matches[1] . ' style=' . $matches[2] . TS_VCSC_MinifyCSS($matches[3]) . $matches[2];
		}
	}
	if (!function_exists('TS_VCSC_MinifyCSS')) {
		// Based On: https://gist.github.com/tovic/d7b310dea3b33e4732c0
		function TS_VCSC_MinifyCSS($input) {
			if (trim($input) === "") return $input;
			return preg_replace(
				array(
					// Remove comment(s)
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
					// Remove unused white-space(s)
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
					// Replace '0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)' with '0'
					'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
					// Replace ':0 0 0 0' with ':0'
					'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
					// Replace 'background-position:0' with 'background-position:0 0'
					'#(background-position):0(?=[;\}])#si',
					// Replace '0.6' with '.6', but only when preceded by ':', ',', '-' or a white-space
					'#(?<=[\s:,\-])0+\.(\d+)#s',
					// Minify string value
					'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
					'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
					// Minify HEX color code
					'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
					// Replace '(border|outline):none' with '(border|outline):0'
					'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
					// Remove empty selector(s)
					'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
				),
				array(
					'$1',
					'$1$2$3$4$5$6$7',
					'$1',
					':0',
					'$1:0 0',
					'.$1',
					'$1$3',
					'$1$2$4$5',
					'$1$2$3',
					'$1:0',
					'$1$2'
				),
			$input);
		}
	}
	if (!function_exists('TS_VCSC_MinifyJS')) {
		function TS_VCSC_MinifyJS($input) {
			if (trim($input) === "") return $input;
			return preg_replace(
				array(
					// Remove comment(s)
					'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
					// Remove white-space(s) outside the string and regex
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
					// Remove the last semicolon
					'#;+\}#',
					// Minify object attribute(s) except JSON attribute(s). From '{'foo':'bar'}' to '{foo:'bar'}'
					'#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
					// --ibid. From 'foo['bar']' to 'foo.bar'
					'#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
				),
				array(
					'$1',
					'$1$2',
					'}',
					'$1$3',
					'$1.$3'
				),
			$input);
		}
	}
    
    // Google Font Manager Functions
    // -----------------------------
    if (!function_exists('TS_VCSC_GetFontFamily')){
        function TS_VCSC_GetFontFamily($id = '', $font_family, $font_type, $style = true, $return = true, $important = true) {
            $url            = plugin_dir_url( __FILE__ );
            $output         = '';
			if (!function_exists("my_strstr")) {
				function my_strstr( $haystack, $needle, $before_needle = false ) {
					if ( !$before_needle ) return strstr( $haystack, $needle );
					else return substr( $haystack, 0, strpos( $haystack, $needle ) );
				}
			}
            if ($font_type == 'google') {
                wp_enqueue_style('ts-font-' . str_replace(':', '-', $font_family), 'https://fonts.googleapis.com/css?family=' . $font_family , null, false, 'all');
                $format_name = strpos($font_family, ':');
                if ($format_name !== false) {
                    $google_font = my_strstr(str_replace('+', ' ', $font_family), ':', true);
                } else {
                    $google_font = str_replace('+', ' ', $font_family);
                }
				if ($style) {
					$output .= '<style type="text/css">#' . $id . ' {font-family: "' . $google_font . '"' . ($important ? " !important" : "") . ';}</style>';
				} else {
					$output .= 'font-family: ' . $google_font . '' . ($important ? ' !important' : '') . '; ';
				}
            } else if ($font_type == 'fontface') {
                $stylesheet = $url . 'assets/fontface/fontface_stylesheet.css';
                $font_dir = FONTFACE_URI;
                if (file_exists( $stylesheet)) {
                    $file_content = file_get_contents($stylesheet);
                    if (preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_family\\1.*?}/is", $file_content, $match)) {
                        $fontface_style = preg_replace("/url\s*\(\s*['|\"]\s*/is", "\\0$font_dir/", $match[0])."\n";
                    }
                    $output = '\n<style type="text/css">' . $fontface_style . '\n';
                    $output .= '#' . $id . ' {font-family: "'.$font_family.'"' . ($important ? " !important" : "") . ';}</style>';
                }
            } else if ($font_type == 'safefont') {
                $format_name = strpos($font_family, ':');
                if ($format_name !== false) {
                    $safe_font = my_strstr(str_replace('+', ' ', $font_family), ':', true);
                } else {
                    $safe_font = str_replace('+', ' ', $font_family);
                }
			if ($style) {
				$output .= '<style type="text/css">#' . $id . ' {font-family: ' . $safe_font . ' ' . ($important ? "!important" : "") . ';}</style>';
			} else {
				$output .= "font-family: " . $safe_font . "" . ($important ? " !important" : "") . "; ";
			}
            }
            if ($return) {
                return $output;
            } else {
                return '';
            }
        }
    }
	
	
	// Custom Button Layout Styling
	// ----------------------------
	if (!function_exists('TS_VCSC_GetCustomFlatButtonStyle')){
		function TS_VCSC_GetCustomFlatButtonStyle($identifier, $classes, $output = 'styleall', $position = '', $hover = false, $color_background, $color_shadow, $section = 'container', $color_other) {
			$styling 					= '';
			$subsection					= '';
			if ($classes != '') {
				$classes 				= explode(" ", $classes);
			}
			if (($output == 'stylestart') || ($output == 'styleall')) {
				$styling .= '<style id="ts-custom-style-' . $identifier . '" type="text/css">';
			}
				if (($output == 'stylecss') || ($output == 'styleall')) {
					if ($section == "icon") {
						$subsection 	= '.ts-color-button-icon ';
					} else if ($section == "text") {
						$subsection 	= '.ts-color-button-title ';
					}
					foreach($classes as $value) {
						if ($hover) {
							$styling .= 'body #' . $identifier . ' a.' . $value . $position . ':hover ' . $subsection . '{';
						} else {
							$styling .= 'body #' . $identifier . ' a.' . $value . $position . ' ' . $subsection .'{';
						}
							if ($section == "container") {
								$styling .= 'background: ' . $color_background . ' !important;';
								$styling .= 'border-bottom: 2px solid ' . $color_shadow . '  !important;';
								$styling .= 'box-shadow: inset 0 -2px ' . $color_shadow . '  !important;';
								$styling .= '-webkit-box-shadow: inset 0 -2px ' . $color_shadow . '  !important;';
								$styling .= '-moz-box-shadow: inset 0 -2px ' . $color_shadow . '  !important;';
							}
							if (($section == "icon") || ($section == "text") || ($position != '')) {
								$styling .= 'color: ' . $color_other . ' !important;';
							}
						$styling .= '}';
					}
				}
			if (($output == 'styleend') || ($output == 'styleall')) {
				$styling .= '</style>';
			}
			return $styling;
		}
	}

    
	// Advanced Link Picker
	if (!function_exists('TS_VCSC_Advancedlinks_GetLinkData')){
		function TS_VCSC_Advancedlinks_GetLinkData($link_string) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			$link_content						= (($link_string == '|') || ($link_string == '||') || ($link_string == '|||') || ($link_string == '||||') || ($link_string == '|||||') || ($link_string == '||||||')) ? '' : $link_string;
			$link_content						= TS_VCSC_Advancedlinks_BuildLinkArray($link_content);
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false") {
				if (($link_content['url'] == "") && ($link_content['id'] != "")) {
					$link_content['url']		= get_permalink($link_id);					
				}
			} else {	
				$link_href						= $link_content['url'];
				$link_title						= $link_content['title'];
				$link_target					= $link_content['target'];
				$link_source					= $link_content['source'];
				$link_id						= $link_content['id'];
				$link_name						= $link_content['name'];
				$link_rel						= $link_content['rel'];
				if (($link_source == "page") && ($link_id != '')) {
					$link_content['url']		= get_permalink($link_id);
				} else if (($link_source == "post") && ($link_id != '')) {
					$link_content['url']		= get_permalink($link_id);
				} else if (($link_source == "custom") && ($link_id != '')) {
					$link_content['url']		= get_permalink($link_id);
				}
			}
			if ((preg_match("@^[a-zA-Z0-9%+-_]*$@", $link_content['url'])) || (urlencode($link_content['url']) == str_replace(array('%', '+'), array('%25', '%2B'), $link_content['url']))) {
			//if ((preg_match("@^[a-zA-Z0-9%+-_]*$@", $link_content['url'])) || (urlencode($link_content['url']) == str_replace(['%', '+'], ['%25', '%2B'], $link_content['url']))) {
				$link_content['url']			= urldecode($link_content['url']);
			}
			unset($link_content['source']);
			unset($link_content['id']);
			unset($link_content['name']);
			return $link_content;
		}
	}	
	if (!function_exists('TS_VCSC_Advancedlinks_BuildLinkArray')){
		function TS_VCSC_Advancedlinks_BuildLinkArray($value) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			// Parse string like "title:Hello world|weekday:Monday" to array('title' => 'Hello World', 'weekday' => 'Monday')
			return TS_VCSC_Advancedlinks_ParseMultiAttribute($value, array('url' => '', 'title' => '', 'target' => '', 'rel' => '', 'source' => '', 'id' => '', 'name' => ''));
		}
	}
	if (!function_exists('TS_VCSC_Advancedlinks_ParseMultiAttribute')){
		function TS_VCSC_Advancedlinks_ParseMultiAttribute($value, $default = array()) {
			$result             	= $default;
			$params_pairs       	= explode('|', $value);
			if (!empty($params_pairs)) {
				foreach($params_pairs as $pair) {
					$param      	= preg_split('/\:/', $pair);
					if (!empty($param[0]) && isset($param[1])) {
						$result[$param[0]] = trim(rawurldecode($param[1]));
					}
				}
			}
			return $result;
		}
	}
	
	// Other Utilized Functions
    // ------------------------
	if (!function_exists('TS_VCSC_IsValidBase64Encoded')){
		function TS_VCSC_IsValidBase64Encoded($string){
			$decoded = base64_decode($string, true);
			// Check if there is no invalid character in strin
			if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;	
			// Decode the string in strict mode and send the responce
			if(!base64_decode($string, true)) return false;	
			// Encode and compare it to origional one
			if(base64_encode($decoded) != $string) return false;	
			return true;
		}
	}
	if (!function_exists('TS_VCSC_RemoveEmptyParagraphs')){
		function TS_VCSC_RemoveEmptyParagraphs($content) {
			$content 						= force_balance_tags($content);
			$content 						= preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
			$content 						= preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content);
			return $content;
		}
	}
	if (!function_exists('TS_VCSC_TrueFalseEqualizer')){
		function TS_VCSC_TrueFalseEqualizer($value, $default) {
			if (isset($value)) {
				if (($value === "false") || ($value === false) || ($value === "0") || ($value === 0)) {
					return "false";
				} else {
					return "true";
				}
			} else {
				return $default;
			}
		}
	}
	if (!function_exists('TS_VCSC_ChildElementsActive')){
		function TS_VCSC_ChildElementsActive($array, $key, $val) {
			foreach ($array as $item) {
				if (is_array($item)) {
				   TS_VCSC_ChildElementsActive($item, $key, $val);
				}
				if (isset($item[$key]) && $item[$key] == $val) {
					return $item;
				}
			};
			return false;
		}
	}
	if (!function_exists('TS_VCSC_STRRPOS_String')){
		function TS_VCSC_STRRPOS_String($haystack, $needle, $offset = 0) { 
			if (trim($haystack) != "" && trim($needle) != "" && $offset <= strlen($haystack)) { 
				$last_pos 		= $offset; 
				$found 			= false; 
				while (($curr_pos = strpos($haystack, $needle, $last_pos)) !== false) { 
					$found 		= true; 
					$last_pos 	= $curr_pos + 1; 
				} 
				if ($found) { 
					return $last_pos - 1; 
				} else { 
					return false; 
				} 
			} else { 
				return false; 
			} 
		} 
	}
    if (!function_exists('TS_VCSC_Color_Average')){
        function TS_VCSC_Color_Average($color1, $color2, $factor) {
            // extract RGB values for color1.
            list($r1, $g1, $b1) = str_split(ltrim($color1, '#'), 2);
            // extract RGB values for color2.
            list($r2, $g2, $b2) = str_split(ltrim($color2, '#'), 2);
            // get the average RGB values.
            $r_avg = (hexdec($r1) * (1-$factor) + hexdec($r2) * $factor);
            $g_avg = (hexdec($g1) * (1-$factor) + hexdec($g2) * $factor);
            $b_avg = (hexdec($b1) * (1-$factor) + hexdec($b2) * $factor);  
            $color_avg = '#' . sprintf("%02s", dechex($r_avg)) . sprintf("%02s", dechex($g_avg)) . sprintf("%02s", dechex($b_avg));
            return $color_avg;
        }
    }
    if (!function_exists('TS_VCSC_CountArrayMatches')){
        function TS_VCSC_CountArrayMatches(array $arr, $arg, $filterValue) {
            $count = 0;
            foreach ($arr as $elem) {
                if (is_array($elem) && isset($elem[$arg]) && $elem[$arg] == $filterValue) {
                    $count++;
                }
            }
            return $count;
        }
    }
    if (!function_exists('TS_VCSC_Memory_Usage')){
        function TS_VCSC_Memory_Usage($decimals = 2) {
            $result = 0;
            if (function_exists('memory_get_usage')) {
                $result = memory_get_usage() / 1024;
            } else {
                if (function_exists('exec')) {
                    $output = array();
                    if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN') {
                        exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
                        $result = preg_replace('/[\D]/', '', $output[5]);
                    } else {
                        exec('ps -eo%mem,rss,pid | grep ' . getmypid(), $output);
                        $output = explode('  ', $output[0]);
                        $result = $output[1];
                    }
                }
            }
            return number_format(intval($result) / 1024, $decimals, '.', '');
        }
    }
    if (!function_exists('TS_VCSC_LetToNumber')){
        function TS_VCSC_LetToNumber( $v ) {
            $l   = substr( $v, -1 );
            $ret = substr( $v, 0, -1 );
            switch ( strtoupper( $l ) ) {
                case 'P': // fall-through
                case 'T': // fall-through
                case 'G': // fall-through
                case 'M': // fall-through
                case 'K': // fall-through
                    $ret *= 1024;
                    break;
                default:
                    break;
            }
            return $ret;
        }
    }
    if (!function_exists('TS_VCSC_CleanNumberData')){
        function TS_VCSC_CleanNumberData($a) {
            if(is_numeric($a)) {
                $a = preg_replace('/[^0-9,]/s', '', $a);
            }
            return $a;
        }
    }
    if (!function_exists('TS_VCSC_FormatSizeUnits')){
        function TS_VCSC_FormatSizeUnits($bytes) {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' Bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' Byte';
            } else {
                $bytes = '0 Bytes';
            }
            return $bytes;
        }
    }
    if (!function_exists('TS_VCSC_TruncateHTML')){
        /**
        * Truncates text.
        *
        * Cuts a string to the length of $length and replaces the last characters
        * with the ending if the text is longer than length.
        *
        * @param string  $text String to truncate.
        * @param integer $length Length of returned string, including ellipsis.
        * @param string  $ending Ending to be appended to the trimmed string.
        * @param boolean $exact If false, $text will not be cut mid-word
        * @param boolean $considerHtml If true, HTML tags would be handled correctly
        * @return string Trimmed string.
        */
        function TS_VCSC_TruncateHTML($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
            if ($considerHtml) {
                // if the plain text is shorter than the maximum length, return the whole text
                if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                    return $text;
                }
                // splits all html-tags to scanable lines
                preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
                $total_length 	= 0;
                $open_tags 		= array();
                $truncate 		= '';
                foreach ($lines as $line_matchings) {
                    // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                    if (!empty($line_matchings[1])) {
                        // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                            // do nothing
                        // if tag is a closing tag (f.e. </b>)
                        } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            // delete tag from $open_tags list
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                                unset($open_tags[$pos]);
                            }
                        // if tag is an opening tag (f.e. <b>)
                        } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                            // add tag to the beginning of $open_tags list
                            array_unshift($open_tags, strtolower($tag_matchings[1]));
                        }
                        // add html-tag to $truncate'd text
                        $truncate .= $line_matchings[1];
                    }
                    // calculate the length of the plain text part of the line; handle entities as one character
                    $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                    if (($total_length + $content_length) > $length) {
                        // the number of characters which are left
                        $left 				= $length - $total_length;
                        $entities_length 	= 0;
                        // search for html entities
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                            // calculate the real length of all entities in the legal range
                            foreach ($entities[0] as $entity) {
                                if ($entity[1] + 1 - $entities_length <= $left) {
                                    $left--;
                                    $entities_length += strlen($entity[0]);
                                } else {
                                    // no more characters left
                                    break;
                                }
                            }
                        }
                        $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                        // maximum lenght is reached, so get off the loop
                        break;
                    } else {
                        $truncate .= $line_matchings[2];
                        $total_length += $content_length;
                    }
                    // if the maximum length is reached, get off the loop
                    if ($total_length >= $length) {
                        break;
                    }
                }
            } else {
                if (strlen($text) <= $length) {
                    return $text;
                } else {
                    $truncate = substr($text, 0, $length);
                }
            }
            // if the words shouldn't be cut in the middle...
            if (!$exact) {
                // ...search the last occurance of a space...
                $spacepos = strrpos($truncate, ' ');
                if (isset($spacepos)) {
                    // ...and cut the text in this position
                    $truncate = substr($truncate, 0, $spacepos);
                }
            }
            // add the defined ending to the text
            $truncate .= ' ' . $ending;
            if ($considerHtml) {
                // close all unclosed html-tags
                foreach ($open_tags as $tag) {
                    $truncate .= '</' . $tag . '>';
                }
            }
            return $truncate;
        }
    }
    if (!function_exists('TS_VCSC_CurrentPageURL')){
        function TS_VCSC_CurrentPageURL() {
            $pageURL = 'http';
            if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
    }
    if (!function_exists('TS_VCSC_CurrentPageName')){
        function TS_VCSC_CurrentPageName() {
            return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
        }
    }
    if (!function_exists('TS_VCSC_CustomFontImportMessages')){
        function TS_VCSC_CustomFontImportMessages($type, $message) {
            echo '<script>
                jQuery(document).ready(function() {
                    jQuery("#ts_vcsc_icons_upload_custom_pack_form").trigger("reset");
					SweetContent					= "' . $message . '";
					SweetType						= "' . $type . '";
					if (SweetType == "success") {
						SweetConfirm				= true;
						SweetCancel					= false;
					} else if ((SweetType == "warning") || (SweetType == "error")) {
						SweetConfirm				= false;
						SweetCancel					= true;
					}
					TS_VCSC_SweetAlert({
						title:                      "Composium - Visual Composer Extensions",
						text:                       "",
						html:                       SweetContent,
						type:                       SweetType,
						allowOutsideClick:          false,
						allowEscapeKey:             false,
						showConfirmButton:          SweetConfirm,
						showCancelButton:           SweetCancel,
						confirmButtonColor:         "#3085d6",
						cancelButtonColor:          "#d33",
						confirmButtonText:          "Close",
						cancelButtonText:           "Close",
						closeOnConfirm:             true,
						closeOnCancel:              true,
					}, function(isConfirm) {
						if (isConfirm) {
                            jQuery("#ts-custom-iconfont-import-file").val("");
                            //location.reload();
                            window.location.href = window.location.href;
						} else {
                            jQuery("#ts-custom-iconfont-import-file").val("");
                            //location.reload();
                            window.location.href = window.location.href;
						}
					});    
                });
            </script>';
        }
    }
    if (!function_exists('TS_VCSC_ResetCustomFont')){
        function TS_VCSC_ResetCustomFont() {
            update_option('ts_vcsc_extend_settings_tinymceCustom', 			'0');
            update_option('ts_vcsc_extend_settings_tinymceCustomJSON', 		'');
            update_option('ts_vcsc_extend_settings_tinymceCustomPath', 		'');
            update_option('ts_vcsc_extend_settings_tinymceCustomArray', 	'');
            update_option('ts_vcsc_extend_settings_tinymceCustomName', 		'Custom User Font');
            update_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 	'Custom User');
            update_option('ts_vcsc_extend_settings_tinymceCustomCount', 	'0');
            update_option('ts_vcsc_extend_settings_tinymceCustomDate',		'');
			update_option('ts_vcsc_extend_settings_tinymceCustomPHP',		'');
        }
    }
    if (!function_exists('TS_VCSC_GetPostOptions')){
        function TS_VCSC_GetPostOptions($query_args, $simple = false) {
			//remove_all_filters('posts_orderby');
            $args = wp_parse_args($query_args, array(
                'post_type' 		=> 'post',
                'posts_per_page'	=> -1,
				'offset'			=> 0,
                'orderby' 			=> 'title',
                'order' 			=> 'ASC',
				'post_status'      	=> 'publish',
            ) );
			$post_options 			= array();
			$post_data				= get_post_type_object($args['post_type']);
			// Retrieve Post Data
            $posts 					= get_posts($args);
            if ($posts) {
                foreach ($posts as $post) {
					if ($simple) {
						$post_options[$post->ID] 	= $post->post_title;
					} else {
						$post_options[] = array(
							'name' 					=> $post->post_title,
							'value' 				=> $post->ID,
							'type'					=> $post_data->labels->singular_name,
							'link'					=> urlencode(get_permalink($post->ID)),
						);
					}
                }
            }
            //TS_VCSC_SortMultiArray($post_options, 'name');
            return $post_options;
        }
    }
    if (!function_exists('TS_VCSC_GetTheCategoryByTax')){
        function TS_VCSC_GetTheCategoryByTax($id = false, $tcat = 'category') {
            $categories = get_the_terms($id, $tcat);
            if ((!$categories) || is_wp_error($categories)) {
                $categories = array();
            }
            $categories = array_values($categories);
            foreach (array_keys($categories) as $key) {
                _make_cat_compat($categories[$key]);
            }
            return apply_filters('get_the_categories', $categories);
        }
    }
    if (!function_exists('TS_VCSC_PluginIsActive')){
        function TS_VCSC_PluginIsActive($plugin_path) {
            $return_var = in_array($plugin_path, apply_filters('active_plugins', get_option('active_plugins')));
            return $return_var;
        }
    }
    if (!function_exists('TS_VCSC_CheckShortcode')){
        function TS_VCSC_CheckShortcode($shortcode = '') {
            $post_to_check = get_post(get_the_ID());
            // false because we have to search through the post content first
            $found = false;
            // if no short code was provided, return false
            if (!$shortcode) {
                return $found;
            }
            // check the post content for the short code
            if (stripos($post_to_check->post_content, '[' . $shortcode) !== false) {
                // we have found the short code
                $found = true;
            }
            // return our final results
            return $found;
        }
    }
    if (!function_exists('TS_VCSC_GetRowSeparator')){
        function TS_VCSC_GetRowSeparator($index, $color1, $color2, $height, $classes) {
			$svgclasses 		= '';
			$svgstyles			= '';
			if (($index > 19) && ($index < 24)) {
				$svgclasses		= 'ts-row-shape-diagonals-basic';
			} else if ($index == 24) {
				$svgclasses		= 'ts-row-shape-top-arrow-out';
			} else if ($index == 25) {
				$svgclasses		= 'ts-row-shape-top-arrow-in';
			} else if ($index == 26) {
				$svgclasses		= 'ts-row-shape-bottom-arrow-out';
			} else if ($index == 27) {
				$svgclasses		= 'ts-row-shape-bottom-arrow-in';
			}
            $svgshapes = array(
                // No Effect (Square) 											/ 0
                '<rect fill="' . $color1 . '" y="-1" width="100" height="1"/>',
                // Triangle Inwards												/ 1
                '<path fill="' . $color1 . '" d="M-0.3,0l50.2,10L100,0H-0.3z"></path>',
                //  Triangle Outwards											/ 2
                '<path fill="' . $color1 . '" d="M0,0h100v9.9L50,0L0,9.9"></path>',
                // Slight Center Curve Inwards									/ 3
                '<path fill="' . $color1 . '" d="M100.2,0C60.1,10.7,40.1,10.7,0,0H100.2z"></path>',
                // Slight Left Curve Inwards									/ 4
                '<path fill="' . $color1 . '" d="M99.9,0C50,8.7,20,8.7,0.1,0H99.9z"></path>',
                // Slight Right Curve Inwards									/ 5
                '<path fill="' . $color1 . '" d="M99.9,0C80,8.7,50,8.7,0.1,0L99.9,0z"></path>',
                // Sharp Left Curce Inwards										/ 6
                '<path fill="' . $color1 . '" d="M99.9,0C28.1,8.7-0.9,8.7,0,0L99.9,0z"></path>',
                // Sharp Right Curve Inwards									/ 7
                '<path fill="' . $color1 . '" d="M100,0c0.9,8.7-28.2,8.7-99.9,0H100z"></path>',
                // Sharp Left Line Inwards										/ 8
                '<path fill="' . $color1 . '" d="M0,9.1L0,0l100,0"></path>',
                // Sharp Right Line Inwards										/ 9
                '<path fill="' . $color1 . '" d="M0,0h100v9.1"></path>',
                // Inwards Wave Pattern											/ 10
                '<path fill="' . $color1 . '" d="M-0.1,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0
                c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0
                c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0
                c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0H-0.1z"></path>',
                // Inwards Cloud Pattern										/ 11
                '<path fill="' . $color1 . '" d="M9,0c-3,8.9-6.1,8.9-9.1,0H9z M13.5,0c-3,11.2-6.1,11.2-9.1,0 M18.1,0C15,7.8,12,7.8,9,0 M22.6,0c-3,10.1-6.1,10.1-9.1,0
                M27.2,0c-3,7.8-6.1,7.8-9.1,0 M31.7,0c-3,12.3-6.1,12.3-9.1,0 M36.3,0c-3,10.1-6.1,10.1-9.1,0 M40.8,0c-3,7.8-6.1,7.8-9.1,0
                M45.4,0c-3,10.1-6.1,10.1-9.1,0 M49.9,0c-3,5.6-6.1,5.6-9.1,0 M54.5,0c-3,8.9-6.1,8.9-9.1,0 M59,0c-3,6.7-6.1,6.7-9.1,0 M63.6,0
               c-3,4.5-6.1,4.5-9.1,0 M68.2,0c-3,5.6-6.1,5.6-9.1,0 M72.7,0c-3,8.9-6.1,8.9-9.1,0 M77.3,0c-3,6.2-6.1,6.2-9.1,0 M81.8,0
               c-3,7.8-6.1,7.8-9.1,0 M86.4,0c-3,8.9-6.1,8.9-9.1,0 M90.9,0c-3,5.6-6.1,5.6-9.1,0 M95.5,0c-3,8.4-6.1,8.4-9.1,0 M100,0
               c-3,9.5-6.1,9.5-9.1,0H100z"></path>',
                // Simple Cloud Pattern											/ 12
                '<path fill="' . $color1 . '" d="M-5 100 Q 0 20 5 100 Z M0 100 Q 5 0 10 100 M5 100 Q 10 30 15 100 M10 100 Q 15 10 20 100 M15 100 Q 20 30 25 100 M20 100 Q 25 -10 30 100 M25 100 Q 30 10 35 100 M30 100 Q 35 30 40 100 M35 100 Q 40 10 45 100 M40 100 Q 45 50 50 100 M45 100 Q 50 20 55 100 M50 100 Q 55 40 60 100 M55 100 Q 60 60 65 100 M60 100 Q 65 50 70 100 M65 100 Q 70 20 75 100 M70 100 Q 75 45 80 100 M75 100 Q 80 30 85 100 M80 100 Q 85 20 90 100 M85 100 Q 90 50 95 100 M90 100 Q 95 25 100 100 M95 100 Q 100 15 105 100 Z"></path>',
               // Stamps														/ 13
               '<path fill="' . $color1 . '" d="M0 0 Q 2.5 40 5 0 Q 7.5 40 10 0Q 12.5 40 15 0Q 17.5 40 20 0Q 22.5 40 25 0 Q 27.5 40 30 0 Q 32.5 40 35 0 Q 37.5 40 40 0 Q 42.5 40 45 0 Q 47.5 40 50 0 Q 52.5 40 55 0 Q 57.5 40 60 0 Q 62.5 40 65 0 Q 67.5 40 70 0 Q 72.5 40 75 0 Q 77.5 40 80 0 Q 82.5 40 85 0 Q 87.5 40 90 0 Q 92.5 40 95 0Q 97.5 40 100 0 Z"></path>',
               // Slits															/ 14
               '<path id="slitPath2" fill="' . $color2 . '" d="M50 100 C49 80 47 0 40 0 L47 0 Z" /><path id="slitPath3" fill="' . $color2 . '" d="M50 100 C51 80 53 0 60 0 L53 0 Z" /><path id="slitPath1" fill="' . $color1 . '" d="M47 0 L50 100 L53 0 Z" ></path>',
               // Big Triangle													/ 15
               '<path d="M0 0 L50 100 L100 0 Z" ></path>',
               // Big Triangle with Shadow										/ 16
               '<path id="trianglePath1" fill="' . $color1 . '" d="M0 0 L50 100 L100 0 Z" /><path id="trianglePath2" fill="' . $color2 . '" d="M50 100 L100 40 L100 0 Z" ></path>',
               // Curve Up														/ 17
               '<path fill="' . $color1 . '" d="M0 100 C 20 0 50 0 100 100 Z"></path>',
               // Curve Down													/ 18
               '<path fill="' . $color1 . '" d="M0 0 C 50 100 80 100 100 0 Z"></path>',
               // Big Half Circle												/ 19
               '<path fill="' . $color1 . '" d="M0 100 C40 0 60 0 100 100 Z"></path>',

			   // Top Diagonal (Full Left - None Right)							/ 20
			   '<polygon points="0,1 1,0 0,0" fill="' . $color1 . '"></polygon>',			   
			   // Top Diagonal (None Left - Full Right)							/ 21
			   '<polygon points="0,0 1,0 1,1" fill="' . $color1 . '"></polygon>',
			   // Bottom Diagonal (Full Left - None Right)						/ 22
			   '<polygon points="0,1 1,1 0,0" fill="' . $color1 . '"></polygon>',
			   // Bottom Diagonal (None Left - Full Right)						/ 23
			   '<polygon points="1,0 1,1 0,1" fill="' . $color1 . '"></polygon>',
			   
			   // Top Center Arrow Out											/ 24
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
			   // Top Center Arrow In											/ 25
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
			   // Bottom Center Arrow Out										/ 26
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
			   // Bottom Center Arrow In										/ 27			   
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
            );
            if ($index < 12) {
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 100 10" preserveAspectRatio="none" style="height: ' . $height . 'px; width: 100%;">' . '' . $svgshapes[$index] . '</svg>';
            } else if ($index < 20) {
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 100 100" preserveAspectRatio="none" style="height: ' . $height . 'px; width: 100%;">' . '' . $svgshapes[$index] . '</svg>';
            } else if ($index < 24) {
				if (($index == 20) || ($index == 21)) {
					$svgstyles		= 'top: 1px';
				} else if (($index == 22) || ($index == 23)) {
					$svgstyles		= 'bottom: 0px';
				}
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 1 1" preserveAspectRatio="none" style="height: ' . $height . 'px; width: 100%; ' . $svgstyles . '">' . '' . $svgshapes[$index] . '</svg>';
            } else if (($index > 23) && ($index < 28)) {
				if ($index == 24) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; top: -' . ($height - 1) . 'px;';	
				} else if ($index == 25) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; top: -' . ($height - 1) . 'px;';	
				} else if ($index == 26) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; bottom: -100%;';	
				} else if ($index == 27) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; bottom: 0px;';	
				}	
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 1 1" preserveAspectRatio="none" style="' . $svgstyles . '">' . '' . $svgshapes[$index] . '</svg>';
            }
        }
    }
    if (!function_exists('TS_VCSC_CheckString')){
        function TS_VCSC_CheckString($string = '') {
            $post_to_check = get_post(get_the_ID());
            // false because we have to search through the post content first
            $found = false;
            // if no string was provided, return false
            if (!$string) {
                return $found;
            }
            // check the post content for the short code
            if (stripos($post_to_check->post_content, '' . $string) !== false) {
                // we have found the string
                $found = true;
            }
            // return our final results
            return $found;
        }
    }
    if (!function_exists('TS_VCSC_GetExtraClass')){
        function TS_VCSC_GetExtraClass($el_class) {
            $output = '';
            if ( $el_class != '' ) {
                $output = " " . str_replace(".", "", $el_class);
            }
            return $output;
        }
    }
    if (!function_exists('TS_VCSC_endBlockComment')){
        function TS_VCSC_endBlockComment($string) {
            return (!empty($_GET['wpb_debug']) && $_GET['wpb_debug']=='true' ? '<!-- END '.$string.' -->' : '');
        }
    }
    if (!function_exists('TS_VCSC_GetCSSAnimation')){
        function TS_VCSC_GetCSSAnimation($css_animation, $convert = "false") {			
			// Old Entry Animation from Visual Composer
			$animation_old		= array(
				"top-to-bottom"			=> "ts-viewport-css-slideInDown",
				"bottom-to-top"			=> "ts-viewport-css-slideInUp",
				"left-to-right"			=> "ts-viewport-css-slideInLeft",
				"right-to-left"			=> "ts-viewport-css-slideInRight",
				"appear"				=> "ts-viewport-css-fadeIn"
			);
            $output 			= '';
            if ($css_animation != '') {
                if (get_option('ts_vcsc_extend_settings_loadWaypoints', 1) == 1) {
                    if (wp_script_is('waypoints', $list = 'registered')) {
                        wp_enqueue_script('waypoints');
                    } else {
                        wp_enqueue_script('ts-extend-waypoints');
                    }
                }
                if ($convert == "false") {
					$output 	= ' wpb_animate_when_almost_visible wpb_' . $css_animation;
				} else {
					if (array_key_exists($css_animation, $animation_old)) {
						$output	= $animation_old[$css_animation];
					} else {
						$output	= $css_animation;
					}
				}
            }
            return $output;
        }
    }
    if (!function_exists('TS_VCSC_DeleteOptionsPrefixed')){
        function TS_VCSC_DeleteOptionsPrefixed($prefix) {
            global $wpdb;
            $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
        }
    }
    if (!function_exists('TS_VCSC_SortMultiArray')){
        function TS_VCSC_SortMultiArray(&$array, $key) {
            foreach($array as &$value) {
                $value['__________'] = $value[$key];
            }
            /* Note, if your functions are inside of a class, use: 
                usort($array, array("My_Class", 'TS_VCSC_SortByDummyKey'));
            */
            usort($array, 'TS_VCSC_SortByDummyKey');
            foreach($array as &$value) {   // removes the dummy key from your array
                unset($value['__________']);
            }
            return $array;
        }
    }
    if (!function_exists('TS_VCSC_SortByDummyKey')){
        function TS_VCSC_SortByDummyKey($a, $b) {
            if($a['__________'] == $b['__________']) return 0;
            if($a['__________'] < $b['__________']) return -1;
            return 1;
        }
    }
    if (!function_exists('TS_VCSC_CaseInsensitiveSort')){
        function TS_VCSC_CaseInsensitiveSort($a,$b) { 
            return strtolower($b) < strtolower($a); 
        }
    }
	if (!function_exists('TS_VCSC_ArrayFlatten')){
		function TS_VCSC_ArrayFlatten($array, $check) {
		   $return 							= array();
		   foreach ($array as $key => $value) {
				if ((is_array($value)) && ($check == true)) {
					$return 				= array_merge($return, TS_VCSC_ArrayFlatten($value, false));
				} else {
					$return[$key] 			= $value;
				}
		   }
		   return $return;
		}
	}
    if (!function_exists('TS_VCSC_getRemoteFile')){
        function TS_VCSC_getRemoteFile($url) {
            // get the host name and url path
            $parsedUrl = parse_url($url);
            $host = $parsedUrl['host'];
            if (isset($parsedUrl['path'])) {
                $path = $parsedUrl['path'];
            } else {
                // the url is pointing to the host like http://www.mysite.com
                $path = '/';
            }
            if (isset($parsedUrl['query'])) {
                $path .= '?' . $parsedUrl['query'];
            }
            if (isset($parsedUrl['port'])) {
                $port = $parsedUrl['port'];
            } else {
                // most sites use port 80
                $port = '80';
            }
            $timeout = 10;
            $response = '';
            // connect to the remote server
            $fp = @fsockopen($host, '80', $errno, $errstr, $timeout );
            if( !$fp ) {
                echo "Cannot retrieve $url";
            } else {
                // send the necessary headers to get the file
                fputs($fp, "GET $path HTTP/1.0\r\n" .
                "Host: $host\r\n" .
                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
                "Accept: */*\r\n" .
                "Accept-Language: en-us,en;q=0.5\r\n" .
                "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                "Keep-Alive: 300\r\n" .
                "Connection: keep-alive\r\n" .
                "Referer: http://$host\r\n\r\n");
                // retrieve the response from the remote server
                while ( $line = fread( $fp, 4096 ) ) {
                    $response .= $line;
                }
                fclose( $fp );
                // strip the headers
                $pos = strpos($response, "\r\n\r\n");
                $response = substr($response, $pos + 4);
            }
            // return the file content
            return $response;
        }
    }
    if (!function_exists('TS_VCSC_retrieveExternalData')){
        function TS_VCSC_retrieveExternalData($url){
            if (function_exists('curl_init')) {
                //echo 'Using CURL';
                // initialize a new curl resource
				$ch                         = curl_init();
				$timeout                    = 60;                             
				curl_setopt($ch, CURLOPT_URL,               $url);
				curl_setopt($ch, CURLOPT_HEADER, 			0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    $timeout);
				curl_setopt($ch, CURLOPT_MAXREDIRS,         3);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 	false);
				curl_setopt($ch, CURLOPT_USERAGENT,         'Composium - Visual Composer Extensions (7190695) by Tekanewa Scripts');
				$content					= curl_exec($ch);
				if (!curl_errno($ch)) {
					$success				= true;
				} else {
					$error					= curl_errno($ch);
				}
				curl_close($ch);
            } else if (ini_get('allow_url_fopen') == '1') {
                //echo 'Using file_get_contents';
                $content = @file_get_contents($url);
                if ($content !== false) {
                    $content = $content;
                } else {
                    $content = '';
                }
            } else {
                //echo 'Using Others';
                $content = TS_VCSC_getRemoteFile($url);
            }
            return $content;
        }
    }
    if (!function_exists('TS_VCSC_cURLcheckBasicFunctions')){
        function TS_VCSC_cURLcheckBasicFunctions() {
            if( !function_exists("curl_init") &&
                !function_exists("curl_setopt") &&
                !function_exists("curl_exec") &&
                !function_exists("curl_close") ) return false;
            else return true;
        }
    }
    if (!function_exists('TS_VCSC_checkValidURL')){
        function TS_VCSC_checkValidURL($url) {
            if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                return true;
            } else {
                return false;
            }
        }
    }
    if (!function_exists('TS_VCSC_makeValidURL')){
        function TS_VCSC_makeValidURL($url) {
            if (preg_match("~^(?:f|ht)tps?://~i", $url)) {
                return $url;
            } else {
                return 'http://' . $url;
            }
        }
    }
    if (!function_exists('TS_VCSC_numberOfDecimals')){
        function TS_VCSC_numberOfDecimals($value) {
            if ((int)$value == $value) {
                return 0;
            } else if (!is_numeric($value)) {
                // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
                return false;
            }
            return strlen($value) - strrpos($value, '.') - 1;
        }
    }
    if (!function_exists('TS_VCSC_RemoveDirectory')){
        function TS_VCSC_RemoveDirectory($directory, $empty = false) { 
            if (substr($directory, -1) == "/") { 
                $directory = substr($directory, 0, -1); 
            } 
            if (!file_exists($directory) || !is_dir($directory)) { 
                return false;
            } elseif (!is_readable($directory)) { 
                return false; 
            } else { 
                $directoryHandle = opendir($directory); 
                while ($contents = readdir($directoryHandle)) { 
                    if ($contents != '.' && $contents != '..') { 
                        $path = $directory . "/" . $contents;
                        if (is_dir($path)) { 
                            TS_VCSC_RemoveDirectory($path); 
                        } else { 
                            unlink($path); 
                        } 
                    } 
                } 
                closedir($directoryHandle); 
                if ($empty == false) { 
                    if (!rmdir($directory)) { 
                        return false; 
                    } 
                }
                return true; 
            } 
        }
    }    
    if (!function_exists('TS_VCSC_GetRandomColorHex')){
        function TS_VCSC_GetRandomColorHex($max_r = 255, $max_g = 255, $max_b = 255) {
            // ensure that values are in the range between 0 and 255
            $max_r = max(0, min($max_r, 255));
            $max_g = max(0, min($max_g, 255));
            $max_b = max(0, min($max_b, 255));
            // generate and return the random color
            return str_pad(dechex(rand(0, $max_r)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(rand(0, $max_g)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(rand(0, $max_b)), 2, '0', STR_PAD_LEFT);
        }
    }
	if (!function_exists('TS_VCSC_ConverToRoman')) {
		function TS_VCSC_ConverToRoman($num){ 
			$n = intval($num); 
			$res = '';		
			//array of roman numbers
			$romanNumber_Array = array( 
				'M'  => 1000, 
				'CM' => 900, 
				'D'  => 500, 
				'CD' => 400, 
				'C'  => 100, 
				'XC' => 90, 
				'L'  => 50, 
				'XL' => 40, 
				'X'  => 10, 
				'IX' => 9, 
				'V'  => 5, 
				'IV' => 4, 
				'I'  => 1); 		
			foreach ($romanNumber_Array as $roman => $number){ 
				//divide to get  matches
				$matches = intval($n / $number); 		
				//assign the roman char * $matches
				$res .= str_repeat($roman, $matches); 		
				//substract from the number
				$n = $n % $number; 
			} 		
			// return the result
			return $res; 
		}
	}
	if (!function_exists('TS_VCSC_ConvertToAlpha')) {
		function TS_VCSC_ConvertToAlpha($num){
			return chr(substr("000".($num+65),-3));
		}
	}
	if (!function_exists('TS_VCSC_ConvertPlaceholderComma')) {
		function TS_VCSC_ConvertPlaceholderComma($string){
			$string = str_replace(array("|comma|", "/comma/", "{comma}", "[comma]"), ",", $string);
			return $string;
		}
	}
	if (!function_exists('TS_VCSC_CreatePreloaderCSS')){
		function TS_VCSC_CreatePreloaderCSS($id, $class, $style, $enqueue) {
			$preloader 						= '';
			$style 							= intval($style);			
			if ($style > -1) {
				if ($enqueue == "true") {
					wp_enqueue_style('ts-extend-preloaders');
				}
				$spancount 					= 0;
				$spandatas					= array(
					0 => 0, 1 => 5, 2 => 4, 3 => 0, 4 => 5, 5 => 0, 6 => 4, 7 => 4, 8 => 0, 9 => 4,
					10 => 0, 11 => 2, 12 => 2, 13 => 1, 14 => 5, 15 => 3, 16 => 6, 17 => 6, 18 => 3, 19 => 0,
					20 => 4, 21 => 5, 22 => 0,
				);
				$spancount 					= (isset($spandatas[$style]) ? $spandatas[$style] : 0);
				$preloader .= '<div id="' . $id . '" class="' . $class . ' ts-preloader-animation-main ts-preloader-animation-' . $style . '">';
					for ($x = 1; $x <= $spancount; $x++) {
						$preloader .= '<span></span>';
					}
				$preloader .= '</div>';
			}
			return $preloader;
		}
	}
?>