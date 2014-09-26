<?php

namespace Simplon\Form\Elements\Anchor;

use Simplon\Form\Elements\CoreElement;

class AnchorElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<a href=":url" class=":class" id=":id">:label</a>';

    /**
     * @var string
     */
    protected $url;

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add url placeholder
        $coreFieldPlaceholders['url'] = $this->getUrl();

        return $coreFieldPlaceholders;
    }

    /**
     * @param string $url
     *
     * @return AnchorElement
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function render()
    {
        return [
            'element' => $this->parseFieldPlaceholders($this->getElementHtml()),
        ];
    }
}