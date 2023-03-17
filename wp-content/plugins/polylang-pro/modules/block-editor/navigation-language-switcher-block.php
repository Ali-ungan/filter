<?php
/**
 * @package Polylang-Pro
 */

/**
 * Language switcher block for navigation.
 *
 * @since 3.2
 */
class PLL_Navigation_Language_Switcher_Block extends PLL_Abstract_Language_Switcher_Block {

	/**
	 * Adds the required hooks specific to the navigation langague switcher.
	 *
	 * @since 3.2
	 *
	 * @return self
	 */
	public function init() {
		parent::init();

		add_action( 'rest_api_init', array( $this, 'register_switcher_menu_item_options_meta_rest_field' ) );

		return $this;
	}

	/**
	 * Returns the navigation language switcher block name with the Polylang's namespace.
	 *
	 * @since 3.2
	 *
	 * @return string The block name.
	 */
	protected function get_block_name() {
		return 'polylang/navigation-language-switcher';
	}

	/**
	 * Returns the supported pieces of context for the 'polylang/navigation-language-switcher' block.
	 * This context will be inherited from the 'core/navigation' block.
	 *
	 * @since 3.3
	 *
	 * @return string[]
	 */
	protected function get_context() {
		return array(
			'showSubmenuIcon',
			'openSubmenusOnClick',
			'textColor',
			'customTextColor',
			'backgroundColor',
			'customBackgroundColor',
			'overlayTextColor',
			'customOverlayTextColor',
			'overlayBackgroundColor',
			'customOverlayBackgroundColor',
			'fontSize',
			'customFontSize',
			'style',
		);
	}

	/**
	 * Renders the `polylang/navigation-language-switcher` block on server.
	 *
	 * @since 3.1
	 * @since 3.3 Accepts two new parameters, $content and $block.
	 *
	 * @param array    $attributes The block attributes.
	 * @param string   $content The saved content. Unused.
	 * @param WP_Block $block The parsed block.
	 * @return string The HTML string output to serve.
	 */
	public function render( $attributes, $content, $block ) {
		$attributes = $this->set_attributes_for_block( $attributes );

		$attributes['raw'] = true;
		$switcher = new PLL_Switcher();
		$switcher_items = $switcher->the_languages( $this->links, $attributes );
		$language_navigation_output = '';

		$top_level_item = $this->find_current_lang_item( $switcher_items );

		if ( $attributes['dropdown'] && $top_level_item ) {
			$language_navigation_output = $this->render_link_item( $top_level_item, $attributes, $block->context, $switcher_items );
		} else {
			foreach ( $switcher_items as $switcher_item ) {
				$language_navigation_output .= $this->render_link_item( $switcher_item, $attributes, $block->context );
			}
		}

		if ( empty( $language_navigation_output ) ) {
			return '';
		}


		// Adds is-layout-flex in admin for space between language items.
		$is_layout_flex = ! empty( $attributes['admin_render'] ) ? ' is-layout-flex' : '';

		// As WordPress won't render our polylang/navigation-language-switcher (see: https://github.com/WordPress/WordPress/blob/5.9/wp-includes/blocks/navigation.php#L488-L491)
		// We have to do it ourselves.
		return sprintf(
			'<ul class="wp-block-navigation__container%s">%s</ul>',
			$is_layout_flex,
			$language_navigation_output
		);
	}

	/**
	 * Register switcher menu item meta options as a REST API field.
	 *
	 * @since 3.2
	 *
	 * @return void
	 */
	public function register_switcher_menu_item_options_meta_rest_field() {
		register_post_meta(
			'nav_menu_item',
			'_pll_menu_item',
			array(
				'object_subtype' => 'nav_menu_item',
				'description'    => __( 'Language switcher settings', 'polylang-pro' ),
				'single'         => true,
				'show_in_rest'   => array(
					'schema' => array(
						'type'                 => 'object',
						'additionalProperties' => array(
							'type' => 'boolean',
						),
					),
				),
			)
		);
	}

