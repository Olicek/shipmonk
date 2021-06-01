<?php declare(strict_types = 1);

namespace App\Services;

use App\Api\DbpApi;
use App\Api\IDbpApi;
use App\DTO\ProductDTO;
use App\Entity\Packaging;
use App\Exceptions\ApiException;
use App\Exceptions\InvalidArgumentexception;
use App\Exceptions\NoResultException;
use App\Repository\PackagingRepository;
use JsonException;

/**
 * Class PackingService
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Services
 */
class PackagingService
{

  private DbpApi $dbpApi;
  /**
   * TODO: doplnit komentar
   *
   * @var PackagingRepository
   */
  private PackagingRepository $packagingRepository;

  /**
   * PackingService constructor.
   *
   * @param DbpApi              $dbpApi
   * @param PackagingRepository $packagingRepository
   */
  public function __construct(IDbpApi $dbpApi, PackagingRepository $packagingRepository)
  {
    $this->dbpApi = $dbpApi;
    $this->packagingRepository = $packagingRepository;
  }

  /**
   * Ziska baleni na zaklade produktu.
   *
   * @param string $productsJson
   *
   * @return Packaging
   * @throws InvalidArgumentexception Pokud jsou spatne zadana vstupni data.
   * @throws JsonException Pokud se nepodari json_decode.
   * @throws NoResultException Pokud se nenajde entita (packaging).
   * @throws ApiException Pokud se nepodari spojit s api.
   */
  public function getPackingByProducts(string $productsJson): Packaging
  {
    $products = json_decode($productsJson, true, 512, JSON_THROW_ON_ERROR);

    $packages = $this->getPackages();

    if(!isset($products['products']))
    {
      throw new InvalidArgumentexception('JSON has wrong structure. Root node "products" missing');
    }
    $productsDTO = $this->prepareProductsDTO($products['products']);

    return $this->dbpApi->getSingleBinPacking($packages, $productsDTO);
  }

  /**
   * @return Packaging
   */
  public function getDefaultPackaging(): Packaging
  {
    $this->packagingRepository->fetchDefault();
  } // getDefaultPackaging()

  /**
   * Vrati vsechny baliky nebo vyhodi vyjimku, pokud zadny nenajde.
   * @return Packaging[]
   * @throws NoResultException
   */
  private function getPackages(): array
  {
    $packages = $this->packagingRepository->findAll();
    if(empty($packages))
    {
      throw new NoResultException('No package has been found.');
    }
    return $packages;
  }

  /**
   * Pripravi prepravku na data pro zadane produkty.
   *
   * @param array $products
   *
   * @return array
   * @throws InvalidArgumentexception
   */
  private function prepareProductsDTO(array $products): array
  {
    $productsDTO = [];
    foreach($products as $product)
    {
      if(!isset($product[ProductDTO::ID]))
      {
        throw new InvalidArgumentexception(ProductDTO::ID . ' has not been set');
      }

      if(!isset($product[ProductDTO::HEIGHT]))
      {
        throw new InvalidArgumentException(ProductDTO::HEIGHT . ' has not been set');
      }

      if(!isset($product[ProductDTO::LENGTH]))
      {
        throw new InvalidArgumentException(ProductDTO::LENGTH . ' has not been set');
      }

      if(!isset($product[ProductDTO::WEIGHT]))
      {
        throw new InvalidArgumentException(ProductDTO::WEIGHT . ' has not been set');
      }

      if(!isset($product[ProductDTO::WIDTH]))
      {
        throw new InvalidArgumentException(ProductDTO::WIDTH . ' has not been set');
      }

      $productsDTO[] = new ProductDTO(
        $product[ProductDTO::ID],
        $product[ProductDTO::WIDTH],
        $product[ProductDTO::HEIGHT],
        $product[ProductDTO::LENGTH],
        $product[ProductDTO::WEIGHT]
      );
    }
    return $productsDTO;
  }

} // PackingService
