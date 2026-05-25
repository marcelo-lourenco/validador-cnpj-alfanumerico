<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Exemplo de Service Provider para registrar a regra como string.
 *
 * Para usar, adicione esta classe em config/app.php:
 *
 *   'providers' => [
 *       // ...
 *       App\Providers\CnpjAlfanumericoServiceProvider::class,
 *   ],
 *
 * Após registrar, a regra pode ser usada como string:
 *
 *   $request->validate(['cnpj' => 'required|cnpj_alfanumerico']);
 */

class CnpjAlfanumericoServiceProvider extends ServiceProvider
{
    private const TAMANHO_SEM_DV = 12;
    private const CNPJ_ZERADO = '00000000000000';
    private const PESOS_DV = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    public function boot(): void
    {
        Validator::extend('cnpj_alfanumerico', function ($attribute, $value) {
            return is_string($value) && $this->isValid($value);
        }, 'O :attribute não é um CNPJ alfanumérico válido.');
    }

    private function isValid(string $cnpj): bool
    {
        if (preg_match('/[^A-Z\d.\/-]/i', $cnpj)) {
            return false;
        }
        $cnpj = str_replace(['.', '/', '-'], '', $cnpj);
        if (!preg_match('/^[A-Z\d]{12}\d{2}$/i', $cnpj) || strtoupper($cnpj) !== $cnpj) {
            return false;
        }
        if ($cnpj === self::CNPJ_ZERADO) {
            return false;
        }
        $dvInformado = substr($cnpj, self::TAMANHO_SEM_DV);
        $somaDV1 = 0;
        $somaDV2 = 0;
        for ($i = 0; $i < self::TAMANHO_SEM_DV; $i++) {
            $ascii = ord($cnpj[$i]) - ord('0');
            $somaDV1 += $ascii * self::PESOS_DV[$i + 1];
            $somaDV2 += $ascii * self::PESOS_DV[$i];
        }
        $dv1 = $somaDV1 % 11 < 2 ? 0 : 11 - ($somaDV1 % 11);
        $somaDV2 += $dv1 * self::PESOS_DV[self::TAMANHO_SEM_DV];
        $dv2 = $somaDV2 % 11 < 2 ? 0 : 11 - ($somaDV2 % 11);
        return $dvInformado === "{$dv1}{$dv2}";
    }
}
