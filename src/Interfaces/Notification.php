<?php
/**
 * This file is part of {@see arabcoders\notification} package.
 *
 * (c) 2014-2016 Abdul.Mohsen B. A. A.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\notification\Interfaces;

/**
 * Notification Manager Interface.
 *
 * @author Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */

interface Notification extends Provider
{
    /**
     * Constructor.
     *
     * @param Provider $provider Provider
     * @param array    $options  options
     **/
    public function __construct( Provider $provider, array $options = [ ] );
}