<?php

namespace AppBundle\Service;

use AppBundle\Exception\ResourceValidationException;
use Symfony\Component\Validator\ConstraintViolationList;


class UserCreater
{
    public function JSONvalidate(ConstraintViolationList $violations)
    {
//        if (count($violations)) {
//        $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
//        foreach ($violations as $violation) {
//        $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
//        }
//        throw new ResourceValidationException($message);
    }


}