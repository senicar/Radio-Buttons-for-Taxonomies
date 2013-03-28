<?php
//
// Category Radio Lists
//

/**
 * Walker to output an unordered list of category radio <input> elements.
 * Mimics Walker_Category_Checklist excerpt for the radio input
 *
 * @see Walker
 * @see wp_category_checklist()
 * @see wp_terms_checklist()
 * @since 2.5.1
 */
class Walker_Category_Radio extends Walker {
    var $tree_type = 'category';
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id'); //TODO: decouple this

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent<ul class='children'>\n";
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    function start_el( &$output, $term, $depth, $args, $id = 0 ) {
        global $post;

        extract($args);

        if ( empty($taxonomy) )
            $taxonomy = 'category';

        $name = 'tax_input['.$taxonomy.']';

        //small tweak so that it works for both hierarchical and non-hierarchical tax

        $value = is_taxonomy_hierarchical( $taxonomy ) ? $term->term_id : $term->slug;

        $class = in_array( $term->term_id, $popular_cats ) ? ' class="popular-category"' : '';

        $output .= sprintf( "\n" . '<li id="%1$s-%2$s" %3$s><label class="selectit"><input id="%4$s" type="radio" name="%5$s" value="%6$s" %7$s %8$s/> %9$s</label>' ,
                $taxonomy, //1
                $value, //2
                $class, //3
                "in-{$taxonomy}-{$term->term_id}", //4
                $name . '[]', //5
                esc_attr( trim( $value ) ), //6
                $checked = checked( in_array( $term->term_id, $selected_cats ), true, false ), //7
                disabled( empty( $args['disabled'] ), false, false ), //8
                esc_html( apply_filters( 'the_category', $term->name ) ) //9
        );


    }

    function end_el( &$output, $term, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}
