<?php
/*
Plugin Name: Ideal Interactive Maps
Plugin URI: http://www.globalnetforce.com
Description: Interactive and Informative map
Version: 1.2.2
*/
define("PLUGINURL", dirname(__FILE__) );
require_once( dirname(__FILE__) ."/metaboxes/meta_box.php");

class ideal_interactive_map{
	var $default_added_map_color = "#5c95c4";
	var $default_disabled_map_color = "#81C1DF";
	var $default_rollover_map_color = "#15a892";
	var $default_selected_map_color = "#15a892";
	var $disabled_flag = false;
	
	function __construct(){
	
		//vars 
        $this->options = get_option( 'map_settings' );        
        $this->default_added_map_color = (isset($this->options['default_added_map_color'])) ? $this->options['default_added_map_color'] : $this->default_added_map_color;
        $this->default_disabled_map_color = (isset($this->options['default_disabled_map_color'])) ? $this->options['default_disabled_map_color'] : $this->default_disabled_map_color;
        $this->default_rollover_map_color = (isset($this->options['default_rollover_map_color'])) ? $this->options['default_rollover_map_color'] : $this->default_rollover_map_color;
        $this->default_selected_map_color = (isset($this->options['default_selected_map_color'])) ? $this->options['default_selected_map_color'] : $this->default_selected_map_color;
        
        $this->disabled_flag = (isset($this->options['disabled_map'])) ? $this->options['disabled_map'] : $this->disabled_flag;
        
        //Actions
		add_action("init", array(&$this, "register_postType"));
		add_shortcode("iwg_maps", array($this, "shortcode"));
		add_action("wp_ajax_mapdata", array($this, "json_mapdata"), 20);
		add_action("wp_ajax_nopriv_mapdata", array($this, "json_mapdata"), 20);
		add_action("wp_ajax_mapsubpage", array($this, "ajax_mapsubpage"), 20);
		add_action("wp_ajax_nopriv_mapsubpage", array($this, "ajax_mapsubpage"), 20);
		add_action( 'wp_enqueue_scripts', array($this, "header") );	
		
		add_action( 'admin_menu', array($this, 'map_add_admin_menu') );
		add_action( 'admin_init', array($this, 'map_settings_init') );
	}
	function header(){
		global $post;

			if( is_a( $post, 'WP_Post' ) && !has_shortcode( $post->post_content, 'iwg_maps') )
			return false;

			$version = "1";
			wp_enqueue_script( 'jquery-mousewheel', plugins_url( 'src/jquery.mousewheel.js' , __FILE__ ) , array( 'jquery' ), '20140319', true );
			wp_enqueue_script( 'jquery-scrollbar', plugins_url( 'src/perfect-scrollbar.js' , __FILE__ ), array( ), '20140319', true );
			
			wp_enqueue_script( 'ammap', plugins_url( 'ammap/ammap.js' , __FILE__ ), array("jquery"), '20140319', true );
			wp_enqueue_script( 'ammap-world', plugins_url( 'ammap/maps/js/worldLow.js' , __FILE__ ), array("jquery"), '20140319', true);
			wp_enqueue_script( 'jquery-ui-core');
			wp_enqueue_script( 'ammap-light', plugins_url( 'ammap/themes/light.js' , __FILE__ ), array("jquery"), '20140319', true);
			wp_enqueue_script('jquery-ui-tabs');
			
			wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css', false, '20140319', false);
			wp_enqueue_style( 'scrollbar', plugins_url( 'src/perfect-scrollbar.css' , __FILE__ ));
			wp_enqueue_style( 'ammap-style', plugins_url( 'src/style.css' , __FILE__ ));
			if( !$this->disabled_flag )
			wp_enqueue_style( 'phoca-flags', plugins_url( 'src/phoca-flags.css' , __FILE__ ), false, '20140319', false);
			
	}
	
	function shortcode( $atts, $content = null){
		
		
		add_action("wp_footer", array($this, "footer"), 20);
		
		$close = plugins_url( 'img/close.png' , __FILE__ );
		$attribute = shortcode_atts( array(
								'zoom_level' => "",
								'zoom_latitude' => "",
								'zoom_longitude' => "",
							), $atts );
		
		$zoomLevel = $attribute['zoom_level'];
		$zoomLongitude = $attribute['zoom_longitude'];
		$zoomLatitude = $attribute['zoom_latitude'];			
		$return = <<<xxx
    	<div id="map-container">
        <div id="mapdiv" data-lat="{$zoomLatitude}" data-long="{$zoomLongitude}" data-zoom="{$zoomLevel}" style="width: 100%; background-color:#EEEEEE; height: 500px;"></div>
        <div id="desc_overlay" class="animated">
	        <h2 class="pop_desc_title"></h2><span id="ico_close"><img src="$close" align="middle" /></span>
	        <div id="overlay-content"></p>
	        </div>
        </div>
    	</div>
    	<div id="mapreadmore">
    	</div> 
xxx;
    	
		return $return;	
	}
	
