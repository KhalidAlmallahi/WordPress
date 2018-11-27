<?php
function shortcode( $attr ) {
	
	$url = false;
	$video_id = false;

	if ( isset( $attr[0] ) ) {
		$url = ltrim( $attr[0] , '=' );
	} elseif ( function_exists ( 'ampforwp_youtube_shortcode' ) ) {
		$url = ampforwp_youtube_shortcode( $attr );
	}

	if ( empty( $url ) ) {
		return '';
	}
	
	if (self::URL_PATTERN != $url) {
		if ( isset( $attr["id"] ) ) {$video_id = $attr["id"];} else {$video_id = $this->get_video_id_from_url( $url );}	
	}
	else {
		$video_id = $this->get_video_id_from_url( $url );
	}

	return $this->render( array(
		'url' => $url,
		'video_id' => $video_id,
	) );
}
?>