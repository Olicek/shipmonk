<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Packaging;
use App\Exceptions\NoResultException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;

/**
 * Class PackingRepository
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Repository
 */
class PackagingRepository
{
  /**
   * TODO: doplnit komentar
   *
   * @var EntityManager
   */
  private EntityManager $entityManager;

  private EntityRepository $entityRepository;

  /**
   * PackingRepository constructor.
   *
   * @throws \Doctrine\ORM\ORMException
   */
  public function __construct()
  {
    $config = Setup::createAnnotationMetadataConfiguration([__DIR__], true, null, null, false);
    $config->setNamingStrategy(new UnderscoreNamingStrategy());

    $entityManager = EntityManager::create([
                                   'driver' => 'pdo_mysql',
                                   'host' => 'mysql',
                                   'user' => 'root',
                                   'password' => 'secret',
                                   'dbname' => 'packing',
                                 ], $config);

    $this->entityManager = $entityManager;
    $this->entityRepository = $this->entityManager->getRepository(Packaging::class);
  }

  /**
   * Vrati jedno, konkretni baleni.
   * @param int $id
   *
   * @return Packaging|object
   */
  public function fetchById(int $id): Packaging
  {
    return $this->entityRepository->find($id);
  }

  /**
   * Vrati vsechny zasilky.
   * @return Packaging[]
   */
  public function findAll(): array
  {
    return $this->entityRepository->findAll();
  }

  /**
   * @param array $packages
   * @param array $products
   *
   * @return Packaging
   * @throws NoResultException
   */
  public function fetchByProductDimensions(array $packages, array $products): Packaging
  {
    throw new NoResultException();
  }

  /**
   * @return Packaging
   */
  public function fetchDefault(): Packaging
  {
    return $this->fetchById(1);
  }

} // PackingRepository
