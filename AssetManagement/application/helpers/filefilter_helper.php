<?php
function getFilePath(
    string $folderName = '',
    string $directName = '',
    string $FileName = ''
): string {
    return base_url($folderName . $directName . '/' . $FileName);
}

function uploadSingleFile(
    array $file = [],
    string $directName = null
): string {
    if (empty($file) || empty($directName)) {
        return FALSE;
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $RandomAccountNumber = uniqid(). time();
    $createFileName = $RandomAccountNumber .'.'. $ext;
    $source = $file['tmp_name'];
    $pathToSaveImage = $directName . '/' . $createFileName ;

    if (move_uploaded_file($source, $pathToSaveImage)) {
        return $createFileName;
    } else {
        return FALSE;
    }
}

function uploadMultiImage(
    array $arrayOfImages = [],
    string $directName = null
): string {

    if (empty($arrayOfImages) || empty($directName)) {
        return FALSE;
    }
    
    $returnImages = [];
    foreach ($arrayOfImages['name'] as $key => $value) {
        
        $ext = pathinfo($arrayOfImages['name'][$key], PATHINFO_EXTENSION);
        $RandomAccountNumber = uniqid() . time();
        $fileName = $RandomAccountNumber . '.' . $ext;
        $source = $arrayOfImages['tmp_name'][$key];
        $pathToSaveImage = $directName . '/' . $fileName;
        move_uploaded_file($source, $pathToSaveImage);
        $returnImages[] = $fileName;
    }
    $returnImages = implode(',', $returnImages);
    return $returnImages;
}

function deletefile(
    string $fileName = null, 
   string  $directoryName = ''
): bool{
    if (unlink($directoryName.$fileName)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function compress($source, $destination, $quality)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

function resize_image($file, $w, $h)
{   
    
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($w / $h > $r) {
        $newwidth = $h * $r;
        $newheight = $h;
    } else {
        $newheight = $w / $r;
        $newwidth = $w;
    }
    $dst = imagecreatetruecolor($newwidth, $newheight);
    $src = imagecreatefromjpeg($file);
    imagecopyresized($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagejpeg($dst, $file);

    return $dst;
}
