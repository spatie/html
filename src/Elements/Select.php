<?php

namespace Spatie\Html\Elements;

use Spatie\Html\Selectable;
use Spatie\Html\BaseElement;

class Select extends BaseElement
{
    /** @var string */
    protected $tag = 'select';

    /** @var array */
    protected $options = [];

    /** @var string */
    protected $value = '';

    /**
     * @param string $name
     *
     * @return static
     */
    public function name(?string $name)
    {
        return $this->attribute('name', $name);
    }

    /**
     * @param iterable $options
     *
     * @return static
     */
    public function options(iterable $options)
    {
        return $this->addChildren($options, function ($text, $value) {
            return Option::create()
                ->value($value)
                ->text($text)
                ->selectedIf($value === $this->value);
        });
    }

    public function placeholder(string $text)
    {
        return $this->prependChild(
            Option::create()
                ->value('')
                ->text($text)
                ->selectedIf(! $this->hasSelection())
        );
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function value(?string $value)
    {
        $element = clone $this;

        $element->value = $value;

        $element->applyValueToOptions();

        return $element;
    }

    protected function hasSelection(): bool
    {
        return $this->children->contains->hasAttribute('selected');
    }

    protected function applyValueToOptions()
    {
        $this->children = $this->children->map(function ($child) {
            if ($child instanceof Selectable) {
                return $child->selectedIf($this->value === $child->getAttribute('value'));
            }

            return $child;
        });
    }
}
