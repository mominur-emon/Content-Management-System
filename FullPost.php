<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $SearchQueryParameter = $_GET["id"]; ?>
<?php
if(isset($_POST["Submit"])){
  $Name = $_POST["CommenterName"];
  $Email = $_POST["CommenterEmail"];
  $Comment = $_POST["CommenterThoughts"];
  date_default_timezone_set("Asia/Dhaka");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($Name)||empty($Email)||empty($Comment)){
    $_SESSION["ErrorMessage"]= "All fields must be filled out";
    Redirect_to("FullPost.php?id={$SearchQueryParameter}");
  }elseif (strlen($Comment)>500) {
    $_SESSION["ErrorMessage"]= "Comment length should be less than 500 charecters";
    Redirect_to("FullPost.php?id={$SearchQueryParameter}");
  }else{
    //Query to insert Comment in DB when everything is fine
    global $ConnectingDB;
    $sql = "INSERT INTO comments(datetime,name,email,comment,post_id)";
    $sql .= "VALUES(:dateTime,:name,:email,:comment,:PostIdFromURL)";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':dateTime',$DateTime);
    $stmt->bindValue(':name',$Name);
    $stmt->bindValue(':email',$Email);
    $stmt->bindValue(':comment',$Comment);
    $stmt->bindValue(':PostIdFromURL',$SearchQueryParameter);
    $Execute=$stmt->execute();
    //var_dump($Execute);

    if($Execute){
      $_SESSION["SuccessMessage"]="Comment Submitted Successfully";
      Redirect_to("FullPost.php?id={$SearchQueryParameter}");
    }else{
      $_SESSION["ErrorMessage"]= "Somthing went wrong. Try Again !";
      Redirect_to("FullPost.php?id={$SearchQueryParameter}");
    }
  }
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://kit.fontawesome.com/430d75d6f7.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" href="Css/Styles.css">
  <link rel="stylesheet" href="Css/Styless.css">
  <title>Full Post Page</title>
