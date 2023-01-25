<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Deleter</title>

</head>
<!-- links complementary programs -->
<body><a href='taskManager.php'>Manage tasks here.</a>
<body><a href='taskRegister.php'>Register tasks here.</a>
<?php 
// sets the time zone to eastern
date_default_timezone_set('America/New_York');
header('Content-Type: text/html; charset=utf-8');

// connection

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// gets input data and deletes a task that matches inputs
$taskNickname = $_POST['taskNickname'];
$taskOwner = $_POST['taskOwner'];
$sql = "DELETE FROM taskManagerTasks WHERE Task = '$taskNickname' AND taskOwner = '$taskOwner';";
mysqli_query($conn, $sql);

?>

<!-- form for inputs -->
<form id="myForm" method='post' action='taskDeleter.php'>
    <label for="taskNicknameInput">Task nickname (only one word), of the task you want to delete.</label>
    <input type='text' name='taskNickname' id='taskNicknameInput'>
    <label for="taskOnerInput">Task owner's username(no spaces)</label>
    <input type='text' name='taskOwner' id="taskOwnerInput" >
    <input type="submit" id="submit">
    </form>
</body>
</html>