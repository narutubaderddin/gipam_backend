<?php


namespace App\Services;


use App\Entity\Attachment;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentService
{

    /**
     * @param array $data
     * @param Attachment $attachment
     * @return array
     * @throws \Exception
     */
    public function formatUpdateAttachmentData(array $data, Attachment $attachment)
    {
        if (isset($data['link']) && !($data['link'] instanceof UploadedFile)) {
            $urlData = explode('/', $data['link']);
            if ($urlData[count($urlData) - 1] !== $attachment->getLink()) {
                throw new \Exception('Url provided Does not match the file already Uploaded');
            }
            unset($data['link']);
        }

        return $data;
    }

    /**
     * @param array $attachmentsData
     * @param Attachment[] $attachments
     * @return array
     * @throws \Exception
     */
    public function formatUpdateNoticeData(array $attachmentsData, $attachments)
    {
        $result = [];
        foreach ($attachmentsData as $data){
            if(isset($data['id']) && ($data['id']!=null || $data['id']!='null'))
            {
                $attachment = $this->getAttachment($data,$attachments);
                if(!$attachment){
                    throw new \Exception('attachment does not belong to The Furniture');
                }
                $data = $this->formatUpdateAttachmentData($data,$attachment);
            }
            unset($data['id']);
            unset($data['name']);
            $result[] =$data;
        }
        return $result;
    }

    /**
     * @param $data
     * @param Attachment[] $attachments
     * @return Attachment|null
     */
        private function getAttachment($data,$attachments){
        foreach ( $attachments as $attachment){
            if($attachment->getId() == (int)json_decode($data['id'])){
                return $attachment;
            }
        }
        return  null;
    }

}
