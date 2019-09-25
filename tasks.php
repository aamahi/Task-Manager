<?php
include 'config.php';
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(!$connection){
    throw new Exception("Cann't Connect to Database\n");
}else{
    $action = $_POST['action'] ?? '';
    if(!$action){
        header('Location: index.php');
        die();
    }else{
        if("add"==$action){
            $task = $_POST['task'];
            $date = $_POST['date'];
            if($task && $date){
                $query = "INSERT INTO ". DB_TABLE ."(tasks,date) VALUES('{$task}' , '{$date}')";
                mysqli_query($connection,$query);
                header("Location:index.php?added=true");
                echo $query;
            }
        }elseif("complete"==$action){
            $taskid = $_POST['taskid'];
           if($taskid){
            $query = "UPDATE `tasks` SET `complete`=1 WHERE `id`= ${taskid} LIMIT 1";
            mysqli_query($connection,$query);
           }
           header("Location:index.php");
        }elseif("incomplete"==$action){
            $taskid = $_POST['taskid'];
           if($taskid){
            $query = "UPDATE `tasks` SET `complete`=0 WHERE `id`= ${taskid} LIMIT 1";
            mysqli_query($connection,$query);
           }
           header("Location:index.php");
        }elseif("delete"==$action){
            $taskid = $_POST['taskid'];
           if($taskid){
            $query = "DELETE FROM `tasks` WHERE `tasks`.`id`= ${taskid} LIMIT 1";
            mysqli_query($connection,$query);
           }
           header("Location:index.php");
        }elseif("bulkcomplete"==$action){
            $taskids = $_POST['taskids'];
            $_taskids = join(",",$taskids);
           if($taskids){
            $query = "UPDATE `tasks` SET `complete`=1 WHERE `id` IN ({$_taskids})";
            mysqli_query($connection,$query);
           }
           header("Location:index.php");
        }elseif("bulkdelete"==$action){
            $taskids = $_POST['taskids'];
            $_taskids = join(",",$taskids);
           if($taskids){
            $query = "DELETE FROM `tasks` WHERE `tasks`.`id` IN ({$_taskids})";
            mysqli_query($connection,$query);
            
           }
           header("Location:index.php");
        }
    }
}