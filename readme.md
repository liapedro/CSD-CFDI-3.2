# CSD

Este paquete te permite encriptar, validar y convertir archivos .cer y .key en .pem para la facturacion electronica en méxico.

- [Introducción](#introduccion)
- [Instalación](#instalacion)
- [Uso](#uso)
  - [Convirtiendo CER en PEM](#convirtiendo-cer-en-pem)
  - [Convirtiendo KEY en PEM](#convirtiendo-key-en-pem)
  - [Validar vigencia del csd](#validar-vigencia-del-csd)
  - [Encriptar KEY.PEM en DER](#encriptar-key.pem-en-der)
  - [Verificar que es un CSD valido](#verificar-que-es-un-csd-valido)
  - [Obtener numero de certificado](#obtener-numero-de-certificado)
- [Licencia](#licencia)


## Introducción
Csd provee un mecanismo para convertir tus archivos .cer y .key a .pem, los necesarios para poder facturar electronicamente en méxico.

Asi mismo te permite encriptar tus llaves (.key) a .der pasando la contraseña que necesites.

```php
require 'vendor/autoload.php';
use JorgeAndrade\Csd;
use JorgeAndrade\Exceptions\CsdException;

$cer = getcwd() . "/csds/aad990814bp7_1210261233s.cer";
$key = getcwd() . "/csds/aad990814bp7_1210261233s.key";
$rfc = "AAD990814BP7";
$pass = "12345678a";

$validar = new Csd($cer, $key, $rfc, $pass, $path = getcwd() . "/csds/");

try {
    $validar->convertCerToPem();
    $validar->convertKeyToPem();

} catch (CsdException $e) {
    var_dump($e->getMessage());
}
```

## Instalación
Simplemente instala el paquete con composer:

```php
composer require jorgeandrade/csd
```
Una vez composer termine de instalar el paquete simplemente importa el paquete y crea una nueva instancia pasando los parametros correspondientes:

```php
require 'vendor/autoload.php';

use JorgeAndrade\Csd;
use JorgeAndrade\Exceptions\CsdException;

$cer = getcwd() . "/csds/aad990814bp7_1210261233s.cer";
$key = getcwd() . "/csds/aad990814bp7_1210261233s.key";
$rfc = "AAD990814BP7";
$pass = "12345678a"; //contraseña de la llave privada
$path = getcwd() . "/csds/"; //este parametro es opcional, por defecto usa **getcwd() . "/csds/"**

$validar = new Csd($cer, $key, $rfc, $pass, $path);
```

## Uso
Convertir, encryptar, validar y obtener es extremadamente facil.
Si algo sale mal las funciones arrojaran una exception de tipo **JorgeAndrade\Exceptions\CsdException**.
## Convirtiendo CER en PEM
```php
 $validar->convertCerToPem();
```

## Convirtiendo KEY en PEM
```php
 $validar->convertKeyToPem();
```

## Validar vigencia del csd
Si todo sale bien retornara un array con la fecha inicial y la final de validez del certificado, cada uno extiende de la clase **DateTime**.
```php
 $periodo = $validar->verifyValidityPeriod();

 var_dump($periodo);

 /**
array(2) {
  ["fecha_inicial"]=>
  object(DateTime)#4 (3) {
    ["date"]=>
    string(26) "2012-10-26 19:22:43.000000"
    ["timezone_type"]=>
    int(2)
    ["timezone"]=>
    string(3) "GMT"
  }
  ["fecha_final"]=>
  object(DateTime)#5 (3) {
    ["date"]=>
    string(26) "2016-10-26 19:22:43.000000"
    ["timezone_type"]=>
    int(2)
    ["timezone"]=>
    string(3) "GMT"
  }
}
 **/
```

## Encriptar KEY.PEM en DER
Para poder encryptar, primero necesitas convertir de key en pem
```php
$validar->convertKeyToPem();
$validar->encryptPemInToDer($pass);
```

## Verificar que es un CSD valido
```php
$check = $validar->verifyValidCsd(); //(bool)
```

## Obtener numero de certificado
```php
$no_certificado = $validar->getNoCertificado();
```

## Licencia

Csd programa de codigo abierto bajo la licencia [MIT license](http://opensource.org/licenses/MIT)