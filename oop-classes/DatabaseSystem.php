<?php
require "DataHiding.php";
    // This script contain functions to work and manage the database 
    class DatabaseSystem {
        // This function will make a connection with the database server 
        function makeConnection(){
            $hostName = 'localhost';
            $databaseUsername = 'root';
            $databasePassword = 'adminRoot';
            $tableName = 'userlogin';
            $databaseConn = mysqli_connect($hostName, $databaseUsername, $databasePassword, $tableName);
            if (!$databaseConn){
                die("<p><b>Connection To Database Failed !!!</b></p>" . mysqli_error($databaseConn));
                return false;
            }
            return $databaseConn;
        }

        function queryUsername($userName){
            $databaseConn = $this->makeConnection();
            $userName = mysqli_real_escape_string($databaseConn, $userName);
            $queryCmd = "SELECT * FROM useracc";
            $querySts = mysqli_query($databaseConn, $queryCmd);

            if (!$querySts){
                die('Query Failed !!!' . mysqli_error($databaseConn));            
            }

            while ($row = mysqli_fetch_assoc($querySts)){
                $databaseUsrName = $row['userName'];
                if ($userName == $databaseUsrName){
                    mysqli_close($databaseConn);
                    return true;
                }
            }
            mysqli_close($databaseConn);
            return false;            
        }

        function encyptPassword($passWord){
            $encryptingType = "oneway";
            $action = "encrypt";
            $openKey = "adminRoot";

            $encryptedPassword = new DataHiding();
            $passWord = $encryptedPassword -> encryptData($encryptingType, $action, $passWord, $openKey);
            return $passWord;
        }

        function registerNewAccount($fullName, $userName, $passWord){
            // Administrator use only
            $databaseConn = $this->makeConnection();
            $fullName = mysqli_real_escape_string($databaseConn, $fullName);
            $userName = mysqli_real_escape_string($databaseConn, $userName);
            $passWord = mysqli_real_escape_string($databaseConn, $passWord);
            $passWord = $this->encyptPassword($passWord);

            $userNameExist = $this->queryUsername($userName);
            if ($userNameExist == true){
                mysqli_close($databaseConn);
                return false;
            } else {
                $queryCmd = "INSERT INTO useracc(fullName, userName, passWord) ";
                $queryCmd .= "VALUES ('$fullName', '$userName', '$passWord')";
                $querySts = mysqli_query($databaseConn, $queryCmd);

                if (!$querySts){
                    die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                    mysqli_close($databaseConn);
                    return false;
                } else {
                    mysqli_close($databaseConn);
                    return true;
                }
            }
        }

        function authenticateAcc($userName, $passWord){
            $databaseConn = $this->makeConnection();
            $userName = mysqli_real_escape_string($databaseConn, $userName);
            $passWord = mysqli_real_escape_string($databaseConn, $passWord);
            $passWord = $this->encyptPassword($passWord);

            $queryCmd = "SELECT * FROM useracc ";
            $queryCmd .= "WHERE userName = '$userName'";

            $querySts = mysqli_query($databaseConn, $queryCmd);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                $row = mysqli_fetch_assoc($querySts);
                $databaseUsrName = $row['userName'];
                $databasePassword = $row['passWord'];
                if ($userName == $databaseUsrName && $passWord == $databasePassword){
                    mysqli_close($databaseConn);
                    return true;
                } else {
                    mysqli_close($databaseConn);
                    return false;
                }
            }
        }

        function resetPassword($userName, $newPassword){
            // Administrator right only
            $databaseConn = $this->makeConnection();
            $userName = mysqli_real_escape_string($databaseConn, $userName);
            $newPassword = mysqli_real_escape_string($databaseConn, $newPassword);
            $newPassword = $this->encyptPassword($newPassword);

            $queryCmd = "UPDATE useracc SET ";
            $queryCmd .= "passWord = '$newPassword' ";
            $queryCmd .= "WHERE userName = '$userName'";

            $querySts = mysqli_query($databaseConn, $queryCmd);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                mysqli_close($databaseConn);
                return true;
            }
        } 

        function deleteAcc($userName, $passWord){
            $databaseConn = $this->makeConnection();
            $userName = mysqli_real_escape_string($databaseConn, $userName);
            $passWord = mysqli_real_escape_string($databaseConn, $passWord);
            
            $authenticationSts = $this->authenticateAcc($userName, $passWord);

            if ($authenticationSts == true){
                $queryCmd = "DELETE FROM useracc ";
                $queryCmd .= "WHERE userName = '$userName'";

                $querySts = mysqli_query($databaseConn, $queryCmd);
                if (!$querySts){
                    die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                    mysqli_close($databaseConn);
                    return false;
                } else {
                    mysqli_close($databaseConn);
                    return true;
                }
            } else {
                mysqli_close($databaseConn);
                return false;
            }
        }
    }
?>