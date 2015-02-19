<?php

// Kwik Videos Utilities



function kwik_player($post_id){
	$kv_options = get_option('kwik_videos_options');
	
	
	$kv_player = '';
	$video_sources = get_post_meta($post_id, 'kv_source', false);
	
	if($kv_options['player'] == 'jwplayer5'){

		$kv_player .= '<div id="kwik_player"></div>';
		$kv_player .= '
		<script type="text/javascript">
			jwplayer("kwik_player").setup({
				file: "'.kv_jwp_video_source($post_id).'",height: '.trim($kv_options['player_height'], "px").',image: "'.kv_cover_image($post_id).'",'.($kv_options['jwp_skin'] == 'default' ? '' : 'skin: "'.plugins_url('/kwik-videos/lib/players/jwplayer5/skins/').$kv_options['jwp_skin'].'/'.$kv_options['jwp_skin'].'.xml",').'width: '.trim($kv_options['player_width'], "px").',flashplayer: "'.plugins_url('/kwik-videos/lib/players/'.$kv_options['player']).'/player.swf", startparam: "starttime"
			});
		</script>';
		
	}
	elseif($kv_options['player'] == 'jwplayer6'){

		$kv_player .= '<div id="kwik_player"></div>';
		$kv_player .= '
		<script type="text/javascript">
			jwplayer("kwik_player").setup({
				file: "'.kv_jwp_video_source($post_id).'",height: '.trim($kv_options['player_height'], "px").',image: "'.kv_cover_image($post_id).'",'.($kv_options['jwp_skin'] == 'default' ? '' : 'skin: "'.plugins_url('/kwik-videos/lib/players/jwplayer/skins/').$kv_options['jwp_skin'].'/'.$kv_options['jwp_skin'].'.xml",').'width: '.trim($kv_options['player_width'], "px").'
			});
		</script>';
		
	} elseif($kv_options['player'] == 'video-js'){
		
		$kv_player .= '
			<video id="kv_video" class="video-js vjs-default-skin" width="' . trim($kv_options['player_width'], "px") . '" height="' . trim($kv_options['player_height'], "px") . '" style="height:' . $kv_options['player_height'] . '; width:' . $kv_options['player_width'] . '" controls="controls" autoplay="autoplay" preload="auto" data-setup="{}" poster="' . kv_cover_image($post_id) . '">';
			$kv_player .= kv_html5_video_sources($post_id);
			
			$kv_player .= kv_flash_fallback($post_id);
			
/*			$kv_player .= '<object width="' . trim($kv_options['player_width'], "px") . '" height="' . trim($kv_options['player_height'], "px") . '" id="live_video_object" type="application/x-shockwave-flash" data="' . get_bloginfo('url') . '/wp-content/plugins/kwik-videos/lib/players/default/universalPlayer.swf" name="live_video_object" >
						<param value="true" name="allowfullscreen">
						<param name="wmode" value="opaque" />
						<param value="always" name="allowscriptaccess">
						<param value="high" name="quality">
						<param name="bgcolor" value="'.($kv_options['player_theme'] == 'light' ? '#ffffff' : '#000000').'"/>
						<param value="player.style.global=' . $kv_options['player_theme'] . '&amp;player.start.' . kv_flash_fallback_source($post_id) . '&amp;player.controls.hd=false&amp;player.start.paused=false&amp;player.start.cover=' . $cover_url . ' name="flashvars">
					</object>';*/
					
					
			$kv_player .= '</video>';
		
		
		
	}else{
		
		$kv_player .= '
			<video id="kv_video" class="video-js vjs-default-skin" width="'.trim($kv_options['player_width'], "px").'" height="' . trim($kv_options['player_height'], "px") . '" style="height:' . $kv_options['player_height'] . '; width:' . $kv_options['player_width'] . '" controls="controls" autoplay="autoplay" preload="auto" data-setup="{}" poster="' . $cover_url . '">';
			$kv_player .= kv_html5_video_sources($post_id);
			
			$kv_player .= kv_flash_fallback($post_id);
			
/*			$kv_player .= '<object width="' . trim($kv_options['player_width'], "px") . '" height="' . trim($kv_options['player_height'], "px") . '" id="live_video_object" type="application/x-shockwave-flash" data="' . get_bloginfo('url') . '/wp-content/plugins/kwik-videos/lib/players/default/universalPlayer.swf" name="live_video_object" >
						<param value="true" name="allowfullscreen">
						<param name="wmode" value="opaque" />
						<param value="always" name="allowscriptaccess">
						<param value="high" name="quality">
						<param name="bgcolor" value="'.($kv_options['player_theme'] == 'light' ? '#ffffff' : '#000000').'"/>
						<param value="player.style.global=' . $kv_options['player_theme'] . '&amp;player.start.' . kv_flash_fallback_source($post_id) . '&amp;player.controls.hd=false&amp;player.start.paused=false&amp;player.start.cover=' . $cover_url . ' name="flashvars">
					</object>';*/
					
			$kv_player .= '</video>';
		
		
		
	}
		
	return $kv_player;
	
}


function kv_jwp_video_source($post_id){
	$video_sources = get_post_meta($post_id, 'kv_source', false);
	$video_sources = $video_sources[0];
	return $video_sources[0];	
}


function kv_html5_video_sources($post_id){
	$video_sources = get_post_meta($post_id, 'kv_source', false);
	$video_sources = $video_sources[0];
	$kv_sources = '';
	    foreach ($video_sources as $video_source) {
            $file_ext = pathinfo($video, PATHINFO_EXTENSION);
            $kv_sources .= '<source src="' . $video_source . '" type=\'' . kv_mimeType($video_source) . '\'/>';            
        } // end foreach videos
		
		return $kv_sources;
	
}


