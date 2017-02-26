<?php
    // Envato Item Information
    // -----------------------
    $this->TS_VCSC_Envato_Defaults                      = array(
        'data'                                          => array(),
        'name'                                          => "N/A",
        'info'                                          => "N/A",
        'link'                                          => "N/A",
        'price'                                         => 0,
        'sales'                                         => 0,
        'rating'                                        => 0,
        'votes'                                         => 0,
        'check'                                         => time(),
        'migrate'                                       => false,
    );
    $this->TS_VCSC_Envato_Globals                       = get_option("ts_vcsc_extend_settings_envato", array());
    if (!is_array($this->TS_VCSC_Envato_Globals)) {
        $this->TS_VCSC_Envato_Globals                   = $this->TS_VCSC_Envato_Defaults;
    } else if(count($this->TS_VCSC_Envato_Globals) == 0) {
        $this->TS_VCSC_Envato_Globals                   = $this->TS_VCSC_Envato_Defaults;
    }
    

    // Check if Provided via Extended License
    // --------------------------------------
    $this->TS_VCSC_PluginExtended				        = (get_option('ts_vcsc_extend_settings_extended', 0) == 1 ? "true" : "false");

    
    // Check and Store VC Version, Applicable Post Types and Icon Picker
    // -----------------------------------------------------------------
    if (defined('WPB_VC_VERSION')){
        $this->TS_VCSC_VisualComposer_Version 			= WPB_VC_VERSION;
        if (TS_VCSC_VersionCompare(WPB_VC_VERSION, '4.3.0') >= 0) {
            if (get_option('ts_vcsc_extend_settings_backendPreview', 1) == 1) {
                $this->TS_VCSC_EditorLivePreview		= "true";
            } else {
                $this->TS_VCSC_EditorLivePreview		= "false";
            }
        } else {
            $this->TS_VCSC_EditorLivePreview			= "false";
        }
        if (TS_VCSC_VersionCompare(WPB_VC_VERSION, '4.4.0') >= 0) {
            $this->TS_VCSC_EditorIconFontsInternal		= "true";
            $this->TS_VCSC_VisualComposer_Compliant		= "true";
            $this->TS_VCSC_EditorFullWidthInternal		= "true";
        } else {
            $this->TS_VCSC_EditorIconFontsInternal		= "false";
            $this->TS_VCSC_VisualComposer_Compliant		= "false";
            $this->TS_VCSC_EditorFullWidthInternal		= "false";
        }
        if ((TS_VCSC_VersionCompare(WPB_VC_VERSION, '4.9.0') >= 0) && (function_exists('vc_lean_map'))) {
            $this->TS_VCSC_VisualComposer_LeanMap 		= "true";
        } else {
            $this->TS_VCSC_VisualComposer_LeanMap 		= "false";
        }
    } else {
        $this->TS_VCSC_EditorLivePreview				= "false";
        $this->TS_VCSC_EditorIconFontsInternal			= "false";
        $this->TS_VCSC_VisualComposer_Compliant			= "false";
        $this->TS_VCSC_VisualComposer_LeanMap 			= "false";
        $this->TS_VCSC_EditorFullWidthInternal			= "false";
    }
    
    
    // Check for Jetpack Plugin + Photon Extensions
    // --------------------------------------------
    if (class_exists('Jetpack') && (method_exists('Jetpack', 'is_module_active'))) {
        if (Jetpack::is_module_active('photon')) {
            $this->TS_VCSC_JetpackPhoton_Active			= "true";
        } else {
            $this->TS_VCSC_JetpackPhoton_Active			= "false";
        }
    } else {
        $this->TS_VCSC_JetpackPhoton_Active				= "false";
    }    
    
    // Always Load + Process Shortcodes
    // --------------------------------
    $this->TS_VCSC_PluginAlways                         = ((get_option('ts_vcsc_extend_settings_shortcodesalways', 0)) == 1 ? "true" : "false");
    
    
    // Downtime Mode Settings
    // ----------------------
    $this->TS_VCSC_CustomPostTypesDownpage              = ((get_option('ts_vcsc_extend_settings_allowDowntimeManager', 0)) == 1 ? "true" : "false");
    $TS_VCSC_Downtime_Manager_Custom                    = get_option('ts_vcsc_extend_settings_downTimeMode', '');
    if (!is_array($TS_VCSC_Downtime_Manager_Custom)) {
        $TS_VCSC_Downtime_Manager_Custom                = array();
    }
    $this->TS_VCSC_Downtime_Manager_Settings = array(
        'active'                                        => ((array_key_exists('active', $TS_VCSC_Downtime_Manager_Custom))          ? $TS_VCSC_Downtime_Manager_Custom['active'] :          $this->TS_VCSC_Downtime_Manager_Defaults['active']),        
        'override'                                      => ((array_key_exists('override', $TS_VCSC_Downtime_Manager_Custom))        ? $TS_VCSC_Downtime_Manager_Custom['override'] :        $this->TS_VCSC_Downtime_Manager_Defaults['override']),        
        'preview'                                       => ((array_key_exists('preview', $TS_VCSC_Downtime_Manager_Custom))         ? $TS_VCSC_Downtime_Manager_Custom['preview'] :         $this->TS_VCSC_Downtime_Manager_Defaults['preview']),
        'cookie'                                        => ((array_key_exists('cookie', $TS_VCSC_Downtime_Manager_Custom))          ? $TS_VCSC_Downtime_Manager_Custom['cookie'] :          $this->TS_VCSC_Downtime_Manager_Defaults['cookie']),
        'timer'                     	                => ((array_key_exists('timer', $TS_VCSC_Downtime_Manager_Custom))           ? $TS_VCSC_Downtime_Manager_Custom['timer'] :           $this->TS_VCSC_Downtime_Manager_Defaults['timer']),
        'timezone'                     	                => ((array_key_exists('timezone', $TS_VCSC_Downtime_Manager_Custom))        ? $TS_VCSC_Downtime_Manager_Custom['timezone'] :        $this->TS_VCSC_Downtime_Manager_Defaults['timezone']),
        'dateonly'						                => ((array_key_exists('dateonly', $TS_VCSC_Downtime_Manager_Custom))        ? $TS_VCSC_Downtime_Manager_Custom['dateonly'] :        $this->TS_VCSC_Downtime_Manager_Defaults['dateonly']),
        'datetime'						                => ((array_key_exists('datetime', $TS_VCSC_Downtime_Manager_Custom))        ? $TS_VCSC_Downtime_Manager_Custom['datetime'] :        $this->TS_VCSC_Downtime_Manager_Defaults['datetime']),
        'timerange'						                => ((array_key_exists('timerange', $TS_VCSC_Downtime_Manager_Custom))       ? $TS_VCSC_Downtime_Manager_Custom['timerange'] :       $this->TS_VCSC_Downtime_Manager_Defaults['timerange']),
        'userroles'                                     => ((array_key_exists('userroles', $TS_VCSC_Downtime_Manager_Custom))       ? $TS_VCSC_Downtime_Manager_Custom['userroles'] :       $this->TS_VCSC_Downtime_Manager_Defaults['userroles']),
        'httpstatus'                                    => ((array_key_exists('httpstatus', $TS_VCSC_Downtime_Manager_Custom))      ? $TS_VCSC_Downtime_Manager_Custom['httpstatus'] :      $this->TS_VCSC_Downtime_Manager_Defaults['httpstatus']),
        'singlepage'					                => ((array_key_exists('singlepage', $TS_VCSC_Downtime_Manager_Custom))      ? $TS_VCSC_Downtime_Manager_Custom['singlepage'] :      $this->TS_VCSC_Downtime_Manager_Defaults['singlepage']),
        'alldevices'					                => ((array_key_exists('alldevices', $TS_VCSC_Downtime_Manager_Custom))      ? $TS_VCSC_Downtime_Manager_Custom['alldevices'] :      $this->TS_VCSC_Downtime_Manager_Defaults['alldevices']),		
        'desktop'						                => ((array_key_exists('desktop', $TS_VCSC_Downtime_Manager_Custom))         ? $TS_VCSC_Downtime_Manager_Custom['desktop'] :         $this->TS_VCSC_Downtime_Manager_Defaults['desktop']),		
        'tablet'						                => ((array_key_exists('tablet', $TS_VCSC_Downtime_Manager_Custom))          ? $TS_VCSC_Downtime_Manager_Custom['tablet'] :          $this->TS_VCSC_Downtime_Manager_Defaults['tablet']),		
        'mobile'						                => ((array_key_exists('mobile', $TS_VCSC_Downtime_Manager_Custom))          ? $TS_VCSC_Downtime_Manager_Custom['mobile'] :          $this->TS_VCSC_Downtime_Manager_Defaults['mobile']),
    );
    
    
    // Sidebars Manager Settings
    // -------------------------
    $this->TS_VCSC_UseSidebarsManager                   = ((get_option('ts_vcsc_extend_settings_allowSidebarsManager', 0)) == 1 ? "true" : "false");
    $TS_VCSC_Sidebars_Manager_Custom                    = get_option('ts_vcsc_extend_settings_customSidebars', array());
    if (!is_array($TS_VCSC_Sidebars_Manager_Custom)) {
        $TS_VCSC_Sidebars_Manager_Custom                = array();
    }
    $this->TS_VCSC_Sidebars_Manager_Settings = array(
        'count'						                    => ((array_key_exists('count', $TS_VCSC_Sidebars_Manager_Custom))           ? $TS_VCSC_Sidebars_Manager_Custom['count'] :           $this->TS_VCSC_Sidebars_Manager_Defaults['count']),
        'ids'						                    => ((array_key_exists('ids', $TS_VCSC_Sidebars_Manager_Custom))             ? $TS_VCSC_Sidebars_Manager_Custom['ids'] :             $this->TS_VCSC_Sidebars_Manager_Defaults['ids']),
        'names'                                         => ((array_key_exists('names', $TS_VCSC_Sidebars_Manager_Custom))           ? $TS_VCSC_Sidebars_Manager_Custom['names'] :           $this->TS_VCSC_Sidebars_Manager_Defaults['names']),
    );

    
    // Default Lightbox Animation
    // --------------------------
    $this->TS_VCSC_Default_Animation 			        = get_option('ts_vcsc_extend_settings_defaultLightboxAnimation', $this->TS_VCSC_Lightbox_Setting_Defaults['animation']);
    
    
    // Load Social Networks API's
    // --------------------------
    $this->TS_VCSC_SocialNetworkAPIs 			        = ((get_option('ts_vcsc_extend_settings_defaultLightboxSocialAPIs', $this->TS_VCSC_Lightbox_Setting_Defaults['loadapis'])) == 1 ? "true" : "false");
    
    
    // Define Menu Position for Post Types
    // -----------------------------------
    $this->TS_VCSC_CustomPostTypesPositions		        = get_option('ts_vcsc_extend_settings_menuPositions', $this->TS_VCSC_Menu_Positions_Defaults);			
    
    
    // Check for MultiSite Activation
    // ------------------------------
    $this->TS_VCSC_PluginIsMultiSiteActive 		        = (is_plugin_active_for_network(COMPOSIUM_SLUG) == true ? "true" : "false");
    
    
    // Activation Redirection
    // ----------------------
    $this->TS_VCSC_ActivationRedirect                   = (get_option('ts_vcsc_extend_settings_redirect', 0) == 1 ? "true" : "false");
    
    // External API Information
    // ------------------------
    $this->TS_VCSC_InformationExternalAPIs              = get_option('ts_vcsc_extend_settings_externalAPIs', array());
    
    // Check and Set other Global Variables
    // ------------------------------------
    // Check if All Files should be loaded
    if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0) 	        { $this->TS_VCSC_LoadFrontEndForcable = "false"; } 			else { $this->TS_VCSC_LoadFrontEndForcable = "true"; }
    // Check if Waypoints should be loaded
    if (get_option('ts_vcsc_extend_settings_loadWaypoints', 1) == 1) 	        { $this->TS_VCSC_LoadFrontEndWaypoints = "true"; } 			else { $this->TS_VCSC_LoadFrontEndWaypoints = "false"; }
    // Check if Modernizr should be loaded
    if (get_option('ts_vcsc_extend_settings_loadModernizr', 1) == 1) 	        { $this->TS_VCSC_LoadFrontEndModernizr = "true"; } 			else { $this->TS_VCSC_LoadFrontEndModernizr = "false"; }
    // Check if CountTo should be loaded
    if (get_option('ts_vcsc_extend_settings_loadCountTo', 1) == 1) 		        { $this->TS_VCSC_LoadFrontEndCountTo = "true"; }			else { $this->TS_VCSC_LoadFrontEndCountTo = "false"; }
    // Check if CountUp should be loaded
    if (get_option('ts_vcsc_extend_settings_loadCountUp', 1) == 1) 		        { $this->TS_VCSC_LoadFrontEndCountUp = "true"; }			else { $this->TS_VCSC_LoadFrontEndCountUp = "false"; }
    // Check if MooTools should be loaded
    if (get_option('ts_vcsc_extend_settings_loadMooTools', 1) == 1)				{ $this->TS_VCSC_LoadFrontEndMooTools = "true"; }			else { $this->TS_VCSC_LoadFrontEndMooTools = "false"; }
    // Check if Lightbox should be loaded
    if (get_option('ts_vcsc_extend_settings_loadLightbox', 0) == 1) 	        { $this->TS_VCSC_LoadFrontEndLightbox = "true"; } 			else { $this->TS_VCSC_LoadFrontEndLightbox = "false"; }
    // Check if Lightbox Integration with Media Manager
    if (get_option('ts_vcsc_extend_settings_lightboxIntegration', 0) == 1)      { $this->TS_VCSC_UseLightboxAutoMedia = "true"; } 			else { $this->TS_VCSC_UseLightboxAutoMedia = "false"; }
    // Check if Lightbox should Replace PrettyPhoto
    if (get_option('ts_vcsc_extend_settings_lightboxPrettyPhoto', 0) == 1)      { $this->TS_VCSC_UseLightboxPrettyPhoto = "true"; }         else { $this->TS_VCSC_UseLightboxPrettyPhoto = "false"; }
    // Check if Tooltips should be loaded
    if (get_option('ts_vcsc_extend_settings_loadTooltip', 0) == 1) 		        { $this->TS_VCSC_LoadFrontEndTooltips = "true"; } 			else { $this->TS_VCSC_LoadFrontEndTooltips = "false"; }
    // Check which Hammer.js should be loaded
    if (get_option('ts_vcsc_extend_settings_loadHammerNew', 1) == 1)			{ $this->TS_VCSC_LoadFrontEndHammerNew = "true"; } 			else { $this->TS_VCSC_LoadFrontEndHammerNew = "false"; }
    // Check if ForceLoad of jQuery
    if (get_option('ts_vcsc_extend_settings_loadjQuery', 0) == 1) 		        { $this->TS_VCSC_LoadFrontEndJQuery = "true"; }				else { $this->TS_VCSC_LoadFrontEndJQuery = "false"; }
    // Check for Editor Image Preview
    if (get_option('ts_vcsc_extend_settings_previewImages', 1) == 1)	        { $this->TS_VCSC_EditorImagePreview = "true"; }				else { $this->TS_VCSC_EditorImagePreview = "false"; }    
    // Check for Container Toggle Control
    if (get_option('ts_vcsc_extend_settings_containerToggle', 0) == 1)	        { $this->TS_VCSC_EditorContainerToggle = "true"; }          else { $this->TS_VCSC_EditorContainerToggle = "false"; }
    // Check for Element Group Filter
     if (get_option('ts_vcsc_extend_settings_elementFilter', 0) == 1)	        { $this->TS_VCSC_EditorElementFilter = "true"; }            else { $this->TS_VCSC_EditorElementFilter = "false"; }
    // Check for Background Indicator
    if (get_option('ts_vcsc_extend_settings_backgroundIndicator', 1) == 1)	    { $this->TS_VCSC_EditorBackgroundIndicator = "true"; }		else { $this->TS_VCSC_EditorBackgroundIndicator = "false"; }   
    // Check for base64 tinyMCE Text Editor
    if (get_option('ts_vcsc_extend_settings_tinymceEncoded', 1) == 1)	        { $this->TS_VCSC_EditorBase64TinyMCE = "true"; } 			else { $this->TS_VCSC_EditorBase64TinyMCE = "false"; }
    // Check for Visual Icon Selector
    if (get_option('ts_vcsc_extend_settings_visualSelector', 1) == 1)	        { $this->TS_VCSC_EditorVisualSelector = "true"; } 			else { $this->TS_VCSC_EditorVisualSelector = "false"; }
    // Check for Built-In Lightbox
    if (get_option('ts_vcsc_extend_settings_builtinLightbox', 1) == 1)	        { $this->TS_VCSC_UseInternalLightbox = "true"; } 			else { $this->TS_VCSC_UseInternalLightbox = "false"; }
    // Google Font Manager
    if (get_option('ts_vcsc_extend_settings_allowGoogleManager', 1) == 1)		{ $this->TS_VCSC_UseGoogleFontManager = "true"; } 			else { $this->TS_VCSC_UseGoogleFontManager = "false"; }
    // Single Page Navigator Builder
    if (get_option('ts_vcsc_extend_settings_allowPageNavigator', 0) == 1)		{ $this->TS_VCSC_UsePageNavigator = "true"; } 				else { $this->TS_VCSC_UsePageNavigator = "false"; }
    // Shortcode Viewer Popup
    if (get_option('ts_vcsc_extend_settings_allowShortcodeViewer', 0) == 1)		{ $this->TS_VCSC_VisualComposer_Shortcodes = "true"; }      else { $this->TS_VCSC_VisualComposer_Shortcodes = "false"; }
    // SmoothScroll Activation
    if (get_option('ts_vcsc_extend_settings_additionsSmoothScroll', 0) == 1)	{ $this->TS_VCSC_UseSmoothScroll = "false"; } 				else { $this->TS_VCSC_UseSmoothScroll = "false"; }
    // Provide Code Editors
    if (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1)				{ $this->TS_VCSC_UseCodeEditors = "true"; }					else { $this->TS_VCSC_UseCodeEditors = "false"; }
    // Provide Deprecated Elements
    if (get_option('ts_vcsc_extend_settings_allowDeprecated', 0) == 1)			{ $this->TS_VCSC_UseDeprecatedElements = "true"; }			else { $this->TS_VCSC_UseDeprecatedElements = "false"; }
    // Plugin Menu Location
    if (get_option('ts_vcsc_extend_settings_mainmenu', 1) == 1)                 { $this->TS_VCSC_PluginMainMenu = "true"; }                 else { $this->TS_VCSC_PluginMainMenu = "false"; }  
    // Enlighter JS
    if (get_option('ts_vcsc_extend_settings_allowEnlighterJS', 0) == 1)			{ $this->TS_VCSC_UseEnlighterJS = "true"; } 				else { $this->TS_VCSC_UseEnlighterJS = "false"; }
    // ThemeBuilder    
    if ($this->TS_VCSC_UseEnlighterJS == "true") {
        if (get_option('ts_vcsc_extend_settings_allowThemeBuilder', 0) == 1)	{ $this->TS_VCSC_UseThemeBuider = "true"; } 				else { $this->TS_VCSC_UseThemeBuider = "false"; }
    } else {
        $this->TS_VCSC_UseThemeBuider 			        = "false";
    }
    // Update Notification
    if (get_option('ts_vcsc_extend_settings_allowNotification', 1) == 1)        { $this->TS_VCSC_UseUpdateNotification = "true"; }          else { $this->TS_VCSC_UseUpdateNotification = "false"; }
    if (get_option('ts_vcsc_extend_settings_allowMenuBarNotice', 0) == 1)       { $this->TS_VCSC_UseUpdateMenuBarNotice = "true"; }         else { $this->TS_VCSC_UseUpdateMenuBarNotice = "false"; }
	// Auto-Update Routine
    if (get_option('ts_vcsc_extend_settings_allowAutoUpdate', 1) == 1)          { $this->TS_VCSC_UseUpdateAutomatic = "true"; }             else { $this->TS_VCSC_UseUpdateAutomatic = "false"; }
    // Shortcodes in Widgets
    if (get_option('ts_vcsc_extend_settings_allowShortcodesWidgets', 1) == 1)   { $this->TS_VCSC_UseShortcodesWidgets = "true"; }           else { $this->TS_VCSC_UseShortcodesWidgets = "false"; }
    // Auto-Paragraph Routine
    if (get_option('ts_vcsc_extend_settings_allowAutoParagraphs', 1) == 1)      { $this->TS_VCSC_UseAutoParagraphs = "true"; }              else { $this->TS_VCSC_UseAutoParagraphs = "false"; }
    // Extendend Container Nesting
    if (get_option('ts_vcsc_extend_settings_allowExtendedNesting', 0) == 1)     { $this->TS_VCSC_UseExtendedNesting = "true"; }             else { $this->TS_VCSC_UseExtendedNesting = "false"; }
    // Visual Composer Auto Assignment
    if (get_option('ts_vcsc_extend_settings_allowAutoAssignment', 1) == 1)      { $this->TS_VCSC_UseAutoAssignmentVC = "true"; }            else { $this->TS_VCSC_UseAutoAssignmentVC = "false"; }
    
    
    // Extended Row + Column Options
    // -----------------------------
    if ((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || (($this->TS_VCSC_PluginExtended == "false"))) {
        if (get_option('ts_vcsc_extend_settings_additionsRows', 0) == 1) {
            $this->TS_VCSC_UseExtendedRows		        = "true";
        } else {
            $this->TS_VCSC_UseExtendedRows 		        = "false";
        }
        if (get_option('ts_vcsc_extend_settings_additionsColumns', 0) == 1) {
            $this->TS_VCSC_UseExtendedColumns 	        = "true";
        } else {
            $this->TS_VCSC_UseExtendedColumns 	        = "false";
        }
    } else {
        $this->TS_VCSC_UseExtendedRows 			        = "false";
        $this->TS_VCSC_UseExtendedColumns 		        = "false";
    }
    
    // Extended Row Modules
    if ($this->TS_VCSC_UseExtendedRows == "true") {
        $TS_VCSC_ExtendedRowsCustomizer                 = get_option('ts_vcsc_extend_settings_extendedRowsCustomizer', array());
        $this->TS_VCSC_ExtendedRowsModules = array(
            "globals"                                   => array (
                "enabled"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["enabled"]))                  ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["enabled"]                 : "true"),
                "rowheight"             => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["rowheight"]))                ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["rowheight"]               : "true"),
                "rowwidth"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["rowwidth"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["rowwidth"]                : "true"),
                "general"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["general"]))                  ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["general"]                 : "true"),
                "visibility"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["visibility"]))               ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["visibility"]              : "true"),
                "columnheight"          => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["columnheight"]))             ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["columnheight"]            : "true"),
                "viewport"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["viewport"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["viewport"]                : "true"),
            ),
            "backgrounds"                               => array (
                "enabled"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["enabled"]))              ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["enabled"]             : "true"),
                "imagesingle"           => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagesingle"]))          ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagesingle"]         : "true"),
                "imagefixed"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagefixed"]))           ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagefixed"]          : "true"),
                "imageslider"           => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageslider"]))          ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageslider"]         : "true"),
                "imageparallax"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageparallax"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageparallax"]       : "true"),
                "imageautomove"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageautomove"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageautomove"]       : "true"),
                "imagemovement"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagemovement"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagemovement"]       : "true"),             
                "colorsingle"           => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorsingle"]))          ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorsingle"]         : "true"),
                "colorgradient"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorgradient"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorgradient"]       : "true"),
                "otherpatternbold"      => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherpatternbold"]))     ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherpatternbold"]    : "true"),
                "otherparticles"        => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherparticles"]))       ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherparticles"]      : "true"),
                "othertriangle"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["othertriangle"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["othertriangle"]       : "true"),
                "videoyoutube"          => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videoyoutube"]))         ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videoyoutube"]        : "true"),
                "videohtml5"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videohtml5"]))           ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videohtml5"]          : "true"),
            ),
            "effects"                                   => array(
                "enabled"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["enabled"]))                  ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["enabled"]                 : "true"),
                "overlays"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["overlays"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["overlays"]                : "true"),
                "kenburns"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["kenburns"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["kenburns"]                : "true"),
                "seperators"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["seperators"]))               ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["seperators"]              : "true"),
                "blurring"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["blurring"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["blurring"]                : "true"),
            ),
        );
    } else {
        $this->TS_VCSC_ExtendedRowsModules              = array();
    }
    
    
    // Define Output Priority for JS Variables
    // ---------------------------------------
    $this->TS_VCSC_Extensions_VariablesPriority         = get_option('ts_vcsc_extend_settings_variablesPriority', '6');
    
    
    // Status of WooCommerce Elements
    // ------------------------------
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $this->TS_VCSC_WooCommerceVersion 			    = $this->TS_VCSC_WooCommerceVersion();
        $this->TS_VCSC_WooCommerceActive 			    = "true";				
    } else {
        $this->TS_VCSC_WooCommerceVersion 			    = "";
        $this->TS_VCSC_WooCommerceActive 			    = "false";
    }
    
    
    // Status of bbPress Elements
    // --------------------------
    if (in_array('bbpress/bbpress.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $this->TS_VCSC_bbPressVersion 			        = "";
        $this->TS_VCSC_bbPressActive 			        = "true";
    } else {
        $this->TS_VCSC_bbPressVersion 			        = "";
        $this->TS_VCSC_bbPressActive 			        = "false";
    }
    
    
    // Other Routine Checks
    // --------------------
    if (($this->TS_VCSC_PluginLicense != '') && ($this->TS_VCSC_PluginLicense != 'emptydelimiterfix') && (in_array(base64_encode($this->TS_VCSC_PluginLicense), $this->TS_VCSC_Avoid_Duplications))) {
        $this->TS_VCSC_PluginUsage				        = "false";
    } else {
        $this->TS_VCSC_PluginUsage				        = "true";
    }
    if ($this->TS_VCSC_PluginUsage == "false") {
        if ($this->TS_VCSC_PluginIsMultiSiteActive == "true") {
            update_site_option('ts_vcsc_extend_settings_licenseInfo', '');
            update_site_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix');
            update_site_option('ts_vcsc_extend_settings_licenseValid', 0);
        } else {
            update_option('ts_vcsc_extend_settings_licenseInfo', '');
            update_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix');
            update_option('ts_vcsc_extend_settings_licenseValid', 0);
        }
    }
    if ($this->TS_VCSC_PluginIsMultiSiteActive == "true") {
        $this->TS_VCSC_PluginKeystring                  = get_site_option('ts_vcsc_extend_settings_license', '');
        $this->TS_VCSC_PluginLicense			        = get_site_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix');
        $this->TS_VCSC_PluginValid				        = (get_site_option('ts_vcsc_extend_settings_licenseValid', 0) == 1 ? "true" : "false");
        $this->TS_VCSC_PluginEnvato				        = get_site_option('ts_vcsc_extend_settings_licenseInfo', '');
    } else {
        $this->TS_VCSC_PluginKeystring                  = get_option('ts_vcsc_extend_settings_license', '');
        $this->TS_VCSC_PluginLicense			        = get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix');
        $this->TS_VCSC_PluginValid				        = (get_option('ts_vcsc_extend_settings_licenseValid', 0) == 1 ? "true" : "false");
        $this->TS_VCSC_PluginEnvato				        = get_option('ts_vcsc_extend_settings_licenseInfo', '');
    }
    if (($this->TS_VCSC_PluginKeystring != '') && (in_array(base64_encode($this->TS_VCSC_PluginKeystring), $this->TS_VCSC_Avoid_Duplications))) {
        $this->TS_VCSC_PluginSupport                    = "false";
    } else {
        $this->TS_VCSC_PluginSupport                    = "true";
    }
    
    
    // Check for Standalone Iconicum Plugin
    // ------------------------------------
    if ((in_array('ts-iconicum-icon-fonts/ts-iconicum-icon-fonts.php', apply_filters('active_plugins', get_option('active_plugins')))) || (class_exists('ICONICUM_ICON_FONTS'))) {
        $this->TS_VCSC_IconicumStandard			        = "true";
    } else {
        $this->TS_VCSC_IconicumStandard			        = "false";
    }
    // Submenu Generator
    if ($this->TS_VCSC_PluginIsMultiSiteActive == "true") {
        if (((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useMenuGenerator', 0) == 1)) || (($this->TS_VCSC_PluginExtended == "false") && (get_option('ts_vcsc_extend_settings_useMenuGenerator', 0) == 1) && ($this->TS_VCSC_PluginValid == "true"))) && ($this->TS_VCSC_PluginUsage == 'true')) {
            $this->TS_VCSC_IconicumMenuGenerator 	    = "true";
        } else {
            $this->TS_VCSC_IconicumMenuGenerator 	    = "false";
        }
    } else {
        if (((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useMenuGenerator', 0) == 1)) || (($this->TS_VCSC_PluginExtended == "false") && (get_option('ts_vcsc_extend_settings_useMenuGenerator', 0) == 1) && ($this->TS_VCSC_PluginValid == "true"))) && ($this->TS_VCSC_PluginUsage == 'true')) {
            $this->TS_VCSC_IconicumMenuGenerator 	    = "true";
        } else {
            $this->TS_VCSC_IconicumMenuGenerator 	    = "false";
        }
    }
    // tinyMCE Editor Generator
    if ($this->TS_VCSC_PluginIsMultiSiteActive == "true") {
        if (((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || (($this->TS_VCSC_PluginExtended == "false") && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && ($this->TS_VCSC_PluginValid == "true"))) && ($this->TS_VCSC_PluginUsage == 'true')) {
            $this->TS_VCSC_IconicumActivated 		    = "true";
        } else {
            $this->TS_VCSC_IconicumActivated 		    = "false";
        }
    } else {
        if (((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || (($this->TS_VCSC_PluginExtended == "false") && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && ($this->TS_VCSC_PluginValid == "true"))) && ($this->TS_VCSC_PluginUsage == 'true')) {
            $this->TS_VCSC_IconicumActivated 		    = "true";
        } else {
            $this->TS_VCSC_IconicumActivated 		    = "false";
        }
    }
    
    
    // -------------------------
    // Custom Setting Parameters
    // -------------------------
    $TS_VCSC_CustomSettingParameters                    = get_option('ts_vcsc_extend_settings_parametersCustom', '');
    if (!is_array($TS_VCSC_CustomSettingParameters)) {
        $TS_VCSC_CustomSettingParameters                = array();
    }
    // Advanced Link Picker
    $TS_VCSC_Advanced_Linkpicker_Settings               = ((array_key_exists('LinkPicker', $TS_VCSC_CustomSettingParameters)) ? $TS_VCSC_CustomSettingParameters['LinkPicker'] : array());
    if (($TS_VCSC_Advanced_Linkpicker_Settings == false) || (empty($TS_VCSC_Advanced_Linkpicker_Settings))) {
        $TS_VCSC_Advanced_Linkpicker_Settings           = array();
    }
    $this->TS_VCSC_ParameterLinkPicker = array(
        'enabled'                                       => (((array_key_exists('enabled', $TS_VCSC_Advanced_Linkpicker_Settings))   ? $TS_VCSC_Advanced_Linkpicker_Settings['enabled']      : $this->TS_VCSC_Advanced_Linkpicker_Defaults['enabled'])       == 1 ? "true" : "false"),
        'global'                                        => (((array_key_exists('global', $TS_VCSC_Advanced_Linkpicker_Settings))    ? $TS_VCSC_Advanced_Linkpicker_Settings['global']       : $this->TS_VCSC_Advanced_Linkpicker_Defaults['global'])        == 1 ? "true" : "false"),
        'offset'                                        => ((array_key_exists('offset', $TS_VCSC_Advanced_Linkpicker_Settings))     ? $TS_VCSC_Advanced_Linkpicker_Settings['offset']       : $this->TS_VCSC_Advanced_Linkpicker_Defaults['offset']),
        'posts'                                         => (((array_key_exists('posts', $TS_VCSC_Advanced_Linkpicker_Settings))     ? $TS_VCSC_Advanced_Linkpicker_Settings['posts']        : $this->TS_VCSC_Advanced_Linkpicker_Defaults['posts'])         == 1 ? "true" : "false"),
        'custom'                                        => (((array_key_exists('custom', $TS_VCSC_Advanced_Linkpicker_Settings))    ? $TS_VCSC_Advanced_Linkpicker_Settings['custom']       : $this->TS_VCSC_Advanced_Linkpicker_Defaults['custom'])        == 1 ? "true" : "false"),
        'orderby'                                       => ((array_key_exists('orderby', $TS_VCSC_Advanced_Linkpicker_Settings))    ? $TS_VCSC_Advanced_Linkpicker_Settings['orderby']      : $this->TS_VCSC_Advanced_Linkpicker_Defaults['orderby']),
        'order'                                         => ((array_key_exists('order', $TS_VCSC_Advanced_Linkpicker_Settings))      ? $TS_VCSC_Advanced_Linkpicker_Settings['order']        : $this->TS_VCSC_Advanced_Linkpicker_Defaults['order']),
    );
    // Numeric Slider Inputs (noUiSlider)
    $TS_VCSC_NoUiSlider_Input_Settings                  = ((array_key_exists('NoUiSlider', $TS_VCSC_CustomSettingParameters)) ? $TS_VCSC_CustomSettingParameters['NoUiSlider'] : array());
    if (($TS_VCSC_NoUiSlider_Input_Settings == false) || (empty($TS_VCSC_NoUiSlider_Input_Settings))) {
        $TS_VCSC_NoUiSlider_Input_Settings              = array();
    }
    $this->TS_VCSC_ParameterNoUiSlider = array(
        'enabled'                                       => (((array_key_exists('enabled', $TS_VCSC_NoUiSlider_Input_Settings))      ? $TS_VCSC_NoUiSlider_Input_Settings['enabled']         : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['enabled'])         == 1 ? "true" : "false"),
        'pips'                                          => (((array_key_exists('pips', $TS_VCSC_NoUiSlider_Input_Settings))         ? $TS_VCSC_NoUiSlider_Input_Settings['pips']            : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['pips'])            == 1 ? "true" : "false"),
        'tooltip'                                       => (((array_key_exists('tooltip', $TS_VCSC_NoUiSlider_Input_Settings))      ? $TS_VCSC_NoUiSlider_Input_Settings['tooltip']         : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['tooltip'])         == 1 ? "true" : "false"),
        'input'                                         => (((array_key_exists('input', $TS_VCSC_NoUiSlider_Input_Settings))        ? $TS_VCSC_NoUiSlider_Input_Settings['input']           : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['input'])           == 1 ? "true" : "false"),
    );
    // Screen Size Settings
    $TS_VCSC_ScreenSizes_Input_Settings                 = ((array_key_exists('ScreenSizes', $TS_VCSC_CustomSettingParameters)) ? $TS_VCSC_CustomSettingParameters['ScreenSizes'] : array());
    $this->TS_VCSC_Screen_Sizes_Custom = array(
        'ExtraLarge'                                    => ((array_key_exists('ExtraLarge', $TS_VCSC_ScreenSizes_Input_Settings))   ? $TS_VCSC_ScreenSizes_Input_Settings['ExtraLarge']     : $this->TS_VCSC_Screen_Sizes_Defaults['ExtraLarge']),
        'Large'                                         => ((array_key_exists('Large', $TS_VCSC_ScreenSizes_Input_Settings))        ? $TS_VCSC_ScreenSizes_Input_Settings['Large']          : $this->TS_VCSC_Screen_Sizes_Defaults['Large']),
        'Medium'                                        => ((array_key_exists('Medium', $TS_VCSC_ScreenSizes_Input_Settings))       ? $TS_VCSC_ScreenSizes_Input_Settings['Medium']         : $this->TS_VCSC_Screen_Sizes_Defaults['Medium']),
        'Small'                                         => ((array_key_exists('Small', $TS_VCSC_ScreenSizes_Input_Settings))        ? $TS_VCSC_ScreenSizes_Input_Settings['Small']          : $this->TS_VCSC_Screen_Sizes_Defaults['Small']),
        'ExtraSmall'                                    => ((array_key_exists('ExtraSmall', $TS_VCSC_ScreenSizes_Input_Settings))   ? $TS_VCSC_ScreenSizes_Input_Settings['ExtraSmall']     : $this->TS_VCSC_Screen_Sizes_Defaults['ExtraSmall']),
    );
    
    // Unset Unneeded Variables
    /*
    unset($TS_VCSC_ExtendedRowsCustomizer);
    unset($TS_VCSC_CustomSettingParameters);
    unset($TS_VCSC_Advanced_NoUiSlider_Settings);
    unset($TS_VCSC_NoUiSlider_Input_Settings);
    */
?>