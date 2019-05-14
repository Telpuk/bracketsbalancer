<?php
declare(strict_types=1);
/**
 * test.
 *
 * @license   MIT
 * @author    tet
 */
namespace Cycle\ORM\Config;
use Cycle\ORM\Exception\ConfigException;
use Cycle\ORM\Relation;
use Cycle\ORM\Select;
use Spiral\Core\Container\Autowire;
use Spiral\Core\InjectableConfig;
final class test extends InjectableConfig
{
 public function create(PropertiesInterface $properties): Response
    {
        [
            'create_api' => $api
        ] = $this->hubSpotConfig->get('contact');

        $request = new Request(
            'POST',
            $this->hubSpotHttpClientService->getUriWithAuth($api),
            ['Content-Type' => 'application/json'],
            $this->jsonEncoder->encode(
                ['properties' => $properties->getProperties()]
            )
        );

        return $this->hubSpotHttpClientService->request($request);
    }
}
