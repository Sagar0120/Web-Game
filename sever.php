<?php
$db_host="localhost";
$db_user="root";
$db_password="";
    
$lnk = mysqli_connect($db_host, $db_user, $db_password);
if(!$lnk)
    die("Data base connection failed !!");
mysqli_select_db($lnk, "puzzlegame") or die("Data base connection failed !!");

if(isset($_GET["info"])){
    $info=json_decode($_GET["info"], true);
    if(addScore($info, $lnk)){
        echo "Success";
    }else{
        echo "Failed!!";
    }
}else{
    $result=getAllScores($lnk);
    echo json_encode($result);
}

function addScore($info, $lnk){
    $query="INSERT INTO Scores (Name, Time, Difficulty) VALUES".
        "('".$info["name"]."',".$info["time"].",'".
        $info["difficulty"]."')";
    $rs=mysqli_query($lnk, $query);
    if(!$rs){
        return false;
    }
    return true;
}

function getAllScores($lnk){
    $easy = getScoresByDefficulty("Easy", $lnk);
    $medium = getScoresByDefficulty("Medium", $lnk);
    $hard = getScoresByDefficulty("Hard", $lnk);
    $insane = getScoresByDefficulty("Insane", $lnk);
    return array("Easy"=>$easy, "Medium"=>$medium, "Hard"=>$hard, "Insane"=>$insane);
}

function getScoresByDefficulty($Difficulty, $lnk){
    $query = "Select Name, Time FROM Scores".
     " WHERE Difficulty Like '".$Difficulty."'".
     " ORDER BY Time";

    $rs=mysqli_query($lnk, $query);

    $results=array();
    if(mysqli_num_rows($rs) > 0){
        while($row=mysqli_fetch_assoc($rs)){
            array_push($results, $row);
        }
    }
    return $results;
}

?>