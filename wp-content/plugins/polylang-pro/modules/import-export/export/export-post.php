<?php
/**
 * @package Polylang-Pro
 */

/**
 * Class PLL_Export_Post
 *
 * @since 3.3
 */
class PLL_Export_Post {
	/**
	 * Allows to add post metas to export file.
	 *
	 * @var PLL_Export_Post_Metas
	 */
	protected $post_meta;

	/**
	 * A reference to the current PLL_Model.
	 *
	 * @var PLL_Model
	 */
	protected $model;

	/**
	 * PLL_Export_Post constructor.
	 *
	 * @since 3.3
	 *
	 * @param PLL_Model $model A reference to the current PLL_Model.
	 */
	public function __construct( $model ) {
		$this->post_meta = new PLL_Export_Post_Metas();
		$this->model     = $model;
	}

	/**
	 * Yield all the piece of information required for the translation
	 * and add them to the export file.
	 *
	 * @since 3.3
	 *
	 * @param PLL_Export_Multi_Files $export          The export file.
	 * @param int                    $post_id         The post id.
	 * @param string                 $target_language Targeted languages.
	 * @return PLL_Export_Multi_Files The given export file with added data.
	 */
	public function export( $export, $post_id, $target_language ) {
		$target_language = $this->model->get_language( $target_language );

		if ( empty( $target_language ) ) {
			return $export;
		}

		$post = get_post( $post_id );

		if ( empty( $post ) ) {
			return $export;
		}

		$source_language = $this->model->post->get_language( $post_id );

		if ( empty( $source_language ) ) {
			return $export;
		}

		$export->set_source_language( $source_language->get_locale( 'display' ) );
		$export->set_target_language( $target_language->get_locale( 'display' ) );
		$export->set_site_reference( get_site_url() );

		$translation_id   = $this->model->post->get_translation( $post_id, $target_language->slug );
		$translation_post = $translation_id ? get_post( $translation_id ) : false;

		$export->set_source_reference( PLL_Import_Export::TYPE_POST, (string) $post->ID );
		$export->add_translation_entry( PLL_Import_Export::POST_TITLE, $post->post_title, empty( $translation_post ) ? '' : $translation_post->post_title );

		$content                 = PLL_Translation_Walker_Factory::create_from( $post->post_content );
		$translations_identified = new PLL_Translations_Identified();
		$content->walk( array( $translations_identified, 'add_entry_or_merge' ) );

		$translations = $translations_identified->entries;

		if ( ! empty( $translations ) ) {
			foreach ( $translations as $entry ) {
				// The translated post content isn't exported when source or translated posts have blocks.
				$target_content = ( ! empty( $translation_post ) && has_blocks( $translation_post->post_content ) ) || empty( $translation_post ) || has_blocks( $post->post_content ) ? '' : $translation_post->post_content;
				$export->add_translation_entry( PLL_Import_Export::POST_CONTENT, $entry->singular, $target_content, array( 'id' => $entry->get_id() ) );
			}
		}

		if ( $post->post_excerpt ) {
			$export->add_translation_entry( PLL_Import_Export::POST_EXCERPT, $post->post_excerpt, empty( $translation_post ) ? '' : $translation_post->post_excerpt );
		}

		return $this->post_meta->export( $export, $post->ID, $translation_post ? $translation_post->ID : 0 );
	}
}
