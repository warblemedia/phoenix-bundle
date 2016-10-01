<?php

namespace WarbleMedia\PhoenixBundle\Util;

class TokenGenerator implements TokenGeneratorInterface
{
    const ENTROPY_BYTES = 32;

    /**
     * @return string
     */
    public function generateToken()
    {
        // Generate an URI safe base64 encoded string that does not contain "+",
        // "/" or "=" which need to be URL encoded and make URLs unnecessarily
        // longer.
        $bytes = random_bytes(self::ENTROPY_BYTES);

        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}
