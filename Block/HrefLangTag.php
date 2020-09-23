<?php

namespace VandersonRamos\HrefLangTag\Block;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\ResourceModel\Page;
use Magento\CmsUrlRewrite\Model\CmsPageUrlPathGenerator;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException as LocalizedExceptionAlias;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Api\Data\StoreInterface;

class HrefLangTag extends Template
{

    /**
     * @var CmsPageUrlPathGenerator
     */
    private $cmsPageUrlPathGenerator;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var Page
     */
    private $pageResource;

    /**
     * @var Http
     */
    private $request;

    /**
     * HrefLangTag constructor.
     * @param Context $context
     * @param Http $request
     * @param CmsPageUrlPathGenerator $cmsPageUrlPathGenerator
     * @param PageRepositoryInterface $pageRepository
     * @param Page $pageResource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Http $request,
        CmsPageUrlPathGenerator $cmsPageUrlPathGenerator,
        PageRepositoryInterface $pageRepository,
        Page $pageResource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cmsPageUrlPathGenerator = $cmsPageUrlPathGenerator;
        $this->pageRepository = $pageRepository;
        $this->pageResource = $pageResource;
        $this->request = $request;
    }

    /**
     * @return array
     * @throws LocalizedExceptionAlias
     */
    public function getStoreAlternativeLinks()
    {
        $storeLinks = [];

        foreach ($this->getStores() as $store) {
            $storeUrl = $this->getAlternativeUrl($store);

            if ($storeUrl) {
                $storeLinks[$this->getLocaleCode($store)] = $storeUrl;
            }
        }
        return $storeLinks;
    }

    /**
     * @param $store
     * @return mixed|null
     * @throws LocalizedExceptionAlias
     */
    private function getAlternativeUrl($store)
    {
        $dataUrl = [
            'cms_page_view' => $this->getCmsPageUrl($this->request->getParam('page_id'), $store),
            'cms_index_index' => $store->getBaseUrl()
        ];

        return ($dataUrl[$this->request->getFullActionName()] ?? null);
    }

    /**
     * @param $id
     * @param $store
     * @return string
     * @throws LocalizedExceptionAlias
     */
    private function getCmsPageUrl($id, $store)
    {
        $page = $this->pageRepository->getById($id);
        $pageId = $this->pageResource->checkIdentifier($page->getIdentifier(), $store->getId());
        if ($pageId) {
            $storePage = $this->pageRepository->getById($pageId);
            $path = $this->cmsPageUrlPathGenerator->getUrlPath($storePage);
            return $store->getBaseUrl() . $path;
        }
    }

    /**
     * @param $store
     * @return mixed
     */
    private function getLocaleCode($store)
    {
        $localeCode = $this->_scopeConfig->getValue('general/locale/code', 'stores', $store->getId());
        return str_replace('_', '-', strtolower($localeCode));
    }

    /**
     * @return StoreInterface[]
     */
    private function getStores()
    {
        return $this->_storeManager->getStores();
    }
}
