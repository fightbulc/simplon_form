<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Semantic;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiJsInterface;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseDataInterface;
use Simplon\Form\View\RenderHelper;

class SemanticApiJs implements DropDownApiJsInterface
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $method;
    /**
     * @var DropDownApiResponseDataInterface
     */
    private $onResponseHandler;
    /**
     * @var array|null
     */
    private $data;
    /**
     * @var array|null
     */
    private $signals;

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method = 'GET')
    {
        $this->url = $url;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return SemanticApiJs
     */
    public function setData(array $data): SemanticApiJs
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSignals(): ?array
    {
        return $this->signals;
    }

    /**
     * @param array|null $signals
     *
     * @return SemanticApiJs
     */
    public function setSignals(?array $signals): SemanticApiJs
    {
        $this->signals = $signals;

        return $this;
    }

    /**
     * @return null|string
     */
    public function renderBeforeXHRJsString(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function renderBeforeSendJsString(): ?string
    {
        $data = $this->getData();
        $signals = $this->getSignals();
        $build = ['var data = null'];

        if ($data)
        {
            $build[] = 'data = ' . RenderHelper::jsonEncode($this->getData());
        }

        if ($signals)
        {
            foreach ($signals as $key => $fieldId)
            {
                $build[] = 'data.' . $key . ' = $("#form-' . $fieldId . '").val()';
            }
        }

        if (!empty($build))
        {
            $build[] = 'settings.data = JSON.stringify(data)';

            return implode('; ', $build);
        }

        return null;
    }

    /**
     * @return DropDownApiResponseDataInterface
     */
    public function getOnResponse(): DropDownApiResponseDataInterface
    {
        if (!$this->onResponseHandler)
        {
            $this->onResponseHandler = new SemanticApiResponseData();
        }

        return $this->onResponseHandler;
    }

    /**
     * @param DropDownApiResponseDataInterface $onResponseHandler
     *
     * @return SemanticApiJs
     */
    public function setOnResponseHandler(DropDownApiResponseDataInterface $onResponseHandler): SemanticApiJs
    {
        $this->onResponseHandler = $onResponseHandler;

        return $this;
    }
}