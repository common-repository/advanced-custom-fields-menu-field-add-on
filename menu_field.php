<?php
/*
* Plugin Name: Advanced Custom Fields - Menu Field add-on
* Description: This plugin is an add-on for Advanced Custom Fields. It provides a field type that displays the items in a WordPress Menu.
* Author:      Chris Wessels
* Version:     1.0.0
* License:     GPLv2
*/
?>
<?php
/*
 * Copyright (c) 2012 Chris Wessels
 *
 * Kudos to Elliot Condon for writing ACF!
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
?>
<?php
if( !class_exists( 'ACF_Menu_Field' ) && class_exists( 'acf_Field' ) ) :
class ACF_Menu_field extends acf_Field {
	function __construct($parent){
		// do not delete!
    	parent::__construct($parent);
    	
    	// set name / title
    	$this->name = 'menu-field'; // variable name (no spaces / special characters / etc)
		$this->title = __("WordPress Menu",'acf'); // field label (Displayed in edit screens)
		
   	}
	function create_options($key, $field){
		// defaults
		$field['multiple'] = isset($field['multiple']) ? $field['multiple'] : '0';
		$field['allow_null'] = isset($field['allow_null']) ? $field['allow_null'] : '0';

		$menus = get_terms('nav_menu');
		ksort($menus);
		$choices = array();
		foreach($menus as $m){
			$choices[$m->slug] = $m->name;
		}
		?>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label>Menu</label>
					<p class="description">Select the menu to display.</p>
				</td>
				<td>
					<?php 
						$this->parent->create_field(array(
							'type'    => 'select',
							'name'    => "fields[{$key}][wp_menu]",
							'value'   => $field['wp_menu'],
							'choices' => $choices,
						));
					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Allow Null?",'acf'); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][allow_null]',
					'value'	=>	$field['allow_null'],
					'choices'	=>	array(
						'1'	=>	__("Yes",'acf'),
						'0'	=>	__("No",'acf'),
					),
					'layout'	=>	'horizontal',
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Select multiple values?",'acf'); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][multiple]',
					'value'	=>	$field['multiple'],
					'choices'	=>	array(
						'1'	=>	__("Yes",'acf'),
						'0'	=>	__("No",'acf'),
					),
					'layout'	=>	'horizontal',
				));
				?>
			</td>
		</tr>
		<?php
	}
	function pre_save_field($field){
		return parent::pre_save_field($field);
	}
	function create_field($field){
		// vars
		$defaults = array(
			'multiple' 		=>	'0',
			'allow_null' 	=>	'0',
			'optgroup'		=>	false,
		);
		
		$field = array_merge($defaults, $field);
		
		// multiple select
		$multiple = '';
		if($field['multiple'] == '1'){
			// create a hidden field to allow for no selections
			echo '<input type="hidden" name="' . $field['name'] . '" />';
			
			$multiple = ' multiple="multiple" size="5" ';
			$field['name'] .= '[]';
		} 
		
		echo '<select id="' . $field['id'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';	

		if($field['allow_null'] == '1'){
			echo '<option value="null"> - Select - </option>';
		}
		$menu = wp_get_nav_menus(array('slug'=>$field['wp_menu']));
		$m = $menu[0];
		$items = wp_get_nav_menu_items($m);
		foreach($items as $item){
				$selected = '';
				if(is_array($field['value']) && in_array($item->title, $field['value'])){

					$selected = 'selected="selected"';
				} else {
					// 3. this is not a multiple select, just check normaly
					if($item->title == $field['value']){
						$selected = 'selected="selected"';
					}
				}	
				echo '<option value="'.$item->title.'" '.$selected.'>'.$item->title.'</option>';
		}

		echo '</select>';
	}
	function admin_head(){

	}
	function admin_print_scripts(){
	
	}
	function admin_print_styles(){
		
	}
	function update_value($post_id, $field, $value){
		parent::update_value($post_id, $field, $value);
	}
	function get_value($post_id, $field){
		$value = parent::get_value($post_id, $field);
		return $value;		
	}
	function get_value_for_api($post_id, $field){
		$value = parent::get_value($post_id, $field);
		if($value == 'null'){
			$value = false;
		}
		return $value;

	}
}
endif; //end class check

if(!class_exists('ACF_Menu_Field_Helper')) :
class ACF_Menu_Field_Helper {
	private static $instance;
	public static function singleton() {
		if( !isset( self::$instance ) ) {
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}
	private function __clone() {
	}
	private function __construct() {
		add_action( 'init', array( &$this, 'register_field' ),  5, 0 );
	}
	public function register_field() {
		if( function_exists( 'register_field' ) ) {
			register_field( 'ACF_Menu_Field', __FILE__ );
		}
	}
}
endif; //end class check

ACF_Menu_Field_Helper::singleton();