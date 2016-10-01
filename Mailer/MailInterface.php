<?php

namespace WarbleMedia\PhoenixBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

interface MailInterface extends ContainerAwareInterface
{
    /**
     * Build the mail message.
     */
    public function build();

    /**
     * @return array
     */
    public function getFrom();

    /**
     * @return array
     */
    public function getTo();

    /**
     * @return array
     */
    public function getCc();

    /**
     * @return array
     */
    public function getBcc();

    /**
     * @return array
     */
    public function getReplyTo();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return array
     */
    public function getAttachments();

    /**
     * @return array
     */
    public function getRawAttachments();

    /**
     * @return string
     */
    public function getView();

    /**
     * @return array
     */
    public function getViewData();

    /**
     * @return string
     */
    public function getTextView();

    /**
     * @return array
     */
    public function getTextViewData();

    /**
     * @return \Closure[]
     */
    public function getCallbacks();
}
