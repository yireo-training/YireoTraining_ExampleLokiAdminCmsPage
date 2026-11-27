# YireoTraining_ExampleLokiAdminCmsPage

<!-- badges.specs.start -->
<div class="github-specs-badges">

![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo-training/magento2-example-loki-admin-cms-page)

</div>
<!-- badges.specs.end -->


**This Magento 2 module demos the usage of [Loki_AdminComponents](https://github.com/LokiExtensions/Loki_AdminComponents) to code a grid with ease.**

### Installation
```bash
composer require yireo-training/magento2-example-loki-admin-cms-page@dev
bin/magento module:enable YireoTraining_ExampleLokiAdminCmsPage
```
### Usage
Navigate via the menu **Content > Pages (Loki)**.

Note that this example module is not about the usage, it is about the code. See how little code was involved to create this grid.

![Screenshot of grid in action](loki-admin-grid-cms-pages.png)

### Permissions
Next to the ACL resources of this module (**Content > Elements > Pages > Pages (Loki)**), saving requires `Magento_Cms::save`, deleting requires `Magento_Cms::page_delete` and changing design settings requires `Magento_Cms::save_design`. Only the content and SEO fields of a page can be edited; layout updates and design settings are kept as they are.

Adobe Commerce admin role scopes (website or store restrictions of AdminGws) are not supported by this example module: an admin user with access to this page can edit pages of every store view.

## Current status

<!-- badges.test.start -->
<div class="github-pipeline-badges">

![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo-training/YireoTraining_ExampleLokiAdminCmsPage/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo-training/YireoTraining_ExampleLokiAdminCmsPage/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo-training/YireoTraining_ExampleLokiAdminCmsPage/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo-training/YireoTraining_ExampleLokiAdminCmsPage/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo-training/YireoTraining_ExampleLokiAdminCmsPage/compile.yml?label=compile)

</div>
<!-- badges.test.end -->
