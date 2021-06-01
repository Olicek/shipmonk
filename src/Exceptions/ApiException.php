<?php declare(strict_types = 1);

namespace App\Exceptions;

/**
 * Class ApiException
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Exceptions
 */
class ApiException extends \Exception
{

  private array $errors = [];

  /**
   * @return array
   */
  public function getErrors(): array
  {
    return $this->errors;
  }

  /**
   * @param string $error
   */
  public function setErrors(string $error): void
  {
    $this->errors[] = $error;
  }

} // ApiException
