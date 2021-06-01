<?php declare(strict_types = 1);

namespace App\Api;

use App\DTO\ProductDTO;
use App\Entity\Packaging;
use App\Entity\ProductDimension;
use App\Exceptions\ApiException;
use App\Exceptions\InvalidArgumentexception;
use App\Exceptions\NoResultException;
use App\Repository\PackagingRepository;
use App\Repository\ProductPackageRepository;
use JsonException;

/**
 * Class CachedDbpApi
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Api
 */
class CachedDbpApi extends DbpApi
{

  /**
   * TODO: doplnit komentar
   *
   * @var PackagingRepository
   */
  private PackagingRepository $packagingRepository;

  /**
   * CachedDbpApi constructor.
   *
   * @param PackagingRepository $packagingRepository
   */
  public function __construct(PackagingRepository $packagingRepository)
  {
    $this->packagingRepository = $packagingRepository;
  }

  /**
   * @param Packaging[]  $packages
   * @param ProductDTO[] $products
   *
   * @return Packaging
   * @throws NoResultException
   * @throws ApiException
   * @throws InvalidArgumentexception
   * @throws JsonException
   */
  public function getSingleBinPacking(array $packages, array $products): Packaging
  {
    try
    {
      return $this->packagingRepository->fetchByProductDimensions($packages, $products);
    }
    catch(NoResultException $e)
    {
      $packaging = parent::getSingleBinPacking($packages, $products);

      foreach($products as $product)
      {
        $productDimension = new ProductDimension($product->getId(), $product->getKey());
      }

      // TODO: Save

      return $packaging;
    }
  }

} // CachedDbpApi
