<?php
namespace Saltus\WP\Plugin\Saltus\InteractiveMaps\Plugin;

use Saltus\WP\Plugin\Saltus\InteractiveMaps\Core;

/**
 * Create Map
 */
class Map {

	public $core;

	/**
	 * Options for Map CPT
	 */
	private $options;


	/**
	 * Current Map ID
	 */
	public $map_id;

	/**
	 * Define Assets
	 *
	 * @param Core $core This plugin's instance.
	 */
	public function __construct( Core $core ) {
		$this->core = $core;
	}

	/**
	 * Setup proper data needed to render map
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function setup( $atts ) {

		$id        = $atts['id'];
		$options   = get_option( 'interactive-maps' );
		$main_meta = $this->get_meta( $id );

		if ( isset( $atts['demo'] ) && isset( $_GET['map'] ) ) {
			$main_meta['map'] = sanitize_text_field( $_GET['map'] );
		}

		$meta = $this->prepare_meta( $main_meta, $id );

		if( ! is_array( $meta ) ) {
			$meta = array();
		}

		$performance = array(
			'animations' => isset( $options['animations'] ) ? $options['animations'] : true,
			'lazyLoad'   => isset( $options['lazyLoad'] ) ? $options['lazyLoad'] : false,
		);

		$meta['performance'] = $performance;

		// default zoom
		$meta['zoomMaster'] = isset( $options['zoomMaster'] ) ? $options['zoomMaster'] : false;

		$this->options = $options;
		$this->meta    = $meta;
		$this->map_id  = $id;

	}

	/**
	 * Prepare meta data to include the proper arguments.
	 * Also prepares
	 *
	 * @param [type] $id
	 * @param [type] $meta
	 * @return void
	 */
	public function prepare_meta( $meta, $id = false ) {

		// set overlay
		if ( ! empty( $meta ) ) {

			if ( $id ) {
				$meta['id']        = $id;
				$meta['container'] = 'map_' . $id;
				$meta['title']     = get_the_title( $id );
				$meta['regions']   = $this->process_regions_dictionary( $id, $meta['regions'] );
			}

			$meta['regions'] = isset( $meta['regions'] ) ? $this->tooltip_nl2br( $id, $meta['regions'] ) : array();
			$meta['roundMarkers'] = isset( $meta['roundMarkers'] ) ? $this->tooltip_nl2br( $id, $meta['roundMarkers'] ) : array();

			$meta['urls']    = [ $meta['map'] ];
			$meta            = apply_filters( 'igm_prepare_meta', $meta );
			$meta['urls']    = $this->convert_source_urls( $meta['urls'] );

			// set map url
			$meta = $this->set_map_url( $meta );

		}

		return $meta;
	}

	/**
	 * Convert tooltip line breaks to br
	 *
	 * @param [type] $id
	 * @param array $regions
	 * @return array $regions
	 */
	function tooltip_nl2br( $id, $entries = array() ) {

		if ( empty( $entries ) ) {
			return $entries;
		}

		foreach ( $entries as $k => &$entry ) {
			if ( isset( $entry['tooltipContent'] ) && $entry['tooltipContent'] !== '' ) {
				if( ! $id ){
					$entry['tooltipContent'] = stripslashes( str_replace( "\r\n", '<br>', $entry['tooltipContent'] ) );
				} else {
					$entry['tooltipContent'] = nl2br( $entry['tooltipContent'] );
				}
			}
		}

		return $entries;
	}

	/**
	 * Convert region names to region codes based on saved data
	 *
	 * @param array $id post id
	 * @param array $regions regions data
	 * @return array $regions converted regions data
	 */
	public function process_regions_dictionary( $id, $regions = array() ) {

		if ( ! $id || empty( $regions ) ) {
			return $regions;
		}

		// get dictionary data
		$data         = get_post_meta( $id, 'map_regions_info', true );
		$regions_data = isset( $data['regionData'] ) ? $data['regionData'] : '';

		if ( $regions_data !== '' ) {
			$json = json_decode( $regions_data, true );

			foreach ( $regions as $k => &$region ) {
				if ( isset( $region['id'] ) && array_key_exists( $region['id'], $json ) ) {
					$region['id'] = $json[ $region['id'] ];
				}
			}
		}

		return $regions;
	}

