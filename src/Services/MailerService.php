<?php


namespace App\Services;


use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class MailerService
{
    private $mailer;
    private $mailFrom;
    private $mailFromName;
    private $environment;
    private $debugModeEnabled;
    private $debugMailFilesPath;
    private $emailCC;
    private $loggerService;
    private $emailAllTo;
    private $router;
    private $baseRoute;

    public function __construct(
        \Swift_Mailer $mailer,
        LoggerService $loggerService,
        RouterInterface $router,
        string $mailFrom,
        string $mailFromName,
        Environment $environnemnt,
        $debugModeEnabled,
        $debugMailFilesPath,
        $emailCC,
        $emailAllTo)
    {
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
        $this->mailFromName = $mailFromName;
        $this->environment = $environnemnt;
        $this->debugModeEnabled = $debugModeEnabled;
        $this->debugMailFilesPath = $debugMailFilesPath;
        $this->emailCC = $emailCC;
        $this->emailAllTo = $emailAllTo;
        $this->router=$router;
        $this->loggerService = $loggerService;
        $this->loggerService->init('mailing_service');
    }

    public function setArguments($baseRoute)
    {
        $this->baseRoute = $baseRoute;
    }
    /**
     * @param $to
     * @param $sendCC
     * @param $subject
     * @param $template
     * @param array $parameters
     * @param array $attachments
     * @param null $cc
     * @param null $bcc
     * @param bool $isTemplate
     * @return bool|false|int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMail($to, $sendCC, $subject, $template, $parameters = [], $attachments = [], $cc = null, $bcc = null, $isTemplate = true)
    {
        $attachments = $attachments ?? [];
        $files = $this->getAttachmentsLinks($attachments);
        $parameters['subject'] = $subject;
        if ($sendCC) {
            $cciEmail = $this->emailCC;
            if ($bcc) {
                array_push($bcc, $cciEmail);
                $cciEmail = $bcc;
            }
        } else {
            $cciEmail = $bcc;
        }

        $formattedSubject = ' [P2G] ' . $subject;
        //simulation
        $destination = (is_array($to)) ? $to[0] : $to;
        if ($this->debugModeEnabled) {
            $formattedTo = (is_array($to)) ? implode(', ', $to) : $to;
            $formattedCC = (is_array($cc)) ? implode(', ', $cc) : $cc;
            $formattedCCIEmail = (is_array($cciEmail)) ? implode(', ', $cciEmail) : $cciEmail;
            $templateName = ($isTemplate) ? $template . '.html.twig' : $template;
            $parameters['simulation'] = true;
            $templateContent = ($isTemplate) ? $this->environment->render($templateName, $parameters) : $template;
            $mailContent = $this->environment->render('Emails\MailContent.html.twig', [
                'subject' => $formattedSubject,
                'from' => $this->mailFrom,
                'to' => $formattedTo,
                'cc' => $formattedCC,
                'bcc' => $formattedCCIEmail,
                'template' => $templateName,
                'templateContent' => $templateContent,
                'files' => $files
            ]);
            $baseFileName = $this->debugMailFilesPath . '/' . $subject;
            $i = 0;
            $fileName = $baseFileName . '_' . $i . '.html';
            while (file_exists($fileName) && $i < 100) {
                $i++;
                $fileName = $baseFileName . '_' . $i . '.html';
            }
            return file_put_contents($fileName, $mailContent);
        } else {
            $message = (new \Swift_Message($formattedSubject))
                ->setFrom(array($this->mailFrom => $this->mailFromName));
            if (is_null($this->emailAllTo) || $this->emailAllTo == '') {
                $message->setTo($to);
                if ($cciEmail != null) {
                    if (is_array($cciEmail)) {
                        foreach ($cciEmail as $item) {
                            $message->setBcc($item);
                        }
                    } else {
                        $message->setBcc($cciEmail);
                    }

                }
                if ($cc != null) {
                    if (is_array($cc)) {
                        foreach ($cc as $item) {
                            $message->setCc($item);
                        }
                    } else {
                        $message->setCc($cc);
                    }
                }
            } else {
                $message->setTo($this->emailAllTo);
            }


            foreach ($attachments as $file) {
                $message->attach(\Swift_Attachment::fromPath($file));
            }

            if ($isTemplate) {
                $message->setBody($this->environment->render($template . '.html.twig', $parameters), 'text/html');

                try {
                    $message->addPart($this->environment->render($template . '.txt.twig', $parameters), 'text/plain');
                } catch (\Exception $exception) {
                    $this->loggerService->addError('Method:[' . __METHOD__ . ']: ' . $exception->getMessage());
                }
            } else {
                $message->setBody($template);
            }
            try {
                $this->mailer->send($message);
            } catch (\Exception $exception) {
                $this->loggerService->addError('Method:[' . __METHOD__ . ']: ' . $exception->getMessage());
            }
        }
        return true;
    }

    private function getAttachmentsLinks(array $attachments): array
    {
        $files = [];
        foreach ($attachments as $name => $filePath) {
            $files[] = [
                'fileName' => $name,
                'fileLink' => $this->baseRoute . $this->router->generate('download_attachment',
                        ['file' => base64_encode($filePath)])
            ];
        }
        return $files;
    }
}