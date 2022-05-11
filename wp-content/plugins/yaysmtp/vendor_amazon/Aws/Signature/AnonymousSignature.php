<?php

namespace YaySMTP\Aws3\Aws\Signature;

use YaySMTP\Aws3\Aws\Credentials\CredentialsInterface;
use YaySMTP\Aws3\Psr\Http\Message\RequestInterface;
/**
 * Provides anonymous client access (does not sign requests).
 */
class AnonymousSignature implements \YaySMTP\Aws3\Aws\Signature\SignatureInterface
{
    public function signRequest(\YaySMTP\Aws3\Psr\Http\Message\RequestInterface $request, \YaySMTP\Aws3\Aws\Credentials\CredentialsInterface $credentials)
    {
        return $request;
    }
    public function presign(\YaySMTP\Aws3\Psr\Http\Message\RequestInterface $request, \YaySMTP\Aws3\Aws\Credentials\CredentialsInterface $credentials, $expires)
    {
        return $request;
    }
}
