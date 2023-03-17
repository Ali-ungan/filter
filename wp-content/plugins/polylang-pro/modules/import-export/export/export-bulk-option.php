<?php
/**
 * Export actions class file
 *
 * @package exportActions
 */

/**
 * A class that handles export actions
 *
 * @file
 *
 * @since 3.3
 */
class PLL_Export_Bulk_Option extends PLL_Bulk_Translate_Option {

	/**
	 * Represents the current file or multiple files to export.
	 *
	 * @var PLL_Export_Multi_Files
	 */
	protected $export;

	/**
	 * Allows to add post data to exported file.
	 *
	 * @var PLL_Export_Post
	 */
	protected $post;

	/**
	 * Allows to add term data to exported file.
	 *
	 * @var PLL_Export_Terms
	 */
	protected $term;

	/**
	 * PLL_Export_Bulk_Option constructor.
	 *
	 * @since 3.3
	 *
	 * @param PLL_Model $model Used to query languages and post translations.
	 */
	public function __construct( $model ) {
		parent::__construct(
			array(
				'name'        => 'export',
				'description' => __( 'Export selected content into a file', 'polylang-pro' ),
				'priority'    => 15,
			),
			$model
		);
	}

	/**
	 * Defines wether the export Bulk Translate option is available given the admin panel and user logged.
	 * Do not add the 'export' bulk translate option if LIBXML extension is not loaded, no matter the screen.
	 *
	 * @since 3.3
	 *
	 * @return bool
	 */
	public function is_available() {
		if ( ! extension_loaded( 'libxml' ) ) {
			return false;
		}

		$screen = get_current_screen();

		if ( empty( $screen ) ) {
			return false;
		}

		switch ( $screen->base ) {
			case 'edit':
			case 'upload':
				$post_type_object = get_post_type_object( $screen->post_type );

				if ( empty( $post_type_object ) ) {
					return false;
				}

				$capability = $post_type_object->cap->edit_posts;
				break;
			default:
				return false;
		}

		return current_user_can( $capability );
	}


	/**
	 *
	 * Export post content for converter.
	 *
	 * @since 3.3
	 *
	 * @param int[]    $post_ids         The ids of the posts selected for export.
	 * @param string[] $target_languages The target languages.
	 *
	 * @throws Exception Exception.
	 *
	 * @return void|array {
	 *     array PLL_Bulk_Translate::ERROR Error notices to be displayed to the user when something wrong occurs when exporting.
	 * }
	 *
	 * @phpstan-param non-empty-array<int<1,max>> $post_ids
	 * @phpstan-param non-empty-array<string> $target_languages
	 * @phpstan-return void|array{
	 *     error: non-empty-array<0,string>
	 * }
	 */
	public function do_bulk_action( $post_ids, $target_languages ) {
		/** @var array<PLL_Language|false> */
		$target_languages = array_combine(
			$target_languages,
			array_map( array( $this->model, 'get_language' ), $target_languages )
		);
		$target_languages = array_filter( $target_languages );

		if ( empty( $target_languages ) ) {
			return array(
				PLL_Bulk_Translate::ERROR => array( esc_html__( 'Invalid target languages.', 'polylang-pro' ) ),
			);
		}

		/** @var array<int<1,max>> $post_ids */
		$is_ambiguous = $this->is_ambiguous( $post_ids );

		if ( is_wp_error( $is_ambiguous ) ) {
			return array(
				PLL_Bulk_Translate::ERROR => array( $is_ambiguous->get_error_message() ),
			);
		}

		// Instantiates the required properties for the later posts export.
		$this->export = new PLL_Export_Multi_Files( new PLL_Xliff_Export() );
		$this->post   = new PLL_Export_Post( $this->model );
		$this->term   = new PLL_Export_Terms( $this->model );

		$this->export( new PLL_Export_Download_Zip(), $post_ids, array_keys( $target_languages ) );
	}

