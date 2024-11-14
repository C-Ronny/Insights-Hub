<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vividly | Register</title>
  <link rel="stylesheet" href="signup.css">
</head>
<body>
  <!--Nav Bar-->
  <nav class="nav-bar">
    <div><a href="login.html">Log In</a></div>
    <div><a href="signup.html">Sign Up</a></div>
  </nav>

  <div class="container">
    <form  method="PUT" enctype="multipart/form-data" id="form" action="#">
      <h1 class="Welcome">Vividly</h1>
      <br>
      <h2>Sign Up</h2>
      <input type="text" placeholder="Firstname" name="fname">
      <br>
      <br>
      <input type="text" placeholder="Lastname" name="lname">
      <br>
      <br>
      <input type="email" placeholder="Email" name="eml">
      <br>
      <br>
      <input type="password" placeholder="Password">
      <br>
      <br>
      
      <input type="password" placeholder=" Confirm Password" name="psw">

      <p>Already got an account? <a id="sign" href="login.html">Login</a></p>

      <button type="submit">Sign Up</button>
    </form>
  </div>
  
</body>
</html>