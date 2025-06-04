<?php
session_start();
include "../../../app/config/db_connection.php";

// Get food bank data
$foodBankQuery = "SELECT intFoodBankId, strMunicipality, dblLatitude, dblLongitude FROM tblfoodbank";
$foodBankResult = mysqli_query($conn, $foodBankQuery);

$foodBanks = array();

while ($row = mysqli_fetch_assoc($foodBankResult)) {
    // Get inventory data for this food bank
    $inventoryQuery = "SELECT intQuantity 
                      FROM tblinventory 
                      WHERE intFoodBankId = " . $row['intFoodBankId'];
    
    $inventoryResult = mysqli_query($conn, $inventoryQuery);
    $inventory = array();
    $totalStock = 0;
    
    while ($invRow = mysqli_fetch_assoc($inventoryResult)) {
        $totalStock += $invRow['intQuantity'];
    }
    
    $foodBanks[] = array(
        'id' => $row['intFoodBankId'],
        'name' => $row['strMunicipality'],
        'lat' => $row['dblLatitude'],
        'lng' => $row['dblLongitude'],
        'stock' => $totalStock
    );
}

header('Content-Type: application/json');
echo json_encode($foodBanks);
?> 