<?php
    require "DatabaseSystem.php";

    class PremiumService {
        function serviceConnection(){
            $connection = new DatabaseSystem();
            $databaseConn = $connection->makeConnection();
            return $databaseConn;
        }

        function getLivingPeriod($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $query = "SELECT * FROM premiumService ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                $row = mysqli_fetch_assoc($querySts);
                $livingPeriod = $row["livingPeriod"];
                mysqli_close($databaseConn);
                return $livingPeriod;
            }
        }

        function serviceKey_exists($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $query = "SELECT * FROM premiumService ";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                while ($row = mysqli_fetch_assoc($querySts)){
                    $databaseServKey = $row["serviceKey"];
                    if ($serviceKey == $databaseServKey){
                        mysqli_close($databaseConn);
                        return true;
                    }
                }
            }
            mysqli_close($databaseConn);
            return false;
        }

        function makeServiceKey($serviceKey, $maxCapacity, $livingPeriod){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);
            // This is table initial properties 
            $clientCounter = 0;
            $keyStatus = 0;

            $keySts = $this->serviceKey_exists($serviceKey);
            if (!$keySts){
                if ($serviceKey != ''){
                    $query = "INSERT INTO premiumService(serviceKey, clientCounter, maxCapacity, assignedTime, livingPeriod, keyStatus) ";
                    $query .= "VALUES ('$serviceKey', '$clientCounter', '$maxCapacity', CURRENT_TIMESTAMP, '$livingPeriod', '$keyStatus')";
                    $querySts = mysqli_query($databaseConn, $query);
    
                    if (!$querySts){
                        die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                        mysqli_close($databaseConn);
                        return false;
                    } else {
                        $this->activateKey($serviceKey);
                        mysqli_close($databaseConn);
                        return true;
                    }
                }
                else {
                    mysqli_close($databaseConn);
                    return false;
                }
            }
            else {
                mysqli_close($databaseConn);
                return false;
            }
        } 

        function removeServiceKey($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);
            $keySts = $this->serviceKey_exists($serviceKey);
            if ($keySts){
                $query = "DELETE FROM premiumService ";
                $query .= "WHERE serviceKey = '$serviceKey'";
                $querySts = mysqli_query($databaseConn, $query);

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

        function activateKey($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $keyStatus = 1;
            $query = "UPDATE premiumService SET ";
            $query .= "keyStatus = '$keyStatus' ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                mysqli_close($databaseConn);
                return true;
            }            
        }

        function deactivateKey($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $keyStatus = 0;
            $query = "UPDATE premiumService SET ";
            $query .= "keyStatus = '$keyStatus' ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                mysqli_close($databaseConn);
                return true;
            }  
        }

        function getMaxCapacity($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $query = "SELECT * FROM premiumService ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return -1;
            } else {
                $row = mysqli_fetch_assoc($querySts);
                $maxCapacity = $row["maxCapacity"];
                mysqli_close($databaseConn);
                return $maxCapacity;
            }  
        }

        function getClientCounter($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $query = "SELECT * FROM premiumService ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return -1;
            } else {
                $row = mysqli_fetch_assoc($querySts);
                $clientCounter = $row["clientCounter"];
                $maxCapacity = $this->getMaxCapacity($serviceKey);
                if ($maxCapacity != -1 && $maxCapacity > $clientCounter){
                    mysqli_close($databaseConn);
                    return $clientCounter;
                } else {
                    mysqli_close($databaseConn);
                    return -1;
                }
            }  
        }

        function clientUse($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);
            $keySts = $this->serviceKey_exists($serviceKey);
            if (!$keySts){
                mysqli_close($databaseConn);
                return false;
            } else {
                $clientCounter = $this->getClientCounter($serviceKey);
                if ($clientCounter != -1){
                    $clientCounter = intval($clientCounter);
                    $clientCounter = $clientCounter + 1;
                    $query = "UPDATE premiumService SET ";
                    $query .= "clientCounter = '$clientCounter' ";
                    $query .= "WHERE serviceKey = '$serviceKey'";
                    $querySts = mysqli_query($databaseConn, $query);

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
        }

        function checkServicePeriod($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $query = "SELECT * FROM premiumService ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                $livingPeriod = $this->getLivingPeriod($serviceKey); 
                $row = mysqli_fetch_assoc($querySts);
                $activatedTime = $row["assignedTime"];
                $activatedTime = strtotime($activatedTime);
                $currentTime = date("Y-m-d H:i:s");
                $currentTime = strtotime($currentTime);
                $remaindingTime = $currentTime - $activatedTime - 21600;

                if ($remaindingTime > $livingPeriod){
                    $this->deactivateKey($serviceKey);
                    mysqli_close($databaseConn);
                    return false;
                } else {
                    $this->activateKey($serviceKey);
                    mysqli_close($databaseConn);
                    return true;
                }
            } 
        } 

        function member_exist($userName){
            $databaseConn = $this->serviceConnection();
            $userName = mysqli_real_escape_string($databaseConn, $userName);

            $query = "SELECT * FROM premiumMembership ";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$querySts){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                while ($row = mysqli_fetch_assoc($querySts)){
                    $databseUsrName = $row["userName"];
                    if ($userName == $databseUsrName){
                        mysqli_close($databaseConn);
                        return true;
                    }
                }
                mysqli_close($databaseConn);
                return false;
            }  
        }

        function checkServiceKeySts($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $keySts = $this->serviceKey_exists($serviceKey);
            if ($keySts){
                $query = "SELECT * FROM premiumService ";
                $query .= "WHERE serviceKey = '$serviceKey'";
                $querySts = mysqli_query($databaseConn, $query);

                if (!$querySts){
                    die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                    mysqli_close($databaseConn);
                    return false;
                } else {
                    $row = mysqli_fetch_assoc($querySts);
                    $clientCounter = $row["clientCounter"];
                    $maxCapacity = $row["maxCapacity"];
                    $keyStatus = $row["keyStatus"];
                    $servicePeriod = $this->checkServicePeriod($serviceKey);

                    if (!$servicePeriod){
                        $this->removeServiceKey($serviceKey);
                        $this->removePremiumMember($serviceKey);
                        mysqli_close($databaseConn);
                        return false;
                    } else {
                        if ($maxCapacity > $clientCounter && $keyStatus == 1){
                            mysqli_close($databaseConn);
                            return true;
                        } else {
                            mysqli_close($databaseConn);
                            return false;
                        }
                    }
                }
            } else {
                mysqli_close($databaseConn);
                return false;
            }
        }

        function removePremiumMember($serviceKey){
            $databaseConn = $this->serviceConnection();
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $query = "DELETE FROM premiumMembership ";
            $query .= "WHERE serviceKey = '$serviceKey'";
            $querySts = mysqli_query($databaseConn, $query);

            if (!$query){
                die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                mysqli_close($databaseConn);
                return false;
            } else {
                mysqli_close($databaseConn);
                return true;
            }
        }

        function registerMember($userName, $passWord, $serviceKey){
            $databaseConn = $this->serviceConnection();
            $userName = mysqli_real_escape_string($databaseConn, $userName);
            $passWord = mysqli_real_escape_string($databaseConn, $passWord);
            $serviceKey = mysqli_real_escape_string($databaseConn, $serviceKey);

            $userAuthenticate = new DatabaseSystem();
            $authenticateSts = $userAuthenticate->authenticateAcc($userName, $passWord);
            $servicePeriod = $this->checkServiceKeySts($serviceKey);
            
            if ($authenticateSts && $servicePeriod){
                $memberSts = $this->member_exist($userName);
                if (!$memberSts){
                    $query = "INSERT INTO premiumMembership(userName, serviceKey) ";
                    $query .= "VALUES ('$userName', '$serviceKey')";
                    $querySts = mysqli_query($databaseConn, $query);

                    if (!$querySts){
                        die("<p><b>Query Failed !!!</b></p>" . mysqli_error($databaseConn));
                        mysqli_close($databaseConn);
                        return false;
                    } else {
                        $this->clientUse($serviceKey);
                        mysqli_close($databaseConn);
                        return true;
                    }
                } else {
                    mysqli_close($databaseConn);
                    return false;
                }
            } else {
                mysqli_close($databaseConn);
                return false;
            }
        }
    }
?>