<?php /*
YARPP's built-in "template"

This "template" is used when you choose not to use a template.

If you want to create a new template, look at templates/template-example.php as an example.
*/

$options = array(
	'before_title'=>"${domainprefix}before_title",
	'after_title'=>"${domainprefix}after_title",
	'show_excerpt'=>"${domainprefix}show_excerpt",
	'excerpt_length'=>"${domainprefix}excerpt_length",
	'before_post'=>"${domainprefix}before_post",
	'after_post'=>"${domainprefix}after_post",
	'before_related'=>"${domainprefix}before_related",
	'after_related'=>"${domainprefix}after_related",
	'no_results'=>"${domainprefix}no_results");
$optvals = array();
foreach (array_keys($options) as $option) {
	$optvals[$option] = yarpp_get_option($options[$option]);
}
extract($optvals);

if (have_posts()) {
	while (have_posts()) {
		the_post();

		$output .= "$before_title<a href='" . get_permalink() . "' rel='bookmark' title='" . esc_attr(get_the_title() ? get_the_title() : get_the_ID()) . "'>".get_the_title()."";
		if (current_user_can('manage_options') && $domain != 'rss')
			$output .= ' <abbr title="'.sprintf(__('%f is the YARPP match score between the current entry and this related entry. You are seeing this value because you are logged in to WordPress as an administrator. It is not shown to regular visitors.','yarpp'),round(get_the_score(),3)).'">('.round(get_the_score(),3).')</abbr>';
		$output .= '</a>';
		if ($show_excerpt) {
			$excerpt = strip_tags( (string) get_the_excerpt() );
			preg_replace( '/([,;.-]+)\s*/','\1 ', $excerpt );
			$excerpt = implode( ' ', array_slice( preg_split('/\s+/',$excerpt), 0, $excerpt_length ) ).'...';
			$output .= $before_post . $excerpt . $after_post;
		}
		$output .=  $after_title."\n";

	}
	$output = $before_related . $output . $after_related;
} else {
	$output = $no_results;
}
