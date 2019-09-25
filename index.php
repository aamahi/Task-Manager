<?php 
include_once'config.php';
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(!$connection){
    throw new Exception("Cann't Connect to Database\n");
}
$query = "SELECT * FROM `tasks` WHERE complete=0 ORDER BY `date`";
$result = mysqli_query($connection,$query);

$completeTaskQuery = "SELECT * FROM `tasks` WHERE complete = 1 ORDER BY `date`";
$completeTaskResult = mysqli_query($connection,$completeTaskQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.css">
    <!-- <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css"> -->
    <title>Task Project</title>
    <style>
        body{
            margin-top:30px;
            color: #fff;
            background:black;
        }
        a{
            color:red;
        }
        input{
            color:#fff;
        }
        #main{
            padding:0 180px 0 180px;
        }
        #action{
            width:180px;
            color:red;
        }
        .h3{
            color: #9b4dca;
            text-align:center;
        }
    
    </style>
</head>
<body>
<div class="container" id ="main">
    <h1 class='h3' style="text-align:left;">Task Manager</h1>
    <p>This is a sample project for managing our daily tasks. I'm going to use HTML, CSS, PHP, JavaScript and MySQL
    for this project</p>   
    <?php 
    if(mysqli_num_rows($completeTaskResult)>0){
    ?>
        <h3 class='h3'>Complete Task</h3>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Task</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($cdata = mysqli_fetch_assoc($completeTaskResult)){
                    $timestamp = strtotime($cdata['date']);
                    $cdate = date("jS M, Y",$timestamp);
                ?>
                    <tr>
                        <td><input type="checkbox" value ="<?php echo $cdata['id']; ?>" class="label-inline"></td>
                        <td><?php echo $cdata['id']; ?></td>
                        <td><?php echo $cdata['tasks']; ?></td>
                        <td><?php echo $cdate;?></td> 
                        <td><a href="#"class="delete"data-taskid="<?php  echo $cdata['id']; ?>" >Delete</a> | 
                        <a href="#"class="incomplete" data-taskid="<?php echo $cdata['id']; ?>">Mark Incomplete</a></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>   
    <?php  
        }
    ?>
    <?php 
    if(mysqli_num_rows($result)==0){?>
        <h3 class="h3">No Tasks Found. </h3><?php
    }else{
    ?>
        <h3 class='h3'>Upcomeing Task</h3>
        <form action='tasks.php' method='post'>
        <table>form
            <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Task</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($data = mysqli_fetch_assoc($result)){
                    $timestamp = strtotime($data['date']);
                    $date = date("jS M, Y",$timestamp);
                ?>
                    <tr>
                        <td><input name="taskids[]" type="checkbox" value ="<?php echo $data['id']; ?>" class="label-inline"></td>
                        <td><?php echo $data['id']; ?></td>
                        <td><?php echo $data['tasks']; ?></td>
                        <td><?php echo $date;?></td> 
                        <td><a href="#"class="delete"data-taskid="<?php  echo $data['id']; ?>" >Delete</a> | 
                        <a href="#"class="complete" data-taskid="<?php echo $data['id']; ?>">Complete</a></td>
                    </tr>
                <?php
                    } 
                mysqli_close($connection);
                ?>
            </tbody>
        </table>
        <select id="action" name="action">
            <option value="0" >With Select</option>
            <option value="bulkdelete">Delete</option>
            <option value="bulkcomplete">Mark as Complete</option>
        </select>
        <input class='btn-primary' type="submit" value="submit" id='bulksubmit'>
        </form>
    <?php } ?>
    <hr>
    <!-- <p class='divider'>----------------------------------------------------------------------------------------------</p> -->
    <form action="tasks.php" method ='post'>
        <fieldset>
            <?php 
            $added = $_GET['added']?? '';
            if($added){
                echo '<h3 class="h3"}>Task Sucessfully Added</h3>';
            }

            ?>
            <label for="task">Task</label>
            <input type="text" id='task' placeholder="task Details" name='task'>
            <label for="date">Date</label>
            <input type="text" id='date' placeholder="Task Date" name='date'>
        <input type="submit" class='btn-primary' value='Add Task'>
        <input type="hidden" name="action" value='add'>
        </fieldset>
    </form>

    <form action="tasks.php"method ='post' id='completeform'>
        <input type="hidden" id='caction' name='action' value='complete'>
        <input type="hidden" id='taskid' name='taskid'>
    </form>
    <form action="tasks.php"method ='post' id='incompleteform'>
        <input type="hidden" id='caction' name='action' value='incomplete'>
        <input type="hidden" id='itaskid' name='taskid'>
    </form>
    <form action="tasks.php"method ='post' id='deleteform'>
        <input type="hidden" id='caction' name='action' value='delete'>
        <input type="hidden" id='dtaskid' name='taskid'>
    </form>
</div>
</body>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script>
    ;(function($){
        $(document).ready(function() {
            $(".complete").on('click',function(){
                var id = $(this).data("taskid");
                $("#taskid").val(id);
                $("#completeform").submit();
            })
            $(".incomplete").on('click',function(){
                var id = $(this).data("taskid");
                $("#itaskid").val(id);
                $("#incompleteform").submit();
            })
            $(".delete").on('click',function(){
                if(confirm("Are Your sure to delete this task ?")){
                    var id = $(this).data("taskid");
                    $("#dtaskid").val(id);
                    $("#deleteform").submit();
                }
            })
            $("#bulksubmit").on('click',function(){
                if($("#action").val()=="bulkdelete"){
                    if(confirm("Are Your sure to delete this task ?")){
                        return ture;
                    }
                }
            })
         });
    })(jQuery)
</script>


</html>