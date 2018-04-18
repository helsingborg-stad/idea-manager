IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.ideaCluster = (function ($) {

    function ideaCluster() {
        $(function() {
            if (!$('#idea-cluster-map').length) {
                return;
            }
            this.init();
        }.bind(this));
    }

    ideaCluster.prototype.init = function() {
        var mapOptions = {
            zoom: 12,
            center: new google.maps.LatLng(56.046467, 12.694512),
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
            iw = new google.maps.InfoWindow({maxWidth: 330}),
            bounds = new google.maps.LatLngBounds();

        // Close info window on click
        function iwClose() { iw.close(); }
        google.maps.event.addListener(map, 'click', iwClose);
        var oms = new OverlappingMarkerSpiderfier(map, {
            markersWontMove: true,
            markersWontHide: true,
        });

        // Create cluster
        var markerCluster = new MarkerClusterer(map, [], { imagePath: ideaManager.cluster.iconPath, zoomOnClick: true, maxZoom: 17, gridSize: 20 });
        // Limits the zoom level
        google.maps.event.addListener(map, 'zoom_changed',
            function() {
                if (map.getZoom() > 18) {
                    map.setZoom(18);
                }
        });

        $.when(this.getLocations()).then(function(data, textStatus, jqXHR) {
            if (textStatus === 'success' && Array.isArray(data)) {
                data.map(function(location, i) {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(location.coordinates)
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
                    // Extend the bounds to include each marker's position
                    bounds.extend(marker.position);
                });

                markerCluster.redraw();
                map.panToBounds(bounds);
            }
        });
    };

    ideaCluster.prototype.getLocations = function() {
        return $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action : 'idea_locations'
            }
        });
    };

    return new ideaCluster();

})(jQuery);
