<?php

namespace Prokopets\Tools\Url;

/**
 * Класс для проверки работоспособности удаленной страницы
 * Class Scaner
 * @package Prokopets\Tools\Url
 */
class Scanner
{
  /**
   * @var array - Массив URL адресов
   */
  protected $urls;

  /**
   * @var \GuzzleHttp\Client - Объект, который делает запросы на удаленный сервер
   */
  protected $httpClient;

  /**
   * Scanner constructor.
   * @param array $urls - массив адресов для сканирования
   */
  public function __construct( array $urls )
  {
    $this->urls = $urls;
    $this->httpClient = new \GuzzleHttp\Client();
  }

  /**
   * Получение списка невалидных неотвечающих адресов
   * @return array $urls - массив невалидных адресов
   */
  public function getInvalidUrls()
  {
    $nvalidUrls = [];

    foreach ( $this->urls as $url ) {
      try {
        $statusCode = $this->getStatusCodeForUrl( $url );
      } catch( \Exception $e ) {
        $statusCode = 500;
      }

      if( $statusCode >= 400 ) {
        array_push( $nvalidUrls, array(
          'url'    => $url,
          'status' => $statusCode
        ) );
      }
    }

    return $nvalidUrls;
  }

  /**
   * Возвращает код состояния HTTP для URL-адреса
   * @param $url - удаленный URL адрес
   * @return mixed - код состояния HTTP
   */
  public function getStatusCodeForUrl( $url )
  {
    $httpResponse = $this->httpClient->options( $url );
    return $httpResponse->getStatusCode();
  }
}