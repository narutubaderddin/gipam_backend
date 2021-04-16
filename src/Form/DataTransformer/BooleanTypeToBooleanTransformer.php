<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class BooleanTypeToBooleanTransformer implements DataTransformerInterface
{
    private $trueValues;
    private $falseValues;

    public function __construct(array $trueValues, array $falseValues)
    {
        $this->trueValues = $trueValues;
        $this->falseValues = $falseValues;
    }

    public function transform($value)
    {
        if (null === $value) {
            return false;
        }

        if (!is_bool($value)) {
            throw new TransformationFailedException('Expected a boolean.');
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        if (in_array($value, $this->trueValues, true)) {
            return true;
        } elseif (in_array($value, $this->falseValues, true)) {
            return false;
        }

        throw new TransformationFailedException('Invalid value.');
    }
}