	/**
	 * Exports the posts with their related items and creates the files before redirecting.
	 *
	 * @since 3.3
	 *
	 * @param PLL_Export_Download_Zip $downloader       Handles the creation of a zip file containing the export.
	 * @param int[]                   $post_ids         The ids of the posts selected for export.
	 * @param string[]                $target_languages The target languages.
	 * @param array                   $args             {
	 *     Optional. A list of optional arguments.
	 *
	 *     @type bool $include_translated_items Tells if items that are already translated in the target languages must
	 *                                          also be exported. This applies only to linked items (like assigned
	 *                                          terms, items from reusable blocks, etc). Default is false.
	 * }
	 * @return void
	 *
	 * @phpstan-param array{include_translated_items?:bool} $args
	 */
	protected function export( PLL_Export_Download_Zip $downloader, array $post_ids, array $target_languages, array $args = array() ) {
		$posts = get_posts(
			array(
				'post__in'               => $post_ids,
				'posts_per_page'         => count( $post_ids ),
				'orderby'                => 'post__in',
				'ignore_sticky_posts'    => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'post_type'              => $this->model->get_translated_post_types(),
				'post_status'            => 'any',
			)
		);

		if ( empty( $posts ) ) {
			return;
		}

		$posts_by_lang = $this->sort_posts_by_language( $posts, $target_languages );

		if ( empty( $posts_by_lang ) ) {
			return;
		}

		$include_translated_items = ! empty( $args['include_translated_items'] );
		$taxonomies               = $this->model->get_translated_taxonomies();
		$collect_posts            = new PLL_Collect_Linked_Posts( $this->model->options );
		$collect_terms            = new PLL_Collect_Linked_Terms();

		foreach ( $posts_by_lang as $lang_slug => $posts ) {
			$lang = $this->model->get_language( $lang_slug );

			if ( empty( $lang ) ) {
				continue;
			}

			$post_ids        = wp_list_pluck( $posts, 'ID' );
			$linked_post_ids = $collect_posts->get_linked_post_ids( $post_ids );

			if ( ! $include_translated_items ) {
				// Remove items that are already translated in this language.
				foreach ( $linked_post_ids as $i => $linked_post_id ) {
					if ( $this->model->post->get_translation( $linked_post_id, $lang_slug ) ) {
						// A translation already exists.
						unset( $linked_post_ids[ $i ] );
					}
				}
			}

			$post_ids_to_export = array_merge( $post_ids, $linked_post_ids );

			// Export posts, and posts collected in them.
			foreach ( $post_ids_to_export as $post_id ) {
				$this->translate( $post_id, $lang_slug );
			}

			// Get terms assigned to linked posts.
			$post_terms = wp_get_object_terms( $post_ids, $taxonomies );
			$post_terms = is_array( $post_terms ) ? $post_terms : array();

			// Collect terms in posts.
			$collected_terms = $collect_terms->get_linked_terms( $posts, $taxonomies );

			if ( ! $include_translated_items ) {
				// Remove items that are already translated in this language.
				foreach ( $collected_terms as $i => $term ) {
					if ( $this->model->term->get_translation( $term->term_id, $lang_slug ) ) {
						// A translation already exists.
						unset( $collected_terms[ $i ] );
					}
				}
			}

			// Merge terms and remove duplicates.
			$all_terms = array();

			foreach ( array_merge( $post_terms, $collected_terms ) as $term ) {
				if ( ! isset( $all_terms[ $term->term_id ] ) ) {
					$all_terms[ $term->term_id ] = $term;
				}
			}

			// Export all terms.
			$this->export = $this->term->export( $this->export, $all_terms, $lang );
		}

		$downloader->create( $this->export );
		add_action( 'wp_redirect', array( $downloader, 'send_response' ) );
	}

	/**
	 * Get post data from id
	 *
	 * @since 3.3
	 *
	 * @param int    $post_id         The ID of the post to export.
	 * @param string $target_language Targeted languages.
	 *
	 * @throws Exception Exception.
	 */
	public function translate( $post_id, $target_language ) {
		$this->export = $this->post->export( $this->export, $post_id, $target_language );
	}

	/**
	 * Groups `WP_Post` objects by their language.
	 *
	 * @since 3.3
	 *
	 * @param WP_Post[] $posts            An array of `WP_Post` objects to translate.
	 * @param string[]  $target_languages The target language slugs for translation.
	 * @return array[]                    An array, keyed with lang slugs, and containing arrays of `WP_Post` objects.
	 *
	 * @phpstan-return array<string, non-empty-array<int, WP_Post>>
	 */
	protected function sort_posts_by_language( array $posts, array $target_languages ) {
		if ( empty( $posts ) || empty( $target_languages ) ) {
			return array();
		}

		$translation_matrix = array();

		// Set the posts to translate for each target language.
		foreach ( $target_languages as $target_language ) {
			foreach ( $posts as $post ) {
				$post_lang = $this->model->post->get_language( $post->ID );

				if ( ! empty( $post_lang ) && $post_lang->slug !== $target_language ) {
					/** @phpstan-var array<string, non-empty-array<int, WP_Post>> $translation_matrix */
					$translation_matrix[ $target_language ][] = $post;
				}
			}
		}

		return $translation_matrix;
	}

