<?php
function processSignFileUpload() {
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] == 0) {
        $fileTmpPath = $_FILES['signature']['tmp_name'];
        $fileName = $_FILES['signature']['name'];
        $fileSize = $_FILES['signature']['size'];
        $fileType = $_FILES['signature']['type'];

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . "SagipPagkain" 
            . DIRECTORY_SEPARATOR . "app" 
            . DIRECTORY_SEPARATOR . "storage" 
            . DIRECTORY_SEPARATOR . "images/";

        $uploadFilePath = $targetDir . basename($fileName);

        $allowedTypes = ['image/jpeg', 'image/png'];

        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid image type"]);
        } else if ($fileSize > 3000000) {
            http_response_code(400);
            echo json_encode(["message" => "Image is too large"]);
        } else if (file_exists($uploadFilePath)) {
            http_response_code(400);
            echo json_encode(["message" => "File already exist"]);
        } else {
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                return $uploadFilePath;
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Server encountered an error, upload failed."]);
            }
        }
    }
}
?>