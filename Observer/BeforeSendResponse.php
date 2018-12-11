<?php

namespace ClawRock\Debug\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class BeforeSendResponse implements ObserverInterface
{
    /**
     * @var \ClawRock\Debug\Helper\Config
     */
    private $config;

    /**
     * @var \ClawRock\Debug\Model\Profiler
     */
    private $profiler;

    public function __construct(
        \ClawRock\Debug\Helper\Config $config,
        \ClawRock\Debug\Model\Profiler $profiler
    ) {
        $this->config = $config;
        $this->profiler = $profiler;
    }

    public function execute(Observer $observer)
    {
        $request  = $observer->getRequest();
        $response = $observer->getResponse();
        if ($this->isProfilerAction($request) || !$this->config->isEnabled()) {
            return;
        }

        $this->profiler->run($request, $response);
    }

    private function isProfilerAction(\Magento\Framework\HTTP\PhpEnvironment\Request $request)
    {
        return $request->getModuleName() === '_debug' || $request->getModuleName() === 'debug';
    }
}
