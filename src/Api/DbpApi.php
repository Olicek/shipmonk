<?php declare(strict_types = 1);

namespace App\Api;

use App\DTO\ProductDTO;
use App\Entity\Packaging;
use App\Exceptions\ApiException;
use App\Exceptions\InvalidArgumentexception;
use App\Exceptions\NoResultException;
use GuzzleHttp\Client;
use JsonException;

/**
 * Class DbpApi
 *
 * @copyright (c) 2021 Sportisimo s.r.o.
 * @package       App\Api
 */
class DbpApi implements IDbpApi
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
  public function getSingleBinPacking(array $packages, array $products): Packaging
  {
    $bins = [];
    foreach($packages as $packaging)
    {
      $bins[] = [
        'w' => $packaging->getWidth(),
        'h' => $packaging->getHeight(),
        'd' => $packaging->getLength(),
        'max_wg' => $packaging->getMaxWeight(),
        'id' => $packaging->getId(),
      ];
    }

    if(empty($bins))
    {
      throw new NoResultException('No package has been found.');
    }

    $items = [];

    foreach($products as $product)
    {
      $key = $product->getKey();
      if(isset($items[$key]))
      {
        $quantity = (int) $items[$key]['q'] + 1;
        $items[$key]['q'] = (string) $quantity;
      }
      else
      {
        $items[$key] = [
          'w' => $product->getWidth(),
          'h' => $product->getHeight(),
          'd' => $product->getLength(),
          'q' => '1',
          'vr' => '1',
          'wg' => $product->getWeight(),
          'id' => $product->getId()
        ];
      }
    }

    $data = [
      'bins' => $bins,
      'items' => $items,
      'username' => 'oli',
      'api_key' => '186630beeb629209ec13d0ba1db8991c',
//      'params' => [
//        'images_background_color' => '255,255,255',
//        'images_bin_border_color' => '59,59,59',
//        'images_bin_fill_color' => '230,230,230',
//        'images_item_border_color' => '214,79,79',
//        'images_item_fill_color' => '177,14,14',
//        'images_item_back_border_color' => '215,103,103',
//        'images_sbs_last_item_fill_color' => '99,93,93',
//        'images_sbs_last_item_border_color' => '145,133,133',
//        'images_width' => '100',
//        'images_height' => '100',
//        'images_source' => 'file',
//        'images_sbs' => '1',
//        'stats' => '1',
//        'item_coordinates' => '1',
//        'images_complete' => '1',
//        'images_separated' => '1'
//      ]
    ];

    $query = json_encode($data, JSON_THROW_ON_ERROR);

//    $client = new Client();
//
//    $res = $client->request('POST', 'http://eu.api.3dbinpacking.com/packer/pack', [
//      'auth' => ['user', 'pass']
//    ]);

    $url = "http://eu.api.3dbinpacking.com/packer/pack";
    $prepared_query = 'query=' . $query;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $prepared_query);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resp = curl_exec($ch);
    if(curl_errno($ch))
    {
      throw new ApiException('Error #' . curl_errno($ch) . ': ' . curl_error($ch));
    }
    curl_close($ch);

    $jsonResponse = json_decode($resp, true, 512, JSON_THROW_ON_ERROR);

    $response = $jsonResponse['response'];

    $apiException = null;
    // display errors
    if(isset($response['errors']) && !empty($response['errors']))
    {
      $apiException = new ApiException('Response contains some errors');
      foreach($response['errors'] as $error)
      {
        if(isset($error['message']))
        {
          $apiException->setErrors($error['message']);
        }
        else
        {
          throw new InvalidArgumentexception('Response contains errors without message');
        }
      }
    }

    if(!isset($response['status']) || $response['status'] !== 1)
    {
      throw new ApiException('Some critical error');
    }

    if($apiException !== null)
    {
      // TODO: Co se ma stat?
      throw $apiException;
    }

    $bestPackage = null;
    // display data
    if($response['status'] > -1)
    {
      $bestPackageValue = 0;
      $b_packed = $response['bins_packed'];
      foreach($b_packed as $bin)
      {
        if(!empty($bin['not_packed_items']))
        {
          continue;
        }

        if(!isset($bin['bin_data'], $bin['bin_data']['id']))
        {
          // TODO: Zalogovat, ze k tomu doslo
          continue;
        }

        $binId = (int) $bin['bin_data']['id'];

        foreach($packages as $package)
        {
          if ($package->getId() === (int) $binId)
          {
            $packaging = $package;
            break;
          }
        }

        // Ziska velikost baleni
        $actualValue = $packaging->getLength() * $packaging->getHeight() * $packaging->getHeight();
        if($bestPackage === null)
        {
          $bestPackage = $packaging;
          $bestPackageValue = $actualValue;
          continue;
        }

        // Pokud je hodnota doposud nejlepsiho baleni vetsi, nez aktualni baleni, tak se nastavi aktualni balnei jako nejlepsi
        if($bestPackageValue > $actualValue)
        {
          $bestPackage = $packaging;
          $bestPackageValue = $actualValue;
        }
      }
    }

    if($bestPackage === null)
    {
      throw new NoResultException('Best package is not found');
    }

    return $bestPackage;
  } // singleBinPacking()

} // DbpApi
