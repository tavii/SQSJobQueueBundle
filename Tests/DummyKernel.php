<?php
class DummyKernel extends Symfony\Component\HttpKernel\Kernel
{

    public function registerBundles()
    {
        return array();
    }

    public function registerContainerConfiguration(Symfony\Component\Config\Loader\LoaderInterface $loader)
    {

    }
}