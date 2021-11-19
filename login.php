
<?php
session_start();
//If the user is trying to sign in.
if (!empty($_POST)) {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    //Associate will put the username and password input
    $usernameInput = $_POST['username'];
    $passwordInput = $_POST['password'];
    $stmt = $devPdo->prepare("SELECT * FROM sales_associates WHERE name = :user");
    $stmt->bindParam(':user', $usernameInput);
    $stmt->execute();
    $found = $stmt->fetch();
    //Check if there is no user found
    if (empty($found)) {
      echo '<div style="text-align:center" class="alert alert-danger">';
      echo '<strong>No User Found Under that Username please try again: ' . $usernameInput;
      echo '</div>';
    } else {
      $userSession = $found[1];
      $password = $found[2];
      $admin = $found[5];
      // Verify user password and set $_SESSION admin and user
      if (md5($passwordInput) == $password) {
        $_SESSION['user_id'] = $userSession;
        $_SESSION['admin'] = $admin;
      } else {
        echo '<div style="text-align:center" class="alert alert-danger">';
        echo '<strong>Incorrect Password, please try again.</strong>';
        echo '</div>';
      }
    }
  }
}