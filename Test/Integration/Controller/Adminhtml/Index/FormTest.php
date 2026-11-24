<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Test\Integration\Controller\Adminhtml\Index;

use Loki\AdminComponents\Acl\ActionAuthorization;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\TestCase\AbstractBackendController;
use YireoTraining\ExampleLokiAdminCmsPage\Form\Item\PageItemConvertor;
use YireoTraining\ExampleLokiAdminCmsPage\Form\Item\PlainTextValidator;

/**
 * @magentoAppArea adminhtml
 */
class FormTest extends AbstractBackendController
{
    private const HIDDEN_FIELDS = [
        'page_layout',
        'layout_update_xml',
        'custom_layout_update_xml',
        'custom_root_template',
        'custom_theme',
        'custom_theme_from',
        'custom_theme_to',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->resource = 'YireoTraining_ExampleLokiAdminCmsPage::form';
        $this->uri = 'backend/loki_admin_cms_page/index/form';
    }

    public function testFormBlockIsSecured(): void
    {
        $block = $this->getFormBlock();

        $this->assertSame('Magento_Cms::save', $block->getData('acl_resource'));
        $this->assertSame('Magento_Cms::save', $block->getData('save_acl_resource'));
        $this->assertSame('Magento_Cms::page_delete', $block->getData('delete_acl_resource'));
        $this->assertSame(PageRepositoryInterface::class, $block->getData('provider'));
        $this->assertSame('repository', $block->getData('provider_handler'));
        $this->assertInstanceOf(PageItemConvertor::class, $block->getData('item_convertor'));
        $this->assertInstanceOf(PlainTextValidator::class, $block->getData('item_validators')['plain_text'] ?? null);

        $fields = (array)$block->getData('fields');
        foreach (self::HIDDEN_FIELDS as $hiddenField) {
            $this->assertFalse($fields[$hiddenField]['visible'] ?? true, $hiddenField);
        }
    }

    public function testWriteActionsRequireCmsPermissions(): void
    {
        $block = $this->getFormBlock();
        $checkedResources = [];
        $authorization = $this->createMock(AuthorizationInterface::class);
        $authorization->method('isAllowed')->willReturnCallback(
            function (string $resource) use (&$checkedResources) {
                $checkedResources[] = $resource;
                return false;
            }
        );

        $actionAuthorization = new ActionAuthorization($authorization);
        $this->assertFalse($actionAuthorization->isAllowed($block, 'save'));
        $this->assertFalse($actionAuthorization->isAllowed($block, 'duplicate'));
        $this->assertFalse($actionAuthorization->isAllowed($block, 'delete'));
        $this->assertSame(['Magento_Cms::save', 'Magento_Cms::save', 'Magento_Cms::page_delete'], $checkedResources);
    }

    private function getFormBlock(): AbstractBlock
    {
        $this->dispatch($this->uri);
        $response = $this->getResponse();
        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertSame(200, $response->getHttpResponseCode());

        $block = $this->_objectManager->get(LayoutInterface::class)->getBlock('loki_admin_cms_page.form');
        $this->assertInstanceOf(AbstractBlock::class, $block);

        return $block;
    }
}
