<?php

declare(strict_types=1);

namespace BloomAtWork\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class QuestionStatsController
 * @Route("/question-stats")
 * @package BloomAtWork\Controller
 */
class QuestionStatsController extends AbstractController
{
    /**
     * @Route("/csv/upload", name="question_stats_upload", methods={"POST", "GET"})
     */
    public function readFile(Request $request): JsonResponse
    {
        return new JsonResponse('Coucou');
    }
}
