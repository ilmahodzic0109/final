<?php
require_once __DIR__."/../dao/FinalDao.php";

class FinalService {
    protected $dao;

    public function __construct(){
        $this->dao = new FinalDao();
    }

    /** TODO
    * Implement service method to login user
    */
    public function login($email) {
        return $this->dao->login($email);
    }
    public function users(){
        return $this->dao->users();
    }
    /** TODO
    * Implement service method to add new investor to investor table and cap-table
    */
    public function investor($investor){
        return $this->dao->investor($investor);
    }

    /** TODO
    * Implement service method to return list of all share classes from share_classes table
    */
    public function share_classes(){
        return $this->dao->share_classes();
    }

    /** TODO
    * Implement service method to return list of all share class categories from share_class_categories table
    */
    public function share_class_categories(){
        return $this->dao->share_class_categories();
    }
}
?>
