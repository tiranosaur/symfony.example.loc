<?php

namespace App\Controller;

use App\Service\FileService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/file', name: 'app_files', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('file/index.html.twig',
            [
                'controller_name' => 'FileController',
                "files" => FileService::getFileNames()
            ]
        );
    }

    #[Route('/file/{filename}', name: 'app_file', methods: ['GET'])]
    public function download(string $filename): Response
    {
        $path = FileService::getUploaddedPath() . "/" . $filename;
        if ($filename != null && file_exists($path)) {
            header("Content-type: {mime_content_type($path)}");
            return new BinaryFileResponse($path);
        }
        return $this->redirect("/file");
    }

    #[Route('/file', name: 'app_file_upload', methods: ['POST'])]
    public function upload(Request $request, LoggerInterface $logger): Response
    {
        try {
            $file = $request->files->get("file");
            $file->move(
                FileService::getUploaddedPath(),
                $file->getClientOriginalName()
            );
        } catch (Exception $e) {
            $logger->error($e->getMessage());
        }
        return $this->redirect("/file");
    }

    #[Route('/file/delete/{filename}', name: 'app_file_delete', methods: ['DELETE'])]
    public function delete(string $filename, Request $request, LoggerInterface $logger): JsonResponse
    {
        try {
            $path = FileService::getUploaddedPath() . "/" . $filename;
            unlink($path);
        } catch (Exception $e) {
            $logger->error($e->getMessage());
            return $this->json(["status" => "ok"], status: 400);
        }
        return $this->json(["status" => "ok"]);
    }
}
