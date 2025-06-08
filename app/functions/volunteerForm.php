<?php
function processSignFileUpload() {
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] == 0) {
        $fileTmpPath = $_FILES['signature']['tmp_name'];
        $fileName = $_FILES['signature']['name'];
        $fileSize = $_FILES['signature']['size'];
        $fileType = $_FILES['signature']['type'];

        $isLocal = $_SERVER['HTTP_HOST'] === 'localhost' || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1');
        $basePath = $isLocal ? 'SagipPagkain' : '';

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . $basePath 
            . DIRECTORY_SEPARATOR . "app" 
            . DIRECTORY_SEPARATOR . "storage" 
            . DIRECTORY_SEPARATOR . "media/";

        $uploadFilePath = $targetDir . basename($fileName);

        $allowedTypes = ['image/jpeg', 'image/png'];

        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Invalid image type"]]);
        } else if ($fileSize > 5000000) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "Image is too large"]]);
        } else if (file_exists($uploadFilePath)) {
            http_response_code(400);
            echo json_encode(["data" => ["message" => "File already exist"]]);
        } else {
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                return $uploadFilePath;
            } else {
                http_response_code(500);
                echo json_encode(["data" => ["message" => "Server encountered an error, upload failed."]]);
            }
        }
    }
}
?>