<?php
function closeResource($conn, $query) {
    if ($conn) $conn->close();
    if ($query) $query->close();
}
?>