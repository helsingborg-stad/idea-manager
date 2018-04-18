<?php

namespace IdeaManager;

class Shortcode
{
    private $locations;

    public function __construct()
    {
        add_action('init', array($this, 'register_shortcodes'));
        add_action('wp_ajax_idea_locations', array($this, 'getIdeaLocations'));
    }

    public function register_shortcodes()
    {
        add_shortcode('idea-map', array($this, 'ideaMap'));
    }

    public function ideaMap()
    {
        if (!defined('G_GEOCODE_SERVER_KEY')) {
            error_log("Define constant 'G_GEOCODE_SERVER_KEY' to continue.");
            return;
        }

        $this->shortcodeScripts();
        echo '<div class="idea-cluster"><div id="idea-cluster-map"></div></div>';
    }

    public function getIdeaLocations()
    {
        if (!$locations = wp_cache_get('idea_locations')) {
            ignore_user_abort();

            $locations = array();
            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'idea',
                'post_status'      => 'publish',
            );
            $ideas = get_posts($args);

            if (!empty($ideas)) {
                foreach ($ideas as $key => $idea) {
                    // Pause one second to avoid 'OVER_QUERY_LIMIT' of 50 requests/sec
                    if (($key + 1) % 49 == 0) {
                        sleep(1);
                    }

                    $formData = get_post_meta($idea->ID, 'form-data', true);
                    $senderAddress = $formData['adress'] ?? $formData['address'] ?? null;
                    if (is_array($senderAddress) && !empty($senderAddress)) {
                        $senderAddress = implode(',', $senderAddress);
                        $coordinates = $this->getCoordinates($senderAddress);
                        if (!empty($coordinates)) {
                            $locations[] = array(
                                'title'      => $idea->post_title,
                                'address'      => $senderAddress,
                                   'excerpt'      => wp_trim_words($idea->post_content, 30),
                                'permalink'   => get_permalink($idea->ID),
                                'coordinates' => $coordinates
                            );
                        }
                    }
                }
            }

            // Save in cache for 2 weeks
            wp_cache_add('idea_locations', $locations, '', 1209600);
        }

        wp_send_json($locations);
    }

    public function getCoordinates($address)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . G_GEOCODE_SERVER_KEY;
        $data = json_decode(file_get_contents($url));
        $coordinates = array();

        if (isset($data->status) && $data->status == 'OK') {
            $coordinates['lat'] = $data->results[0]->geometry->location->lat;
            $coordinates['lng'] = $data->results[0]->geometry->location->lng;
        }

        return $coordinates;
    }

    public function shortcodeScripts()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script('marker-clusterer', IDEAMANAGER_URL  . '/source/js/vendor/MarkerClusterer.min.js', array(), '', true);
            wp_enqueue_script('marker-spiderfier', IDEAMANAGER_URL  . '/source/js/vendor/OverlappingMarkerSpiderfier.min.js', array(), '', true);

            wp_enqueue_script('idea-manager');
            wp_enqueue_script('google-maps-api');
            wp_enqueue_style('idea-manager');
            wp_localize_script('idea-manager', 'ideaManager', array(
                'cluster' => array(
                    'iconPath' => IDEAMANAGER_URL . '/source/assets/images/'
                ),
                'readMore' => __('Read more', 'idea-manager')
            ));
        });
    }
}
