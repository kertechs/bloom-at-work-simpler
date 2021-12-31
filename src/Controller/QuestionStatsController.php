<?php

declare(strict_types=1);

namespace BloomAtWork\Controller;

use BloomAtWork\Service\QuestionStats;
use BloomAtWork\Model\QuestionStatsDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * Check if a csv file was uploaded
     * Give it to the QuestionStatsService
     * Build the stats
     * Build the response then return it
     *
     * @Route("/csv/upload", name="question_stats_upload", methods={"POST"})
     */
    public function readFile(Request $request): JsonResponse
    {
        try {
            $file = $request->files->get('csvfile');

            if (!$file) {
                throw new \Exception('No file uploaded');
            }

            /**
             * @var UploadedFile $file
             */
            //todo: add more validation/tests for the content type
            if (! ($file->getClientOriginalExtension() == 'csv' || $request->getContentType() == 'application/csv') ) {
                throw new \Exception('File is not valid');
            }

            $questionStatsService = new QuestionStats($file->getRealPath());
            $statsDto = new QuestionStatsDto($questionStatsService);

            return new JsonResponse($statsDto->getDto());
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
