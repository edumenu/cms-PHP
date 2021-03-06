<?php include "includes/db.php";?>
<?php include "includes/header.php";?>
   
   
        <!-- Navigation  -->   
<?php include "includes/navigation.php";?>
   
   
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
              
            <div class="col-md-8">
                
                <?php
                
                //Retrieving the post's ID number using the get request
                if(isset($_GET['p_id'])){
                    
                    //$the_post_id = escape($_GET['p_id']);
                    $the_post_id = mysqli_real_escape_string($connection, $_GET['p_id']);
                    $the_post_author = mysqli_real_escape_string($connection, $_GET['p_author']);
                    
                }
                
                $stmt = mysqli_prepare($connection, "SELECT post_title, post_user, post_date, post_image, post_content FROM posts WHERE post_user = ?");
                mysqli_stmt_bind_param($stmt, "s", $the_post_author);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $post_title, $post_author, $post_date, $post_image, $post_content);

                confirmQuery($stmt);
                  
                //Print out data obtained from posts database
                //this function fetches a row from the database as 
                //associative array
                while(mysqli_stmt_fetch($stmt)){

                ?>

                <!-- First Blog Post -->
                <h2>
                   <!-- The title of the post -->
                     <a href="post.php?p_id=<?php echo $the_post_id; ?>"><?php echo $post_title?></a>
                </h2>
                <p class="lead">All post by: 
                <!-- The author of the post -->
                <?php echo $post_author?>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date?></p> <!-- The date of the post -->
                <hr>
                <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                <hr>
                <!-- The content of the post -->
                <p><?php echo $post_content?></p>

                <hr>
                   
            <?php  } ?>
            
             <!-- Blog Comments -->
             
                <?php
                //Creating comments for each post
                if(isset($_POST['create_comment'])){
                    
                    
                     $the_post_id = mysqli_real_escape_string($connection, $_GET['p_id']); 
                    
                    $comment_author = $_POST['comment_author'];
                    $comment_email = $_POST['comment_email'];
                    $comment_content = $_POST['comment_content'];
                    
                    
                    if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                        
                        
                    $query = "INSERT INTO comments (comment_post_id,comment_author,comment_email,comment_content, comment_status, comment_date) ";
                    
                    $query .= "VALUES ($the_post_id,'{$comment_author}','{$comment_email}','{$comment_content}', 'Unapproved', now())";
                    
                    $create_comment_query = mysqli_query($connection,$query);
                    
                    if(!$create_comment_query){
                        
                        die('QUERY FAILED' . mysqli_error($connection));
                    }  
                    
                //This increments the number comments in the db
                 $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                 $query .= "WHERE post_id = $the_post_id ";
                    
                 $update_comment_count = mysqli_query($connection,$query);
                    
                    
                }else{
                       
                    echo "<script> alert ('Fields cannot be empty!') </script>";
                        
                    }
                        
                        
                    }
                    
                ?>

            </div>
            
            
        <!-- Blog sidebar widgets columns -->
            <?php include "includes/sidebar.php"?>

        </div>
        <!-- /.row -->

        <hr>
<?php include "includes/footer.php"; ?>
