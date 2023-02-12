<?php

namespace App\WebScraper\Adjuster;

class AdjusterService implements AdjusterServiceInterface
{
    /**
     * @param AdjusterInterface[] $adjusters
     */
    public function __construct(
        private readonly iterable $adjusters,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function adjustData(array $data, array $context): array
    {
        foreach ($this->adjusters as $adjuster) {
            if ($adjuster->supports($data, $context)) {
                $data = $adjuster->adjust($data, $context);
            }
        }

        return $data;
    }
}