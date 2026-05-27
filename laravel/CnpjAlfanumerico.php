<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjAlfanumerico implements ValidationRule
{
    private const TAMANHO_SEM_DV = 12;
    private const CNPJ_ZERADO = '00000000000000';
    private const PESOS_DV = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !$this->isValid($value)) {
            $fail("O :attribute não é um CNPJ alfanumérico válido.");
        }
    }

    private function isValid(string $cnpj): bool
    {
        if (preg_match('/[^A-Z\d.\/-]/i', $cnpj)) {
            return false;
        }

        $cnpj = $this->removeMascara($cnpj);

        if (!preg_match('/^[A-Z\d]{12}\d{2}$/i', $cnpj) || strtoupper($cnpj) !== $cnpj) {
            return false;
        }

        if ($cnpj === self::CNPJ_ZERADO) {
            return false;
        }

        $dvInformado = substr($cnpj, self::TAMANHO_SEM_DV);

        return $dvInformado === $this->calculaDV(substr($cnpj, 0, self::TAMANHO_SEM_DV));
    }

    private function calculaDV(string $cnpj): string
    {
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

        return "{$dv1}{$dv2}";
    }

    private function removeMascara(string $cnpj): string
    {
        return str_replace(['.', '/', '-'], '', $cnpj);
    }
}
