<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>

</head>
<body>
<!-- links to programs that complement the task manager -->
<a href='https://foodintorg.com/Nazar/Task%20Manager/taskRegister.php'>Register a task here.</a>
<a href='https://foodintorg.com/Nazar/Task%20Manager/taskDeleter.php'>Delete a task here.</a>
<a href='https://foodintorg.com/Nazar/Task%20Manager/taskManager.php'>Check another user's tasks.</a>
<?php 
//sets the timezone to eastern
date_default_timezone_set('America/New_York');
header('Content-Type: text/html; charset=utf-8');


// connects to the database

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

//starts the main parts of the program if the page receives the task owner information otherwise sends you a default message
$taskOwner = $_POST['taskOwner'];
if(isset($taskOwner) && !empty($taskOwner)){
// empty array for tasks
$tasks = array();

// gets the number of tasks the user has
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM taskManagerTasks WHERE taskOwner = '$taskOwner'");
$data = mysqli_fetch_assoc($result);
$num_rows = $data['total'];
mysqli_free_result($result);

// gets all the users task and their information such as completion status and frequency
$result = mysqli_query($conn, "SELECT * FROM taskManagerTasks WHERE taskOwner = '$taskOwner'");
$tasksDB = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

// loops through each of the tasks and operates on them
for($i = 0; $num_rows > $i; $i++){
    
// makes the array easier for me
    $received = $_POST[$tasksDB[$i]['Task']];
    array_push($tasks, [$received, $tasksDB[$i]['Task'], $tasksDB[$i]['taskText'], $tasksDB[$i]['Frequency']]);
    
// gets the completion of the task    
    if($tasks[$i][0] !== 'yes'){
$sql = "SELECT Completion FROM taskManagerTasks WHERE Task = '{$tasks[$i][1]}' AND taskOwner = '$taskOwner';";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$tasks[$i][0] = $row['Completion'];
}

// if the task is complete  the completion status in the database is set to yes
if($tasks[$i][0] == 'yes'){
$sql = "UPDATE taskManagerTasks SET Completion = 'yes' WHERE Task = '{$tasks[$i][1]}' AND taskOwner = '$taskOwner';";
mysqli_query($conn, $sql);    
}

// if the task is neither complete or incomplete(it was just registered) it gets assigned the value of no to its completion
if($tasks[$i][0] !== 'yes' && $tasks[$i][0] !== 'no'){
$sql = "UPDATE taskManagerTasks SET Completion = 'no' WHERE Task = '{$tasks[$i][1]}' AND taskOwner = '$taskOwner';";
mysqli_query($conn, $sql);    
}
// deletes a one time task from the database if it has been completed
if($tasks[$i][0] == 'yes'){
$sql = "DELETE FROM taskManagerTasks WHERE Task = '{$tasks[$i][1]}' AND taskOwner = '$taskOwner' AND Frequency = 'Once';";
mysqli_query($conn, $sql);    
}
}

// makes a time zone variable
$timezone = new DateTimeZone('America/New_York');

// gets the day tasks were last manipulated by the user
$sql = "SELECT Day FROM taskManagerDate";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$dbDateDay = new DateTime($row['Day'], $timezone);
$dbDateDay = new DateTime($dbDateDay->format('Y-m-d'), $timezone);
mysqli_free_result($result); 

// gets the day the last time Week was updated
$sql = "SELECT Week FROM taskManagerDate";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$dbDateWeek = new DateTime($row['Week'], $timezone);
$dbDateWeek = new DateTime($dbDateWeek->format('Y-m-d'), $timezone);
mysqli_free_result($result);
// gets todays date and the time difference between today's date and recorded dates
$todayDate = new DateTime('now', $timezone);
$intervalWeekly = $dbDateWeek->diff($todayDate);
$intervalDaily = $dbDateDay->diff($todayDate);

// if more than a day has passed all daily tasks get reset
if ($intervalDaily->days >= 1) {
    $sql = "UPDATE taskManagerDate SET Day = '{$todayDate->format('Y-m-d')}' WHERE 1 = 1 ;";
    mysqli_query($conn, $sql);
    
    $sql = "UPDATE taskManagerTasks SET Completion = 'no' WHERE Frequency = 'Daily';";
    $result = mysqli_query($conn, $sql);
    $affectedRows = mysqli_affected_rows($conn);
}

//if more than a week has passed all Weekly tasks get reset
if ($intervalWeekly->days >= 7) {
    $sql = "UPDATE taskManagerDate SET Week = '{$todayDate->format('Y-m-d')}' WHERE 1 = 1;";
    mysqli_query($conn, $sql);
    
    $sql = "UPDATE taskManagerTasks SET Completion = 'no' WHERE Frequency = 'Weekly';";
    
     mysqli_query($conn, $sql);
    
    
}

// echoes a form that takes different appearances and functions based on user inputs
    echo '<h1>Tasks :</h1>';
    echo "<form id='myForm' method='post' action='taskManager.php'>";
    
    if($num_rows == 0){
       echo '<p>This user has no tasks, go register some</p> <br>';
    }else{
            for($i = 0; $num_rows > $i; $i++){
    if ($tasks[$i][0] !== 'yes') { echo "<div id={$tasks[$i][1]}>
    <label>{$tasks[$i][2]}</label><br>
    <input class='checkbox' type='checkbox' name={$tasks[$i][1]} value='yes'>
    <input class='hidden' type='hidden' name={$tasks[$i][1]} value='no'>
    </div>"; } else if ($tasks[$i][0] == 'yes'){echo "<div id='{$tasks[$i][1]}'><p>{$tasks[$i][2]}, completed.</p></div>";}
    }  
    
    }
echo '<input type="hidden" id="inputTaskOwner" name="taskOwner" value= "'.$taskOwner.'">';
echo '<input type="submit" id="submit">';
echo '</form>';
}
else{
    echo '
    <form id="otherForm" method="post" action="taskManager.php">
    <label for="inputTaskOwner">Insert whose tasks to show. I you are\'nt a user then go register some tasks under your username</label>
    <input type="text" id="inputTaskOwner" name="taskOwner">
     <input type="submit" id="submit">
     </form>';
}
     ?>
<!-- links a js file that makes the checkboxes that were unchecked still send a value (No) -->
<script src="index.js"> </script>
</body>
</html>