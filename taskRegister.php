<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Register</title>

</head>
<!-- links complentary programs -->
<body><a href='https://foodintorg.com/Nazar/Task%20Manager/taskManager.php' target="blank">Manage tasks here.</a>
<body><a href='https://foodintorg.com/Nazar/Task%20Manager/taskDeleter.php' target="blank">Delete a task here.</a>
<?php 
 // eastern time set
date_default_timezone_set('America/New_York');


$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// input receive
$task = $_POST['task'];
$taskText = $_POST['taskText'];
$frequency = $_POST['frequency'];
$taskOwner = $_POST['taskOwner'];
// adds a new task based on parameters
if($task !== null && $taskText !== null && $frequency!== null){
$sql = "INSERT INTO taskManagerTasks (Task, Frequency, taskText, taskOwner) VALUES ('$task', '$frequency', '$taskText', '$taskOwner');";
mysqli_query($conn, $sql);}    
?>

<!-- form for inputs -->
<form id="myForm" method='post' action='https://foodintorg.com/Nazar/Task%20Manager/taskRegister.php'>
    <label for="taskInput">Task nickname (no spaces)</label>
    <input type='text' name='task' id='taskInput'>
    <label for="taskTextInput">Task text (text to display on the task manager)</label>
    <input type='text' name='taskText' id="taskTextInput" >
    <label for="frequencyInput">Frequency</label>
    <select name="frequency" id="frequencyInput">
        <option value="Daily" >Daily</option>
        <option value="Weekly">Weekly</option>
        <option value="Once">Once</option>
    </select>
    <label for="taskOnerInput">Task owner's username (no spaces)</label>
    <input type='text' name='taskOwner' id="taskOwnerInput" >
    <input type='submit' id='submit'>
    </form>
</body>
</html>