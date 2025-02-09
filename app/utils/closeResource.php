<?php
function closeResource($conn, $query = null) {
    if ($conn && method_exists($conn, 'close')) {
        $conn->close();
    }
    if ($query && method_exists($query, 'close')) {
        $query->close();
    }
}
?>