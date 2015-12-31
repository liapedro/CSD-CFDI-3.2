<?php

namespace JorgeAndrade;

use DateTime;
use DateTimeZone;
use JorgeAndrade\Exceptions\CsdException;

class Csd
{
    protected $cer;

    protected $cerPem;

    protected $key;

    protected $keyPem;

    protected $keyEnc;

    protected $rfc;

    protected $pass;

    protected $path;

    protected $validCer = false;

    protected $validKey = false;

    public function __construct($cer = null, $key = null, $rfc = null, $pass = null, $path = null)
    {
        $this->cer = $cer;
        $this->key = $key;
        $this->rfc = $rfc;
        $this->pass = $pass;
        $this->path = is_null($path) ? getcwd() . "/csds/" : $path;
    }

    public function convertCerToPem()
    {
        $this->cerPem = "{$this->path}{$this->rfc}.cer.pem";
        exec("openssl x509 -inform DER -outform PEM -in {$this->cer} -pubkey -out {$this->cerPem}", $out, $errors);
        if ($errors) {
            $this->throwError('Error al convertir el archivo: .cer, verifica la ruta del archivo.');
        }

        $this->validCer = true;
    }

    public function convertKeyToPem()
    {
        $this->keyPem = "{$this->path}{$this->rfc}.key.pem";
        exec("openssl pkcs8 -inform DER -in {$this->key} -passin pass:{$this->pass} -out {$this->keyPem}", $out, $errors);
        if ($errors) {
            $this->throwError("Error al convertir el archivo: .cer, verificar la contraseña.");
        }

        $this->validKey = true;
    }

    public function encryptPemInToDer($pass)
    {
        if (!$this->validKey) {
            $this->throwError("No haz convertido el archivo .key a .pem");
        }
        $this->keyEnc = "{$this->path}{$this->rfc}.enc.key";

        exec("openssl rsa -in {$this->keyPem} -des3 -out {$this->keyEnc} -passout pass:{$pass}", $out, $errors);

        if ($errors) {
            $this->throwError("Error al encryptar el archivo key.");
        }
    }

    public function verifyValidityPeriod()
    {
        if (!$this->validCer) {
            $this->throwError("No haz convertido el archivo .cer a .pem");
        }

        exec("openssl x509 -noout -in {$this->cerPem} -dates", $out, $errors);

        if ($errors) {
            $this->throwError(".cer", "Ha ocurrido un error al intentar validar el csd.");
        }
        $fecha_inicial = explode("=", $out[0]);
        $fecha_final = explode("=", $out[1]);

        $now = new DateTime("now", new DateTimeZone('America/Mexico_City'));
        $fecha_inicial = new DateTime(end($fecha_inicial), new DateTimeZone('America/Mexico_City'));
        $fecha_final = new DateTime(end($fecha_final), new DateTimeZone('America/Mexico_City'));

        if ($now < $fecha_inicial || $now > $fecha_final) {
            $this->throwError("El csd no es valido.");
        }

        return compact('fecha_inicial', 'fecha_final');
    }

    public function verifyValidCsd()
    {
        exec("openssl x509 -inform DER -in {$this->cer} -subject -noout", $out, $errors);
        if ($errors) {
            $this->throwError("Ha ocurrido un error al intentar validar el csd.");
        }
        $vars = preg_split("/\s?\/\s?/", $out[0]);
        $validCsd = end($vars);
        $valid = explode('=', $validCsd);

        return $valid[0] === 'OU';
    }

    public function getNoCertificado()
    {
        exec("openssl x509 -inform DER -in {$this->cer} -noout -serial", $out, $errors);

        if ($errors) {
            $this->throwError("Ha ocurrido un error al intentar obtener el numero de certificado.");
        }

        $vars = explode("=", $out[0]);
        $certificado = end($vars);
        $no_certificado = '';

        for ($i = 0; $i < strlen($certificado); $i++) {
            if ($i % 2 != 0) {
                $no_certificado .= substr($certificado, $i, 1);
            }
        }

        return $no_certificado;
    }

    protected function throwError($error)
    {
        throw new CsdException(
            sprintf("%s", $error)
        );
    }

}
