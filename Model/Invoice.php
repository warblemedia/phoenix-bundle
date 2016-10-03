<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Gedmo\Timestampable\Traits\Timestampable;

abstract class Invoice implements InvoiceInterface
{
    use Timestampable;

    /** @var mixed */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
