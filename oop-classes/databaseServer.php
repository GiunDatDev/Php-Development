<?php
    // User session
    function getUserID($userName, $passWord){
        $databaseConn = makeConnection();
        $userName = mysqli_real_escape_string($databaseConn, $userName);
        $passWord = mysqli_real_escape_string($databaseConn, $passWord);
        $passWord = encryptInfo($passWord);
        $userName = dataHiding('encrypt', $userName, $passWord);

        $query = "SELECT id FROM useracc WHERE userName = '$userName'";
        $querySts = mysqli_query($databaseConn, $query);

        if (!$querySts){
            die('Query Failed !!!' . mysqli_error($databaseConn));            
            return -1;
        }
            
        $row = mysqli_fetch_assoc($querySts);
        return $row["id"];
    }
    function registerUserSession($userName, $passWord){
        $userID = getUserID($userName, $passWord);
        $_SESSION["userID"] = $userID;
    }

    function resetSession(){
        session_unset();
        session_destroy();
    }

    function timeOutSession($duration){
        $lastAct = $_SESSION["last_act"];
        $remaindingTime = time() - $lastAct;
        if (isset($lastAct) && $remaindingTime > $duration){
            resetSession();
            header("Location: login.php");
        }
    }
?>