	function json_mapdata(){
	
       $targetSVG = "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";
        $countries = $this->mappoints();
        
        $args = array("post_type" => "map_maps", "posts_per_page"=> -1, "post_status" => "publish" );
        $the_query = new WP_Query( $args );

		$images_array = array();
		$areas = array();
			
		// The Loop
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) { $the_query->the_post();
				$id = get_the_ID();
				$country_code = get_post_meta($id, "map_country", true);
				$map_color = get_post_meta($id, "map_color", true);
				if($country_code && isset($countries[$country_code])){
						
					$areas[] = array(
										"id"=>$country_code,
										"groupId"=>$country_code,
										"color"=> (!$map_color || ( $map_color && $map_color == '#')) ? $this->default_added_map_color : $map_color
									);
									
					$images_array[] = array(
								            "svgPath" => $targetSVG,
								            "zoomLevel"=> 2,
								            "scale"=> .7,
								            "color"=> "#234589",
								            "title"=> $countries[$country_code][0],
								            "latitude"=> $countries[$country_code][1],
								            "longitude"=> $countries[$country_code][2],
								            "groupId"=> $country_code,
								            "custom"=> array(
								            	"desc"=> 
								            	((!$this->disabled_flag) ?
								            	'<div class="phoca-flagbox"><span class="phoca-flag '. strtolower($country_code) .'"></span></div>' : '') . get_the_content()
								            )
										);
				}
			}
		} 

        	
	echo json_encode(
					array(
						"map"=>"worldLow",
						"zoomOnDoubleClick"=>false,
						"areas"=> $areas,
						"images" => $images_array
				)
			);
		exit;
	}
	function footer(){
		
	 $image = plugins_url( 'ammap/images/' , __FILE__ );
	 
	 $jsonparse = admin_url('admin-ajax.php?action=mapdata');
	 $mapsubpageurl = admin_url('admin-ajax.php?action=mapsubpage');
	 
	 //Colors
	 $unlistedColor = $this->default_disabled_map_color;
	 $default_rollover_map_color = $this->default_rollover_map_color;
	echo <<<xxxx
		<script type="text/javascript">
   	     	var map;
			var mapAttributes = jQuery("#map-container #mapdiv");
			var iwgdatalat = mapAttributes.attr("data-lat");
			var iwgdatalong = mapAttributes.attr("data-long");
			var iwgdatazoom = mapAttributes.attr("data-zoom");

			var mapDataProvider = {
			        map: "worldLow",
			        zoomOnDoubleClick: false
			    };
			

			AmCharts.ready(function() {
				map = new AmCharts.AmMap();
				map.type = "map";
				map.theme = "light";
				map.pathToImages = "$image";
				map.centerMap = true;
				map.getAreasFromMap = true;
				map.mouseWheelZoomEnabled = true;
				map.dataProvider = {};
				
				map.imagesSettings = {
									    rollOverScale: 2,
									    selectedScale: 2,
								        zoomX: 2,
								        color: "#15a892"
									};
				map.zoomControl = {
				        buttonFillColor: "#70A7D1",
				        gridHeight: 50 };
				map.selectedObject = { color: "#15a892"},
				
			    map.areasSettings = {
			        rollOverColor: "{$default_rollover_map_color}",
			        color: "#5296cb",
			        unlistedAreasColor: "{$unlistedColor}",
			        selectedColor: "#15a892",
					rollOverAlpha: 0.8
			    };
				
				
				jQuery.getJSON( "{$jsonparse}", function(xconsole){
					map.dataProvider = xconsole;
					
					if(iwgdatalat != ""){
						map.dataProvider.zoomLatitude = iwgdatalat;
					}
					
					if(iwgdatalong != ""){
						map.dataProvider.zoomLongitude = iwgdatalong;
					}
					
					if( iwgdatazoom != ""){
						map.dataProvider.zoomLevel = iwgdatazoom;
					}
									    
				map.write("mapdiv");
				});
				
			    map.addListener("clickMapObject", function (event) {
			    	console.log(event);
			    	onregionClick(event);
			    });
			    
			    map.addListener("homeButtonClicked", function (event) {
				     jQuery("#ico_close").click();
			    });
			    
			});
			
			function zoomOut () {
			    map.zoomOut(); 
			}
			
		    function centerMap () {
			  //  map.zoomToLongLat(map.zoomLevel(), map.initialZoomLongitude, map.initialZoomLatitude, true);
			}
			
		    jQuery(document).ready(function ($) {
		    	/** Scrollbar*/
		        jQuery('#overlay-content').perfectScrollbar({suppressScrollX: true});
		        jQuery("#ico_close").on("click",function(){
	       		     
			        var map_overlay = jQuery("#desc_overlay");
						map_overlay.removeClass("fadeInDown");
						map_overlay.addClass("fadeOutDown");
						
					  zoomOut();
					  map_overlay.delay( 1200 ).css({"display": 'none'});
					  jQuery("#mapreadmore").html('');
	       		});
		      });

			  
		     function onregionClick(country){
				 var map_overlay = jQuery("#desc_overlay");
				 var obj = country.mapObject;
				jQuery("#mapreadmore").html('');
				jQuery(".pop_desc_title").html(obj.title);
				jQuery("#overlay-content").html(obj.custom.desc);
				
						map_overlay.removeClass("fadeOutDown");
						map_overlay.delay( 800 ).css({"display": 'block'});
						map_overlay.addClass("fadeInDown");
						map_overlay.drags();
					
				    jQuery(".a_readmore").on( "click", function() {
				    	var pid = jQuery(this).attr("data-pid");
				    	jQuery.get(
								    '{$mapsubpageurl}&pid='+pid, 
									    function(response){
									    	jQuery("#mapreadmore").html(response);
									    	jQuery( "#maptabs" ).tabs();
									    }
								);

						jQuery('html, body').animate({
					        scrollTop: jQuery( "#mapreadmore" ).offset().top - 30
					    }, 1000);
					    return false;
					});
					
				}
				
			(function($) {
				
				//Draggable
			    jQuery.fn.drags = function(opt) {
			
			        opt = $.extend({handle:"",cursor:"move"}, opt);
			
			        if(opt.handle === "") {
			            var el = this;
			        } else {
			            var el = this.find(opt.handle);
			        }
			
			        return el.css('cursor', opt.cursor).on("mousedown", function(e) {
			           
			            if(opt.handle === "") {
			                var drag = jQuery(this).addClass('draggable');
			            } else {
			                var drag = jQuery(this).addClass('active-handle').parent().addClass('draggable');
			            }
			            
			            var z_idx = drag.css('z-index'),
			                drg_h = drag.outerHeight(),
			                drg_w = drag.outerWidth(),
			                pos_y = drag.offset().top + drg_h - e.pageY,
			                pos_x = drag.offset().left + drg_w - e.pageX;
			            drag.css('z-index', 1000).parents().on("mousemove", function(e) {
			                jQuery('.draggable').offset({
			                    top:e.pageY + pos_y - drg_h,
			                    left:e.pageX + pos_x - drg_w
			                }).on("mouseup", function() {
			                    jQuery(this).removeClass('draggable').css('z-index', z_idx);
			                });
			            });
			            e.preventDefault(); // disable selection
			        }).on("mouseup", function() {
			            if(opt.handle === "") {
			                jQuery(this).removeClass('draggable');
			            } else {
			                jQuery(this).removeClass('active-handle').parent().removeClass('draggable');
			            }
			        });
			
			    }
			})(jQuery);
        </script>
xxxx;
	}

	function ajax_mapsubpage(){
		
        $id = wp_filter_kses($_REQUEST['pid']);
		
		$field = get_field("subpages", $id);
		if($field){
			
			foreach( $field as $key => $row )
			{
				$title .= '<li><a href="#tabs-'. $key .'">'. $row['title'] .'</a></li>';
				$content .= '<div id="tabs-'. $key .'">'. $row['content'] .'</div>';
			}
		
		echo '<div id="maptabs">
				  <ul class="nav-tabs">'. $title .'</ul>
				  '. $content .'
				</div>';
		}
		exit;	
	}
	
	function register_postType(){
			$labels = array(
			'name'                => 'Manage Maps',
			'singular_name'       => 'Manage Map',
			'menu_name'           => 'Manage Maps',
			'parent_item_colon'   => 'Parent Item:',
			'all_items'           => 'All Items',
			'view_item'           => 'View Item',
			'add_new_item'        => 'Add New Item',
			'add_new'             => 'Add New',
			'edit_item'           => 'Edit Item',
			'update_item'         => 'Update Item',
			'search_items'        => 'Search Item',
			'not_found'           => 'Not found',
			'not_found_in_trash'  => 'Not found in Trash',
		);
		$args = array(
			'label'               => 'interactive_map',
			'description'         => 'Interactive Map',
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'map_maps', $args );
		
	
		$prefix = 'map_';
		
		$countryoption = array();
		foreach($this->mappoints() as $index => $cname){
			$countryoption[$index] = array("label" => $cname[0], "value" => $index);
		}
			asort($countryoption);
		$fields = array(
				array(
					'label'	=> 'Country',
					'desc'	=> 'Select Country Name.',
					'id'	=> $prefix.'country',
					'type'	=> 'select',
					'options' => $countryoption
				),
				array(
					'label'	=> 'Color',
					'desc'	=> 'Select country color. <i>Set or change the default color <a href="' . admin_url("options-general.php?page=ideal_interactive_map") . '" target="_blank">Here</a></i>',
					'id'	=> $prefix.'color',
					'type'	=> 'color',
					'meta' 	=> $this->default_added_map_color
				)
			);
		new custom_add_meta_box( 'map_box', 'Map Properties', $fields, array("map_maps"), true );
        
		add_action('admin_footer-edit.php', array($this, "admin_footer") );
	}
	
    function admin_footer(){
     	$queried_post_type = get_query_var('post_type');
    	if( $queried_post_type != "map_maps" ) return ;
    	$ideal_interactive_map_url = admin_url("options-general.php?page=ideal_interactive_map");
        $html = <<<xxxx
        <script type="html/template" class="iwg_help">
        <p style="color: #7105ad; clear: both; cursor:pointer">[ <span class="maphelp">click to show map shortcode and attributes</a> ] [ <a style="color: #7105ad;text-decoration:none" href="{$ideal_interactive_map_url}">Set Default Ideal Interactive Map Settings</a> ]</p>
        <div class="iwg_help_cont" style="display:none;padding: 10px; background: #FFF">
        <p><strong>Shortcode:</strong></p>
            <code>[iwg_maps]</code>
            <p><em><strong>Advance Attributes</strong></em><br />
            zoom_level = magnifies the view to a specific portion in the map (increasing number = magnifying view)            
            <p><em>Epicenter of the map depends on the values that you will declare below:</em></p>
zoom_longitude<br />zoom_latitude
            <p><em>HOW:</em> To accurately get the location coordinates use this <a href="http://www.mapcoordinates.net/en" target="_blank">http://www.mapcoordinates.net/en</a></p>
            <code>[iwg_maps zoom_level="3" zoom_longitude= "121" zoom_latitude="12"]</code>
            </p>
        </div>
        </script>
        <script type="text/javascript">
        	jQuery(".post-type-map_maps #wpbody-content .wrap > h2").after( "<div class=\"iwg_help_wrapper\">" +jQuery(".iwg_help").html() + "</div>");
			jQuery(document).ready(function(e) {
                jQuery(".iwg_help_wrapper").on("click", ".maphelp", function(){
					jQuery(".iwg_help_wrapper .iwg_help_cont").toggle();
				});
            });
        </script>
xxxx;
		echo $html;
    }
	function mappoints($code = ""){
			// format country, latitude, longitude
			$countries = array(
					"AD"=> array("Andorra", 42.546245, 1.601554),
                    "AE"=> array("United Arab Emirates", 23.424076, 53.847818),
                    "AF"=> array("Afghanistan", 33.93911, 67.709953),
                    "AG"=> array("Antigua and Barbuda", 17.060816, -61.796428),
                    "AI"=> array("Anguilla", 18.220554, -63.068615),
                    "AL"=> array("Albania", 41.153332, 20.168331),
                    "AM"=> array("Armenia", 40.069099, 45.038189),
                    "AN"=> array("Netherlands Antilles", 12.226079, -69.060087),
                    "AO"=> array("Angola", -11.202692, 17.873887),
                    "AQ"=> array("Antarctica", -75.250973, -0.071389),
                    "AR"=> array("Argentina", -38.416097, -63.616672),
                    "AS"=> array("American Samoa", -14.270972, -170.132217),
                    "AT"=> array("Austria", 47.516231, 14.550072),
                    "AU"=> array("Australia", -25.274398, 133.775136),
                    "AW"=> array("Aruba", 12.52111, -69.968338),
                    "AZ"=> array("Azerbaijan", 40.143105, 47.576927),
                    "BA"=> array("Bosnia and Herzegovina", 43.915886, 17.679076),
                    "BB"=> array("Barbados", 13.193887, -59.543198),
                    "BD"=> array("Bangladesh", 23.684994, 90.356331),
                    "BE"=> array("Belgium", 50.503887, 4.469936),
                    "BF"=> array("Burkina Faso", 12.238333, -1.561593),
                    "BG"=> array("Bulgaria", 42.733883, 25.48583),
                    "BH"=> array("Bahrain", 25.930414, 50.637772),
                    "BI"=> array("Burundi", -3.373056, 29.918886),
                    "BJ"=> array("Benin", 9.30769, 2.315834),
                    "BM"=> array("Bermuda", 32.321384, -64.75737),
                    "BN"=> array("Brunei", 4.535277, 114.727669),
                    "BO"=> array("Bolivia", -16.290154, -63.588653),
                    "BR"=> array("Brazil", -14.235004, -51.92528),
                    "BS"=> array("Bahamas", 25.03428, -77.39628),
                    "BT"=> array("Bhutan", 27.514162, 90.433601),
                    "BV"=> array("Bouvet Island", -54.423199, 3.413194),
                    "BW"=> array("Botswana", -22.328474, 24.684866),
                    "BY"=> array("Belarus", 53.709807, 27.953389),
                    "BZ"=> array("Belize", 17.189877, -88.49765),
                    "CA"=> array("Canada", 56.130366, -106.346771),
                    "CC"=> array("Cocos [Keeling] Islands", -12.164165, 96.870956),
                    "CD"=> array("Congo [DRC]", -4.038333, 21.758664),
                    "CF"=> array("Central African Republic", 6.611111, 20.939444),
                    "CG"=> array("Congo [Republic]", -0.228021, 15.827659),
                    "CH"=> array("Switzerland", 46.818188, 8.227512),
                    "CI"=> array("Côte d'Ivoire", 7.539989, -5.54708),
                    "CK"=> array("Cook Islands", -21.236736, -159.777671),
                    "CL"=> array("Chile", -35.675147, -71.542969),
                    "CM"=> array("Cameroon", 7.369722, 12.354722),
                    "CN"=> array("China", 35.86166, 104.195397),
                    "CO"=> array("Colombia", 4.570868, -74.297333),
                    "CR"=> array("Costa Rica", 9.748917, -83.753428),
                    "CU"=> array("Cuba", 21.521757, -77.781167),
                    "CV"=> array("Cape Verde", 16.002082, -24.013197),
                    "CX"=> array("Christmas Island", -10.447525, 105.690449),
                    "CY"=> array("Cyprus", 35.126413, 33.429859),
                    "CZ"=> array("Czech Republic", 49.817492, 15.472962),
                    "DE"=> array("Germany", 51.165691, 10.451526),
                    "DJ"=> array("Djibouti", 11.825138, 42.590275),
                    "DK"=> array("Denmark", 56.26392, 9.501785),
                    "DM"=> array("Dominica", 15.414999, -61.370976),
                    "DO"=> array("Dominican Republic", 18.735693, -70.162651),
                    "DZ"=> array("Algeria", 28.033886, 1.659626),
                    "EC"=> array("Ecuador", -1.831239, -78.183406),
                    "EE"=> array("Estonia", 58.595272, 25.013607),
                    "EG"=> array("Egypt", 26.820553, 30.802498),
                    "EH"=> array("Western Sahara", 24.215527, -12.885834),
                    "ER"=> array("Eritrea", 15.179384, 39.782334),
                    "ES"=> array("Spain", 40.463667, -3.74922),
                    "ET"=> array("Ethiopia", 9.145, 40.489673),
                    "FI"=> array("Finland", 61.92411, 25.748151),
                    "FJ"=> array("Fiji", -16.578193, 179.414413),
                    "FK"=> array("Falkland Islands [Islas Malvinas]", -51.796253, -59.523613),
                    "FM"=> array("Micronesia", 7.425554, 150.550812),
                    "FO"=> array("Faroe Islands", 61.892635, -6.911806),
                    "FR"=> array("France", 46.227638, 2.213749),
                    "GA"=> array("Gabon", -0.803689, 11.609444),
                    "GB"=> array("United Kingdom", 55.378051, -3.435973),
                    "GD"=> array("Grenada", 12.262776, -61.604171),
                    "GE"=> array("Georgia", 42.315407, 43.356892),
                    "GF"=> array("French Guiana", 3.933889, -53.125782),
                    "GG"=> array("Guernsey", 49.465691, -2.585278),
                    "GH"=> array("Ghana", 7.946527, -1.023194),
                    "GI"=> array("Gibraltar", 36.137741, -5.345374),
                    "GL"=> array("Greenland", 71.706936, -42.604303),
                    "GM"=> array("Gambia", 13.443182, -15.310139),
                    "GN"=> array("Guinea", 9.945587, -9.696645),
                    "GP"=> array("Guadeloupe", 16.995971, -62.067641),
                    "GQ"=> array("Equatorial Guinea", 1.650801, 10.267895),
                    "GR"=> array("Greece", 39.074208, 21.824312),
                    "GS"=> array("South Georgia and the South Sandwich Islands", -54.429579, -36.587909),
                    "GT"=> array("Guatemala", 15.783471, -90.230759),
                    "GU"=> array("Guam", 13.444304, 144.793731),
                    "GW"=> array("Guinea-Bissau", 11.803749, -15.180413),
                    "GY"=> array("Guyana", 4.860416, -58.93018),
                    "GZ"=> array("Gaza Strip", 31.354676, 34.308825),
                    "HK"=> array("Hong Kong", 22.396428, 114.109497),
                    "HM"=> array("Heard Island and McDonald Islands", -53.08181, 73.504158),
                    "HN"=> array("Honduras", 15.199999, -86.241905),
                    "HR"=> array("Croatia", 45.1, 15.2),
                    "HT"=> array("Haiti", 18.971187, -72.285215),
                    "HU"=> array("Hungary", 47.162494, 19.503304),
                    "ID"=> array("Indonesia", -0.789275, 113.921327),
                    "IE"=> array("Ireland", 53.41291, -8.24389),
                    "IL"=> array("Israel", 31.046051, 34.851612),
                    "IM"=> array("Isle of Man", 54.236107, -4.548056),
                    "IN"=> array("India", 20.593684, 78.96288),
                    "IO"=> array("British Indian Ocean Territory", -6.343194, 71.876519),
                    "IQ"=> array("Iraq", 33.223191, 43.679291),
                    "IR"=> array("Iran", 32.427908, 53.688046),
                    "IS"=> array("Iceland", 64.963051, -19.020835),
                    "IT"=> array("Italy", 41.87194, 12.56738),
                    "JE"=> array("Jersey", 49.214439, -2.13125),
                    "JM"=> array("Jamaica", 18.109581, -77.297508),
                    "JO"=> array("Jordan", 30.585164, 36.238414),
                    "JP"=> array("Japan", 36.204824, 138.252924),
                    "KE"=> array("Kenya", -0.023559, 37.906193),
                    "KG"=> array("Kyrgyzstan", 41.20438, 74.766098),
                    "KH"=> array("Cambodia", 12.565679, 104.990963),
                    "KI"=> array("Kiribati", -3.370417, -168.734039),
                    "KM"=> array("Comoros", -11.875001, 43.872219),
                    "KN"=> array("Saint Kitts and Nevis", 17.357822, -62.782998),
                    "KP"=> array("North Korea", 40.339852, 127.510093),
                    "KR"=> array("South Korea", 35.907757, 127.766922),
                    "KW"=> array("Kuwait", 29.31166, 47.481766),
                    "KY"=> array("Cayman Islands", 19.513469, -80.566956),
                    "KZ"=> array("Kazakhstan", 48.019573, 66.923684),
                    "LA"=> array("Laos", 19.85627, 102.495496),
                    "LB"=> array("Lebanon", 33.854721, 35.862285),
                    "LC"=> array("Saint Lucia", 13.909444, -60.978893),
                    "LI"=> array("Liechtenstein", 47.166, 9.555373),
                    "LK"=> array("Sri Lanka", 7.873054, 80.771797),
                    "LR"=> array("Liberia", 6.428055, -9.429499),
                    "LS"=> array("Lesotho", -29.609988, 28.233608),
                    "LT"=> array("Lithuania", 55.169438, 23.881275),
                    "LU"=> array("Luxembourg", 49.815273, 6.129583),
                    "LV"=> array("Latvia", 56.879635, 24.603189),
                    "LY"=> array("Libya", 26.3351, 17.228331),
                    "MA"=> array("Morocco", 31.791702, -7.09262),
                    "MC"=> array("Monaco", 43.750298, 7.412841),
                    "MD"=> array("Moldova", 47.411631, 28.369885),
                    "ME"=> array("Montenegro", 42.708678, 19.37439),
                    "MG"=> array("Madagascar", -18.766947, 46.869107),
                    "MH"=> array("Marshall Islands", 7.131474, 171.184478),
                    "MK"=> array("Macedonia [FYROM]", 41.608635, 21.745275),
                    "ML"=> array("Mali", 17.570692, -3.996166),
                    "MM"=> array("Myanmar [Burma]", 21.913965, 95.956223),
                    "MN"=> array("Mongolia", 46.862496, 103.846656),
                    "MO"=> array("Macau", 22.198745, 113.543873),
                    "MP"=> array("Northern Mariana Islands", 17.33083, 145.38469),
                    "MQ"=> array("Martinique", 14.641528, -61.024174),
                    "MR"=> array("Mauritania", 21.00789, -10.940835),
                    "MS"=> array("Montserrat", 16.742498, -62.187366),
                    "MT"=> array("Malta", 35.937496, 14.375416),
                    "MU"=> array("Mauritius", -20.348404, 57.552152),
                    "MV"=> array("Maldives", 3.202778, 73.22068),
                    "MW"=> array("Malawi", -13.254308, 34.301525),
                    "MX"=> array("Mexico", 23.634501, -102.552784),
                    "MY"=> array("Malaysia", 4.210484, 101.975766),
                    "MZ"=> array("Mozambique", -18.665695, 35.529562),
                    "NA"=> array("Namibia", -22.95764, 18.49041),
                    "NC"=> array("New Caledonia", -20.904305, 165.618042),
                    "NE"=> array("Niger", 17.607789, 8.081666),
                    "NF"=> array("Norfolk Island", -29.040835, 167.954712),
                    "NG"=> array("Nigeria", 9.081999, 8.675277),
                    "NI"=> array("Nicaragua", 12.865416, -85.207229),
                    "NL"=> array("Netherlands", 52.132633, 5.291266),
                    "NO"=> array("Norway", 60.472024, 8.468946),
                    "NP"=> array("Nepal", 28.394857, 84.124008),
                    "NR"=> array("Nauru", -0.522778, 166.931503),
                    "NU"=> array("Niue", -19.054445, -169.867233),
                    "NZ"=> array("New Zealand", -40.900557, 174.885971),
                    "OM"=> array("Oman", 21.512583, 55.923255),
                    "PA"=> array("Panama", 8.537981, -80.782127),
                    "PE"=> array("Peru", -9.189967, -75.015152),
                    "PF"=> array("French Polynesia", -17.679742, -149.406843),
                    "PG"=> array("Papua New Guinea", -6.314993, 143.95555),
                    "PH"=> array("Philippines", 12.879721, 121.774017),
                    "PK"=> array("Pakistan", 30.375321, 69.345116),
                    "PL"=> array("Poland", 51.919438, 19.145136),
                    "PM"=> array("Saint Pierre and Miquelon", 46.941936, -56.27111),
                    "PN"=> array("Pitcairn Islands", -24.703615, -127.439308),
                    "PR"=> array("Puerto Rico", 18.220833, -66.590149),
                    "PS"=> array("Palestinian Territories", 31.952162, 35.233154),
                    "PT"=> array("Portugal", 39.399872, -8.224454),
                    "PW"=> array("Palau", 7.51498, 134.58252),
                    "PY"=> array("Paraguay", -23.442503, -58.443832),
                    "QA"=> array("Qatar", 25.354826, 51.183884),
                    "RE"=> array("Réunion", -21.115141, 55.536384),
                    "RO"=> array("Romania", 45.943161, 24.96676),
                    "RS"=> array("Serbia", 44.016521, 21.005859),
                    "RU"=> array("Russia", 61.52401, 105.318756),
                    "RW"=> array("Rwanda", -1.940278, 29.873888),
                    "SA"=> array("Saudi Arabia", 23.885942, 45.079162),
                    "SB"=> array("Solomon Islands", -9.64571, 160.156194),
                    "SC"=> array("Seychelles", -4.679574, 55.491977),
                    "SD"=> array("Sudan", 12.862807, 30.217636),
                    "SE"=> array("Sweden", 60.128161, 18.643501),
                    "SG"=> array("Singapore", 1.352083, 103.819836),
                    "SH"=> array("Saint Helena", -24.143474, -10.030696),
                    "SI"=> array("Slovenia", 46.151241, 14.995463),
                    "SJ"=> array("Svalbard and Jan Mayen", 77.553604, 23.670272),
                    "SK"=> array("Slovakia", 48.669026, 19.699024),
                    "SL"=> array("Sierra Leone", 8.460555, -11.779889),
                    "SM"=> array("San Marino", 43.94236, 12.457777),
                    "SN"=> array("Senegal", 14.497401, -14.452362),
                    "SO"=> array("Somalia", 5.152149, 46.199616),
                    "SR"=> array("Suriname", 3.919305, -56.027783),
                    "ST"=> array("São Tomé and Príncipe", 0.18636, 6.613081),
                    "SV"=> array("El Salvador", 13.794185, -88.89653),
                    "SY"=> array("Syria", 34.802075, 38.996815),
                    "SZ"=> array("Swaziland", -26.522503, 31.465866),
                    "TC"=> array("Turks and Caicos Islands", 21.694025, -71.797928),
                    "TD"=> array("Chad", 15.454166, 18.732207),
                    "TF"=> array("French Southern Territories", -49.280366, 69.348557),
                    "TG"=> array("Togo", 8.619543, 0.824782),
                    "TH"=> array("Thailand", 15.870032, 100.992541),
                    "TJ"=> array("Tajikistan", 38.861034, 71.276093),
                    "TK"=> array("Tokelau", -8.967363, -171.855881),
                    "TL"=> array("Timor-Leste", -8.874217, 125.727539),
                    "TM"=> array("Turkmenistan", 38.969719, 59.556278),
                    "TN"=> array("Tunisia", 33.886917, 9.537499),
                    "TO"=> array("Tonga", -21.178986, -175.198242),
                    "TR"=> array("Turkey", 38.963745, 35.243322),
                    "TT"=> array("Trinidad and Tobago", 10.691803, -61.222503),
                    "TV"=> array("Tuvalu", -7.109535, 177.64933),
                    "TW"=> array("Taiwan", 23.69781, 120.960515),
                    "TZ"=> array("Tanzania", -6.369028, 34.888822),
                    "UA"=> array("Ukraine", 48.379433, 31.16558),
                    "UG"=> array("Uganda", 1.373333, 32.290275),
                    "UM"=> array("U.S. Minor Outlying Islands", 28.200001,-177.333328),
                    "US"=> array("United States", 37.09024, -95.712891),
                    "UY"=> array("Uruguay", -32.522779, -55.765835),
                    "UZ"=> array("Uzbekistan", 41.377491, 64.585262),
                    "VA"=> array("Vatican City", 41.902916, 12.453389),
                    "VC"=> array("Saint Vincent and the Grenadines", 12.984305, -61.287228),
                    "VE"=> array("Venezuela", 6.42375, -66.58973),
                    "VG"=> array("British Virgin Islands", 18.420695, -64.639968),
                    "VI"=> array("U.S. Virgin Islands", 18.335765, -64.896335),
                    "VN"=> array("Vietnam", 14.058324, 108.277199),
                    "VU"=> array("Vanuatu", -15.376706, 166.959158),
                    "WF"=> array("Wallis and Futuna", -13.768752, -177.156097),
                    "WS"=> array("Samoa", -13.759029, -172.104629),
                    "XK"=> array("Kosovo", 42.602636, 20.902977),
                    "YE"=> array("Yemen", 15.552727, 48.516388),
                    "YT"=> array("Mayotte", -12.8275, 45.166244),
                    "ZA"=> array("South Africa", -30.559482, 22.937506),
                    "ZM"=> array("Zambia", -13.133897, 27.849332),
                    "ZW"=> array("Zimbabwe", -19.015438, 29.154857),
				 );

		return (!empty($code) && isset($countries[$code])) ? $countries[$code] : $countries;

	}
	
	
	function map_add_admin_menu(  ) { 
	
		add_options_page( 'Ideal Interactive Map Settings', 'Ideal Interactive Map', 'manage_options', 'ideal_interactive_map', array( $this, 'map_options_page' ));
	
	}
	
	
	function map_settings_init(  ) { 
	
		register_setting( 'pluginPage', 'map_settings' );
	
		add_settings_section(
			'map_pluginPage_section2', 
			__( '', 'iwg' ), 
			array( $this, 'map_settings_section_callback'), 
			'pluginPage'
		);
		add_settings_field( 
			'disabled_map', 
			__( 'Disable Country Flag', 'iwg' ), 
			array( $this, 'disabled_map_render'), 
			'pluginPage', 
			'map_pluginPage_section2' 
		);
		
		add_settings_section(
			'map_pluginPage_section', 
			__( 'Default Colors', 'iwg' ), 
			array( $this, 'map_settings_section_callback'), 
			'pluginPage'
		);
	
		add_settings_field( 
			'default_added_map_color', 
			__( 'Added Country Map', 'iwg' ), 
			array( $this, 'default_added_map_color_render'), 
			'pluginPage', 
			'map_pluginPage_section' 
		);
		add_settings_field( 
			'default_disabled_map_color', 
			__( 'Disabled Country Map', 'iwg' ), 
			array( $this, 'default_disabled_map_color_render'), 
			'pluginPage', 
			'map_pluginPage_section' 
		);
		add_settings_field( 
			'default_rollover_map_color', 
			__( 'Rollover for Country Map', 'iwg' ), 
			array( $this, 'default_rollover_map_color_render'), 
			'pluginPage', 
			'map_pluginPage_section' 
		);	
		add_settings_field( 
			'default_selected_map_color', 
			__( 'Selected for Country Map', 'iwg' ), 
			array( $this, 'default_selected_map_color_render'), 
			'pluginPage', 
			'map_pluginPage_section' 
		);	
	
	
	}
	
	function default_added_map_color_render(  ) { 
	
		$options = get_option( 'map_settings' );
		?>
		<input type='text' name='map_settings[default_added_map_color]' value='<?php echo (isset($options['default_added_map_color'])) ? $options['default_added_map_color'] : $this->default_added_map_color; ?>'>
		 <small><i>ex: #FFFFFF</i></small><?php
	
	}
	
	function default_disabled_map_color_render(  ) { 
	
		$options = get_option( 'map_settings' );
		?>
		<input type='text' name='map_settings[default_disabled_map_color]' value='<?php echo (isset($options['default_disabled_map_color'])) ? $options['default_disabled_map_color'] : $this->default_disabled_map_color; ?>'>
		 <small><i>ex: #FFFFFF</i></small><?php
	
	}
	
	function default_rollover_map_color_render(  ) { 
	
		$options = get_option( 'map_settings' );
		?>
		<input type='text' name='map_settings[default_rollover_map_color]' value='<?php echo (isset($options['default_rollover_map_color'])) ? $options['default_rollover_map_color'] : $this->default_rollover_map_color; ?>'>
		 <small><i>ex: #FFFFFF</i></small><?php
	
	}
	
	function default_selected_map_color_render(  ) { 
	
		$options = get_option( 'map_settings' );
		?>
		<input type='text' name='map_settings[default_selected_map_color]' value='<?php echo (isset($options['default_selected_map_color'])) ? $options['default_selected_map_color'] : $this->default_selected_map_color; ?>'>
		 <small><i>ex: #FFFFFF</i></small><?php
	
	}
	
	function disabled_map_render(  ) { 
	
		$options = get_option( 'map_settings' );
		?>
		<label><input type="checkbox" name='map_settings[disabled_map]' <?php if( $this->disabled_flag ) checked( $options['disabled_map'], 1 ); ?> value='1'> Yes</label>
		<?php
	
	}
	
	function map_settings_section_callback(  ) { 
	
	
	}
	
	function map_options_page(  ) { 
	
		?>
		<form action='options.php' method='post'>
			
			<h2>Ideal Interactive Map Settings</h2>
			
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>
			
		</form>
		<?php
	
	}
	
}
$ideal_interactive_map = new ideal_interactive_map();


?>