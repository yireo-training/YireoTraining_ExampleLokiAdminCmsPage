<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Test\Integration;

use Magento\Framework\Acl\Builder as AclBuilder;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use YireoTraining\ExampleLokiAdminCmsPage\Controller\Adminhtml\Index\Form;
use YireoTraining\ExampleLokiAdminCmsPage\Controller\Adminhtml\Index\Grid;

#[AppArea('adminhtml')]
class AclTest extends TestCase
{
    private const MODULE = 'YireoTraining_ExampleLokiAdminCmsPage';

    public function testAclResourcesAreNestedUnderCmsPages(): void
    {
        $acl = Bootstrap::getObjectManager()->get(AclBuilder::class)->getAcl();

        foreach (['index', 'grid', 'form'] as $resource) {
            $resourceId = self::MODULE . '::' . $resource;
            $this->assertTrue($acl->hasResource($resourceId), $resourceId);
            $this->assertTrue($acl->inheritsResource($resourceId, 'Magento_Cms::page'), $resourceId);
        }

        $this->assertTrue($acl->inheritsResource(self::MODULE . '::grid', self::MODULE . '::index', true));
        $this->assertTrue($acl->inheritsResource(self::MODULE . '::form', self::MODULE . '::index', true));
    }

    public function testControllersOnlyAcceptGet(): void
    {
        foreach ([Grid::class => 'grid', Form::class => 'form'] as $controllerClass => $resource) {
            $this->assertTrue(
                (new ReflectionClass($controllerClass))->implementsInterface(HttpGetActionInterface::class),
                $controllerClass
            );
            $this->assertSame(self::MODULE . '::' . $resource, $controllerClass::ADMIN_RESOURCE);
        }
    }
}
