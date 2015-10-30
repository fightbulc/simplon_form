<?php

namespace Simplon\Form\View\Elements\Support\Typeahead;

use Simplon\Form\View\RenderHelper;

/**
 * Class TypeaheadRemoteSource
 * @package Simplon\Form\View\Elements\Support\Typeahead
 */
class TypeaheadRemoteSource implements TypeaheadSourceInterface
{
    use TypeaheadSourceTrait;

    const REQUEST_TYPE_GET = 'GET';
    const REQUEST_TYPE_POST = 'POST';

    /**
     * @var array
     */
    private $assets;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $rootAttr;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var
     */
    private $requestType = self::REQUEST_TYPE_GET;

    /**
     * @var string
     */
    private $responseType = 'json';

    /**
     * @param string $url
     * @param string $rootAttr
     * @param string $idAttr
     * @param string $labelAttr
     */
    public function __construct($url, $rootAttr, $idAttr, $labelAttr)
    {
        $this->url = $url;
        $this->rootAttr = $rootAttr;
        $this->idAttr = $idAttr;
        $this->labelAttr = $labelAttr;
    }

    /**
     * @return string
     */
    public function renderSource()
    {
        $code = RenderHelper::codeLines([
            "$.ajax({ url: '{url}', type: '{request}', dataType: '{response}', data: {{data}}, success: function(r) { if(r.hasOwnProperty('{root}')) { var suggestions = r.{root}; async(suggestions); } } })",
        ]);

        $data = [
            'q: q',
        ];

        foreach ($this->data as $k => $v)
        {
            $data[] = $k . ': "' . $v . '"';
        }

        return RenderHelper::placeholders("function(q, sync, async) { {code} }", [
            'code'     => $code,
            'url'      => $this->url,
            'root'     => $this->rootAttr,
            'data'     => join(', ', $data),
            'request'  => $this->requestType,
            'response' => $this->responseType,
        ]);
    }

    /**
     * @param mixed $requestType
     *
     * @return TypeaheadRemoteSource
     */
    public function setRequestType($requestType)
    {
        if (in_array($requestType, [self::REQUEST_TYPE_GET, self::REQUEST_TYPE_POST]) === false)
        {
            $requestType = self::REQUEST_TYPE_GET;
        }

        $this->requestType = $requestType;

        return $this;
    }

    /**
     * @param string $responseType
     *
     * @return TypeaheadRemoteSource
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return TypeaheadRemoteSource
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param array $assets
     *
     * @return TypeaheadRemoteSource
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return TypeaheadRemoteSource
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}