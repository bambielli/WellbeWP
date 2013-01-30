<?php
/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Author Archives - Filter Query To Retrieve Different Statuses And Types
- Author Archives - Determine The Label For A Contribution
- Author Archives - Get Array of User Data
- Author Archives - Get The Social Icons
- Author Archives - Get The Author Info Box
- Author Archives - Add Custom Fields to User Profile Admin Screens
- Author Archives - Save Custom Fields from User Profile Admin Screens
- Author Archives - Setup Field Groups and Fields For User Profile Admin Screens
- Author Archives - Add Custom Contact Methods

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Filter Query To Retrieve Different Statuses And Types */
/*-----------------------------------------------------------------------------------*/

add_filter( 'pre_get_posts', 'woo_filter_author_archive_query', 10 );

if ( ! function_exists( 'woo_filter_author_archive_query' ) ) {
	function woo_filter_author_archive_query ( $query ) {
		if ( ! $query->is_admin && $query->is_author ) {
			$query->set( 'post_type', array( 'post', 'revision', 'attachment', 'page' ) );
			$query->set( 'post_status', array( 'publish' ) );
			$query->set( 'posts_per_page', '30' );
			$query->set( 'orderby', 'post_date' );
			$query->set( 'order', 'DESC' );
			$query->parse_query();
		}
	
		return $query;
	} // End woo_filter_author_archive_query()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Determine The Label For A Contribution */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_contribution_label' ) ) {
	function woo_get_contribution_label ( $type, $status, $title ) {
		switch ( $type ) {
			case 'post':
				$label = __( 'Posted', 'woothemes' ) . ' ' . $title;
			break;
			
			case 'revision':
				$label = __( 'Revised', 'woothemes' ) . ' ' . $title;
			break;
			
			case 'attachment':
				$label = __( 'Uploaded', 'woothemes' ) . ' ' . $title;
			break;
			
			case 'page':
				$label = sprintf( __( 'Created the &quot;%s&quot; page', 'woothemes' ), $title );
			break;
			
			default:
			$label = __( 'Added', 'woothemes' ) . ' ' . $title;
			break;
			
			do_action( 'woo_get_contribution_label', $type, $status );
		}
		
		return apply_filters( 'woo_contribution_label', $label, $type, $status );
	} // End woo_get_contribution_label()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Get Array of User Data */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_user_data' ) ) {
	function woo_get_user_data ( $author ) {
		if ( ! is_numeric( $author ) ) { return array(); }
		
		$data = array();
		
		// Array of fields to retrieve.
		$fields = array( 'email', 'display_name', 'user_description', 'facebook', 'twitter', /*'byline', */'gender', 'location', 'timezone' );
		
		$field_settings = array();
		$field_data = woo_get_profile_fields_settings();
		
		foreach ( $field_data['fields'] as $k => $v ) {
			foreach ( $v as $i => $j ) {
				$field_settings[$j['id']] = $j;
			}
		}
		
		$value = '';
		
		foreach ( $fields as $k => $v ) {
			$value = get_the_author_meta( $v, $author );
			if ( $value != '' ) {
				$data[$v] = $value;
				
				if ( isset( $field_settings[$v]['options'][$value] ) ) {
					$data[$v] = $field_settings[$v]['options'][$value];
				}
			}
		}
		
		$data['avatar'] = get_avatar( $data['email'], 40 );
		
		return $data;
	} // End woo_get_user_data()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Get The Social Icons */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_author_social_links' ) ) {
	function woo_get_author_social_links ( $author, $user_data ) {
		$social_links = array( 'rss' => get_author_feed_link( $author ) );
		
		foreach ( array( 'facebook', 'twitter' ) as $k => $v ) {
			if ( isset( $user_data[$v] ) && ( $user_data[$v] != '' ) ) {
				$social_links[$v] = esc_url( $user_data[$v] );
			}
		}
		
		$social_icons = '<ul class="social-icons">' . "\n";
		foreach ( $social_links as $k => $v ) {
			$social_icons .= '<li class="' . $k . '"><a href="' . $v . '"></a></li>' . "\n";
		}
		$social_icons .= '</ul>' . "\n";
		
		return $social_icons;
	} // End woo_get_author_social_links()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Get The Author Info Box */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_author_info_box' ) ) {
	function woo_get_author_info_box ( $author ) {
		if ( ! is_numeric( $author ) ) { return; }
		
		$data = woo_get_user_data( $author );
		
		$fields = apply_filters( 'woo_author_info_box_fields', array( 'display_name' => '', 'gender' => '', 'location' => '', 'timezone' => '' ) );
		
		$field_settings = woo_get_profile_fields_settings();
		
		foreach ( $field_settings['fields'] as $k => $v ) {
			foreach ( $v as $i => $j ) {
				if ( in_array( $j['id'], array_keys( $fields ) ) ) {
					$fields[$j['id']] = $j['label'];
				}
			}
		}

		// Fill in the default WordPress fields.
		$fields['display_name'] = __( 'Name', 'woothemes' );

		$output = array();

		if ( is_array( $data ) ) {
			foreach ( $fields as $k => $v ) {
				if ( isset( $data[$k] ) && $data[$k] != '' ) { $output[$k] = array( 'label' => $v, 'value' => $data[$k] ); }
			}
		}
		
		$html = '';
		
		if ( count( $output ) > 0 ) {
			$html .= '<div id="author-info-box" class="author-info-box">' . "\n";
			$html .= '<h3>' . $output['display_name']['value'] . '</h3>' . "\n";
			
			unset( $output['display_name'] );
			
			$html .= '<dl>' . "\n";
			foreach ( $output as $k => $v ) {
				$html .= '<dt>' . $v['label'] . '</dt>' . "\n";
				$html .= '<dd>' . $v['value'] . '</dd>' . "\n";
			}
			$html .= '</dl>' . "\n" . '</div><!--/.author-info-box-->' . "\n";
		}
		
		return $html;
	} // End woo_get_author_info_box()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Add Custom Fields to User Profile Admin Screens */
/*-----------------------------------------------------------------------------------*/

add_action( 'show_user_profile', 'woo_add_profile_fields_admin', 10 );
add_action( 'edit_user_profile', 'woo_add_profile_fields_admin', 10 );

if ( ! function_exists( 'woo_add_profile_fields_admin' ) ) {
	function woo_add_profile_fields_admin ( $user ) {
		// Setup field groups and fields.
		$data = woo_get_profile_fields_settings();
					   
		// Generate HTML for output.
		$html = '';
		
		foreach ( $data['groups'] as $k => $v ) {
			$html .= '<h3>' . $v . '</h3>' . "\n";
			
			if ( isset( $data['fields'][$k] ) && is_array( $data['fields'][$k] ) ) {
				$html .= '<table class="form-table">' . "\n" . '<tbody>' . "\n";
					foreach ( $data['fields'][$k] as $k => $v ) {
						// Determine the value to display.
						$value = get_the_author_meta( $v['id'], $user->ID );
						if ( $value == '' && isset( $v['default'] ) && ( $v['default'] != '' ) ) { $value = $v['default']; }
					
						if ( $v['type'] != 'date_day' && $v['type'] != 'date_year' && $v['type'] != 'text_no_open' && $v['type'] != 'text_no_open_close' && $v['type'] != 'select_no_open' && $v['type'] != 'select_no_open_close' ) { 
						$html .= '<tr>' . "\n";
						$html .= '<th><label for="' . $v['id'] . '">' . esc_attr( $v['label'] ) . '</label></th>' . "\n";
						$html .= '<td>' . "\n";
						}
							
							// Determine field display based on $v['type'].
							switch ( $v['type'] ) {
								
								/* Select Fields
								--------------------------------------------------*/
								case 'select_no_open':
								case 'select_no_open_close':
								case 'select':
									if ( isset( $v['options'] ) && is_array( $v['options'] ) ) {
										$html .= '<select name="' . $v['id'] . '" id="' . $v['id'] . '">' . "\n";
											foreach ( $v['options'] as $i => $j ) {
												$html .= '<option value="' . $i . '"' . selected( $i, $value, false ) . '>' . $j . '</option>' . "\n";
											}
										$html .= '</select>' . "\n";
									} else {
										$html .= '<input type="text" name="' . $v['id'] . '" id="' . $v['id'] . '" value="' . esc_attr( $value ) . '" class="regular-text" />' . "\n";
									}
								break;

								case 'date_month':
								case 'date_day':
								case 'date_year':

									$date_start = 1;
									$date_end = 12;
									if ($v['type'] == 'date_day') $date_end = 31;
									if ($v['type'] == 'date_year') {
										$date_start = 1901;
										$date_end = 2012;
									}


										$html .= '<select name="' . $v['id'] . '" id="' . $v['id'] . '">' . "\n";
											$html .= '<option value=""></option>';

											for ( $i = $date_start; $i <= $date_end; $i++  ) {
												$html .= '<option value="' . $i . '"' . selected( $i, $value, false ) . '>' . $i . '</option>' . "\n";
											}
										$html .= '</select>' . "\n";
								break;
								
								/* Timezone Fields
								--------------------------------------------------*/
								case 'timezone':
									$current_offset = get_option( 'gmt_offset' );
									
									$check_zone_info = true;
									
									// Remove old Etc mappings.  Fallback to gmt_offset.
									if ( false !== strpos( $value, 'Etc/GMT' ) )
										$value = '';
									
									if ( empty( $value ) ) { // Create a UTC+- zone if no timezone string exists
										$check_zone_info = false;
										if ( 0 == $current_offset )
											$value = 'UTC+0';
										elseif ($current_offset < 0)
											$value = 'UTC' . $current_offset;
										else
											$value = 'UTC+' . $current_offset;
									}
								
									$html .= '<select name="' . $v['id'] . '" id="' . $v['id'] . '">' . "\n";
										$html .= wp_timezone_choice( $value );
									$html .= '</select>' . "\n";
								break;
								

								/* Bigger "TextBox" Fields
								--------------------------------------------------*/
								case 'textbox':
									$html .= '<textarea name="' . $v['id'] . '" id="' . $v['id'] . '" class="regular-text" rows="5" cols="30" />' . $value . '</textarea>' . "\n";
								break;

								/* checkbox
								--------------------------------------------------*/
								case 'checkbox':
									$checked = "";
									if ($value == "1") $checked = "checked";
									$html .= '<input type=checkbox name="' . $v['id'] . '" id="' . $v['id'] . '" value=1 ' . $checked . '/>&nbsp;&nbsp;&nbsp;&nbsp;';
									if ( isset( $v['description'] ) && ( $v['description'] != '' ) ) { $html .= '<span class="description">' . esc_attr( $v['description'] ) . '</span>' . "\n"; }
								break;

								/* Default "Text" Fields
								--------------------------------------------------*/
								default:
									$html .= '<input type="text" name="' . $v['id'] . '" id="' . $v['id'] . '" value="' . esc_attr( $value ) . '" class="regular-text" />' . "\n";
								break;
							}
							
						
						if ( $v['type'] != 'date_month' && $v['type'] != 'date_day' && $v['type'] != 'checkbox' && $v['type'] != 'text_no_close' && $v['type'] != 'text_no_open_close' && $v['type'] != 'select_no_open_close' ) { 

						if ( isset( $v['description'] ) && ( $v['description'] != '' ) ) { $html .= '<br /><span class="description">' . esc_attr( $v['description'] ) . '</span>' . "\n"; }

						$html .= '</td>' . "\n";
						$html .= '</tr>' . "\n";
						}
						
						if ( isset( $v['description'] ) && ( $v['description'] == '<br>' ) ) {
							$html .= "<br>";
						}


					}
				$html .= '</tbody>' . "\n" . '</table>' . "\n";
			}
		}
		
		echo $html;
	} // End woo_add_profile_fields_admin()	
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Save Custom Fields from User Profile Admin Screens */
/*-----------------------------------------------------------------------------------*/

add_action( 'personal_options_update', 'woo_save_custom_profile_fields' );
add_action( 'edit_user_profile_update', 'woo_save_custom_profile_fields' );

if ( ! function_exists( 'woo_save_custom_profile_fields' ) ) {
	function woo_save_custom_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		
			$fields = array();
			$field_data = woo_get_profile_fields_settings();
			
			foreach ( $field_data['fields'] as $k => $v ) {
				foreach ( $v as $i => $j ) {
					$fields[] = $j['id'];
				}
			}
			
			if ( count( $fields ) > 0 ) {
				foreach ( $fields as $k => $v ) {
					if ( isset( $_POST[$v] ) && ( $_POST[$v] != '' ) ) {
						update_user_meta( $user_id, $v, esc_attr( $_POST[$v] ) );
					}
					
					if ( ! isset( $_POST[$v] ) || ( isset( $_POST[$v] ) && $_POST[$v] == '' ) ) {
						delete_user_meta( $user_id, $v );
					}
				}
			}
	} // End woo_save_custom_profile_fields()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Setup Field Groups and Fields For User Profile Admin Screens */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_profile_fields_settings' ) ) {
	function woo_get_profile_fields_settings () {
		$field_groups = array(
								'personal-info' => __( 'Additional Biographic Details', 'woothemes' ), 
								'location-info' => __( 'Location Information', 'woothemes' )
							);
		
		$fields = array(
						'personal-info' => array(
													array(
															'id' => 'gender', 
															'label' => __( 'Gender', 'woothemes' ), 
															'type' => 'select', 
															'default' => 'm', 
															'description' => __( 'Your gender.', 'woothemes' ), 
															'options' => array(
																				'm' => __( 'Male', 'woothemes' ), 
																				'f' => __( 'Female', 'woothemes' )
																			  )
														), 
/*
													array(
															'id' => 'age', 
															'label' => __( 'Age', 'woothemes' ), 
															'type' => 'text', 
															'default' => '', 
															'description' => __( 'Your current age.', 'woothemes' )
														),
*/
													array(
															'id' => 'birthday_month', 
															'label' => __( 'Birthday', 'woothemes' ), 
															'type' => 'date_month' 
														),
													array(
															'id' => 'birthday_day', 
															'label' => __( 'Birthday', 'woothemes' ), 
															'type' => 'date_day' 
															),
													array(
															'id' => 'birthday_year', 
															'label' => __( 'Birthday', 'woothemes' ), 
															'type' => 'date_year', 
															'description' => __( 'Your birth date (month / day / year).', 'woothemes' )
														),

													array(
															'id' => 'healthconditions_1', 
															'label' => __( 'Health Conditions', 'woothemes' ), 
															'type' => 'text_no_close', 
															'default' => '', 
															'description' => __( 'Please list your current or past health conditions.', 'woothemes' )
														),
													array(
															'id' => 'healthconditions_current_1', 
															'label' => __( 'Current', 'woothemes' ), 
															'type' => 'select_no_open_close', 
															'default' => 'current', 
															'description' => __( '<br>', 'woothemes' ), 
															'options' => array(
																				'current' => __( 'Current', 'woothemes' ), 
																				'past' => __( 'Past', 'woothemes' )
																			  )
														), 
													array(
															'id' => 'healthconditions_2', 
															'label' => __( 'Health Conditions', 'woothemes' ), 
															'type' => 'text_no_open_close', 
															'default' => '', 
															'description' => __( 'Please list your current or past health conditions.', 'woothemes' )
														),
													array(
															'id' => 'healthconditions_current_2', 
															'label' => __( 'Current', 'woothemes' ), 
															'type' => 'select_no_open_close', 
															'default' => 'current', 
															'description' => __( '<br>', 'woothemes' ), 
															'options' => array(
																				'current' => __( 'Current', 'woothemes' ), 
																				'past' => __( 'Past', 'woothemes' )
																			  )
														), 
													array(
															'id' => 'healthconditions_3', 
															'label' => __( 'Health Conditions', 'woothemes' ), 
															'type' => 'text_no_open_close', 
															'default' => '', 
															'description' => __( 'Please list your current or past health conditions.', 'woothemes' )
														),
													array(
															'id' => 'healthconditions_current_3', 
															'label' => __( 'Current', 'woothemes' ), 
															'type' => 'select_no_open_close', 
															'default' => 'current', 
															'description' => __( '<br>', 'woothemes' ), 
															'options' => array(
																				'current' => __( 'Current', 'woothemes' ), 
																				'past' => __( 'Past', 'woothemes' )
																			  )
														), 

													array(
															'id' => 'healthconditions_4', 
															'label' => __( 'Health Conditions', 'woothemes' ), 
															'type' => 'text_no_open_close', 
															'default' => '', 
															'description' => __( 'Please list your current or past health conditions.', 'woothemes' )
														),
													array(
															'id' => 'healthconditions_current_4', 
															'label' => __( 'Current', 'woothemes' ), 
															'type' => 'select_no_open', 
															'default' => 'current', 
															'description' => __( 'Please list your current or past health conditions.', 'woothemes' ), 
															'options' => array(
																				'current' => __( 'Current', 'woothemes' ), 
																				'past' => __( 'Past', 'woothemes' )
																			  )
														), 

													array(
															'id' => 'healthinterests_1', 
															'label' => __( 'Health Interests', 'woothemes' ), 
															'type' => 'text_no_close', 
															'default' => '', 
															'description' => __( '<br>', 'woothemes' )
														),
													array(
															'id' => 'healthinterests_2', 
															'label' => __( 'Health Interests', 'woothemes' ), 
															'type' => 'text_no_open_close', 
															'default' => '', 
															'description' => __( '<br>', 'woothemes' )
														),
													array(
															'id' => 'healthinterests_3', 
															'label' => __( 'Health Interests', 'woothemes' ), 
															'type' => 'text_no_open_close', 
															'default' => '', 
															'description' => __( '<br>', 'woothemes' )
														),

													array(
															'id' => 'healthinterests_4', 
															'label' => __( 'Health Interests', 'woothemes' ), 
															'type' => 'text_no_open', 
															'default' => '', 
															'description' => __( 'Please list your current health interests.', 'woothemes' )
														),

/*
													array(
															'id' => 'healthconditions', 
															'label' => __( 'Health Conditions', 'woothemes' ), 
															'type' => 'textbox', 
															'default' => '', 
															'description' => __( 'Please list your current or past health conditions.', 'woothemes' )
														),
													array(
															'id' => 'healthinterests', 
															'label' => __( 'Health Interests', 'woothemes' ), 
															'type' => 'textbox', 
															'default' => '', 
															'description' => __( 'Please list your current health interests.', 'woothemes' )
														),
*/
													array(
															'id' => 'profession', 
															'label' => __( 'Profession', 'woothemes' ), 
															'type' => 'text', 
															'default' => '', 
															'description' => __( 'Your profession.', 'woothemes' )
														),
													array(
															'id' => 'income', 
															'label' => __( 'Household Income', 'woothemes' ), 
															'type' => 'select', 
															'default' => '', 
															'description' => __( 'Household income level.', 'woothemes' ), 
															'options' => array(
'' => __( '', 'woothemes' ), 																				'<$39,999' => __( '<$39,999', 'woothemes' ), 
'$40,000-$79,999' => __( '$40,000-$79,999', 'woothemes' ), 
'$80,000-$129,000' => __( '$80,000-$129,000', 'woothemes' ), 
'$130,000-$199,999' => __( '$130,000-$199,999', 'woothemes' ), 
'$200,000-$299,999' => __( '$200,000-$299,999', 'woothemes' ), 
'>$300,000' => __( '>$300,000', 'woothemes' )																		  )
														),
													array(
															'id' => 'otherperson_check', 
															'label' => __( 'Do you search for information on wellbe for anyone besides yourself?', 'woothemes' ), 
															'type' => 'checkbox',
'description' => __( 'If so who?', 'woothemes' ) 
															),

													array(
															'id' => 'otherperson', 
															'label' => __( 'Do you search for information on wellbe for anyone besides yourself?', 'woothemes' ), 
															'type' => 'text_no_open', 
															'default' => ''
														)
												), 
						'location-info' => array(
													array(
															'id' => 'city', 
															'label' => __( 'City', 'woothemes' ), 
															'type' => 'text', 
															'default' => '', 
															'description' => __( 'Your current city.', 'woothemes' )
														)
, 
array(
															'id' => 'state', 
															'label' => __( 'State', 'woothemes' ), 
															'type' => 'select', 
															'default' => '', 
															'description' => __( 'Your current state.', 'woothemes' ), 
															'options' => array(
																				'' => __( '', 'woothemes' ),
'AL' => __( 'AL', 'woothemes' ),
'AK' => __( 'AK', 'woothemes' ),
'AZ' => __( 'AZ', 'woothemes' ),
'AR' => __( 'AR', 'woothemes' ),
'CA' => __( 'CA', 'woothemes' ),
'CO' => __( 'CO', 'woothemes' ),
'CT' => __( 'CT', 'woothemes' ),
'DE' => __( 'DE', 'woothemes' ),
'FL' => __( 'FL', 'woothemes' ),
'GA' => __( 'GA', 'woothemes' ),
'HI' => __( 'HI', 'woothemes' ),
'ID' => __( 'ID', 'woothemes' ),
'IL' => __( 'IL', 'woothemes' ),
'IN' => __( 'IN', 'woothemes' ),
'IA' => __( 'IA', 'woothemes' ),
'KS' => __( 'KS', 'woothemes' ),
'KY' => __( 'KY', 'woothemes' ),
'LA' => __( 'LA', 'woothemes' ),
'ME' => __( 'ME', 'woothemes' ),
'MD' => __( 'MD', 'woothemes' ),
'MA' => __( 'MA', 'woothemes' ),
'MI' => __( 'MI', 'woothemes' ),
'MN' => __( 'MN', 'woothemes' ),
'MS' => __( 'MS', 'woothemes' ),
'MO' => __( 'MO', 'woothemes' ),
'MT' => __( 'MT', 'woothemes' ),
'NE' => __( 'NE', 'woothemes' ),
'NV' => __( 'NV', 'woothemes' ),
'NH' => __( 'NH', 'woothemes' ),
'NJ' => __( 'NJ', 'woothemes' ),
'NM' => __( 'NM', 'woothemes' ),
'NY' => __( 'NY', 'woothemes' ),
'NC' => __( 'NC', 'woothemes' ),
'ND' => __( 'ND', 'woothemes' ),
'OH' => __( 'OH', 'woothemes' ),
'OK' => __( 'OK', 'woothemes' ),
'OR' => __( 'OR', 'woothemes' ),
'PA' => __( 'PA', 'woothemes' ),
'RI' => __( 'RI', 'woothemes' ),
'SC' => __( 'SC', 'woothemes' ),
'SD' => __( 'SD', 'woothemes' ),
'TN' => __( 'TN', 'woothemes' ),
'TX' => __( 'TX', 'woothemes' ),
'UT' => __( 'UT', 'woothemes' ),
'VT' => __( 'VT', 'woothemes' ),
'VA' => __( 'VA', 'woothemes' ),
'WA' => __( 'WA', 'woothemes' ),
'WV' => __( 'WV', 'woothemes' ),
'WI' => __( 'WI', 'woothemes' ),
'WY' => __( 'WY', 'woothemes' )
																			  )
														)												)
					   );
					   
		return apply_filters( 'woo_get_profile_fields_settings', array( 'groups' => $field_groups, 'fields' => $fields ) );
	} // End woo_get_profile_fields_settings()
}

/*-----------------------------------------------------------------------------------*/
/* Author Archives - Add Custom Contact Methods */
/*-----------------------------------------------------------------------------------*/

add_filter( 'user_contactmethods', 'woo_add_user_contact_methods', 10, 2 );

if ( ! function_exists( 'woo_add_user_contact_methods' ) ) {
	function woo_add_user_contact_methods ( $methods, $user ) {
		$methods['facebook'] = __( 'Facebook URL', 'woothemes' );
		$methods['twitter'] = __( 'Twitter URL', 'woothemes' );
		return $methods;
	} // End woo_add_user_contact_methods()
}
?>