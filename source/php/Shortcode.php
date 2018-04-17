<?php

namespace IdeaManager;

class Shortcode
{
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
            'post_status'      => 'publish'
        );
        $ideas = get_posts($args);

        if (!empty($ideas)) {
            foreach ($ideas as $idea) {
                $formData = get_post_meta($idea->ID, 'form-data', true);
                $senderAddress = $formData['adress'] ?? $formData['address'] ?? null;
                if (is_array($senderAddress)) {
                    $addressString = implode(',', $senderAddress);
                    $coordinates = $this->getCoordinates($addressString);
                    if ($coordinates) {
                        $result[] = array(
                            'location' => $idea->post_title,
                            'geo' => $coordinates,
                       		'excerpt' => wp_trim_words($idea->post_content, 30),
                            'permalink' => get_permalink($idea->ID)
                        );
                    }
                }
            }
        }

        //Get center of all points
        if (is_array($result) && !empty($result)) {
            $lat = (float) 0;
            $lng = (float) 0;
            //Sum all lat lng
            foreach ($result as $latLngItem) {
                $lat = $lat + (float) $latLngItem['geo']['lat'];
                $lng = $lng + (float) $latLngItem['geo']['lng'];
            }
            //Calc center position
            $center = array(
                'lat' => $lat/count($result),
                'lng' => $lng/count($result)
            );
        }

        //Add view
        if (isset($center) && !empty($center) && isset($result) && !empty($result)) {
        	$data = array('locations' => $result, 'center' => $center);
        	echo \IdeaManager\App::blade('map', $data);
        }
    }

    public function getCoordinates($address)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=';
        $data = json_decode(file_get_contents($url));
        $coordinates = array();

        if (isset($data->status) && $data->status == 'OK') {
            $coordinates['lat'] = $data->results[0]->geometry->location->lat;
            $coordinates['lng'] = $data->results[0]->geometry->location->lng;
        }

        return $coordinates;
    }
}
