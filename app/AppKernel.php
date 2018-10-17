<?php
/**
 * @category    ClassificationstoreBundle
 * @date        09/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

use Pimcore\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundlesToCollection(\Pimcore\HttpKernel\BundleCollection\BundleCollection $collection)
    {
        $collection->addBundle(new \Divante\ClassificationstoreBundle\ClassificationstoreBundle());
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        \Pimcore::setKernel($this);
    }
}
