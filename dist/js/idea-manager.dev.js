var IdeaManager = {};
IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.ideaCLuster = (function ($) {

    function ideaCLuster() {
        if (!$('#idea-cluster-map').length) {
            return;
        }

        console.log("ideaCLuster");

        var markers = [],
            mapOptions = {
            zoom: 14,
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

IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.renderMap = (function ($) {

    function RenderMap() {
        if (!$('.idea-location').length) {
            return;
        }

        var geocoder,
            map,
            $mapTargets = $('.idea-location__map');

        geocoder = new google.maps.Geocoder();
        $.each($mapTargets, function(i, value) {
            var address = $(value).attr('data-location');
            geocoder.geocode({'address': address}, function(results, status) {
                if (status == 'OK') {
                    var streetAddress = address.substr(0, address.indexOf(','));

                    var mapOptions = {
                        zoom: 15,
                        center: results[0].geometry.location,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControl: true
                    };

                    map = new google.maps.Map($mapTargets[i], mapOptions);

                    var infowindow = new google.maps.InfoWindow({
                        content: '<b>' + streetAddress + '</b>'
                    });

                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            strokeColor: '#E553F9',
                            scale: 7
                        },
                        title: address
                    });

                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                    });
                } else {
                    console.log('Error; Geocode was not successful: ' + status);
                    $('#location-box').parents("div[class^='grid-']").hide();
                }
            });
        });
    }

    return new RenderMap();

})(jQuery);
