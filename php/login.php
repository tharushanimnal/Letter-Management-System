<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM login WHERE name=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userType = $row['user_type'];
        $Id = $row['Id']; 
        $name = $row['name']; 

        if ($userType === 'Admin' || $userType === 'User') {
            echo "<script>
            localStorage.setItem('loggedIn', 'true');
            localStorage.setItem('user_type', '{$userType}');
            localStorage.setItem('Id', '{$Id}');
            localStorage.setItem('name', '{$name}');
            
            if ('{$userType}' === 'Admin') {
                window.location.href = '/Letter-Management/admin.html';
            } else {
                window.location.href = '/Letter-Management/user.html';
            }
          </script>";
        } else {
            echo "<script>
                    alert('Invalid User Type');
                    window.location.href = '/Letter-Management/logIn.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid Username or Password');
                window.location.href = '/Letter-Management/logIn.html';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
