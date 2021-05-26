<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FileUploader
{
    private $targetDirectory;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @param null $fileName
     * @return string
     */
    public function upload(UploadedFile $file,$fileName=null)
    {

        if(!$fileName){
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = iconv('UTF-8','ASCII//TRANSLIT',$originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->getClientOriginalExtension();
        }else{
            $fileName.='.'.$file->getClientOriginalExtension();
        }



        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $fileName;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}