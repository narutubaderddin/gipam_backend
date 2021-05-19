<?php

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormValidationException extends HttpException
{
    protected $form;

    /**
     * FormValidationException constructor.
     * @param FormInterface $form
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(FormInterface $form, string $message = 'Validation Failed', int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $this->form = $form;

        parent::__construct($statusCode, $message);
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return JsonResponse
     */
    public function getJsonResponse(): JsonResponse
    {
        $data['code'] = $this->getStatusCode();
        $data['message'] = $this->getMessage();
        $data['errors'] = $this->getErrors($this->form);

        return new JsonResponse($data);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getErrors(FormInterface $form) :array
    {
        $errors = [];
        foreach ($form->all() as $field) {
            $fieldKey = $field->getName();
            if (1 < count($field->all())){
                foreach ($field->all() as $i => $f){
                    $fkey = $f->getName();
                    if (1 < count($f->all())){
                        foreach ($f->all() as $fp){
                            $fpkey = $fp->getName();
                            foreach ($fp->getErrors(true) as $errp) {
                                if(isset($errors[$fieldKey][$fkey]) && array_key_exists($fpkey, $errors[$fieldKey][$fkey])) {
                                    $errors[$fieldKey][$fkey][$i][$fpkey][] = $errp->getMessage();
                                } else {
                                    $errors[$fieldKey][$fkey][$i][$fpkey] = [$errp->getMessage()];
                                }
                            }
                        }
                    }else{
                        foreach ($f->getErrors(true) as $err) {
                            if(isset($errors[$fieldKey]) && array_key_exists($fkey, $errors[$fieldKey])) {
                                $errors[$fieldKey][$fkey][] = $err->getMessage();
                            } else {
                                $errors[$fieldKey][$fkey] = [$err->getMessage()];
                            }
                        }
                    }
                }
            }else {
                foreach ($field->getErrors(true) as $error) {
                    if(array_key_exists($fieldKey, $errors)) {
                        $errors[$fieldKey][] = $error->getMessage();
                    } else {
                        $errors[$fieldKey] = [$error->getMessage()];
                    }
                }
            }
        }

        return $errors;
    }
}
