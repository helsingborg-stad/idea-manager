var IdeaManager = {};
IdeaManager = IdeaManager || {};
IdeaManager.Idea = IdeaManager.Idea || {};

IdeaManager.Idea.renderMap = (function ($) {
        var geocoder;
        var map;

        function RenderMap() {

            geocoder = new google.maps.Geocoder();
            var address = document.getElementById('box-idea-location').getAttribute('data-location');
            console.log(address);
            geocoder.geocode({'address': address}, function(results, status) {
                if (status == 'OK') {
                    console.log("geo OK");
                    var streetAddress = address.substr(0, address.indexOf(','));
                    var mapOptions = {
                        zoom: 15,
                        center: results[0].geometry.location,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControl: true
                    };

                    map = new google.maps.Map(document.getElementById('box-idea-location'), mapOptions);

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
                    console.log(map);
                } else {
                    console.log('Error; Geocode was not successful: ' + status);
                    $('#location-box').parents("div[class^='grid-']").hide();
                }
            });
        }

    return new RenderMap();

})(jQuery);
