<?php declare(strict_types = 1);

namespace App\Api;

use App\DTO\ProductDTO;
use App\Entity\Packaging;
use App\Exceptions\ApiException;
use App\Exceptions\InvalidArgumentexception;
use App\Exceptions\NoResultException;

/**
 * Interface IDbpApi
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Api
 */
interface IDbpApi
{

  /**
   * @param Packaging[]  $packages
   * @param ProductDTO[] $products
   *
   * @return Packaging
   * @throws ApiException Pokud se nepodari spojit s API serverem.
   * @throws JsonException
   * @throws NoResultException
   * @throws InvalidArgumentexception
   */
  public function getSingleBinPacking(array $packages, array $products): Packaging;

} // IDbpApi
