</pre><div id="ResultDiv_<?php echo $index ?>" style="text-align: left; height: 550px; width: 550px"></div><pre>
    <?php
    $key = "AIzaSyAeYSwNi2l9ZMjR-uapObNWPZnS-P8B8aY";
    echo "<script src=\"http://maps.googleapis.com/maps/api/js?key=" . $key . "&sensor=true\" type=\"text/javascript\"></script>";
    ?>

<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markermanager/src/markermanager.js"></script>
<script type="text/javascript">
    //var myLatlng = new google.maps.LatLng(-25.4283563, -49.2732515);
    var myOptions = {
        zoom: 17,
        //center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        draggable: true,
        mapTypeControl: true,
        navigationControl: true,
    }
    var address = <?php echo "'" . $endereco . "'"; ?>;
    map = new google.maps.Map(document.getElementById("ResultDiv_<?php echo $index ?>"), myOptions);
    var addr = address + ', <?php echo $localidade; ?>, Brasil';
    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({'address': addr}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                title: '<?php echo $endereco; ?>'
            });
            var infowindow = new google.maps.InfoWindow(), marker;

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent("<?php echo $conteudo_marcador; ?>");
                    infowindow.open(map, marker);
                }
            })(marker))

        } else {
            alert('Geocode não funcionou corretamente : ' + status);
        }
    });
</script>