<head>
<body>
  <!-- navbar -->
  <div Style="height:10px; background:#27aae1;"></div>

  </div>

  </div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">abc.com</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>

      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">

    <u1 class="navbar-nav mr-auto">

        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">About Us</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Contact Us</a>
        </li>
    </u1>
   <u1 class="navbar-nav ml-auto">
   <form class="form-inline d-none d-sm-block" action="Blog.php">
     <div class="form-group">
     <input class="form-control mr-2" type="text" name="Search" placeholder="Search here" value="">
     <button  class="btn btn-primary" name="SearchButton">Go</button>

     </div>
   </form>
   </u1>
  </div>
  </div>
  </nav>
    <div Style="height:10px; background:#27aae1;"></div>
    <!-- navbar end -->
    <!-- header -->
     <div class="container">
       <div class="row mt-4">
         <!-- main area start -->
         <div class="col-sm-8"  >
           <h1>The Complete Respons cms blog</h1>
           <h1 class="lead">The complete blog by using php by emon</h1>
           <?php
           echo ErrorMessage();
           echo SuccessMessage();
            ?>
           <?php
           global $ConnectingDB;
           //sql query when Search buutton is active
           if(isset($_GET["SearchButton"])){
            $Search = $_GET["Search"];
            $sql = "SELECT * FROM posts
            WHERE datetime LIKE :search
            OR title LIKE :search
            OR category LIKE :search
            OR post LIKE :search";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':search','%'.$Search.'%');
            $stmt->execute();
           }
           //the default sql query
           else {
             $PostIdFromURL = $_GET["id"];
             if (!isset($PostIdFromURL)) {
               $_SESSION["ErrorMessage"]="Bad Request !";
               Redirect_to("Blog.php");
             }
             $sql = "SELECT * FROM posts WHERE id= '$PostIdFromURL'";
             $stmt =$ConnectingDB->query($sql);
             $Result = $stmt->rowcount();
             if ($Result!=1){
               $_SESSION["ErrorMessage"]="Bad Request !";
               Redirect_to("Blog.php?page=1");
             }
           }
           while ($DataRows = $stmt->fetch()) {
             $PostId          = $DataRows["id"];
             $DateTime        = $DataRows["datetime"];
             $PostTitle       = $DataRows["title"];
             $Category        = $DataRows["category"];
             $Admin           = $DataRows["author"];
             $Image           = $DataRows["image"];
             $PostDescription = $DataRows["post"];
              ?>
           <div class="card">
             <img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height:450px;" class="img-fluid card-img-top"/>
             <div class="card-body">
               <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
               <small class="text-muted">Category: <span class="text-dark"> <a href="Blog.php?category=<?php echo htmlentities($Category); ?>"><?php echo htmlentities($Category); ?></a></span> & Written by <span class="text-dark"><a href="Profile.php?username=<?php echo htmlentities($Admin); ?>"> <?php echo htmlentities($Admin); ?></a></span> On <span class="text-dark"><?php echo htmlentities($DateTime); ?></span></small>

               <hr>
               <p class="card-text">
                 <?php echo nl2br($PostDescription); ?></p>

             </div>

           </div>
           <?php  } ?>
            <!-- Comments part start-->
            <!-- fetching existing Comments start-->
            <br>
            <span class="FieldInfo">Comments</span>
            <br><br>
            <?php
            global $ConnectingDB;
            $sql = "SELECT * FROM comments WHERE post_id='$SearchQueryParameter'";
            $stmt =$ConnectingDB->query($sql);
            while ($DataRows = $stmt->fetch()) {
              $CommentDate = $DataRows['datetime'];
              $CommenterName =$DataRows['name'];
              $CommentContent= $DataRows['comment'];
         ?>
          <div>
            <div class="media CommentBlock">
              <img class="d-block img-fluid align-self-start" src="images/comment.png" alt="" height="50px" width="50px">
              <div class="media-body ml-2">
                <h6 class="lead"><?php echo $CommenterName; ?></h6>
                <p class="small"><?php echo $CommentDate; ?></p>
                <p><?php echo $CommentContent; ?></p>
              </div>
            </div>
          </div>
          <hr>
          <?php } ?>

            <!-- fetching existing Comments end-->

           <div class="">
             <form class="" action="FullPost.php?id=<?php echo $SearchQueryParameter; ?>" method="post">
               <div class="card mb-3">
                 <div class="card-header">
                   <h5 class="FieldInfo">Share your thoughts about this post</h5>
                 </div>
                 <div class="card-body">
                   <div class="form-group">
                     <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                      </div>
                     <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">
                     </div>
                   </div>
                   <div class="form-group">
                     <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                      </div>
                     <input class="form-control" type="email" name="CommenterEmail" placeholder="Email" value="">
                     </div>
                   </div>
                   <div class="form-group">
                     <textarea name="CommenterThoughts" class="form-control" rows="6" cols="80"></textarea>
                   </div>
                   <div class="">
                     <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
                   </div>

                 </div>
               </div>
             </form>

           </div>
           <!-- Comments part end-->
         </div>
         <!-- main area end -->

         <!-- side area -->
         <div class="col-sm-4">
           <div class="card mt-4">
             <div class="card-body">
               <img src="images/startblog.png" class="d-block img-fluid mb-3" alt="">
               <div class="text-center">
                 Ads Price of the essential kitchen commodity shoots up by 60% in week The prices of onion, garlic and chicken have gone up again in the wet

               </div>
             </div>
           </div>
           <br>
           <div class="card">
             <div class="card-header bg-dark text-light">
               <h2 class="lead">Sing Up !</h2>
             </div>
             <div class="card-body">
               <button type="button" class="btn btn-success btn-block text-center text-white mb-4" name="button">Join the Forum</button>
               <button type="button" class="btn btn-danger btn-block text-center text-white mb-4" name="button">Login</button>
               <div class="input-group mb-3">
                 <input type="text" class="form-control" name="" placeholder="Enter your email" value="">
                 <div class="input-group-append">
                   <button type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribe Now</button>
                 </div>
               </div>
             </div>
           </div>
           <br>
           <div class="card">
             <div class="card-header bg-primary text-light">
               <h2 class="lead">Categories</h2>
               </div>
               <div class="card-body">
                 <?php
                 global $ConnectingDB;
                 $sql="SELECT * FROM category ORDER BY id desc";
                 $stmt=$ConnectingDB->query($sql);
                 while ($DataRows=$stmt->fetch()) {
                   $CategoryId=$DataRows["id"];
                   $CategoryName=$DataRows["title"];
                  ?>
                <a href="Blog.php?category=<?php echo $CategoryName; ?>"><span class="heading"> <?php echo $CategoryName; ?></span></a><br>
                  <?php } ?>
               </div>
             </div>
             <br>
             <div class="card">
               <div class="card-header bg-info text-white">
                 <h2 class="lead"> Recent Posts</h2>
               </div>
               <div class="card-body">
                 <?php
                 global $ConnectingDB;
                 $sql="SELECT * FROM Posts ORDER BY id desc LIMIT 0,5";
                 $stmt=$ConnectingDB->query($sql);
                 while ($DataRows=$stmt->fetch()) {
                   $Id       =$DataRows['id'];
                   $title    =$DataRows['title'];
                   $DateTime =$DataRows['datetime'];
                   $Image    =$DataRows['image'];

                  ?>
                 <div class="media">
                   <img src="Uploads/<?php echo htmlentities($Image); ?>" class="d-block img-fluid align-self-start" width="90" height="94" alt="">
                   <div class="media-body ml-2">
                     <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank"> <h6 class="lead"><?php echo htmlentities($title); ?></h6></a>
                     <p class="small"><?php echo htmlentities($DateTime); ?></p>
                   </div>
                 </div>
                 <hr>
                 <?php } ?>
               </div>
             </div>
           </div>
         <!-- side area end -->

       </div>

     </div>
    <!-- header end -->
 <br>
   <!-- Footer -->
   <footer class="bg-dark text-white">
     <div class="container">
       <div class="raw">
         <div class="col">
         <p class="lead text-center">Theme by | abc....|study paper|<span id="year"></span>&copy;...All right Reserved.</p>
        </div>
      </div>
    </div>
     <div Style="height:10px; background:#27aae1;"></div>
  </footer>

    <!-- Footer end -->


  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<script>
  $('#year').text(new Date().getFullYear());
</script>
<body>
</html>
