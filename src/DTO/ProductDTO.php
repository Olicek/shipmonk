<?php declare(strict_types = 1);

namespace App\DTO;

/**
 * Class ProductDTO
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\DTO
 */
class ProductDTO
{

  public const ID = 'id';
  public const WIDTH = 'width';
  public const HEIGHT = 'height';
  public const LENGTH = 'length';
  public const WEIGHT = 'weight';

  private int $id;

  private float $width;

  private float $height;

  private float $length;

  private float $weight;

  /**
   * ProductDTO constructor.
   * Seradi vstupni parametry od nejvetsiho delka, vyska, sirka.
   *
   * @param int   $id
   * @param float $width
   * @param float $height
   * @param float $length
   * @param float $weight
   */
  public function __construct(int $id, float $width, float $height, float $length, float $weight)
  {
    $this->id = $id;

    $arr= [$width, $height, $length];
    rsort($arr);

    $this->length = $arr[0];
    $this->height = $arr[1];
    $this->width = $arr[2];
    $this->weight = $weight;
  }

  /**
   * @return string
   */
  public function getKey(): string
  {
    return 'k' . $this->getWidth() . '-' . $this->getHeight() . '-' . $this->getLength(
      ) . '-' . $this->getWeight();
  } // getKey()

  /**
   * @return int|null
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @return float
   */
  public function getWidth(): float
  {
    return $this->width;
  }

  /**
   * @return float
   */
  public function getHeight(): float
  {
    return $this->height;
  }

  /**
   * @return float
   */
  public function getLength(): float
  {
    return $this->length;
  }

  /**
   * @return float
   */
  public function getWeight(): float
  {
    return $this->weight;
  }

} // ProductDTO
