<?php

namespace App\Command;


use App\Services\MailerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class TestSendMailCommand extends Command
{
    protected static $defaultName = 'minefi:test:mail';

    protected $mailer;

    protected $twig;

    protected $kernel;

    /**
     * testSendMailCommand constructor.
     * @param MailerService $mailer
     * @param Environment $twig
     * @param KernelInterface $kernel
     */
    public function __construct(MailerService $mailer, Environment $twig, KernelInterface $kernel)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->kernel = $kernel;
    }


    protected function configure()
    {
        $this
            ->setDescription('')
            ->addArgument('to', InputArgument::REQUIRED, 'Destination')
            ->addOption('cc', null, InputOption::VALUE_NONE, 'Add CC address ?');


    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sendCC = false;
        $to = $input->getArgument('to');
        $ccValue = $input->getOption('cc');
        if ($ccValue) {
            $sendCC = true;
        }
        $attachment = $this->kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Emails' . DIRECTORY_SEPARATOR . 'test_mail.html.twig';
        try {
            $this->mailer->sendMail($to,
                                    $sendCC,
                                    'Test Send Mail',
                                    'Emails/test_mail',
                                    [],
                                    ['testFile' => $attachment],
                                    'cc_exemple@test.com',
                                    ['bcc_exemple@test.com'],
                                    true);
        } catch (\Exception $e) {
            echo "An exception : " . $e->getMessage();
        }
    }
}
