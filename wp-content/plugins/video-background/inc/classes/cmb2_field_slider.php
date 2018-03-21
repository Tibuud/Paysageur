<?php
class Vidbg_Field_Slider {
	const VERSION = '0.1.0';
	public function hooks() {
		add_filter( 'cmb2_render_vidbg_slider',  array( $this, 'vidbg_slider_field' ), 10, 5 );
	}
	public function vidbg_slider_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		echo '<div class="vidbg-slider-field"></div>';
		echo $field_type_object->input( array(
			'type'       => 'hidden',
			'class'      => 'vidbg-slider-field-value',
			'readonly'   => 'readonly',
			'data-start' => absint( $field_escaped_value ),
			'data-min'   => $field->min(),
			'data-max'   => $field->max(),
			'desc'       => '',
		) );
		echo '<span class="vidbg-slider-field-value-display">'. $field->value_label() .' <span class="vidbg-slider-field-value-text"></span></span>';
		$field_type_object->_desc( true, true );
	}
}
$vidbg_field_slider = new Vidbg_Field_Slider();
$vidbg_field_slider->hooks();
?>
