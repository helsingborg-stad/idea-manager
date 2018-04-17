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
