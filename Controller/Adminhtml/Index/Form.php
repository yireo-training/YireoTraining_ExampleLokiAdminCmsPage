<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;

/**
 * Class Index
 *
 * @package YireoTraining\ExampleLokiAdminCmsPage\Controller\Index
 */
class Form extends Action
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'YireoTraining_ExampleLokiAdminCmsPage::form';

    /**
     * @var ResultPageFactory
     */
    private $resultPageFactory;


    /**
     * @param Context $context
     * @param ResultPageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        ResultPageFactory $resultPageFactory,
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return Page
     * @throws \Exception
     */
    public function execute(): Page
    {
        $title = __('Create Page');
        if ($this->getRequest()->getParam('id')) {
            $title = __('Edit Page');
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('YireoTraining_ExampleLokiAdminCmsPage::index');
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
