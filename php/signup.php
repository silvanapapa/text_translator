<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");

try {
    $conn = new PDO("mysql:host=localhost;dbname=text_translator", 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the username is already taken
    $checkQuery = "SELECT COUNT(*) as count FROM users WHERE username = :username";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute(array('username' => $username));
    $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // Username is already taken
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Username is already taken. Please choose a different username.'));
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO users (username, email, password, last_modified) VALUES (:username, :email, :password, NOW())";
    $arr = array('username'=> $username, 'email' => $email, 'password'=>$hashedPassword);
    $stmt = $conn->prepare($sql);
    $stmt->execute($arr);
    
    // echo json_encode($user, JSON_PRETTY_PRINT );
    // die();
    
    header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'message' => 'User registered successfully'));

} catch (PDOException $e) {
    // Error handling for database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
    exit();
}
?>
