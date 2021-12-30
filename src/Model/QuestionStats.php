<?php

declare(strict_types=1);

namespace BloomAtWork\Model;

class QuestionStats extends AbstractQuestion
{
    private AnswerStatList $answers;

    public function __construct(string $label)
    {
        parent::__construct($label);
    }

    public function getMin(): float
    {
        // TODO: Implement getMin() method.
    }

    public function getMax(): float
    {
        // TODO: Implement getMax() method.
    }

    public function getMean(): float
    {
        // TODO: Implement getMean() method.
    }
}
