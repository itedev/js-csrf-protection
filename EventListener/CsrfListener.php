<?php

namespace ITE\Js\Csrf\EventListener;

use ITE\Js\Csrf\Annotation\CsrfSecure;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class CsrfListener
 *
 * @author c1tru55 <mr.c1tru55@gmail.com>
 */
class CsrfListener
{
    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    protected $csrfTokenManager;

    /**
     * @var string $csrfTokenId
     */
    protected $csrfTokenId;

    /**
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param string $csrfTokenId
     */
    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, $csrfTokenId)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->csrfTokenId = $csrfTokenId;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        /** @var CsrfSecure $csrfSecure */
        if (null === $csrfSecure = $request->attributes->get('_csrf_secure')) {
            return;
        }

        $csrfTokenId = $csrfSecure->hasTokenId() ? $csrfSecure->getTokenId() : $this->csrfTokenId;
        $csrfTokenValue = $request->request->get('_token');
        $csrfToken = new CsrfToken($csrfTokenId, $csrfTokenValue);

        if (false === $this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new InvalidCsrfTokenException('The CSRF token is invalid');
        }
    }

}