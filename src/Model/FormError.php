<?php

namespace App\Model;

use Symfony\Component\Form\FormInterface;
use JMS\Serializer\Annotation as JMS;

class FormError
{
    /**
     * @var array
     * @JMS\Groups("errors")
     */
    protected $errors;

    public function __construct(FormInterface $form)
    {
        $this->errors = $this->getFormErrors($form);
    }

    private function getFormErrors(FormInterface $form)
    {
        $formErrors = [];
        if ($form->count() > 0) {
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $formErrors[$child->getName()] = $this->getFormErrors($child);
                }
            }
        } else {
            foreach ($form->getErrors() as $error) {
                $formErrors[] = $error->getMessage();
            }
        }
        return $formErrors;
    }
}
