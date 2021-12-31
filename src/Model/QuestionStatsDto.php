<?php

declare(strict_types=1);

namespace BloomAtWork\Model;

class QuestionStatsDto
{
    /**
     * @var array $dto
     */
    private array $dto = [];

    public function __construct(\BloomAtWork\Service\QuestionStats $questionStatsService)
    {
        $questionStats = $questionStatsService->getQuestionStats();

        $this->dto =
            [
                "question" => [
                    "label" => $questionStats->getLabel(),

                    "statistics" => [
                        "min" => $questionStats->getMin(),
                        "max" => $questionStats->getMax(),
                        "mean" => $questionStats->getMean(),
                    ],
                ]
            ];
    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
