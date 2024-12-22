<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:test-mailer',
    description: 'Test the mailer configuration'
)]
class TestMailerCommand extends Command
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    protected function configure(): void
    {
        $this->setDescription('Tests the mailer configuration by sending a test email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Sending test email...');
        $output->writeln('MAILER_DSN: ' . $_ENV['MAILER_DSN']);

        try {
            $email = (new Email())
                ->from(new Address('sofia@exercices.com', 'Sofia'))
                ->to(new Address('sofia.test@example.com', 'Sofia Test'))
                ->subject('Test email from Symfony Application ' . date('Y-m-d H:i:s'))
                ->text('This is a test email sent at ' . date('Y-m-d H:i:s'))
                ->html('
                    <h1>Test Email</h1>
                    <p>This is a test email sent at ' . date('Y-m-d H:i:s') . '</p>
                    <p>If you receive this, the mailer configuration is working!</p>
                ');

            $output->writeln('Attempting to send email...');
            $this->mailer->send($email);
            $output->writeln('<info>Email sent successfully!</info>');
            $output->writeln('<info>Please check your Mailtrap inbox at https://mailtrap.io/inboxes</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error sending email: ' . $e->getMessage() . '</error>');
            $output->writeln('<error>Stack trace: ' . $e->getTraceAsString() . '</error>');
            return Command::FAILURE;
        }
    }
} 