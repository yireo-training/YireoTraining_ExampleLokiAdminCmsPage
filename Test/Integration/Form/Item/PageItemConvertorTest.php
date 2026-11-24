<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Test\Integration\Form\Item;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DbIsolation;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use YireoTraining\ExampleLokiAdminCmsPage\Form\Item\PageItemConvertor;

#[AppArea('adminhtml')]
#[DbIsolation(true)]
class PageItemConvertorTest extends TestCase
{
    private const LAYOUT_UPDATE = '<referenceContainer name="content"><block class="Foo"/></referenceContainer>';

    public function testOnlyEditableFieldsOfAnExistingPageAreKept(): void
    {
        $pageId = $this->createPage();
        $item = Bootstrap::getObjectManager()->get(PageRepositoryInterface::class)->getById($pageId);
        $item->setTitle('Changed title');
        $item->setContent('<p>Changed content</p>');
        $item->setLayoutUpdateXml(self::LAYOUT_UPDATE);
        $item->setCustomLayoutUpdateXml(self::LAYOUT_UPDATE);
        $item->setCustomTheme('42');
        $item->setPageLayout('empty');
        $item->setOrigData();

        $page = $this->getConvertor()->beforeSave($item);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertNotSame($item, $page);
        $this->assertSame($pageId, (int)$page->getId());
        $this->assertSame('Changed title', $page->getTitle());
        $this->assertSame('<p>Changed content</p>', $page->getContent());
        $this->assertEmpty($page->getLayoutUpdateXml());
        $this->assertEmpty($page->getCustomLayoutUpdateXml());
        $this->assertEmpty($page->getCustomTheme());
        $this->assertSame('1column', $page->getPageLayout());
        $this->assertSame('Original title', $page->getOrigData('title'));
        $this->assertEmpty($page->getOrigData('layout_update_xml'));
    }

    public function testOnlyEditableFieldsOfANewPageAreKept(): void
    {
        $item = new DataObject([
            'title' => 'New page',
            'identifier' => 'new-page',
            'layout_update_xml' => self::LAYOUT_UPDATE,
            'custom_theme' => '42',
            'orig_data' => ['layout_update_xml' => self::LAYOUT_UPDATE],
        ]);

        $page = $this->getConvertor()->beforeSave($item);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertNull($page->getId());
        $this->assertSame('New page', $page->getTitle());
        $this->assertSame('new-page', $page->getIdentifier());
        $this->assertNull($page->getLayoutUpdateXml());
        $this->assertNull($page->getCustomTheme());
        $this->assertNull($page->getData('orig_data'));
        $this->assertNull($page->getOrigData());
    }

    private function getConvertor(): PageItemConvertor
    {
        return Bootstrap::getObjectManager()->create(PageItemConvertor::class);
    }

    private function createPage(): int
    {
        $objectManager = Bootstrap::getObjectManager();
        $page = $objectManager->create(Page::class);
        $page->setData([
            'title' => 'Original title',
            'identifier' => 'loki-convertor-test-page-' . uniqid(),
            'is_active' => 1,
            'page_layout' => '1column',
            'content' => '<p>Original content</p>',
            'stores' => [0],
        ]);
        $objectManager->get(PageResource::class)->save($page);

        return (int)$page->getId();
    }
}
