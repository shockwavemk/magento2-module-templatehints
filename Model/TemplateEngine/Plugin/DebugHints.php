<?php
/**
 * Plugin for the template engine factory that makes a decision of whether to activate debugging hints or not
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Shockwavemk\Templatehints\Model\TemplateEngine\Plugin;

use Magento\Developer\Helper\Data as DevHelper;
use Magento\Developer\Model\TemplateEngine\Decorator\DebugHintsFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\TemplateEngineFactory;
use Magento\Framework\View\TemplateEngineInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class DebugHints extends \Magento\Developer\Model\TemplateEngine\Plugin\DebugHints
{
    /**
     * Wrap template engine instance with the debugging hints decorator, depending of the store configuration
     *
     * @param TemplateEngineFactory $subject
     * @param TemplateEngineInterface $invocationResult
     *
     * @return TemplateEngineInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreate(
        TemplateEngineFactory $subject,
        TemplateEngineInterface $invocationResult
    ) {
        $storeCode = $this->storeManager->getStore()->getCode();
        $debugTemplateHintsCookie = isset($_COOKIE['debugTemplateHints']) && $_COOKIE['debugTemplateHints'] === 'true';

        if ($debugTemplateHintsCookie || ($this->scopeConfig->getValue($this->debugHintsPath, ScopeInterface::SCOPE_STORE, $storeCode)
            && $this->devHelper->isDevAllowed())) {
            $showBlockHints = $this->scopeConfig->getValue(
                self::XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            return $this->debugHintsFactory->create([
                'subject' => $invocationResult,
                'showBlockHints' => $showBlockHints,
            ]);
        }
        return $invocationResult;
    }
}
