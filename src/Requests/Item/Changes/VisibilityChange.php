<?php

namespace FINDOLOGIC\Api\Requests\Item\Changes;

class VisibilityChange extends Change
{
    private bool $isVisible = false;

    public function getKey(): string
    {
        return 'visible';
    }

    public function getValue(): bool
    {
        return $this->isVisible();
    }

    public function setVisible(): void
    {
        $this->isVisible = true;
    }

    public function setInvisible(): void
    {
        $this->isVisible = false;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }
}
