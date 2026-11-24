<?php declare(strict_types=1);

namespace YireoTraining\ExampleLokiAdminCmsPage\Form\Item;

use Loki\AdminComponents\Form\Item\ItemValidatorInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\DataObject;

class PlainTextValidator implements ItemValidatorInterface
{
    public const PLAIN_TEXT_FIELDS = [
        PageInterface::TITLE,
        PageInterface::IDENTIFIER,
        PageInterface::CONTENT_HEADING,
        PageInterface::META_TITLE,
        PageInterface::META_KEYWORDS,
        PageInterface::META_DESCRIPTION,
    ];

    public function validate(DataObject $item): true|array
    {
        $errors = [];
        foreach (self::PLAIN_TEXT_FIELDS as $field) {
            $value = $item->getData($field);
            if (is_scalar($value) && preg_match('/[<>]/', (string)$value)) {
                $errors[] = (string)__('The field "%1" may not contain HTML.', $field);
            }
        }

        return empty($errors) ? true : $errors;
    }
}
