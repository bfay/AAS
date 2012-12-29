function initializeGoogleMap(lat, long, zoomp) {
    zoomp = parseInt(zoomp);
    var myLatlng = new google.maps.LatLng(lat, long);
    var myOptions = { 
        zoom: zoomp,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("google-map-location"), myOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map
    });
}
jQuery(document).ready(function(){
    initializeGoogleMap(kidsGoogle.lat, kidsGoogle.long, kidsGoogle.zoom);
});