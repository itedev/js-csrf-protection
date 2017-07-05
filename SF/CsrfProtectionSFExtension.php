<?php

namespace ITE\Js\Csrf\SF;

use ITE\JsBundle\SF\SFExtension;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class CsrfProtectionSFExtension
 *
 * @author c1tru55 <mr.c1tru55@gmail.com>
 */
class CsrfProtectionSFExtension extends SFExtension
{
    /**
     * {@inheritdoc}
     */
    public function getConfiguration(ContainerBuilder $container)
    {
        $node = new TreeBuilder();
        $node = $node->root('csrf_protection');
        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('token_id')
                    ->defaultValue('app')
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function loadConfiguration(array $config, ContainerBuilder $container)
    {
        if ($config['extensions']['csrf_protection']['enabled']) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('sf.csrf_protection.yml');

            $container->setParameter('ite_js.csrf_protection.token_id', $config['extensions']['csrf_protection']['token_id']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts()
    {
        return [__DIR__.'/../Resources/public/js/sf.csrf_protection.js'];
    }
}
