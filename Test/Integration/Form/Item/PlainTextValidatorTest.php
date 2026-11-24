<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Test\Integration\Form\Item;

use Magento\Framework\DataObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use YireoTraining\ExampleLokiAdminCmsPage\Form\Item\PlainTextValidator;

class PlainTextValidatorTest extends TestCase
{
    public function testPlainTextAndHtmlContentAreAllowed(): void
    {
        $item = new DataObject([
            'title' => 'About us & more',
            'identifier' => 'about-us',
            'meta_description' => 'Everything about us',
            'content' => '<p>HTML is allowed in the content</p>',
        ]);

        $this->assertTrue((new PlainTextValidator())->validate($item));
    }

    #[DataProvider('getPlainTextFields')]
    public function testHtmlIsRejectedInPlainTextFields(string $field): void
    {
        $item = new DataObject([$field => '<img src=x onerror=alert(1)>']);

        $result = (new PlainTextValidator())->validate($item);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertStringContainsString($field, (string)$result[0]);
    }

    public static function getPlainTextFields(): array
    {
        return array_map(fn (string $field) => [$field], PlainTextValidator::PLAIN_TEXT_FIELDS);
    }
}
