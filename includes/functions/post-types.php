<?php
use sixlabs\wp\cpt;
use sixlabs\wp\Tax;

/**
 * Example CPT
 *
 * Class reference in functions-base.php
 * Icons take font awesome unicode value
 *
 * (new Cpt)
 *     ->register('example', 'Example', 'Examples', $additionalArgs = [])
 *     ->setIcon('f281')
 */

/**
 * Example Taxnonmy
 *
 * Category:
 * (new Tax)
 *   ->register('post', 'example-categories', 'Example', 'Example', true);
 *
 *  Tag:
 *  (new Tax)
 *   ->register('post', 'example-categories', 'Example', 'Example', false);
 */

 /**
  * Returns null if no post type is available.
  */
 $current_post_type = get_current_admin_post_type();

 /**
  * Returns current post object and post template. Returns as empty object with
  *   $post_obj->template = null if not available.
  *
  * Post template: $post_obj->template.
  */
 $post_obj = get_current_admin_post_object();

 (new Cpt)
 	->register('leadership', 'Leadership', 'Leadership', [
 			   // 'has_archive'=> true,
 				'supports' => ['title','editor','page-attributes','thumbnail']])
	->setIcon('f1ae');