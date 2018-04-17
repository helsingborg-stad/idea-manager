IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.ideaCLuster = (function ($) {

    function ideaCLuster() {
        if (!$('#idea-cluster-map').length) {
            return;
        }

        var markers = [],
            mapOptions = {
            zoom: 12,
            center: ideaManager.cluster.center,
            panControl: false,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
            },
            map = new google.maps.Map(document.getElementById('idea-cluster-map'), mapOptions),
            iw = new google.maps.InfoWindow({maxWidth: 330});

        function iwClose() { iw.close(); }
        google.maps.event.addListener(map, 'click', iwClose);

        var oms = new OverlappingMarkerSpiderfier(map, {
            markersWontMove: true,
            markersWontHide: true,
        });

        for (var i = 0, len = ideaManager.cluster.locations.length; i < len; i ++) {
            (function() {
                var markerData = ideaManager.cluster.locations[i];
                var html = '<h3>' + markerData.location + '</h3><p>' + markerData.excerpt + '</p><br><a target="_top" class="btn btn-md btn-primary" href="' + markerData.permalink + '">' + 'Read more' + '</a>';
                var latLng = new google.maps.LatLng(markerData.geo.lat,markerData.geo.lng);
                var marker = new google.maps.Marker({
                    position: latLng,
                    title: markerData.location
                });
                google.maps.event.addListener(marker, 'click', iwClose);
                oms.addMarker(marker, function(e) {
                    iw.setContent(html);
                    iw.open(map, marker);
                });
                markers.push(marker);
            })();
        }

        // for debugging/exploratory use in console
        window.map = map;
        window.oms = oms;

        // Make a cluster with the markers from the array
        var markerCluster = new MarkerClusterer(map, markers, { imagePath: ideaManager.cluster.iconPath, zoomOnClick: true, maxZoom: 17, gridSize: 20 });

        google.maps.event.addListener(map, 'zoom_changed',
            function() {
                if (map.getZoom() > 18) {
                map.setZoom(18);
            };
        });
    }

    return new ideaCLuster();

})(jQuery);
