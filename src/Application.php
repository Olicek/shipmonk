<?php

namespace App;

use App\Services\PackagingService;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Application
{

  /**
   * TODO: doplnit komentar
   *
   * @var PackagingService
   */
  private PackagingService $packagingService;

  /**
   * Application constructor.
   *
   * @param PackagingService $packagingService
   */
  public function __construct(PackagingService $packagingService)
    {
      $this->packagingService = $packagingService;
    }

    public function run(RequestInterface $request): ResponseInterface
    {
      $content = $request->getBody()->getContents();
      if(!is_string($content))
      {
        return new Response(400);
      }

      try
      {
        $packaging = $this->packagingService->getPackingByProducts($content);
      }
      // Spatne zadane parametry na vstupu (chybi u nektereho produktu vstupni data)
      catch(Exceptions\InvalidArgumentexception $e)
      {
        // TODO: zalovoat vjimku
        return new Response(200, [], 'id: ' . $this->packagingService->getDefaultPackaging()->getId());
      }
      // Pokud se nepodari dekodovat json
      catch(\JsonException $e)
      {
        // TODO: zalovoat vjimku
        return new Response(200, [], 'id: ' . $this->packagingService->getDefaultPackaging()->getId());
      }
      // Pokud se nenajde baleni v databazi
      catch(Exceptions\NoResultException $e)
      {
        // TODO: zalovoat vjimku
        return new Response(200, [], 'id: ' . $this->packagingService->getDefaultPackaging()->getId());
      }
      catch(\Exception $e)
      {
        // TODO: zalovoat vjimku
        return new Response(200, [], 'id: ' . $this->packagingService->getDefaultPackaging()->getId());
      }

      // your implementation entrypoint
      return new Response(200, [], 'id: ' . $packaging->getId());
    }

}