	/**
	 * Convert map names into urls and create a reference array
	 *
	 * @param $maps array of map names
	 * @return array
	 */
	public function convert_source_urls( $maps ) {
		if ( empty( $maps ) ) {
			return;
		}

		$urls = [];

		foreach ( $maps as $name ) {
			if ( 'custom' === $name ) {
				continue;
			}
			$urls[ $name ] = $this->get_url_from_name( $name );
		}

		return $urls;
	}

	/**
	 * Get url for map data based on provided name
	 * string $name
	 * bool $json - to return the url of the json file or js file
	 */
	public function get_url_from_name( $name, $json = false ) {

		$base_url = 'https://www.amcharts.com/lib/4/geodata/';

		// maps built by cmoreira
		if ( strpos( $name, 'custom_' ) === 0 ) {
			$base_url = plugins_url( '/src/geodata/', $this->core->file_path );
		}

		if ( $json ) {
			return $base_url . 'json/' . $name . '.json';
		}

		return $base_url . $name . '.js';

	}

	/**
	 * Get map_info meta data
	 *
	 * @param [int] $id map id
	 * @return array of map meta data
	 */
	public function get_meta( $id ) {

		$meta = get_post_meta( $id, 'map_info', true );
		return $meta;

	}

	/**
	 * Set meta mapURL value to full url
	 *
	 * @param [type] $meta
	 * @return void
	 */
	private function set_map_url( $meta ) {

		if ( $meta['map'] !== 'custom' ) {
			$meta['mapURL'] = $this->get_url_from_name( $meta['map'], false );
		}

		return $meta;
	}

	/**
	 * Render html for the map and enqueue necessary assets
	 *
	 * @return string html code for the map container
	 */
	public function render( $atts, $core ) {

		// reset filters, they might have been added by other maps
		remove_all_filters('igm_mapbox_before');
		remove_all_filters('igm_mapbox_after');
		remove_all_filters('igm_mapbox_classes');

		$this->setup( $atts );

		$id     = $atts['id'];
		$assets = new Assets( $core );
		$assets->load_map_scripts( $this->meta );
		$assets->load_map_styles();

		$before = apply_filters( 'igm_mapbox_before', '', $id );
		$after  = apply_filters( 'igm_mapbox_after', '', $id );

		// check for placeholders that might have been added in the before/after content
		$search = array(
			'{description}',
		);

		$ck_desc = isset( $this->meta['description'] ) ? $this->meta['description'] : '';
		$replace = array(
			$ck_desc,
		);

		$before = str_replace( $search, $replace, $before );
		$after  = str_replace( $search, $replace, $after );

		$height = isset( $this->meta['visual']['paddingTop'] ) ? $this->meta['visual']['paddingTop'] : '56.25';

		// if percentage sign was included, remove it
		$height    = strpos( $height, '%' ) !== false ? str_replace( '%', '', $height ) : $height;
		$max_width = isset( $this->meta['visual']['maxWidth'] ) && '' !== $this->meta['visual']['maxWidth'] ? $this->meta['visual']['maxWidth'] : '2200';

		$map_classes = apply_filters( 'igm_mapbox_classes', 'map_box' );

		$html = sprintf(
			'<div class="map_wrapper" id="map_wrapper_%5$s">
				<div class="%1$s" style="max-width:%2$s">
					%3$s
					<div class="map_aspect_ratio" style="padding-top:%4$s">
						<div class="map_container">
							<div class="map_render" id="map_%5$s"></div>
						</div>
					</div>
				</div>%6$s
			</div>',
			$map_classes,
			$max_width . 'px',
			$before,
			$height . '%',
			$id,
			$after
		);

		if ( isset( $_GET['debug'] ) ) {
			$html .= sprintf(
				'<pre>%s</pre>',
				wp_json_encode( $this->meta, JSON_PRETTY_PRINT )
			);
		}

		$html = apply_filters( 'igm_map_after', $html, $id );

		return $html;
	}
}
