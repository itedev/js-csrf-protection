<?php

namespace ITE\Js\Csrf\Twig\Extension;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;

/**
 * Class LinkExtension
 *
 * @author c1tru55 <mr.c1tru55@gmail.com>
 */
class LinkExtension extends Twig_Extension
{
    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    protected $csrfTokenManager;

    /**
     * @var string $csrfTokenId
     */
    protected $csrfTokenId;

    /**
     * @param TranslatorInterface $translator
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param string $csrfTokenId
     */
    public function __construct(
        TranslatorInterface $translator,
        CsrfTokenManagerInterface $csrfTokenManager,
        $csrfTokenId
    ) {
        $this->translator = $translator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->csrfTokenId = $csrfTokenId;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('link_attr', [$this, 'linkAttributes'], ['is_safe' => ['html']]),
        );
    }

    /**
     * @param string $method
     * @param string|null $confirmation
     * @param string|null $csrfTokenId
     * @return string
     */
    public function linkAttributes($method, $confirmation = null, $csrfTokenId = null)
    {
        $attr = sprintf('data-method="%s"', $method);
        if (false !== $confirmation) {
            $attr .= sprintf(' data-confirm="%s"', $this->getConfirmation($confirmation));
        }
        $attr .= sprintf(' data-csrf-token="%s"', $this->getCsrfTokenValue($csrfTokenId));

        return $attr;
    }

    /**
     * @param null $confirmation
     * @return string
     */
    protected function getConfirmation($confirmation = null)
    {
        $message = $confirmation ? : 'csrf_protection.link.confirmation';

        return $this->translator->trans($message, [], 'ITEJsBundle');
    }

    /**
     * @param string|null $csrfTokenId
     * @return string
     */
    protected function getCsrfTokenValue($csrfTokenId = null)
    {
        $csrfTokenId = $csrfTokenId ? $csrfTokenId : $this->csrfTokenId;

        return $this->csrfTokenManager->getToken($csrfTokenId)->getValue();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ite_js.csrf_protection.twig.extension.link';
    }
}
