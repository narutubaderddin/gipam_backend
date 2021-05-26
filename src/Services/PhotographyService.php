<?php


namespace App\Services;


use App\Entity\Photography;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotographyService
{


    /**
     * @param array $data
     * @param Photography $photography
     * @return array
     * @throws \Exception
     */
    public function formatUpdatePhotographyData(array $data, Photography $photography)
    {
        if (isset($data['imagePreview']) && !($data['imagePreview'] instanceof UploadedFile)) {
            $urlData = explode('/', $data['imagePreview']);
            if ($urlData[count($urlData) - 1] !== $photography->getImagePreview()) {
                throw new \Exception('Url provided Does not match the file already Uploaded');
            }
            unset($data['imagePreview']);
        }

        return $data;
    }

    /**
     * @param array $photographiesData
     * @param Photography[] $photographies
     */
    public function formatUpdateNoticeData(array $photographiesData, $photographies)
    {
        $result = [];
        foreach ($photographiesData as $data){
            if(isset($data['id'])&& $data["id"]!='null')
            {
                $photography = $this->getPhotography($data,$photographies);
                if(!$photography){
                    throw new \Exception('photographie does not belong to The Furniture');
                }
                $data = $this->formatUpdatePhotographyData($data,$photography);
                unset($data['id']);
            }
            $result[] =$data;
        }
        return $result;
    }

    /**
     * @param $data
     * @param Photography[] $photographies
     */
    private function getPhotography($data,$photographies){
        foreach ( $photographies as $photography){
            if($photography->getId() == $data['id']){
                return $photography;
            }
        }
        return  null;
    }

}