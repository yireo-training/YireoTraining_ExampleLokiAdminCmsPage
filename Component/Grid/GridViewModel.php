<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Component\Grid;

use Magento\Framework\DataObject;
use Yireo\LokiAdminComponents\Component\Grid\GridViewModel as OriginalGridViewModel;
use Yireo\LokiAdminComponents\Grid\Cell\CellAction;

class GridViewModel extends OriginalGridViewModel
{
    public function getIndexUrl(): string
    {
        return $this->urlFactory->create()->getUrl('loki_admin_cms_page/index/grid');
    }

    public function getNewUrl(): string
    {
        return $this->urlFactory->create()->getUrl('loki_admin_cms_page/index/form');
    }

    public function getRowAction(DataObject $item): CellAction
    {
        $editUrl = $this->urlFactory->create()->getUrl('cms/page/edit', [
            'page_id' => $item->getId(),
        ]);

        return $this->cellActionFactory->create($editUrl, 'Edit');
    }

    public function getSearchableFields(): array
    {
        // @todo: Replace this with automatic logic of product entity
        return [
            'name',
        ];
    }

    protected function getAdditionalActions(DataObject $item): array
    {
        $cellActions = [];

        $editUrl = $this->urlFactory->create()->getUrl('loki_admin_cms_page/index/form', [
            'id' => $item->getId(),
        ]);

        $cellActions[] = $this->cellActionFactory->create($editUrl, 'Quick Edit');

        return $cellActions;
    }
}