	/**
	 * Renders language switcher in dropdown mode for navigation block. Applies recursively for dropdown.
	 * Partial copy of the WordPress functions render_block_core_navigation_link() and render_block_core_navigation_submenu().
	 * See: https://github.com/WordPress/wordpress-develop/blob/5.9.0/src/wp-includes/blocks/navigation-link.php#L116-L125 and https://github.com/WordPress/wordpress-develop/blob/5.9.0/src/wp-includes/blocks/navigation-submenu.php#L116-L125
	 *
	 * @since 3.2
	 *
	 * @param array    $switcher_item Raw element of a language switcher.
	 * @param array    $attributes    The attributes of the language switcher.
	 * @param string[] $context       The pieces of context inherited from the 'core/navigation' block.
	 * @param array    $inner_items   Elements of the submenu, used for dropdown. Default to empty array.
	 * @return string The rendered switcher.
	 */
	protected function render_link_item( $switcher_item, $attributes, $context = array(), $inner_items = array() ) {
		$has_submenu = ! empty( $inner_items ) && $attributes['dropdown'] && $switcher_item['current_lang'];
		$attributes['isTopLevelItem'] = $has_submenu || ! $attributes['dropdown']; // used in block_core_navigation_submenu_build_css_colors();
		$is_active   = $switcher_item['current_lang'];
		$show_submenu_indicators = isset( $context['showSubmenuIcon'] ) && $context['showSubmenuIcon'] && $has_submenu;
		$open_on_click           = isset( $context['openSubmenusOnClick'] ) && $context['openSubmenusOnClick'] && $has_submenu;
		$open_on_hover_and_click = isset( $context['openSubmenusOnClick'] ) && ! $context['openSubmenusOnClick'] && $show_submenu_indicators;

		$submenu_classes = array(
			'wp-block-navigation-submenu',
			'has-child',
			$open_on_click ? 'open-on-click' : '',
			$open_on_hover_and_click ? 'open-on-hover-click' : '',
		);

		$colors          = block_core_navigation_submenu_build_css_colors( $context, $attributes );
		$font_sizes      = block_core_navigation_submenu_build_css_font_sizes( $context );
		$style_classes   = array_merge(
			$colors['css_classes'],
			$font_sizes['css_classes']
		);
		$style_attributes = safecss_filter_attr( $colors['inline_styles'] . $font_sizes['inline_styles'] );

		if ( ! empty( $style_attributes ) ) {
			// safecss_filter_attr() strips last semicolon out, let's put it back.
			$style_attributes = rtrim( $style_attributes, ';' ) . ';';
		}

		$wp_classes = array(
			$is_active ? 'current-menu-item wp-block-navigation-item' : 'wp-block-navigation-item',
		);
		$classes = array_merge(
			isset( $switcher_item['classes'] ) ? $switcher_item['classes'] : array(),
			$style_classes,
			$wp_classes,
			$has_submenu ? $submenu_classes : array( 'wp-block-navigation-link' )
		);
		$css_classes = trim( implode( ' ', $classes ) );

		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $css_classes,
				'style' => $style_attributes,
			)
		); // Returns escaped attributes.

		$aria_label = esc_attr__( 'Language switcher submenu.', 'polylang-pro' );

		$html = '<li ' . $wrapper_attributes . '>';

		if ( ! $open_on_click ) {

			// Start appending HTML attributes to anchor tag.
			$html .= '<a class="wp-block-navigation-item__content"';
			if ( ! $has_submenu ) { // Don't create an hyperlink for the parent item.
				$html .= ' href="' . esc_url( $switcher_item['url'] ) . '"';
			}

			if ( $is_active ) {
				$html .= ' aria-current="page"';
			}

			if ( isset( $switcher_item['locale'] ) ) {
				$html .= ' hreflang="' . esc_attr( $switcher_item['locale'] ) . '"';

				$html .= ' lang="' . esc_attr( $switcher_item['locale'] ) . '"';
			}

			$html .= '>'; // End appending HTML attributes to anchor tag.

			$html .= $this->get_item_title( $switcher_item, $attributes );

			if ( $show_submenu_indicators ) {
				$html .= '<span class="wp-block-navigation__submenu-icon">' . block_core_navigation_submenu_render_submenu_icon() . '</span>';
			}

			$html .= '</a>'; // End anchor tag content.
		} else {
			// If menus open on click, we render the parent as a button.
			$html .= '<button aria-label="' . $aria_label . '" class="wp-block-navigation-item__content wp-block-navigation-submenu__toggle" aria-expanded="false">';

			$html .= $this->get_item_title( $switcher_item, $attributes );

			$html .= '<span class="wp-block-navigation__submenu-icon">' . block_core_navigation_submenu_render_submenu_icon() . '</span>';

			$html .= '</button>';
		}

		if ( $has_submenu ) {
			// Start inner content for submenu.
			$inner_blocks_html = '';
			foreach ( $inner_items as $inner_item ) {
				$inner_blocks_html .= $this->render_link_item( $inner_item, $attributes, $context );
			}

			$html .= sprintf(
				'<ul class="wp-block-navigation__submenu-container">%s</ul>',
				$inner_blocks_html
			);
			// End inner content for submenu.
		}

		$html .= '</li>';

		return $html;
	}

	/**
	 * Returns the current language item from the switcher elements.
	 *
	 * @since 3.2
	 *
	 * @param array $switcher_items An array of raw switcher items.
	 * @return array|false The item in the current language if found, false otherwise.
	 */
	protected function find_current_lang_item( $switcher_items ) {
		$filtered_items = wp_list_filter( $switcher_items, array( 'current_lang' => true ) );

		return reset( $filtered_items );
	}

	/**
	 * Formats a language item title based on attributes.
	 *
	 * @since 3.3
	 *
	 * @param array $item       A raw language switcher item.
	 * @param array $attributes The language switcher attributes.
	 * @return string Formatted menu item title
	 */
	protected function get_item_title( $item, $attributes ) {
		if ( $attributes['show_flags'] ) {
			if ( $attributes['show_names'] ) {
				$title = sprintf( '%1$s<span style="margin-%2$s:0.3em;">%3$s</span>', $item['flag'], is_rtl() ? 'right' : 'left', esc_html( $item['name'] ) );
			} else {
				$title = $item['flag'];
			}
		} else {
			$title = esc_html( $item['name'] );
		}

		// Wrap title with span to isolate it from submenu icon.
		$html  = '<span class="wp-block-navigation-item__label">';
		$html .= $title;
		$html .= '</span>';

		return $html;
	}
}
