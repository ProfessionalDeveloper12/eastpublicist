<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_ow_repeater
 * @author      Luciano "WebCaos" Ubertini
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if ( !defined ( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( !class_exists ( 'ReduxFramework_ow_repeater' ) ) {

    /**
     * Main ReduxFramework_ow_repeater class
     *
     * @since       1.0.0
     */
    class ReduxFramework_ow_repeater {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct ( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render () {

            $defaults = array(
				'title' => true,
				'image' => true,

				'textOne' => false,
				'textTwo' => false,
				'textThree' => false,
				'textFour' => false,
				'textFive' => false,
				'textSix' => false,
				'textSeven' => false,
				'textEight' => false,
				'textNine' => false,
				'textTen' => false,

				'description' => false,
				'url' => true,
                'content_title' => __ ( 'Item', 'redux-framework' )
            );

            $this->field = wp_parse_args ( $this->field, $defaults );

            echo '<div class="redux-ow_repeater-accordion" data-new-content-title="' . esc_attr ( sprintf ( __ ( 'New %s', 'redux-framework' ), $this->field[ 'content_title' ] ) ) . '">';

            $x = 0;

            $multi = ( isset ( $this->field[ 'multi' ] ) && $this->field[ 'multi' ] ) ? ' multiple="multiple"' : "";

            if ( isset ( $this->value ) && is_array ( $this->value ) && !empty ( $this->value ) ) {

                $ow_repeater = $this->value;

                foreach ( $ow_repeater as $slide ) {

                    if ( empty ( $slide ) ) {
                        continue;
                    }

                    $defaults = array(
                        'title' => '',
                        'description' => '',
                        'sort' => '',
                        'url' => '',

                        'textOne' => '',
                        'textTwo' => '',
                        'textThree' => '',
						'textFour' => '',
						'textFive' => '',
						'textSix' => '',
						'textSeven' => '',
						'textEight' => '',
						'textNine' => '',
						'textTen' => '',

                        'image' => '',
                        'thumb' => '',
                        'attachment_id' => '',
                        'height' => '',
                        'width' => '',
                        'select' => array(),
                    );
                    $slide = wp_parse_args ( $slide, $defaults );

                    echo '<div class="redux-ow_repeater-accordion-group"><fieldset class="redux-field" data-id="' . $this->field[ 'id' ] . '"><h3><span class="redux-ow_repeater-header">' . $slide[ 'title' ] . '</span></h3><div>';

                    $hide = '';

					if ( $this->field['image'] === true ) {

						if ( empty ( $slide[ 'thumb' ] ) && !empty ( $slide[ 'attachment_id' ] ) ) {
							$img = wp_get_attachment_image_src ( $slide[ 'attachment_id' ], 'full' );
							$slide[ 'image' ] = $img[ 0 ];
							$slide[ 'width' ] = $img[ 1 ];
							$slide[ 'height' ] = $img[ 2 ];
						}

						if ( empty ( $slide[ 'image' ] ) ) {
							$hide = ' hide';
						}

						echo '<div class="screenshot' . $hide . '">';
						echo '<a class="of-uploaded-image" href="' . $slide[ 'image' ] . '">';
						echo '<img class="redux-ow_repeater-image" id="image_image_id_' . $x . '" src="' . $slide[ 'thumb' ] . '" alt="" target="_blank" rel="external" />';
						echo '</a>';
						echo '</div>';

						echo '<div class="redux_ow_repeater_add_remove">';

						echo '<span class="button media_upload_button" id="add_' . $x . '">' . __ ( 'Upload', 'redux-framework' ) . '</span>';

						$hide = '';
						if ( empty ( $slide[ 'image' ] ) || $slide[ 'image' ] == '' ) {
							$hide = ' hide';
						}

						echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $slide[ 'attachment_id' ] . '">' . __ ( 'Remove', 'redux-framework' ) . '</span>';

						echo '</div>' . "\n";
					}

                    echo '<ul id="' . $this->field[ 'id' ] . '-ul" class="redux-ow_repeater-list">';

					$title_type = "text";

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'title' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'title' ] ) : __ ( 'Title', 'redux-framework' );
                    echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-title_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][title]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'title' ] ) . '" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                    if ( $this->field['textOne'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textOne' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textOne' ] ) : __ ( 'Text 1', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textOne_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textOne]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textOne' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textTwo'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textTwo' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textTwo' ] ) : __ ( 'Text 2', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textTwo_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textTwo]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textTwo' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textThree'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textThree' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textThree' ] ) : __ ( 'Text 3', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textThree_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textThree]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textThree' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textFour'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textFour' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textFour' ] ) : __ ( 'Text 4', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textFour_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textFour]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textFour' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textFive'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textFive' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textFive' ] ) : __ ( 'Text 5', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textFive_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textFive]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textFive' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textSix'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textSix' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textSix' ] ) : __ ( 'Text 6', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textSix_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textSix]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textSix' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textSeven'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textSeven' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textSeven' ] ) : __ ( 'Text 7', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textSeven_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textSeven]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textSeven' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textEight'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textEight' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textEight' ] ) : __ ( 'Text 8', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textEight_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textEight]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textEight' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textNine'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textNine' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textNine' ] ) : __ ( 'Text 9', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textNine_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textNine]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textNine' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['textTen'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textTen' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textTen' ] ) : __ ( 'Text 10', 'redux-framework' );
						echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textTen_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textTen]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'textTen' ] ) . '" placeholder="' . $placeholder . '" class="full-text" /></li>';
					}

                    if ( $this->field['description'] === true ) {

                        $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'description' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'description' ] ) : __ ( 'Description', 'redux-framework' );
                        echo '<li><textarea name="' . $this->field[ 'name' ] . '[' . $x . '][description]' . $this->field['name_suffix'] . '" id="' . $this->field[ 'id' ] . '-description_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6">' . esc_attr ( $slide[ 'description' ] ) . '</textarea></li>';
                    }

					if ( $this->field['url'] === true ) {

						$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'url' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'url' ] ) : __ ( 'URL', 'redux-framework' );
						$url_type = "text";
						echo '<li><input type="' . $url_type . '" id="' . $this->field[ 'id' ] . '-url_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][url]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'url' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';
					}

                    echo '<li><input type="hidden" class="slide-sort" name="' . $this->field[ 'name' ] . '[' . $x . '][sort]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-sort_' . $x . '" value="' . $slide[ 'sort' ] . '" />';

					if ( $this->field['image'] === true )
					{
						echo '<li><input type="hidden" class="upload-id" name="' . $this->field[ 'name' ] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_id_' . $x . '" value="' . $slide[ 'attachment_id' ] . '" />';
						echo '<input type="hidden" class="upload-thumbnail" name="' . $this->field[ 'name' ] . '[' . $x . '][thumb]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-thumb_url_' . $x . '" value="' . $slide[ 'thumb' ] . '" readonly="readonly" />';
						echo '<input type="hidden" class="upload" name="' . $this->field[ 'name' ] . '[' . $x . '][image]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_url_' . $x . '" value="' . $slide[ 'image' ] . '" readonly="readonly" />';
						echo '<input type="hidden" class="upload-height" name="' . $this->field[ 'name' ] . '[' . $x . '][height]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_height_' . $x . '" value="' . $slide[ 'height' ] . '" />';
						echo '<input type="hidden" class="upload-width" name="' . $this->field[ 'name' ] . '[' . $x . '][width]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_width_' . $x . '" value="' . $slide[ 'width' ] . '" /></li>';
                    }

					echo '<li><a href="javascript:void(0);" class="button deletion redux-ow_repeater-remove">' . __ ( 'Delete', 'redux-framework' ) . '</a></li>';
                    echo '</ul></div></fieldset></div>';
                    $x ++;
                }
            }

            if ( $x == 0 ) {
                echo '<div class="redux-ow_repeater-accordion-group"><fieldset class="redux-field" data-id="' . $this->field[ 'id' ] . '"><h3><span class="redux-ow_repeater-header">' . esc_attr ( sprintf ( __ ( 'New %s', 'redux-framework' ), $this->field[ 'content_title' ] ) ) . '</span></h3><div>';

                $hide = ' hide';

                if ( $this->field['image'] === true ) {

					echo '<div class="screenshot' . $hide . '">';
					echo '<a class="of-uploaded-image" href="">';
					echo '<img class="redux-ow_repeater-image" id="image_image_id_' . $x . '" src="" alt="image" target="_blank" rel="external" />';
					echo '</a>';
					echo '</div>';

					// Upload controls DIV
					echo '<div class="upload_button_div">';

					// If the user has WP3.5+ show upload/remove button
					echo '<span class="button media_upload_button" id="add_' . $x . '">' . __ ( 'Upload', 'redux-framework' ) . '</span>';

					echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $this->parent->args[ 'opt_name' ] . '[' . $this->field[ 'id' ] . '][attachment_id]">' . __ ( 'Remove', 'redux-framework' ) . '</span>';

					echo '</div>' . "\n";
				}

                echo '<ul id="' . $this->field[ 'id' ] . '-ul" class="redux-ow_repeater-list">';

				$title_type = "text";

                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'title' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'title' ] ) : __ ( 'Title', 'redux-framework' );
                echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-title_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][title]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                if ( $this->field['textOne'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textOne' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textOne' ] ) : __ ( 'Text 1', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textOne_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textOne]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textTwo'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textTwo' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textTwo' ] ) : __ ( 'Text 2', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textTwo_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textTwo]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textThree'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textThree' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textThree' ] ) : __ ( 'Text 3', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textThree_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textThree]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textFour'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textFour' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textFour' ] ) : __ ( 'Text 4', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textFour_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textFour]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textFive'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textFive' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textFive' ] ) : __ ( 'Text 5', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textFive_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textFive]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textSix'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textSix' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textSix' ] ) : __ ( 'Text 6', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textSix_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textSix]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textSeven'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textSeven' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textSeven' ] ) : __ ( 'Text 7', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textSeven_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textSeven]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textEight'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textEight' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textEight' ] ) : __ ( 'Text 8', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textEight_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textEight]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textNine'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textNine' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textNine' ] ) : __ ( 'Text 9', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textNine_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textNine]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

                if ( $this->field['textTen'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'textTen' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'textTen' ] ) : __ ( 'Text 10', 'redux-framework' );
					echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-textTen_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][textTen]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text" /></li>';
				}

				if ( $this->field['description'] === true ) {

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'description' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'description' ] ) : __ ( 'Description', 'redux-framework' );
                    echo '<li><textarea name="' . $this->field[ 'name' ] . '[' . $x . '][description]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-description_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6"></textarea></li>';
                }

                if ( $this->field['url'] === true ) {

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'url' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'url' ] ) : __ ( 'URL', 'redux-framework' );
					$url_type = "text";
					echo '<li><input type="' . $url_type . '" id="' . $this->field[ 'id' ] . '-url_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][url]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';
                }

				echo '<li><input type="hidden" class="slide-sort" name="' . $this->field[ 'name' ] . '[' . $x . '][sort]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-sort_' . $x . '" value="' . $x . '" />';

				if ( $this->field['image'] === true ) {

					echo '<li><input type="hidden" class="upload-id" name="' . $this->field[ 'name' ] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_id_' . $x . '" value="" />';
					echo '<input type="hidden" class="upload" name="' . $this->field[ 'name' ] . '[' . $x . '][image]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_url_' . $x . '" value="" readonly="readonly" />';
					echo '<input type="hidden" class="upload-height" name="' . $this->field[ 'name' ] . '[' . $x . '][height]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_height_' . $x . '" value="" />';
					echo '<input type="hidden" class="upload-width" name="' . $this->field[ 'name' ] . '[' . $x . '][width]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_width_' . $x . '" value="" /></li>';
					echo '<input type="hidden" class="upload-thumbnail" name="' . $this->field[ 'name' ] . '[' . $x . '][thumb]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-thumb_url_' . $x . '" value="" /></li>';
                }

				echo '<li><a href="javascript:void(0);" class="button deletion redux-ow_repeater-remove">' . __ ( 'Delete', 'redux-framework' ) . '</a></li>';
                echo '</ul></div></fieldset></div>';
            }
            echo '</div><a href="javascript:void(0);" class="button redux-ow_repeater-add button-primary" rel-id="' . $this->field[ 'id' ] . '-ul" rel-name="' . $this->field[ 'name' ] . '[title][]' . $this->field['name_suffix'] .'">' . sprintf ( __ ( 'Add %s', 'redux-framework' ), $this->field[ 'content_title' ] ) . '</a><br/>';
        }

        /**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue () {

			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
			else {
				wp_enqueue_script( 'media-upload' );
			}

            wp_enqueue_script( 'redux-field-media-js', ReduxFramework::$_url . 'assets/js/media/media' . Redux_Functions::isMin() . '.js', array( 'jquery', 'redux-js' ), time(), true );

			wp_enqueue_style( 'redux-field-ow_repeater', $this->extension_url . '/field_ow_repeater.css', time(), true );

            wp_enqueue_script ( 'redux-field-ow_repeater', $this->extension_url . '/field_ow_repeater.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-sortable', 'redux-field-media-js' ), time (), true );
        }
    }
}