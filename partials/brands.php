<?php
	$brands = get_field('brands');

	if(!empty($brands)){
		echo '<section class="brands-section">';
			foreach($brands as $b){
				echo '<div class="brand">';
					echo '<div class="wrapper">';
						// LOGO
						echo '<div class="logo">';
							if(!empty($b['link'])){
								echo '<a href="'.$b['link'].'">';
							}
							echo '<img src="'.$b['logo']['url'].'">';
							if(!empty($b['link'])){
								echo '</a>';
							}						
						echo '</div>';

						// CONTENT
						echo '<div class="content-editor">'.$b['content'].'</div>';
					echo '</div>';
				echo '</div>';
			}
		echo '</section>';
	}
?>