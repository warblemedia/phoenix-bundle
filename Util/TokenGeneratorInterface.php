<?php

namespace WarbleMedia\PhoenixBundle\Util;

interface TokenGeneratorInterface
{
    /**
     * @return string
     */
    public function generateToken();
}
