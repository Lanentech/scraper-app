<?php

namespace App\WebScraper\Adjuster;

use App\WebScraper\Source\SourceInterface;

class PriceAdjuster implements AdjusterInterface
{
    /**
     * @inheritDoc
     */
    public function supports(array $data, array $context): bool
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $datum) {
            if (array_key_exists('price', $datum) && is_numeric($datum['price'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function adjust(array $data, array $context): array
    {
        foreach ($data as &$datum) {
            /** @var SourceInterface $source */
            $source = $context['sourceInstance'];

            if (isset($datum['price']) && is_numeric($datum['price'])) {
                $datum['price'] = $source->getPriceSymbolPrefix() . number_format((float) $datum['price'], 2);
            }
        }

        return $data;
    }
}