<?php
function processDocFileUpload() {
    if (empty($_FILES['uploadDocumentation']['name'])) {
        return;
    } else {
        $fileTmpPath = $_FILES['uploadDocumentation']['tmp_name'];
        $fileName = $_FILES['uploadDocumentation']['name'];
        $fileSize = $_FILES['uploadDocumentation']['size'];
        $fileType = $_FILES['uploadDocumentation']['type'];

        $targetDir = $_SERVER["DOCUMENT_ROOT"] 
            . DIRECTORY_SEPARATOR . "SagipPagkain" 
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

    exit();
}
?>