	/**
	 * Checks there is no ambiguity in the selected posts to export.
	 * Ambiguity happens when 2 posts that are translations of each other are selected to be translated.
	 *
	 * @since 3.3
	 *
	 * @param int[] $post_ids The ids of the posts selected for export.
	 * @return WP_Error|false An error if an ambiguity is found, false otherwise. The error message should not be
	 *                        escaped: it contains `<br>` tags and the texts are already escaped.
	 *
	 * @phpstan-param array<int<1,max>> $post_ids
	 */
	protected function is_ambiguous( array $post_ids ) {
		$duplicates = $this->find_duplicate_translations( $post_ids );

		if ( empty( $duplicates ) ) {
			return false;
		}

		$post_titles = $this->get_post_titles( array_merge( ...$duplicates ) ); // List of post titles keyed by post ID.

		if ( empty( $post_titles ) ) {
			// Uh?
			return false;
		}

		// Wrap post titles into quotes.
		$post_titles = array_map(
			function ( $post_title ) {
				// translators: %s is a post title.
				return sprintf( _x( '"%s"', 'quoted post title', 'polylang-pro' ), $post_title );
			},
			$post_titles
		);

		$message = esc_html__( 'Ambiguous choice of contents to export. Please do not select contents that are translations of each other.', 'polylang-pro' );

		foreach ( $duplicates as $duplicate ) {
			$duplicate_titles = array_intersect_key( $post_titles, array_flip( $duplicate ) );

			$message .= '<br/>' . esc_html(
				sprintf(
					// translators: %s is a list of post titles.
					_x( '- %s.', 'ambiguous posts list', 'polylang-pro' ),
					wp_sprintf_l( '%l', $duplicate_titles )
				)
			);
		}

		return new WP_Error( 'pll_export_target_source_language', $message );
	}

	/**
	 * Find duplicate translations among the given list of post IDs.
	 *
	 * @since 3.3
	 *
	 * @param int[] $post_ids The ids of the posts selected for export.
	 * @return int[][] A list of arrays of post IDs.
	 *
	 * @phpstan-param array<int<1,max>> $post_ids
	 * @phpstan-return array<non-empty-array<int<1,max>>>
	 */
	protected function find_duplicate_translations( array $post_ids ) {
		$return  = array();
		$all_ids = array();

		foreach ( $post_ids as $post_id ) {
			if ( in_array( $post_id, $all_ids, true ) ) {
				// Already processed this case (already met one of this post's translations).
				continue;
			}

			/** @var array<int<1,max>> $translation_ids */
			$translation_ids = $this->model->post->get_translations( $post_id );

			// Look for this post's translations in the list of posts to translate.
			$common_post_ids = array_intersect( $post_ids, $translation_ids );

			if ( count( $common_post_ids ) <= 1 ) {
				// No translations found except this post.
				continue;
			}

			// Ambiguity found.
			$return[] = $common_post_ids;
			$all_ids  = array_merge( $all_ids, $common_post_ids );
		}

		return $return;
	}

	/**
	 * Returns a list of post titles, given a list of post IDs.
	 *
	 * @since 3.3
	 *
	 * @param int[] $post_ids The ids of the posts selected for export.
	 * @return string[] An array of post titles, keyed by post ID.
	 *
	 * @phpstan-param array<int<1,max>> $post_ids
	 * @phpstan-return array<int<1,max>,string>
	 */
	protected function get_post_titles( array $post_ids ) {
		$posts = new WP_Query(
			array(
				'post__in'               => $post_ids,
				'post_type'              => 'any',
				'post_status'            => 'any',
				'posts_per_page'         => count( $post_ids ), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'lang'                   => '',
			)
		);

		/** @var array<int<1,max>,string> */
		return wp_list_pluck( $posts->posts, 'post_title', 'ID' );
	}
}
