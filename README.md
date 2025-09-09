# final-makeup-2024

## STEP 1: Conection with database : (/connection-check)

**ExamDao.php:**

```php
    public function __construct(){
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */
          $servername = "127.0.0.1";
            $username = "root";
            $password = "stavi_koji_je_dat";
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
```


**ExamRoutes.php:**

```php
Flight::route('GET /connection-check', function(){
    /** TODO
    * This endpoint prints the message from constructor within ExamDao class
    * Goal is to check whether connection is successfully established or not
    * This endpoint does not have to return output in JSON format
    */
    new ExamDao(); // samo poziva konstruktor
});

```

## STEP 2: returns performance report for every employee. (GET /employees/performance)

**ExamDao.php:**

```php
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
```

**ExamRoutes.php:**

```php
Flight::route('GET /employees/performance', function(){
    /** TODO
    * This endpoint returns performance report for every employee.
    * It should return array of all employees where every element
    * in array should have following properties
    *   `id` -> employeeNumber of the employee
    *   `full_name` -> concatenated firstName and lastName of the employee
    *   `total` -> total amount of money earned for every employee.
    *              aggregated amount from payments table for every employee
    * This endpoint should return output in JSON format
    * 10 points
    */
    Flight::json(Flight::examService()->employees_performance_report());
});
```

**ExamServices.php:**

```php
public function employees_performance_report(){
        return $this->dao->employees_performance_report();
    }
```

## STEP 3: 2. delete the employee from database with provided id. (DELETE /employee/delete/@employee_id)
```php
**ExamDao.php:**

public function delete_employee($employee_id) {
      $stmt1 = $this->conn->prepare("UPDATE customers SET salesRepEmployeeNumber = NULL WHERE salesRepEmployeeNumber = ?");
      $stmt1->execute([$employee_id]);

      $stmt2 = $this->conn->prepare("DELETE FROM employees WHERE employeeNumber = ?");
      $stmt2->execute([$employee_id]);
      return ['message' => 'Employee deleted successfully'];
    }
```

**ExamRoutes.php:**
```php
Flight::route('DELETE /employee/delete/@employee_id', function($employee_id){
    /** TODO
    * This endpoint should delete the employee from database with provided id.
    * This endpoint should return output in JSON format that contains only 
    * `message` property that indicates that process went successfully.
    * 5 points
    */
    Flight::json(Flight::examService()->delete_employee($employee_id));
});
```

**ExamServices.php:**
```php
/** TODO
    * Implement service method used to delete employee by id */
public function delete_employee($employee_id){
        return $this->dao->delete_employee($employee_id);
    }
```

## STEP 4: edited employee to the database. (PUT /employee/edit/@employee_id)

**ExamDao.php**
```php
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
```

**ExamRoutes.php:**
```php
Flight::route('PUT /employee/edit/@employee_id', function($employee_id) {
    /** TODO
    * This endpoint should save edited employee to the database.
    * The data that will come from the form (if you don't change
    * the template form) has following properties
    *   `first_name` -> first name of the employee
    *   `last_name` -> last name of the employee
    *   `email` -> email of the employee
    * This endpoint should return the edited customer in JSON format
    * 10 points
    */
    $data = Flight::request()->data->getData();
    Flight::json(Flight::examService()->edit_employee($employee_id, $data));
});
```

**ExamService.php:**
```php
/** TODO
    * Implement service method used to edit employee data
    */
    public function edit_employee($employee_id, $data){
        return $this->dao->edit_employee($employee_id, $data);
    }
```

## STEP 5: return the array of all products in a single order with the provided id. (GET /order/details/@order_id) SKUPA SA STEP 6!

## STEP 5: return the report for every order in the database. (/orders/report)

 
**ExamDao.php**
```php
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
```

**ExamRoutes.php:**
```php
Flight::route('GET /orders/report', function(){
    /** TODO
    * This endpoint should return the report for every order in the database.
    * For every order we need the amount of money spent for the order. In order
    * to get total money for every order quantityOrdered should be multiplied 
    * with priceEach from the orderdetails table. The data should be summarized
    * in order to get accurate report. paginated. Every item returned should 
    * have following properties:
    *   `details` -> the html code needed on the frontend. Refer to `orders.html` page
    *   `order_number` -> orderNumber of the order
    *   `total_amount` -> aggregated amount of money spent per order
    * This endpoint should return output in JSON format
    * 10 points
    */
    Flight::json(Flight::examService()->get_orders_report());
});
```

**ExamServices.php:**
```php
/** TODO
    * Implement service method used to get orders report*/
    public function get_orders_report(){
        return $this->dao->get_orders_report();
    }
```

## STEP 6: return the array of all products in a single order with the provided id. (GET /order/details/@order_id)

 
**ExamDao.php**
```php
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
```

**ExamRoutes.php:**
```php
Flight::route('GET /order/details/@order_id', function($order_id){
    /** TODO
    * This endpoint should return the array of all products in a single 
    * order with the provided id. Every food returned should have 
    * following properties:
    *   `product_name` -> productName from the database
    *   `quantity` -> quantity from the orderdetails table
    *   `price_each` -> priceEach from the orderdetails table
    * This endpoint should return output in JSON format
    * 10 points
    */
    Flight::json(Flight::examService()->get_order_details($order_id));
});
```

**ExamServices.php:**
```php
/** TODO
    * Implement service method used to get all products in a single order*/
    public function get_order_details($order_id){
        return $this->dao->get_order_details($order_id);
    }
```
