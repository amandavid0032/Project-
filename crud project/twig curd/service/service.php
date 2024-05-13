<?php
require_once '../../model/user.php';
class UserService
{
    private $model;

    public function __construct()
    {
        $this->model = new dataBase(); 
    }

    public function getUsers($limit, $offset, $searchValue, $sortSQL)
    {
        if ($searchValue !== "" or $sortSQL !=="") {
            return $this->model->searchUser($searchValue, $sortSQL, $offset, $limit);
        } elseif ($sortSQL !== '') {
            return $this->model->getUsersSorted($sortSQL, $offset, $limit);
        } else {
            return $this->model->selectAllUser($offset, $limit,);
        }
    }

    public function getTotalRecords($searchValue)
    {
        return $this->model->getTotalRecords(['f_name'], $searchValue);
    }

    public function registerUser($userData)
    {
        return $this->model->registerUser($userData);
    }

    public function getUserByEmail($email)
    {
        return $this->model->getUserByEmail($email);
    }

    public function updateUser($id, $data)
    {
        $result = $this->model->updateUser($data, "uid = '$id'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
