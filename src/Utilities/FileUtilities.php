<?php


namespace App\Utilities;


class FileUtilities
{
    public static function  copySecureFile($FromLocation,$ToLocation,$VerifyPeer=false,$VerifyHost=2)
    {
        $Channel = curl_init($FromLocation);
        $File = fopen ($ToLocation, "w");
        curl_setopt($Channel, CURLOPT_FILE, $File);
        curl_setopt($Channel, CURLOPT_HEADER, 0);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, $VerifyPeer);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, $VerifyHost);
        curl_exec($Channel);
        curl_close($Channel);
        fclose($File);
        return file_exists($ToLocation);
    }
}