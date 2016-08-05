<?php
/**
 * Decorator that inserts debugging hints into the rendered block contents
 *
 * Copyright © 2015 Martin Kramer. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile
namespace Shockwavemk\Templatehints\Model\TemplateEngine\Decorator;

use Magento\Theme\Block\Adminhtml\Wysiwyg\Files\Content;

class DebugHints extends \Magento\Developer\Model\TemplateEngine\Decorator\DebugHints
{
    /**
     * Cache
     *
     * @var \Magento\Framework\App\CacheInterface
     */
    private $_cache;

    /**
     * Cache State
     *
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\View\TemplateEngineInterface
     */
    private $_subject;

    /**
     * @var bool
     */
    private $_showBlockHints;

    /**
     * @param \Magento\Framework\View\TemplateEngineInterface $subject
     * @param bool $showBlockHints Whether to include block into the debugging information or not
     */
    public function __construct(
        \Magento\Framework\View\TemplateEngineInterface $subject,
        $showBlockHints,
        \Magento\Framework\View\Element\Context $context
    )
    {
        $this->_subject = $subject;
        $this->_showBlockHints = $showBlockHints;

        $this->_cache = $context->getCache();
        $this->_cacheState = $context->getCacheState();
    }

    /**
     * Insert debugging hints into the rendered block contents
     *
     * {@inheritdoc}
     */
    public function render(\Magento\Framework\View\Element\BlockInterface $block, $templateFile, array $dictionary = [])
    {
        $result = $this->_subject->render($block, $templateFile, $dictionary);
        $result = $this->_renderAdvancedTemplateHints($result, $templateFile, $block);
        return $result;
    }

    /**
     * Insert template debugging hints into the rendered block contents
     *
     * @param $block \Magento\Framework\View\Element\AbstractBlock|\Magento\Framework\View\Element\BlockInterface
     * @param string $blockHtml
     * @param string $templateFile
     * @return string
     */
    protected function _renderAdvancedTemplateHints($blockHtml, $templateFile, $block)
    {
        $moduleName = var_export($block->getModuleName(), true);

        $cacheTags = implode(',<br/>', $this->getCacheTags($block));
        $cacheKeyInfoString = implode(',<br/>', $block->getCacheKeyInfo());

        $parentString = $this->getParentString($block);
        $lifetimeString = !empty($this->getCacheLifetime($block)) ? $this->getCacheLifetime($block) : '-';
        $colors = $this->getCacheColors($block);

        $content = <<<Content
        <table>
            <tr><th>General</th></tr>
            <tr><td><strong>Template:</strong></td><td>{$templateFile}</td></tr>
            <tr><td><strong>Type:</strong></td><td>{$block->getType()}</td></tr>
            <tr><td><strong>Layout:</strong></td><td>{$block->getNameInLayout()}</td></tr>
            <tr><td><strong>Module:</strong></td><td>{$moduleName}</td></tr>
            <tr><td><strong>Parent:</strong></td><td>{$parentString}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><th>Cache</th></tr>
            <tr><td><strong>Key:</strong></td><td>{$block->getCacheKey()}</td><tr>
            <tr><td><strong>Tags:</strong></td><td>{$cacheTags}</td><tr>
            <tr><td><strong>Lifetime:</strong></td><td>{$lifetimeString}</td><tr>
            <tr><td><strong>Info:</strong></td><td>{$cacheKeyInfoString}</td><tr>
            <tr><td><strong>Status:</strong></td><td>{$this->getCacheStatusString($block)}</td><tr>
        </table>
Content;

        return <<<HTML
<div class="debugging-hints" data-ot="{$content}" data-ot-target="true" data-ot-show-on="click" data-ot-hide-trigger="closeButton"
     data-ot-target-joint="top left" data-ot-background="{$colors['light']}" data-ot-border-color="{$colors['dark']}"
     style="border: 1px dotted {$colors['dark']}; ">
{$blockHtml}
</div>
HTML;
    }

    /**
     * Get tags array for saving cache
     * @see AbstractBlock, method in class is protected in original version
     *
     * @param $block \Magento\Framework\View\Element\AbstractBlock
     * @return array
     */
    protected function getCacheTags($block)
    {
        if (!$block->hasData('cache_tags')) {
            $tags = [];
        } else {
            $tags = $block->getData('cache_tags');
        }
        $tags[] = $block::CACHE_GROUP;
        return $tags;
    }

    /**
     * Get block cache life time
     * @see AbstractBlock, method in class is protected in original version
     *
     * @return int
     */
    protected function getCacheLifetime($block)
    {
        if (!$block->hasData('cache_lifetime')) {
            return null;
        }
        return $block->getData('cache_lifetime');
    }

    /**
     * Returns cache status for given block.
     * @see AbstractBlock, method in class is protected in original version
     *
     * @param $block
     * @return bool
     */
    protected function getCacheStatus($block)
    {
        if ($this->getCacheLifetime($block) === null || !$this->_cacheState->isEnabled($block::CACHE_GROUP)) {
            return false;
        }

        $cacheKey = $block->getCacheKey();

        if(empty($cacheKey))
        {
            return false;
        }

        $cacheData = $this->_cache->load($cacheKey);

        return isset($cacheData);
    }

    /**
     * @param $block
     * @return bool
     */
    protected function getCacheState($block)
    {
        return $this->_cacheState->isEnabled($block::CACHE_GROUP);
    }

    /**
     * Returns array for colorizing tool-tips depending on cache state / status
     * Todo: Refactor this
     *
     * @param $block
     * @return array
     */
    protected function getCacheColors($block)
    {
        $cacheState = $this->getCacheState($block);
        $cacheStatus = $this->getCacheStatus($block);

        $color = array(
            'light' => '#F6CECE',
            'dark' => 'red'
        );

        if(!$cacheState)
        {
            $color = array(
                'light' => '#F2F5A9',
                'dark' => 'yellow'
            );
        }

        if($cacheStatus)
        {
            $color = array(
                'light' => '#D0F5A9',
                'dark' => 'green'
            );
        }

        return $color;
    }

    /**
     * Returns descriptive string with detailed information about cache state / cache status
     *
     * @param $block
     * @return string
     */
    protected function getCacheStatusString($block)
    {
        $cacheState = $this->getCacheState($block);
        $cacheStatus = $this->getCacheStatus($block);

        $cacheString = '';

        if(!$cacheState)
        {
            $cacheString .= 'Cache disabled for group:' . $block::CACHE_GROUP .'.';
        }

        if($cacheStatus)
        {
            $cacheString .= 'Found in cache.';
        }
        else
        {
            $cacheString .= 'Not found in cache.';
        }

        return $cacheString;
    }

    /**
     * Returns descriptive string with name of parent block in layout
     *
     * @param $block
     * @return string
     */
    protected function getParentString($block)
    {
        $parent = $block->getParentBlock();

        if(!empty($parent))
        {
            return $parent->getNameInLayout();
        }

        return 'No parent block';
    }
}
