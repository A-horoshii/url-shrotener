<?php

namespace Horoshii\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class UrlShortenerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        if (isset($config['hash_min_length'])) {
            $container->setParameter('url_shortener.hash_min_length', $config['hash_min_length']);
        }
        if (isset($config['hash_salt'])) {
            $container->setParameter('url_shortener.hash_salt', $config['hash_salt']);
        }
        if (isset($config['hash_alphabet'])) {
            $container->setParameter('url_shortener.hash_alphabet', $config['hash_alphabet']);
        }
        if (isset($config['link_default_ttl'])) {
            $container->setParameter('url_shortener.link_default_ttl', $config['link_default_ttl']);
        }
        if (isset($config['check_user_info'])) {
            $container->setParameter('url_shortener.check_user_info', $config['check_user_info']);
        }
    }
}