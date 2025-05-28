<?php
function getCoordinates($location) {
    try {
        // Append "Laguna, Philippines" to make the search more accurate
        $address = urlencode($location . ", Laguna, Philippines");
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . $address;

        // Set up cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SagipPagkain');
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        $data = json_decode($response, true);

        if (!empty($data)) {
            return array(
                'success' => true,
                'lat' => $data[0]['lat'],
                'lng' => $data[0]['lon']
            );
        }
        
        return array(
            'success' => false,
            'message' => 'Location not found'
        );
    } catch (Exception $e) {
        return array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
}
?> 