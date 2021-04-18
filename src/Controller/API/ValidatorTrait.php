<?php

namespace App\Controller\API;

trait ValidatorTrait
{
    private function isValid($entity, array &$errors)
    {
        $violations = $this->validator->validate($entity);
        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return false;
        }
        return true;
    }
}