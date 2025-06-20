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
      $stmt = $this->conn->prepare("
            SELECT 
                e.employeeNumber AS id,
                CONCAT(e.firstName, ' ', e.lastName) AS full_name,
                SUM(p.amount) AS total
            FROM employees e
            JOIN customers c ON e.employeeNumber = c.salesRepEmployeeNumber
            JOIN payments p ON c.customerNumber = p.customerNumber
            GROUP BY e.employeeNumber
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** TODO
     * Implement DAO method used to delete employee by id
     */
    public function delete_employee($employee_id) {
      $stmt1 = $this->conn->prepare("UPDATE customers SET salesRepEmployeeNumber = NULL WHERE salesRepEmployeeNumber = ?");
      $stmt1->execute([$employee_id]);

      $stmt2 = $this->conn->prepare("DELETE FROM employees WHERE employeeNumber = ?");
      $stmt2->execute([$employee_id]);
      return ['message' => 'Employee deleted successfully'];
    }

    /** TODO
     * Implement DAO method used to edit employee data
     */
    public function edit_employee($employee_id, $data){
      $stmt = $this->conn->prepare("
        UPDATE employees 
        SET firstName = :first_name, lastName = :last_name, email = :email 
        WHERE employeeNumber = :employee_id
      ");
      $stmt->execute([
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'email' => $data['email'],
          'employee_id' => $employee_id
      ]);

    return ['message' => 'Employee updated successfully'];
    }

    /** TODO
     * Implement DAO method used to get orders report
     */
    public function get_orders_report(){
      $stmt = $this->conn->prepare("
          SELECT 
              o.orderNumber AS order_number,
              SUM(od.quantityOrdered * od.priceEach) AS total_amount
          FROM orders o
          JOIN orderdetails od ON o.orderNumber = od.orderNumber
          GROUP BY o.orderNumber
      ");
      $stmt->execute();
      $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($orders as &$order) {
          $id = $order['order_number'];
          $order['details'] = "<button class='btn btn-sm btn-info' onclick='showDetails($id)'>Details</button>";
      }

      return $orders;
    }

    /** TODO
     * Implement DAO method used to get all products in a single order
     */
    public function get_order_details($order_id){
      $stmt = $this->conn->prepare("
            SELECT 
                p.productName AS product_name,
                od.quantityOrdered AS quantity,
                od.priceEach AS price_each
            FROM orderdetails od
            JOIN products p ON od.productCode = p.productCode
            WHERE od.orderNumber = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
