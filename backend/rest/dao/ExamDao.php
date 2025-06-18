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
          $host = "db1.ibu.edu.ba";
          $database = "webmakeup";
          $user = "webmakeup_24";
          $password = "web24makePWD";
          $port = "3306";

          /** TODO
           * Create new connection
           */
          $dsn = "mysql:host=$host;port=$port;database=$database";
          $this->conn = new PDO($dsn, $user, $password);
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }



    /** TODO
     * Implement DAO method used to get employees performance report
     */
    public function employees_performance_report(){
      $sql = "SELECT e.employeeNumber AS id, CONCAT(lastName, firstName) AS full_name, p.amount AS total
              FROM webmakeup.employees e
              JOIN webmakeup.customers c ON e.employeeNumber = c.salesRepEmployeeNumber
              JOIN webmakeup.payments p ON c.customerNumber = p.customerNumber";

      try{
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }catch(Exception $e){
        $e ->getMessage();
      }
    }

    /** TODO
     * Implement DAO method used to delete employee by id
     */
    public function delete_employee($employee_id) {
      $sql = "DELETE FROM employees WHERE employeeNumber = $employee_id";

      try{
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }catch(Exception $e){
        $e ->getMessage();
      }
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
      $sql = "SELECT o.orderNumber AS order_number, SUM(od.quantityOrdered * od.priceEach) AS total_amount
              FROM orders o
              JOIN orderdetails od ON o.orderNumber = od.orderNumber
              GROUP BY o.orderNumber
              ORDER BY total_amount DESC";

        try{
          $stmt = $this->conn->prepare($sql);
          $stmt->execute();
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
          $e->getMessage();
        }
    }

    /** TODO
     * Implement DAO method used to get all products in a single order
     */
    public function get_order_details($order_id){
      $sql = "SELECT p.productName AS product_name, od.quantityOrdered AS quantity, od.priceEach as price_each
              FROM orderdetails od
              JOIN products p ON od.productCode = p.productCode
              WHERE od.orderNumber = $order_id";

      try{
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }catch(Exception $e){
        $e->getMessage();
      }
    }
}
?>
