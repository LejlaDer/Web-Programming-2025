<?php
require_once __DIR__."/../dao/ExamDao.php";

class ExamService {
    protected $dao;

    public function __construct(){
        $this->dao = new ExamDao();
    }

    /** TODO
    * Implement service method to get all customers
    */
    public function get_customers(){
        return $this->dao->get_customers();
    }

    /** TODO
    * Implement service method to get all customer meals
    */
    public function get_customer_meals($customer_id){
         return $this->dao->get_customer_meals($customer_id);

    }

    /** TODO
    * Implement service method to add customer to the database
    */
    public function add_customer($customer){
         return $this->dao->add($customer);
    }

    /** TODO
    * Implement service method to return detailed list of foods
    * and total of nutrients for each food
    */
    public function foods_report(){
         $examService = new ExamService();
        Flight::json($examService->foods_report());
    }

    // for auth
    public function get_user_by_email($email) {
        return $this->dao->get_user_by_email($email);
    }
}
?>
