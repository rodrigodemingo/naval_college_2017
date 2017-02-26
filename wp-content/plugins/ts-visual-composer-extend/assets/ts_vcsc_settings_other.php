<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
?>

<div id="ts-settings-other" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-menu"></i>Single Page Navigator Builder <span class="ts-vcsc-element-count">(<i class="dashicons-image-filter"></i> <?php echo ($Extra_Navigator); ?>)</span></div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				This plugin includes dedicated elements to quickly and easily build navigation bars for a single site, linking rows or any other elements with an ID to a specific menu item, therefore allowing your users to quickly
				navigate a single, but large page. If you do not require such a feature, or your theme or another plugin is already providing a similar one for you, you can disable it here.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Single Page Navigator Builder:</div>
				<p style="font-size: 12px;">Enable or disable the use of the Single Page Navigator elements:</p>
				<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_allowPageNavigator == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
					<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_allowPageNavigator" class="toggle-check ts_vcsc_extend_settings_allowPageNavigator" name="ts_vcsc_extend_settings_allowPageNavigator" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_allowPageNavigator); ?>/>
					<div class="toggle toggle-light" style="width: 80px; height: 20px;">
						<div class="toggle-slide">
							<div class="toggle-inner">
								<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_allowPageNavigator == 1 ? 'active' : ''); ?>">Yes</div>
								<div class="toggle-blob"></div>
								<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_allowPageNavigator == 0 ? 'active' : ''); ?>">No</div>
							</div>
						</div>
					</div>
				</div>
				<label class="labelToggleBox" for="ts_vcsc_extend_settings_allowPageNavigator">Enable Single Page Navigator Builder Elements <span class="ts-vcsc-element-count">(<?php echo $Extra_Navigator; ?>)</span></label>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-editor-code"></i>EnlighterJS - Syntax Highlighter <span class="ts-vcsc-element-count">(<i class="dashicons-image-filter"></i> <?php echo ($Extra_Enlighter); ?>)</span></div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				This plugin includes dedicated elements to quickly and easily highlight code in a variety of programming languages, using multiple available themes. While very useful and important for a variety of uses, it is not
				a feature that every user requires, which is why you can easily enable or disable it, based on your needs.
			</div>
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				When enabled and a matching element has been embedded on a page and post, this plugin will load the MooTools library, in addition to the standard jQuery library that WordPress is already loading. Please ensure that
				your theme and other plugins properly enclose and define their jQuery routines in order to prevent any conflicts between both libraries; MooTools will be used in its no-conflict mode.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">EnlighterJS - Syntax Highlighter:</div>
				<p style="font-size: 12px;">Enable or disable the use of the EnlighterJS - Syntax Highlighter elements:</p>
				<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_allowEnlighterJS == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
					<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_allowEnlighterJS" class="toggle-check ts_vcsc_extend_settings_allowEnlighterJS" name="ts_vcsc_extend_settings_allowEnlighterJS" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_allowEnlighterJS); ?>/>
					<div class="toggle toggle-light" style="width: 80px; height: 20px;">
						<div class="toggle-slide">
							<div class="toggle-inner">
								<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_allowEnlighterJS == 1 ? 'active' : ''); ?>">Yes</div>
								<div class="toggle-blob"></div>
								<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_allowEnlighterJS == 0 ? 'active' : ''); ?>">No</div>
							</div>
						</div>
					</div>
				</div>
				<label class="labelToggleBox" for="ts_vcsc_extend_settings_allowPageNavigator">Enable EnlighterJS - Syntax Highlighter Elements <span class="ts-vcsc-element-count">(<?php echo $Extra_Enlighter; ?>)</span></label>
			</div>
			<div id="ts_vcsc_extend_settings_allowEnlighterJS_true" style="margin-top: 20px; margin-bottom: 10px; margin-left: 25px; <?php echo ($ts_vcsc_extend_settings_allowEnlighterJS == 0 ? 'display: none;' : 'display: block;'); ?>">
				<h4>Enable Custom Theme Editor:</h4>
				<p style="font-size: 12px;">If the included themes for the syntax highlighter are not enough for you, the optional theme builder allows you to customize the theme styling, based on the default "Enlighter" theme:</p>
				<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_allowThemeBuilder == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
					<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_allowThemeBuilder" class="toggle-check ts_vcsc_extend_settings_allowThemeBuilder" name="ts_vcsc_extend_settings_allowThemeBuilder" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_allowThemeBuilder); ?>/>
					<div class="toggle toggle-light" style="width: 80px; height: 20px;">
						<div class="toggle-slide">
							<div class="toggle-inner">
								<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_allowThemeBuilder == 1 ? 'active' : ''); ?>">Yes</div>
								<div class="toggle-blob"></div>
								<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_allowThemeBuilder == 0 ? 'active' : ''); ?>">No</div>
							</div>
						</div>
					</div>
				</div>
				<label class="labelToggleBox" for="ts_vcsc_extend_settings_allowThemeBuilder">Enable Custom Theme Builder</label>
			</div>
		</div>
	</div>
</div>