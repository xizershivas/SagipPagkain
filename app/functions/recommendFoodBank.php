<?php

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371) {
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}

function recommendFoodBank($conn, $beneficiaryId, $itemIds) {
    if (empty($itemIds)) return null;

    $placeholders = implode(',', array_fill(0, count($itemIds), '?'));

    $stmt = $conn->prepare("SELECT dblLatitude, dblLongitude FROM tblbeneficiary WHERE intBeneficiaryId = ?");
    $stmt->bind_param("i", $beneficiaryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $beneficiary = $result->fetch_assoc();
    if (!$beneficiary) return null;

    $lat1 = $beneficiary['dblLatitude'];
    $lon1 = $beneficiary['dblLongitude'];

    $sql = "
        SELECT 
            fbd.intFoodBankDetailId,
            fbd.strFoodBankName,
            fbd.strAddress,
            fbd.dblLatitude,
            fbd.dblLongitude,
            i.intItemId,
            i.intQuantity,
            i.dtmExpirationDate
        FROM tblfoodbankdetail fbd
        JOIN tblinventory i ON fbd.intFoodBankDetailId = i.intFoodBankDetailId
        WHERE i.intItemId IN ($placeholders) AND i.intQuantity > 0
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
    $stmt->execute();
    $result = $stmt->get_result();

    $foodBanks = [];

    while ($row = $result->fetch_assoc()) {
        $id = $row['intFoodBankDetailId'];
        if (!isset($foodBanks[$id])) {
            $foodBanks[$id] = [
                "id" => $id,
                "name" => $row['strFoodBankName'],
                "address" => $row['strAddress'],
                "latitude" => $row['dblLatitude'],
                "longitude" => $row['dblLongitude'],
                "items" => [],
                "score" => 0,
            ];
        }
        $foodBanks[$id]["items"][] = $row;
    }

    if (empty($foodBanks)) return null;

    $bestScore = 0;
    $bestFoodBank = null;

    foreach ($foodBanks as &$fb) {
        $totalQuantity = 0;
        $soonestExpiry = null;
        $missingItem = false;

        foreach ($itemIds as $neededItemId) {
            $found = false;
            foreach ($fb["items"] as $item) {
                if ($item['intItemId'] == $neededItemId) {
                    $totalQuantity += $item['intQuantity'];
                    if (!$soonestExpiry || $item['dtmExpirationDate'] < $soonestExpiry) {
                        $soonestExpiry = $item['dtmExpirationDate'];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $missingItem = true;
                break;
            }
        }

        if ($missingItem) continue;

        $daysUntilExpiry = max((strtotime($soonestExpiry) - time()) / 86400, 0);
        $expirationScore = min($daysUntilExpiry / 30, 1);

        $stockScore = min($totalQuantity / (count($itemIds) * 10), 1);

        $lat2 = $fb['latitude'];
        $lon2 = $fb['longitude'];
        $distance = haversineDistance($lat1, $lon1, $lat2, $lon2);
        $distanceScore = 1 / (1 + $distance);

        $overallScore = (0.4 * $stockScore) + (0.3 * $expirationScore) + (0.3 * $distanceScore);
        $fb['score'] = $overallScore;
        $fb['stockScore'] = $stockScore;
        $fb['expirationScore'] = $expirationScore;
        $fb['distanceScore'] = $distanceScore;
        $fb['distance'] = round($distance, 2);

        if ($overallScore > $bestScore) {
            $bestScore = $overallScore;
            $bestFoodBank = $fb;
        }
    }

    return $bestFoodBank;
}

function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}


?>