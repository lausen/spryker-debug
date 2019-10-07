<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ShopApplication;

use Inviqa\Yves\Communication\Plugin\SprykerDebugControllerProvider;
use Pyz\Yves\Test\Plugin\Provider\TestControllerProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Spryker\Shared\Application\Business\Routing\SilexRouter;
use Spryker\Shared\Application\ServiceProvider\RoutingServiceProvider;
use SprykerShop\Yves\ShopApplication\YvesBootstrap as SprykerYvesBootstrap;
use Spryker\Shared\ErrorHandler\Plugin\ServiceProvider\WhoopsErrorHandlerServiceProvider;

class YvesBootstrap extends SprykerYvesBootstrap
{
    /**
     * @return void
     */
    protected function registerServiceProviders()
    {
        $this->application['debug'] = true;
        $this->application->register(new ServiceControllerServiceProvider());
        $this->application->register(new RoutingServiceProvider());
        $this->application->register(new WhoopsErrorHandlerServiceProvider());
    }

    /**
     * @return void
     */
    protected function registerRouters()
    {
        $this->application->addRouter(new SilexRouter($this->application));
    }

    /**
     * @return void
     */
    protected function registerControllerProviders()
    {
        $isSsl = $this->config->isSslEnabled();

        $controllerProviders = $this->getControllerProviderStack($isSsl);

        foreach ($controllerProviders as $controllerProvider) {
            $this->application->mount($controllerProvider->getUrlPrefix(), $controllerProvider);
        }
    }

    /**
     * @param bool|null $isSsl
     *
     * @return \SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider[]
     */
    protected function getControllerProviderStack($isSsl)
    {
        return [
            new TestControllerProvider(),
            new SprykerDebugControllerProvider(),
        ];
    }
}
