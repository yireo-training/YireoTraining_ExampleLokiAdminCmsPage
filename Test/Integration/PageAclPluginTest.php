<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Test\Integration;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\Page\Authorization as PageAuthorization;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Cms\Observer\PageAclPlugin;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\ObjectManager\Config\Reader\Dom as DiConfigReader;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DbIsolation;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

#[AppArea('adminhtml')]
#[DbIsolation(true)]
class PageAclPluginTest extends TestCase
{
    private const PLUGIN_NAME = 'YireoTraining_ExampleLokiAdminCmsPage::aclCheck';

    protected function tearDown(): void
    {
        $objectManager = $this->getObjectManager();
        $objectManager->removeSharedInstance(PageAuthorization::class);
        $objectManager->removeSharedInstance(PageAclPlugin::class);
    }

    public function testPluginIsOnlyRegisteredForAdminhtml(): void
    {
        $reader = $this->getObjectManager()->get(DiConfigReader::class);

        $adminhtmlConfig = $reader->read('adminhtml');
        $plugin = $adminhtmlConfig[PageRepositoryInterface::class]['plugins'][self::PLUGIN_NAME] ?? [];
        $this->assertSame(PageAclPlugin::class, $plugin['instance'] ?? null);

        $globalConfig = $reader->read('global');
        $this->assertArrayNotHasKey(
            self::PLUGIN_NAME,
            $globalConfig[PageRepositoryInterface::class]['plugins'] ?? []
        );
    }

    public function testDesignChangeIsDeniedWithoutSaveDesignPermission(): void
    {
        $pageId = $this->createPage();
        $this->denyAllPermissions();

        $pageRepository = $this->getObjectManager()->get(PageRepositoryInterface::class);
        $page = $pageRepository->getById($pageId);
        $page->setPageLayout('empty');

        $this->expectException(AuthorizationException::class);
        $pageRepository->save($page);
    }

    public function testContentChangeIsAllowedWithoutSaveDesignPermission(): void
    {
        $pageId = $this->createPage();
        $this->denyAllPermissions();

        $pageRepository = $this->getObjectManager()->get(PageRepositoryInterface::class);
        $page = $pageRepository->getById($pageId);
        $page->setTitle('Changed title');
        $pageRepository->save($page);

        $this->assertSame('Changed title', $pageRepository->getById($pageId)->getTitle());
    }

    private function denyAllPermissions(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $authorization->method('isAllowed')->willReturn(false);

        $objectManager = $this->getObjectManager();
        $objectManager->addSharedInstance(
            $objectManager->create(PageAuthorization::class, ['authorization' => $authorization]),
            PageAuthorization::class
        );
        $objectManager->removeSharedInstance(PageAclPlugin::class);
    }

    private function createPage(): int
    {
        $objectManager = $this->getObjectManager();
        $page = $objectManager->create(Page::class);
        $page->setData([
            'title' => 'Loki ACL test page',
            'identifier' => 'loki-acl-test-page-' . uniqid(),
            'is_active' => 1,
            'page_layout' => '1column',
            'content' => '<p>Test</p>',
            'stores' => [0],
        ]);
        $objectManager->get(PageResource::class)->save($page);

        return (int)$page->getId();
    }

    private function getObjectManager(): ObjectManager
    {
        /** @var ObjectManager $objectManager */
        $objectManager = Bootstrap::getObjectManager();

        return $objectManager;
    }
}
