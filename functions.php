/**
 * Display a custom taxonomy dropdown in admin
 */
add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'product'; // change to your post type
	$taxonomy  = 'collections'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( 'Select a collection', 'textdomain' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}
/**
 * Filter posts by taxonomy in admin
 */
add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
function tsm_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'product'; // change to your post type
	$taxonomy  = 'collections'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}


/**
 * Filter product by tag in admin
 */
add_filter( 'woocommerce_product_filters', 'wdo_filter_by_custom_taxonomy_dashboard_products' );
 
function wdo_filter_by_custom_taxonomy_dashboard_products( $output ) {
   
  global $wp_query;
 
  $output .= wc_product_dropdown_categories( array(
    'show_option_none' => 'Filter by product tag',
    'taxonomy' => 'product_tag',
    'name' => 'product_tag',
    'selected' => isset( $wp_query->query_vars['product_tag'] ) ? $wp_query->query_vars['product_tag'] : '',
  ) );
   
  return $output;
}
