IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.ideaCluster = (function ($) {

    function ideaCluster() {
        if (ideaManager.cluster.locations === undefined || ideaManager.cluster.locations.length == 0) {
            return;
        }

        var center = new google.maps.LatLng(56.046467, 12.694512);
            mapOptions = {
            zoom: 12,
            center: center,
            panControl: false,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
            },
            map = new google.maps.Map(document.getElementById('idea-cluster-map'), mapOptions),
            geocoder = new google.maps.Geocoder(),
            iw = new google.maps.InfoWindow({maxWidth: 330});

        function iwClose() { iw.close(); }
        google.maps.event.addListener(map, 'click', iwClose);
        var oms = new OverlappingMarkerSpiderfier(map, {
            markersWontMove: true,
            markersWontHide: true,
        });

        // Make a cluster with the markers from the array
        var markerCluster = new MarkerClusterer(map, [], { imagePath: ideaManager.cluster.iconPath, zoomOnClick: true, maxZoom: 17, gridSize: 20 });
        // Limits the zoom level
        google.maps.event.addListener(map, 'zoom_changed',
            function() {
                if (map.getZoom() > 18) {
                map.setZoom(18);
            };
        });

        ideaManager.cluster.locations.map(function(location, i) {
            geocoder.geocode({'address': location.address}, function(results, status) {
                if (status == 'OK') {
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                    });

                    var html = '<h3>' + location.title + '</h3><p>' + location.excerpt + '</p><br><a target="_top" class="btn btn-md btn-primary" href="' + location.permalink + '">' + ideaManager.readMore + '</a>';

                    // Add marker to map with Spiderfier
                    google.maps.event.addListener(marker, 'click', iwClose);
                    oms.addMarker(marker, function(e) {
                        iw.setContent(html);
                        iw.open(map, marker);
                    });

                    // Add marker to cluster
                    markerCluster.addMarker(marker, true);
                    markerCluster.redraw();
                }
            });
        });

        // for debugging/exploratory use in console
        window.map = map;
        window.oms = oms;
    }

    return new ideaCluster();

})(jQuery);
