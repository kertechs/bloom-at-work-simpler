<?php

declare(strict_types=1);

namespace BloomAtWork\Model;

/**
 * Class AnswerStat
 * @package BloomAtWork\Model
 * @description Aims to represent one of the Answers given by an employee.
 * The answer values has to be a float value between 0 and 10.
 */
class AnswerStat
{
    public function __construct(private float $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException('Value must be greater than 0 and lower than 10');
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public static function isValid(float $value): bool
    {
        return $value >= 0 && $value <= 10;
    }
}
