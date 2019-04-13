<?php
    require "DatabaseSystem.php";

    class LivingSession {
        function getAccID($userName, $passWord){
            $databaseConn = new DatabaseSystem();
            $mysqlConnection = $databaseConn->makeConnection();
            $userName = mysqli_real_escape_string($mysqlConnection, $userName);
            $passWord = mysqli_real_escape_string($mysqlConnection, $passWord);
            $authenticationSts = $databaseConn->authenticateAcc($userName, $passWord);

            if ($authenticationSts == false){
                mysqli_close($mysqlConnection);
                return false;
            } else {
                $query = "SELECT id FROM useracc WHERE userName = '$userName'";
                $querySts = mysqli_query($mysqlConnection, $query);
                if (!$querySts){
                    die('Query Failed !!!' . mysqli_error($mysqlConnection));   
                    mysqli_close($mysqlConnection);    
                    return false;
                }

                $row = mysqli_fetch_assoc($querySts);
                $userID = $row["id"];
                mysqli_close($mysqlConnection);
                return $userID;
            }
        }

        function newSession($userName, $passWord){
            session_start();
            $sessionID = $this->getAccID($userName, $passWord);
            $_SESSION["sessionID"] = $sessionID;
        }

        function killSession(){
            session_unset();
            session_destroy();
        }

        function setLivingtime($duration, $routingLocation){
            $lastAct = $_SESSION["last_act"];
            $remaindingTime = time() - $lastAct;
            if (isset($lastAct) && $remaindingTime > $duration){
                $this->killSession();
                header("Location: $routingLocation");
            }
        }
    }
?>