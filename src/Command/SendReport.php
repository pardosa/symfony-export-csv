<?php
// src/Command/ExportOrder.php
namespace App\Command;

//use \Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

class SendReport extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:send-report';

    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this -> setName('send')
            -> setDescription('Send CSV File to email.')
            -> addArgument('email', InputArgument::REQUIRED, 'Email Destination')
            -> addArgument('filename', InputArgument::REQUIRED, 'CSV File Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = (new \Swift_Message())
            ->setSubject('Order Report')
            ->setFrom(['sipipin123@gmail.com' => 'Order Report'])
            ->setTo([$input->getArgument('email') => 'Admin'])
            ->setBody('Order Report File Attached')
            ->attach(\Swift_Attachment::fromPath(__DIR__ . '/' . $input->getArgument('filename')))
        ;
        $this->mailer->send($message);
        
    }
}