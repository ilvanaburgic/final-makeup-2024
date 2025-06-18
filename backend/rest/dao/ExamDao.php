<?php

class ExamDao {

    private $conn;

    /**
     * constructor of dao class
     */
    public function __construct(){
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */
          $servername = "127.0.0.1";
            $username = "root";
            $password = "12nana123";
            $dbname = "classicmodels";
            $port = 3306;

            $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          /** TODO
           * Create new connection
           */
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }



    /** TODO
     * Implement DAO method used to get employees performance report
     */
    public function employees_performance_report(){

    }

    /** TODO
     * Implement DAO method used to delete employee by id
     */
    public function delete_employee($employee_id) {

    }

    /** TODO
     * Implement DAO method used to edit employee data
     */
    public function edit_employee($employee_id, $data){

    }

    /** TODO
     * Implement DAO method used to get orders report
     */
    public function get_orders_report(){

    }

    /** TODO
     * Implement DAO method used to get all products in a single order
     */
    public function get_order_details($order_id){

    }
}
?>
