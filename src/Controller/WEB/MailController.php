<?php

namespace App\Controller\WEB;


use App\Entity\ArtWork;
use App\Form\ArtWorkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class MailController
 * @package App\Controller
 * @Route("/mails")
 */
class MailController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/download/attachment/{file}", name="download_attachment")
     */
    public function downloadAttachment(Request $request, $file)
    {
        $realPath = base64_decode($file);
        try {
            return $this->file($realPath);
        } catch (\Exception $exception) {
            return new Response("Exception: " . $exception->getMessage());
        }
    }

    /**
     * @param $file
     * @Route("/open/file/{file}", name="open_file")
     * @return BinaryFileResponse|Response
     */
    public function openFile($file)
    {
        $realPath = base64_decode($file);
        try {
            $fileSystem = new Filesystem();
            if ($fileSystem->exists($realPath)) {
                return new BinaryFileResponse($realPath);
            } else {
                return new Response("Exception: The file \"" . $realPath . "\" does not exist");
            }
        } catch (\Exception $exception) {
            return new Response("Exception: " . $exception > getMessage());
        }
    }

    /**
     * @param $file
     * @Route("/delete/file/{file}", name="delete_file")
     * @return BinaryFileResponse|Response
     */
    public function deleteFile($file)
    {
        $realPath = base64_decode($file);
        $fileSystem = new Filesystem();
        try {
            if ($fileSystem->exists($realPath)) {
                $fileSystem->remove($realPath);
                return $this->redirectToRoute("mail_dashboard");
            } else {
                return new Response("Exception: The file \"" . $realPath . "\" does not exist");
            }
        } catch (\Exception $exception) {
            return new Response('exception :' . $exception->getMessage());
        }
    }

    /**
     * @param ParameterBagInterface $params
     * @return Response
     * @Route("/dashboard", name="mail_dashboard")
     */
    public function mailDashboard(ParameterBagInterface $params)
    {

        $form = $this->createForm(ArtWorkType::class, new ArtWork(), ['status' => ArtWorkType::PROPERTY_STATUS]);
        if (!($params->get('email_debug_enabled'))) {
            throw new AccessDeniedException();
        }
        $mailsDirectory = $params->get('debug_mail_files_path');
        $finder = new Finder();
        $finder->files()->in($mailsDirectory);
        $content = [];
        foreach ($finder->files()->name('*.html')
                     ->sort(function (\SplFileInfo $a, \SplFileInfo $b) {
                         return strcmp($b->getCTime(), $a->getCTime());
                     }) as $file) {
            /** @var File $file */
            $content[] = ['date' => $file->getMTime(),
                'name' => $file->getBasename(),
                'path' => $file->getRealPath(),
                'download' => $this->generateUrl("download_attachment", ["file" => base64_encode($file->getRealPath())]),
                'view' => $this->generateUrl('open_file', ["file" => base64_encode($file->getRealPath())]),
                'delete' => $this->generateUrl('delete_file', ["file" => base64_encode($file->getRealPath())])
            ];
        }
        return $this->render('Emails/dashboard.html.twig', ['files' => $content, 'form' => $form->createView()]);
    }
}
