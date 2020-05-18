<?php
/**
 * Copyright © Graycore, LLC. All rights reserved.
 * See LICENSE.md for details.
 */
namespace Graycore\Cors\Plugin\Rest;

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\Rest\Request as RestRequest;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Webapi\Controller\Rest as RestController;
use Graycore\Cors\Response\HeaderManager;

/**
 * PreflightRequestHandler is responsible for returning a
 * 200 response to an options request on a REST endpoint.
 * @author    Graycore <damien@graycore.io>
 * @copyright Graycore, LLC (https://www.graycore.io/)
 * @license   MIT https://github.com/graycoreio/magento2-cors/license
 * @link      https://github.com/graycoreio/magento2-cors
 */
class PreflightRequestHandler
{

    /** @var HttpResponse */
    private $_response;

    /** @var HeaderManager */
    private $headerManager;

    public function __construct(HttpResponse $response, HeaderManager $headerManager)
    {
        $this->_response = $response;
        $this->_headerManager = $headerManager;
    }

    /**
     * @param RestController $subject
     * @param callable $next
     * @param RequestInterface $request
     * @return \Magento\Framework\App\Response\HttpInterface
     */
    public function aroundDispatch(RestController $subject, callable $next, RequestInterface $request)
    {
        if ($request instanceof Http && $request->isOptions()) {
            return $this->_headerManager->applyHeaders($this->_response);
        }

        return $next($request);
    }
}
