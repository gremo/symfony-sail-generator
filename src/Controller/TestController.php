<?php

namespace App\Controller;

use App\Generator\FilesDownloadGenerator;
use App\Generator\OutputGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use ZipStream\ZipStream;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(
        #[MapQueryParameter()] string $hash,
        OutputGenerator $generator,
    ): Response {
        /** @var \App\Context\GlobalContext $context */
        $context = unserialize(base64_decode($hash));

        $filename = sprintf('ss-%s.zip', crc32($hash));
        $files = $generator->getResult($context, FilesDownloadGenerator::class);

        $response = new StreamedResponse(function () use ($filename, $files) {
            $zip = new ZipStream(
                outputName: $filename,
                defaultEnableZeroHeader: true,
                contentType: 'application/octet-stream',
            );

            foreach ($files as $filename => $content) {
                $zip->addFile($filename, $content);
            }

            $zip->finish();
        });

        return $response;
    }
}
