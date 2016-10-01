<?php

namespace WarbleMedia\PhoenixBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer implements MailerInterface
{
    /** @var \Twig_Environment */
    private $twig;

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    /**
     * Mailer constructor.
     *
     * @param \Twig_Environment                                         $twig
     * @param \Swift_Mailer                                             $mailer
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Mailer\MailInterface $mail
     */
    public function send(MailInterface $mail)
    {
        $mail->setContainer($this->container);
        $mail->build();

        $html = $this->renderView($mail->getView(), $mail->getViewData());
        $text = $this->renderView($mail->getTextView(), $mail->getTextViewData());

        $message = new \Swift_Message();
        $message->setFrom($mail->getFrom());
        $message->setTo($mail->getTo());
        $message->setCc($mail->getCc());
        $message->setBcc($mail->getBcc());
        $message->setReplyTo($mail->getReplyTo());
        $message->setSubject($mail->getSubject());

        if ($html !== null) {
            $message->setBody($html, 'text/html');
            $message->addPart($text, 'text/plain');
        } else {
            $message->setBody($text, 'text/plain');
        }

        foreach ($mail->getAttachments() as $attachment) {
            $entity = \Swift_Attachment::fromPath($attachment['file'], $attachment['content_type']);
            if ($attachment['filename'] !== null) {
                $entity->setFilename($attachment['filename']);
            }

            $message->attach($entity);
        }

        foreach ($mail->getRawAttachments() as $attachment) {
            $entity = \Swift_Attachment::newInstance($attachment['data'], $attachment['filename'], $attachment['content_type']);
            $message->attach($entity);
        }

        foreach ($mail->getCallbacks() as $callback) {
            $callback($message);
        }

        $this->mailer->send($message);
    }

    /**
     * @param string $view
     * @param array  $parameters
     * @return null|string
     */
    protected function renderView($view, array $parameters = [])
    {
        if ($view) {
            try {
                $template = $this->twig->createTemplate($view);
                $parameters = $this->twig->mergeGlobals($parameters);

                return $template->render($parameters);
            } catch (\Twig_Error $e) {
                // Do nothing
            }
        }

        return null;
    }
}
