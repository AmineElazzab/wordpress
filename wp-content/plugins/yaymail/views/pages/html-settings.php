<?php
defined( 'ABSPATH' ) || exit;
?>

<div id="nta-web-page-builder"></div>

<style id="nta-web-page-custom-style"></style>

<div id="nta-web-preeditor" class="hidden">
	<?php
	wp_editor(
		'',
		'nta-web-edit-content-template',
		array(
			'quicktags'     => false,
			'textarea_name' => 'ntawebEditContentTemplate',
			'textarea_rows' => 6,
			'media_buttons' => true,
			'tinymce'       => true,
		)
	);
	?>
</div>
