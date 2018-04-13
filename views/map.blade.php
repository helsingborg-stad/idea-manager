@if(isset($data) && is_array($data) && !empty($data))
    <script>
      var jsonPlots = {!!json_encode($data)!!}
    </script>

	<div class="gutter gutter-top">
      <style scoped>
        .area-map {
          width: 100%;
        }
        #areaMap {
          height: 400px;
          margin-bottom: 30px;
        }
      </style>

      <div class="area-map c-area-map t-area-map">
          <div id="areaMap"></div>
          <script>
            function areaInitMap() {
              //Declare stuff
              var marker, item, map;
              //Create new map
              map = new google.maps.Map(document.getElementById('areaMap'), {
                zoom: 14,
                center: {!!json_encode($center)!!},
                disableDefaultUI: false
              });
              for(var item in jsonPlots) {
                //Get details about pin
                name  = jsonPlots[item].location;
                info  = jsonPlots[item].excerpt;
                link  = jsonPlots[item].permalink;
                //Append html markup for infowindow
                jsonPlots[item].info  = '<h3>' + name + '</h3>' + '<a target="_top" class="btn btn-md btn-primary" href="' + link + '"><?php _e("Read more", 'idea'); ?></a>';

                //Create new marker
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(jsonPlots[item].geo.lat,jsonPlots[item].geo.lng),
                    name: name,
                    map: map
                });
                //Add infowindow trigger
                google.maps.event.addListener(marker, 'click', (function(marker, item) {
                  return function() {
                      var infoWindow = new google.maps.InfoWindow();
                      infoWindow.setContent(jsonPlots[item].info);
                      infoWindow.open(map, marker);
                  }
                })(marker, item));
              }
            }
          </script>
          <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ G_GEOCODE_KEY }}&callback=areaInitMap"></script>
    </div>
</div>
@endif
