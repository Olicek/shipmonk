<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Entity
 * @ORM\Entity
 */
class ProductDimension
{

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  private ?int $id = null;

  /**
   * @ORM\Column(type="string")
   */
  private string $dimension;

  /**
   * ProductDTO constructor.
   *
   * @param int    $id
   * @param string $dimension
   */
  public function __construct(int $id, string $dimension)
  {
    $this->id = $id;
    $this->dimension = $dimension;
  }

  /**
   * @return int|null
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @return float|string
   */
  public function getDimension()
  {
    return $this->dimension;
  }

} // Product
