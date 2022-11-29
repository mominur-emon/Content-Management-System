<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php");?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//echo $_SESSION["TrackingURL"];
Confirm_Login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://kit.fontawesome.com/430d75d6f7.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" href="Css/Styles.css">
  <title>Posts</title>
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
          <a href="MyProfile.php" class="nav-link"><i class="fas fa-user text-success"></i> My Profile</a>
        </li>
        <li class="nav-item">
          <a href="Dashboard.php" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="Posts.php" class="nav-link">Posts</a>
        </li>
        <li class="nav-item">
          <a href="Categories.php" class="nav-link">Categories</a>
        </li>
        <li class="nav-item">
          <a href="Admins.php" class="nav-link">Manage Admins</a>
        </li>

        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link">Live Blogs</a>
        </li>
    </u1>
   <u1 class="navbar-nav ml-auto">

     <li class="nav-item"><a href="Logout.php" class="nav-link text-danger">
       <i class="fa fa-user-times"></i> Logout</a></li>
   </u1>
      </div>
    </div>
  </nav>
    <div Style="height:10px; background:#27aae1;"></div>
    <!-- navbar end -->
    <!-- header -->
    <header class="bg-dark text-white py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
        <h1><i class="fas fa-blog" Style="color:#27aae1;"></i> Blog Posts </h1>
        </div>
        <div class="col-lg-4 mb-2">
          <a href="AddNewPost.php" class="btn btn-primary btn-block">
          <i class="fas fa-edit"></i> Add New Post
          </a>
        </div>
        <div class="col-lg-4 mb-2">
          <a href="Categories.php" class="btn btn-info btn-block">
          <i class="fas fa-folder-plus"></i> Add New Category
          </a>
        </div>
        <div class="col-lg-4 mb-2">
          <a href="Admins.php" class="btn btn-warning btn-block">
          <i class="fas fa-user-plus"></i> Add New Admin
          </a>
        </div>


      </div>
    </div>
    </header>
    <!-- header end -->

    <!-- main area -->
    <section class="container py-2 mb-4">
      <div class="row">
        <div class="col-lg-12">
          <?php
          echo ErrorMessage();
          echo SuccessMessage();
           ?>
          <table class="table table-striped table-hover">
           <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Category</th>
              <th>Date&Time</th>
              <th>Author</th>
              <th>Banner</th>
              <th>Comments</th>
              <th>Action</th>
              <th>Live Preview</th>
            </tr>
          </thead>
            <?php
             global $ConnectingDB;
             $sql  = "SELECT * FROM posts";
             $stmt = $ConnectingDB->query($sql);
             $Sr = 0;
             while ($DataRows = $stmt->fetch()) {
               $Id        = $DataRows["id"];
               $DateTime  = $DataRows["datetime"];
               $PostTitle = $DataRows["title"];
               $Category  = $DataRows["category"];
               $Admin     = $DataRows["author"];
               $Image     = $DataRows["image"];
               $PostText  = $DataRows["post"];
               $Sr++;
             ?>
            <tbody>
             <tr>
               <td><?php echo $Sr; ?></td>
               <td>
                <?php
                 if(strlen($PostTitle)>15) {$PostTitle= substr($PostTitle,0,15).'..';}
                 echo $PostTitle;
                ?>
               </td>
               <td>
                 <?php
                 if(strlen($Category)>10) {$Category= substr($Category,0,10).'..';}
                 echo $Category;
                 ?>
               </td>
               <td>
                 <?php
                 if(strlen($DateTime)>11) {$DateTime= substr($DateTime,0,11).'..';}
                 echo $DateTime;
                 ?>
               </td>
               <td>
                 <?php
                  if(strlen($Admin)>6) {$Admin= substr($Admine,0,6).'..';}
                  echo $Admin;
                  ?>
               </td>
               <td><img src="Uploads/<?php echo $Image; ?>" width="120px;" height="45px;"</td>
               <td>
                   <?php $Total = ApproveCommentsAccordingtoPost($Id);
                   if($Total>0){
                     ?>
                     <span class="badge badge-success">
                       <?php
                     echo $Total; ?>
                   </span>
                  <?php } ?>
               </td>
               <td>
                 <a href="EditPost.php?id=<?php echo $Id; ?>"><span class="btn btn-warning">Edit</span> </a>
                 <a href="DeletePost.php?id=<?php echo $Id; ?>"><span class="btn btn-danger">Delete</span> </a>
               </td>
               <td>
                 <a href="FullPost.php?id=<?php echo $Id; ?>" target="_blank"><span class="btn btn-primary">Live Preview</span> </a>
               </td>
             </tr>
           </tbody>
             <?php  } ?>
          </table>

        </div>

      </div>

    </section>
    <!-- main area end -->


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
