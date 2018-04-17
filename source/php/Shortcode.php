<?php

namespace IdeaManager;

class Shortcode
{
	private $locations;

    public function __construct()
    {
        add_action('init', array($this, 'register_shortcodes'));
    }

    public function register_shortcodes()
    {
        add_shortcode('idea-map', array($this, 'ideaMap'));
    }

    public function ideaMap()
    {
        if (!defined('G_GEOCODE_KEY')) {
            echo "Define constant 'G_GEOCODE_KEY' to continue.";
            return;
        }

        $result = array();
        $args = array(
			'posts_per_page'   => -1,
            'post_type'        => 'idea',
            'post_status'      => 'publish',
        );
        $ideas = get_posts($args);

        if (!empty($ideas)) {
            foreach ($ideas as $idea) {
                $formData = get_post_meta($idea->ID, 'form-data', true);
                $senderAddress = $formData['adress'] ?? $formData['address'] ?? null;
                if (is_array($senderAddress) && !empty($senderAddress)) {
                    $senderAddress = implode(',', $senderAddress);
                    $result[] = array(
                        'title' 	=> $idea->post_title,
                        'address' 	=> $senderAddress,
                   		'excerpt' 	=> wp_trim_words($idea->post_content, 30),
                        'permalink' => get_permalink($idea->ID)
                    );
                }
            }
        }

        // Display map
        if (!empty($result)) {
        	$this->locations = $result;
        	$this->shortcodeScripts();
        	echo '<div class="idea-cluster"><div id="idea-cluster-map"></div></div>';
        }
    }

    public function shortcodeScripts()
    {
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_script('marker-clusterer', IDEAMANAGER_URL  . '/source/js/vendor/MarkerClusterer.min.js', array(), '', true);
			wp_enqueue_script('marker-spiderfier', IDEAMANAGER_URL  . '/source/js/vendor/OverlappingMarkerSpiderfier.min.js', array(), '', true);

	    	wp_enqueue_script('idea-manager');
	    	wp_enqueue_script('google-maps-api');
	    	wp_enqueue_style('idea-manager');
	    	wp_localize_script('idea-manager', 'ideaManager', array(
	    		'cluster' => array(
	    			'locations' => $this->locations,
	    			'iconPath' => IDEAMANAGER_URL . '/source/assets/images/'
	    		),
	    		'readMore' => __('Read more', 'idea-manager')
			));
		});
    }
}
