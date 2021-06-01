<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 */
class Packaging
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="float")
     */
    private float $width;

    /**
     * @ORM\Column(type="float")
     */
    private float $height;

    /**
     * @ORM\Column(type="float")
     */
    private float $length;

    /**
     * @ORM\Column(type="float")
     */
    private float $maxWeight;

  /**
   *
   *  * @ORM\ManyToMany(targetEntity="ProductDimension", inversedBy="features")
   * @ORM\JoinTable(name="product_dimensions_to_packaging",
   *      joinColumns={@ORM\JoinColumn(name="product_dimension_id", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="packaging_id", referencedColumnName="id")}
   *      )
   * @var PersistentCollection
   */
    private PersistentCollection $productDimensions;

    public function __construct(float $width, float $height, float $length, float $maxWeight)
    {
        $this->width = $width;
        $this->height = $height;
        $this->length = $length;
        $this->maxWeight = $maxWeight;
        $this->productDimensions = new PersistentCollection();
    }

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
   * @param float $width
   */
  public function setWidth(float $width): void
  {
    $this->width = $width;
  }

  /**
   * @return float
   */
  public function getHeight(): float
  {
    return $this->height;
  }

  /**
   * @param float $height
   */
  public function setHeight(float $height): void
  {
    $this->height = $height;
  }

  /**
   * @return float
   */
  public function getLength(): float
  {
    return $this->length;
  }

  /**
   * @param float $length
   */
  public function setLength(float $length): void
  {
    $this->length = $length;
  }

  /**
   * @return float
   */
  public function getMaxWeight(): float
  {
    return $this->maxWeight;
  }

  /**
   * @param float $maxWeight
   */
  public function setMaxWeight(float $maxWeight): void
  {
    $this->maxWeight = $maxWeight;
  }

}
