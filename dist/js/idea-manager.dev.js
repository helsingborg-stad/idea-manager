var IdeaManager = {};
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

IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.singleMap = (function ($) {

    function singleMap() {
        $(function() {
            if (!$('.idea-map').length) {
                return;
            }
            this.init();
        }.bind(this));
    }

    singleMap.prototype.init = function() {
        var geocoder,
            map,
            $mapTargets = $('.idea-map'),
            geocoder = new google.maps.Geocoder(),
            streetAddress;

        // Loop over each target if target div is rendered multiple times
        $.each($mapTargets, function(i, value) {
            var address = $(value).data('location');
            geocoder.geocode({'address': address}, function(results, status) {
                if (status == 'OK') {
                    $('.single-idea-map').show();

                    var mapOptions = {
                        zoom: 15,
                        center: results[0].geometry.location,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControl: true
                    };

                    map = new google.maps.Map($mapTargets[i], mapOptions);
                    streetAddress = address.substr(0, address.indexOf(','));
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
                }
            });
        });
    }

    return new singleMap();

})(jQuery);
