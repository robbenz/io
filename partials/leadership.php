<?php
	$executives; //holds executive staff
	$presidents; //holds president staff

	// builds the staff thumbnail and info/link
	// $p: staff post object
	function build_leadership_post($p) {
		$image = get_the_post_thumbnail_url($p->ID);
		$title = get_field('title', $p->ID);
		$link = get_the_permalink($p->ID);

		$html = '<li class="leadership-single">';
			$html .= '<a href="'.$link.'" class="link-strip-styles">';
				if(!empty($image)){
					$html .= '<div class="headshot" style="background-image:url('.$image.')"></div>';
				}
				$html .= '<div class="name">'.$p->post_title.'</div>';
				$html .= '<div class="title">'.$title.'</div>';
			$html .= '</a>';
		$html .= '</li>';
		return $html;
	}

	if(get_the_title() == 'Leadership'){
		$executives = get_posts([
			'post_type' => 'leadership',
			'numberposts' => -1,
			'meta_key' => 'leadership_type',
			'meta_value' => 'executive',
			'orderby' => 'menu_order',
			'order'=> 'ASC',
		]);

		$presidents = get_posts([
			'post_type' => 'leadership',
			'numberposts' => -1,
			'meta_key' => 'leadership_type',
			'meta_value' => 'division',
			'orderby' => 'menu_order',			
			'order'=> 'ASC',			
		]);

		if(!empty($executives)){
			echo '<div class="wrapper">';
				echo '<section class="leadership-section">';
					echo '<h2>Executive Team</h2><hr>';
					echo '<ul class="team-list">';
						foreach($executives as $e){
							echo build_leadership_post($e);
						}
					echo '</ul>';
				echo '</section>';
			echo '</div>';
		}

		if(!empty($presidents)){
			echo '<div class="wrapper">';
				echo '<section class="leadership-section">';
					echo '<h2>Division Presidents</h2><hr>';
					echo '<ul class="team-list">';
						foreach($presidents as $p){
							echo build_leadership_post($p);
						}
					echo '</ul>';
				echo '</section>';
			echo '</div>';
		}
	}
?>