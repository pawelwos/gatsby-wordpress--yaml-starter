<?php
// and less important stuff
// Adds client custom colors to WYSIWYG editor and ACF color picker.

function change_acf_color_picker() {

  echo "<script>
  (function($){
    try {
		acf.add_filter('color_picker_args', function( args, field ){
			
			// do something to args
			args.palettes = ['#663399', '#333', '#35051e', '#184235', '#818181', '#c0c0bf']
			
			
			// return
			return args;
					
		});
    }
    catch (e) {}
  })(jQuery)
  </script>";
}

add_action( 'acf/input/admin_head', 'change_acf_color_picker' );

function my_mce4_options($init) {

    $custom_colours = '
        "663399", "Primary",
        "333", "Secondary",
        "35051e", "Color 3",
        "184235", "Color 4",
        "818181", "Color 5",
        "c0c0bf", "Color 6",
        "000000", "Color 7",
        "ffffff", "Color 8",
    ';

    // build colour grid default+custom colors
    $init['textcolor_map'] = '['.$custom_colours.']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;

    return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');