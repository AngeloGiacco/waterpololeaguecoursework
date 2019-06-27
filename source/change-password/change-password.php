<?php
  function leave() {
    ?><script>
        alert("something went wrong, please try again!");
        window.location.replace("index.php");
      </script><?php
  }
  session_start();
  array_map("htmlspecialchars", $_POST);
  include_once("connection.php");
  if (isset($_SESSION["userType"]) and isset($_SESSION["email"])) {
    $userType = $_SESSION["userType"];
    $email = $_SESSION["email"];
    if (isset($_POST["pswd"])) {
      $pswd = $_POST["new-pswd"];
      $pswdrepeat = $_POST["new-pswd-repeat"];
      if ($pswd === $pswdrepeat){
        if ($userType == "coach") {
          $stmt = $conn->prepare("SELECT password FROM coaches WHERE email = :email");
          $stmt->bindParam(':email',$email);
          $stmt->execute();
          $hashed = $stmt->fetch(PDO::FETCH_ASSOC)["password"];
        } else {
          $stmt = $conn->prepare("SELECT password FROM players WHERE email = :email");
          $stmt->bindParam(':email',$email);
          $stmt->execute();
          $hashed = $stmt->fetch(PDO::FETCH_ASSOC)["password"];
        }
        if (password_verify($_POST["pswd"],$hashed)) {
          if ($userType == "coach") {
            $stmt = $conn->prepare("UPDATE coaches SET password = :hashedNewPswd WHERE email = :email");
          } else {
            $stmt = $conn->prepare("UPDATE players SET password = :hashedNewPswd WHERE email = :email");
          }
          $stmt->bindParam(':email',$email);
          $stmt->bindParam(':hashedNewPswd',password_hash($_POST["new-pswd"],PASSWORD_BCRYPT));
          $stmt->execute();
        } else {
          ?><script>
              alert("The old password was not corrrect, please try again!");
              window.location.replace("change.php");
            </script><?php
        }
      } else {
        ?><script>
            alert("the new passwords did not match, please try again!");
            window.location.replace("change.php");
          </script><?php
      }
    } else {
      leave();
    }
  } else {
    leave();
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EMIS Water Polo</title>

    <link href="styles/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link href="styles/template.css" rel="stylesheet">
  </head>
  <body>

    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a style = "color: #fbc93d" class="navbar-brand" href="#">EMIS Water Polo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="table.html">View League Table</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="stats.html">View Statistics</a>
            </li>
          </ul>
          <a href = "login.html"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Login</button></a>
        </div>
      </nav>
    </header>

    <main role="main" height = "100%">
      <div height = "50rem" id="myCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="img-fluid" src="styles/images/waterpolo.jpg" alt="Water Polo">
            <div class="container">
              <div class="carousel-caption text-left" height="40rem">
                <h1>Hurrah!</h1>
                <p>Your password was successfully changed!</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <h1 text-align = "center">CONTENT</h1>
      </div>

			<footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>Made by &copy;AngeloGiacco
        <a style = "font-size: 40px; margin-right: 20px; margin-left:10px" href="https://twitter.com/giaccoangelo" target="_blank"><i class="fa fa-twitter"></i></a>
        <a style = "font-size: 40px" href="https://www.linkedin.com/in/angelo-giacco-450b2017a/" target="_blank"><i class="fa fa-linkedin"></i></a></p>
        <p>Share This:
        <a style = "font-size: 40px; margin-right: 20px; margin-left: 10px;" href="https://twitter.com/home?status=Completely%20free,%20modern,%20water%20polo%20leageue%20management%20website%20by%20@giaccoangelo" target="_blank"><i class="fa fa-twitter"></i></a>
        <a style = "font-size: 40px; margin-right: 20px;" href="https://www.facebook.com/sharer/sharer.php?u=https://github.com/AngeloGiacco/waterpololeaguecoursework" target="_blank"><i class="fa fa-facebook"></i></a>
        <a style = "font-size: 40px; margin-right: 20px;"
        href="https://www.reddit.com/submit?title=Completely%20free,%20modern,%20water%20polo%20leageue%20management%20website&url=emiswaterpololeague.herokuapp.com" target="_blank"><i class="fa fa-reddit"></i></a>
        <a style = "font-size: 40px" href="https://www.linkedin.com/shareArticle?mini=true&url=emiswaterpololeague.herokuapp.com&title=Completely%20free,%20modern,%20water%20polo%20leageue%20management%20website" target="_blank"><i class="fa fa-linkedin"></i></a></p>
      </footer>
		</main>

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  </body>
</html>