<?php

namespace Simplon\Form\Elements\Anchor;

use Simplon\Form\Elements\CoreElement;

class AnchorElement extends CoreElement
{
    protected $elementHtml = '<a href=":url" class=":class" id=":id">:label</a>';
    protected $url;

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add options
        $coreFieldPlaceholders['class'] = 'btn btn-default btn-wide btn-embossed';

        // add options
        $coreFieldPlaceholders['url'] = $this->getUrl();

        return $coreFieldPlaceholders;
    }

    /**
     * @param mixed $url
     *
     * @return AnchorElement
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
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