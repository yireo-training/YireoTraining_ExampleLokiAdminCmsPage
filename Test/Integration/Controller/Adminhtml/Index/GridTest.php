<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Test\Integration\Controller\Adminhtml\Index;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class GridTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->resource = 'YireoTraining_ExampleLokiAdminCmsPage::grid';
        $this->uri = 'backend/loki_admin_cms_page/index/grid';
    }

    public function testGridBlockIsSecured(): void
    {
        $this->dispatch($this->uri);
        $response = $this->getResponse();
        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertSame(200, $response->getHttpResponseCode());

        $block = $this->_objectManager->get(LayoutInterface::class)->getBlock('loki_admin_cms_page.grid');
        $this->assertInstanceOf(AbstractBlock::class, $block);
        $this->assertSame('Magento_Cms::page', $block->getData('acl_resource'));
        $this->assertSame('Magento_Cms::save', $block->getData('save_acl_resource'));
        $this->assertSame('Magento_Cms::page_delete', $block->getData('delete_acl_resource'));
        $this->assertSame(PageRepositoryInterface::class, $block->getData('provider'));
        $this->assertSame('repository', $block->getData('provider_handler'));

        $massActions = (array)$block->getData('mass_actions');
        $this->assertSame('Magento_Cms::save', $massActions['enable']['acl_resource'] ?? null);
        $this->assertSame('Magento_Cms::save', $massActions['disable']['acl_resource'] ?? null);
        $this->assertSame('Magento_Cms::page_delete', $massActions['delete']['acl_resource'] ?? null);
    }
}
