<?php
require_once __DIR__ . '..\..\model\user.php';

class file
{
    private $model;

    public function __construct()
    {
        $this->model = new dataBase();
    }

    public function uploadImages($files)
    {
        $imageNames = array();
        if (!empty($files['image']['name'][0])) {
            foreach ($files['image']['name'] as $key => $value) {
                $name = $files['image']['name'][$key];
                $temp_name = $files['image']['tmp_name'][$key];
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $unique_name = uniqid() . '_' . time() . '.' . $extension;
                $folder = $_SERVER['DOCUMENT_ROOT'] . "/php-Pratice/twig/uploads/" . $unique_name;
                if (move_uploaded_file($temp_name, $folder)) {
                    $imageNames[] = $unique_name;
                } else {
                    return false;
                }
            }
            return $imageNames;
        } else {
            return false;
        }
    }
}
