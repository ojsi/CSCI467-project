
<?php
include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");

$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);
$success = false;
$salesaid = -1;
session_start();
//If the user is trying to sign in.
if (!empty($_POST)) {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    //Associate will put the username and password input
    $usernameInput = $_POST['username'];
    $passwordInput = $_POST['password'];
    $stmt = $pdoQuoteDB->prepare("SELECT * FROM SalesAssoc WHERE name = :user");
    $stmt->bindParam(':user', $usernameInput);
    $stmt->execute();
    $found = $stmt->fetch(PDO::FETCH_ASSOC);
    //Check if there is no user found
    if (empty($found)) {
      echo '<div style="text-align:center" class="alert alert-danger">';
      echo '<strong>No user was found under that username please try again: ' . $usernameInput;
      echo '</div>';
    } else {
      $name = $found['name'];
      $password = $found['passwd'];
      $salesaid = $found['salesAID'];
      // Verify user password and set $_SESSION admin and user
      if (strcmp($passwordInput, $password) == 0) {
        $success=true;
      } else {
        $success=false;
        echo '<div style="text-align:center" class="alert alert-danger">';
        echo '<strong>Incorrect password, please try again.</strong>';
        echo '</div>';
      }
    }
  }
}

if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
} else {
  $loggedOut = '<p style="text-align:center" class="bg-danger text-white">Please Login To Access System</p>';
  echo $loggedOut;
}


echo '<html>

<head>
  <title>Login Page</title>
</head>

<body>

  <div style="text-align:center" class="jumbotron jumbotron-fluid p-2 m-1 bg-info text-white rounded">
    <h1>Sales Associate Login</h1>
  </div>
  <div class="p-1 m-1 btn-group d-flex">
    <a href="index.html" class="btn btn-dark" role="button">Back To The Home Page</a>
  </div>
<!--Login Form with validation--> ';
if($success){
echo '<form action="CustomerAccess.php" class="border border-primary rounded m-2 p-2 needs-validation" method="post">';
echo ' <input type="hidden" name="salesAID" value="' . $salesaid . '">';
}
else{
  echo'<form action="login.php" class="border border-primary rounded m-2 p-2 needs-validation" method="post">';}
  echo'<div class="form-group">';
  echo '<label for="uname">Username:</label>
      <input type="text" class="form-control" id="uname" placeholder="Enter username" name="username" required>
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required>
    </div>
    <div class="p-1 m-1 btn-group d-flex">
      <button type="submit" class="btn btn-primary">Login</button>
    </div>
  </form>
</body>';
?>