// below function needs to be reconfigured for 
function kv_flash_fallback_source($post_id){
	$video_sources = get_post_meta($post_id, 'kv_source', true);
	$kv_sources = '';
	    foreach ($video_sources as $video_source) {
            // Set the video resource to be used by the flash fallback 
            if (strstr($video_source, 'rtmp')) {
                $kv_flash_fallback_source = "resource=rtmp:default:" . htmlentities($video_source);
            } else if (strstr($video_source, 'youtube.com')) {
                $kv_flash_fallback_source = parse_url($video_source, PHP_URL_QUERY);
                $kv_flash_fallback_source = parse_str($kv_flash_fallback_source, $feed_url_output);
                $kv_flash_fallback_source = "id=" . $feed_url_output['v'];
            } else {
                $kv_flash_fallback_source = "resource=video:default:" . $video_source;
            }            
        } // end foreach videos
		
		return $kv_flash_fallback_source;	
	
}


function kv_flash_fallback($post_id){
	
	$kv_options = get_option('kwik_videos_options');
	$cover		= wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
    $cover_url  = $cover['0'];
	
	    $kv_flash_fallback = '<object id="flash_fallback" class="vjs-flash-fallback" width="'.trim($kv_options['player_width'], "px").'" height="'.trim($kv_options['player_height'], "px").'" type="application/x-shockwave-flash"
        data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf">
        <param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" />
        <param name="allowfullscreen" value="true" />
        <param name="flashvars" value=\'config={"playlist":["$cover_url", {"url": "http://video-js.zencoder.com/oceans-clip.mp4","autoPlay":false,"autoBuffering":true}]}\' />

        <img src="$cover_url" width="'.$kv_options['player_width'].'" height="'.$kv_options['player_height'].'" alt="Poster Image"
          title="No video playback capabilities." />
      </object>';
	  
	  return $kv_flash_fallback;
	
	
	}



function kv_cover_image($post_id){
	
	$cover		= wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');
    return $cover['0'];
	
}




function kv_get_types_select(){
	$kv_options  = get_option('kwik_videos_options');
    $video_types = $kv_options['video_types'];

	$output = '<select name="video_type" id="video_type">';
	$output .= '<option class="vid_type-option" value="all">'.__('All','kwik').'</option>';
	foreach ($video_types as $video_type){	
		$output .= '<option class="cat-option" value="' . $video_type . '">' . $video_type . '</option>'; 
	}
	$output .= '</select>';
	echo $output;
}


function kv_get_category_select(){
	$categories = get_categories();
	$output = '<select name="news_cat" id="cat_filter">';
	$output .= '<option class="cat-option" value="all">'.__('All','kwik').'</option>';
	foreach ($categories as $cat){	
		$output .= '<option class="cat-option" value="' . $cat->slug . '">' . $cat->name . '</option>'; 
	}
	$output .= '</select>';
	echo $output;
}



function kv_get_years_select($post_type = array('post')){
	$output = '<select id="publish_year" name="publish_year">';
	$output .= '<option value="all" selected="selected">'. __('All','kwik').'</option>';
	$news_args = array(
		'post_status' => 'publish',
		'post_type' => $post_type,
		'posts_per_page' => -1
	);	
	$news = new wp_query($news_args);
	$the_years = array();
	if( $news->have_posts() ) {
		while ($news->have_posts()) : $news->the_post();
			$the_years[] = get_the_date('Y');		 
		endwhile; wp_reset_postdata(); rewind_posts();	 
		$the_years = array_unique($the_years);
		arsort($the_years);		
		foreach($the_years as $the_year){		
			$output .= '<option value="'.$the_year.'">'.$the_year.'</option>';		
		}	 
	} 
	$output .= '</select>';
	echo $output;
}

















class Browser { 
    /** 
    Figure out what browser is used, its version and the platform it is 
    running on. 

    The following code was ported in part from JQuery v1.3.1 
    */ 
    public static function detect() { 
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 

        // Identify the browser. Check Opera and Safari first in case of spoof. Let Google Chrome be identified as Safari. 
        if (preg_match('/opera/', $userAgent)) { 
            $name = 'opera'; 
        } 
        elseif (preg_match('/webkit/', $userAgent)) { 
            $name = 'safari'; 
        } 
        elseif (preg_match('/msie/', $userAgent)) { 
            $name = 'msie'; 
        } 
        elseif (preg_match('/mozilla/', $userAgent) && !preg_match('/compatible/', $userAgent)) { 
            $name = 'mozilla'; 
        } 
        else { 
            $name = 'unrecognized'; 
        } 

        // What version? 
        if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches)) { 
            $version = $matches[1]; 
        } 
        else { 
            $version = 'unknown'; 
        } 

        // Running on what platform? 
        if (preg_match('/linux/', $userAgent)) { 
            $platform = 'linux'; 
        } 
        elseif (preg_match('/macintosh|mac os x/', $userAgent)) { 
            $platform = 'mac'; 
        } 
        elseif (preg_match('/windows|win32/', $userAgent)) { 
            $platform = 'windows'; 
        } 
        else { 
            $platform = 'unrecognized'; 
        } 

        return array( 
            'name'      => $name, 
            'version'   => $version, 
            'platform'  => $platform, 
            'userAgent' => $userAgent 
        ); 
    } 
}
