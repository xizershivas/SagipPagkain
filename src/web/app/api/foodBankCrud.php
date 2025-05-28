<?php
session_start();
include "../../../app/config/db_connection.php";

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Error handling function
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

// Get and validate action
if (!isset($_POST['action'])) {
    sendError('Action is required');
}

$action = $_POST['action'];

try {
    switch ($action) {
        case 'add':
            // Validate input
            if (!isset($_POST['strFoodBank']) || empty(trim($_POST['strFoodBank']))) {
                sendError('Food bank location is required');
            }
            
            $strFoodBank = trim($_POST['strFoodBank']);
            
            // Get coordinates using Nominatim
            $address = urlencode($strFoodBank . ", Laguna, Philippines");
            $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . $address;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'SagipPagkain');
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                sendError('Error getting coordinates: ' . curl_error($ch));
            }
            
            curl_close($ch);
            
            $data = json_decode($response, true);
            
            if (empty($data)) {
                sendError('Could not find coordinates for this location');
            }
            
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO tblfoodbank (strFoodBank, dblLatitude, dblLongitude) VALUES (?, ?, ?)");
            if (!$stmt) {
                sendError('Database error: ' . $conn->error);
            }
            
            $lat = $data[0]['lat'];
            $lng = $data[0]['lon'];
            
            $stmt->bind_param("sdd", $strFoodBank, $lat, $lng);
            
            if (!$stmt->execute()) {
                sendError('Error adding food bank: ' . $stmt->error);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Food bank added successfully',
                'id' => $stmt->insert_id
            ]);
            break;
            
        case 'update':
            // Validate input
            if (!isset($_POST['intFoodBankId']) || !is_numeric($_POST['intFoodBankId'])) {
                sendError('Valid food bank ID is required');
            }
            if (!isset($_POST['strFoodBank']) || empty(trim($_POST['strFoodBank']))) {
                sendError('Food bank location is required');
            }
            
            $intFoodBankId = (int)$_POST['intFoodBankId'];
            $strFoodBank = trim($_POST['strFoodBank']);
            
            // Get new coordinates
            $address = urlencode($strFoodBank . ", Laguna, Philippines");
            $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . $address;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'SagipPagkain');
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                sendError('Error getting coordinates: ' . curl_error($ch));
            }
            
            curl_close($ch);
            
            $data = json_decode($response, true);
            
            if (empty($data)) {
                sendError('Could not find coordinates for this location');
            }
            
            // Update database
            $stmt = $conn->prepare("UPDATE tblfoodbank SET strFoodBank = ?, dblLatitude = ?, dblLongitude = ? WHERE intFoodBankId = ?");
            if (!$stmt) {
                sendError('Database error: ' . $conn->error);
            }
            
            $lat = $data[0]['lat'];
            $lng = $data[0]['lon'];
            
            $stmt->bind_param("sddi", $strFoodBank, $lat, $lng, $intFoodBankId);
            
            if (!$stmt->execute()) {
                sendError('Error updating food bank: ' . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                sendError('Food bank not found or no changes made');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Food bank updated successfully'
            ]);
            break;
            
        case 'delete':
            // Validate input
            if (!isset($_POST['intFoodBankId']) || !is_numeric($_POST['intFoodBankId'])) {
                sendError('Valid food bank ID is required');
            }
            
            $intFoodBankId = (int)$_POST['intFoodBankId'];
            
            // Delete from database
            $stmt = $conn->prepare("DELETE FROM tblfoodbank WHERE intFoodBankId = ?");
            if (!$stmt) {
                sendError('Database error: ' . $conn->error);
            }
            
            $stmt->bind_param("i", $intFoodBankId);
            
            if (!$stmt->execute()) {
                sendError('Error deleting food bank: ' . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                sendError('Food bank not found');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Food bank deleted successfully'
            ]);
            break;
            
        default:
            sendError('Invalid action');
    }
} catch (Exception $e) {
    sendError('Server error: ' . $e->getMessage(), 500);
}
?> 