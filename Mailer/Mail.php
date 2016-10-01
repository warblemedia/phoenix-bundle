<?php

namespace WarbleMedia\PhoenixBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class Mail implements MailInterface
{
    use ContainerAwareTrait;

    /** @var array */
    protected $from = [];

    /** @var array */
    protected $to = [];

    /** @var array */
    protected $cc = [];

    /** @var array */
    protected $bcc = [];

    /** @var array */
    protected $replyTo = [];

    /** @var string */
    protected $subject;

    /** @var string */
    protected $view;

    /** @var array */
    protected $viewData = [];

    /** @var string */
    protected $textView;

    /** @var array */
    protected $textViewData = [];

    /** @var array */
    protected $attachments = [];

    /** @var array */
    protected $rawAttachments = [];

    /** @var \Closure[] */
    protected $callbacks = [];

    /**
     * @param string      $address
     * @param string|null $name
     * @return $this
     */
    public function from($address, $name = null)
    {
        $this->from[$address] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string      $address
     * @param string|null $name
     * @return $this
     */
    public function to($address, $name = null)
    {
        $this->to[$address] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string      $address
     * @param string|null $name
     * @return $this
     */
    public function cc($address, $name = null)
    {
        $this->cc[$address] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param string      $address
     * @param string|null $name
     * @return $this
     */
    public function bcc($address, $name = null)
    {
        $this->bcc[$address] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param string      $address
     * @param string|null $name
     * @return $this
     */
    public function replyTo($address, $name = null)
    {
        $this->replyTo[$address] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $file
     * @param string $filename
     * @param string $contentType
     * @return $this
     */
    public function attach($file, $filename = null, $contentType = null)
    {
        $this->attachments[] = [
            'file'         => $file,
            'filename'     => $filename,
            'content_type' => $contentType,
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param string $data
     * @param string $filename
     * @param string $contentType
     * @return $this
     */
    public function attachData($data, $filename, $contentType = null)
    {
        $this->rawAttachments[] = [
            'data'         => $data,
            'filename'     => $filename,
            'content_type' => $contentType,
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getRawAttachments()
    {
        return $this->rawAttachments;
    }

    /**
     * @param string $view
     * @param array  $parameters
     * @return $this
     */
    public function render($view, array $parameters = [])
    {
        $this->view = $view;
        $this->viewData = $parameters;

        return $this;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return array
     */
    public function getViewData()
    {
        return $this->viewData;
    }

    /**
     * @param string $view
     * @param array  $parameters
     * @return $this
     */
    public function renderPlain($view, array $parameters = [])
    {
        $this->textView = $view;
        $this->textViewData = $parameters;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextView()
    {
        return $this->textView;
    }

    /**
     * @return array
     */
    public function getTextViewData()
    {
        return $this->textViewData;
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function modifyMessage(\Closure $callback)
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * @return \Closure[]
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param int    $referenceType
     * @return string
     */
    protected function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * @param string $id
     * @return bool
     */
    protected function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * @param string $id
     * @return object
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }
}
