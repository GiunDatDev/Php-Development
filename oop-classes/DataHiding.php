<?php
    class DataHiding {
        function encryptData($encryptionType, $action, $data, $secret_key) {
            if ($encryptionType == 'twoway'){
                $output = false;
                $encrypt_method = "AES-256-CBC";
                $secret_iv = 'adminRoot9500H$a';
                // hash
                $key = hash('sha3-512', $secret_key);
                
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv = substr(hash('sha3-512', $secret_iv), 0, 16);
                if ( $action == 'encrypt' ) {
                    $output = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
                    $output = base64_encode($output);
                    return $output;
                } else if( $action == 'decrypt' ) {
                    $output = openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);
                    if ($output == ''){
                        return -1;
                    } else {
                        return $output;
                    }
                }
            }
            else if ($encryptionType == 'oneway'){
                $data = crypt($data, '$6$rounds=5000$encryptpasswordmakestrong$');
                return $data;
            }
        }
    }
?>
