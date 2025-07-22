<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpreadsheetViewController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('spreadsheet_view/index.html.twig');
    }

    /**
     * @Route("/upload-excel", name="handle_excel_upload", methods={"POST"})
     */
    public function handleUpload(Request $request): Response
    {
        $file = $request->files->get('excel_file');

        if (!$file) {
            $this->addFlash('error', 'No file uploaded.');
            return $this->redirectToRoute('home');
        }

        $newFilename = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getParameter('kernel.project_dir') . '/public/uploads', $newFilename);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Upload failed.');
            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('view_excel', ['filename' => $newFilename]);
    }

    /**
     * @Route("/view-excel/{filename}", name="view_excel")
     */
    public function viewExcel(string $filename): Response
    {
        return $this->render('spreadsheet_view/view.html.twig', [
            'filename' => $filename,
        ]);
    }
}
