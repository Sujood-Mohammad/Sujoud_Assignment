<?php
// URL of the RSS feed
$rss_url = 'https://careers.moveoneinc.com/rss/all-rss.xml/';

// Fetch RSS feed
$rss_content = file_get_contents($rss_url);

// Parse RSS feed
$rss_xml = simplexml_load_string($rss_content);

// Extract job titles and locations
$jobs = [];
$locations = [];
foreach ($rss_xml->channel->item as $item) {
    $title = (string)$item->title;
    $description = (string)$item->description;
    
    // Extract the location from the description
    preg_match('/<b>Job Location:<\/b><\/td>\s*<td[^>]*>(.*?)<\/td>/i', $description, $matches);
    $location = $matches[1] ?? 'Unknown';
    
    if ($location !== 'Unknown') {
        $locations[] = $location;
    }
    
    $jobs[] = [
        'title' => $title,
        'location' => $location
    ];
}

// Encode locations for JavaScript
$locations_json = json_encode($locations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDG7g-FUvD3TZgTj_Iov4fineTrYHOIosU"></script>
</head>
<body class="bg-light">
    <h1 class="text-center my-4">Job Listings</h1>
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['title']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($job['location']); ?>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($job['location']); ?>" target="_blank">
                            <img src="https://maps.google.com/mapfiles/ms/icons/blue-dot.png" alt="Map" style="width:20px; height:20px;">
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Google Maps -->
        <h2 class="my-4">Job Locations Map</h2>
        <div id="map" style="width: 100%; height: 500px;"></div>
    </div>

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 2,
                center: {lat: 0, lng: 0}
            });

            var geocoder = new google.maps.Geocoder();
            var locations = <?php echo $locations_json; ?>;

            locations.forEach(function(location) {
                geocoder.geocode({'address': location}, function(results, status) {
                    if (status === 'OK') {
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                            title: location
                        });
                        map.setCenter(results[0].geometry.location);
                    }
                });
            });
        }

        google.maps.event.addDomListener(window, 'load', initMap);
    </script>
</body>
</html>
