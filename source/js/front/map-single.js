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
