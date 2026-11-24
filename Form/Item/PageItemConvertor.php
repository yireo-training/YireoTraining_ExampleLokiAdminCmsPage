<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Form\Item;

use Loki\AdminComponents\Form\Item\ItemConvertorInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\DataObject;

class PageItemConvertor implements ItemConvertorInterface
{
    public const EDITABLE_FIELDS = [
        PageInterface::TITLE,
        PageInterface::IDENTIFIER,
        PageInterface::IS_ACTIVE,
        PageInterface::CONTENT_HEADING,
        PageInterface::CONTENT,
        PageInterface::META_TITLE,
        PageInterface::META_KEYWORDS,
        PageInterface::META_DESCRIPTION,
        PageInterface::SORT_ORDER,
    ];

    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
        private readonly PageFactory $pageFactory,
    ) {
    }

    public function afterLoad(DataObject $item): DataObject
    {
        return $item;
    }

    public function beforeSave(DataObject $item): DataObject
    {
        $page = $this->getPage((int)$item->getData(PageInterface::PAGE_ID));
        foreach (self::EDITABLE_FIELDS as $field) {
            if ($item->hasData($field)) {
                $page->setData($field, $item->getData($field));
            }
        }

        return $page;
    }

    private function getPage(int $pageId): Page
    {
        if ($pageId > 0) {
            /** @var Page $page */
            $page = $this->pageRepository->getById($pageId);

            return $page;
        }

        return $this->pageFactory->create();
    }
}
