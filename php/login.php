<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");


try {
    $conn = new PDO("mysql:host=localhost;dbname=text_translator", 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT username, password FROM users WHERE username = :username";
    $arr = array('username'=> $username);
    $stmt = $conn->prepare($sql);
    $stmt->execute($arr);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo json_encode($user, JSON_PRETTY_PRINT );
    // die();

    if($user !== false) {
        if(password_verify($password, $user['password'])) {
        // Username and password match
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'user' => array('username' => $user['username'])));}
    } else {
        // Invalid username or password
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
    }
} catch (PDOException $e) {
    // Error handling for database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
    exit();
}
?>
