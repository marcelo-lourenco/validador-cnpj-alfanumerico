<?php

class CNPJ
{
    private const TAMANHO_SEM_DV = 12;
    private const CNPJ_ZERADO = '00000000000000';
    private const PESOS_DV = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    public static function isValid(string $cnpj): bool
    {
        if (preg_match('/[^A-Z\d.\/-]/i', $cnpj)) {
            return false;
        }

        $cnpj = self::removeMascara($cnpj);

        if (!preg_match('/^[A-Z\d]{12}\d{2}$/i', $cnpj) || strtoupper($cnpj) !== $cnpj) {
            return false;
        }

        if ($cnpj === self::CNPJ_ZERADO) {
            return false;
        }

        $dvInformado = substr($cnpj, self::TAMANHO_SEM_DV);
        $dvCalculado = self::calculaDV(substr($cnpj, 0, self::TAMANHO_SEM_DV));

        return $dvInformado === $dvCalculado;
    }

    public static function calculaDV(string $cnpj): string
    {
        if (preg_match('/[^A-Z\d.\/-]/i', $cnpj)) {
            throw new \InvalidArgumentException('Não é possível calcular o DV pois o CNPJ fornecido é inválido');
        }

        $cnpj = self::removeMascara($cnpj);

        if (!preg_match('/^[A-Z\d]{12}$/i', $cnpj) || strtoupper($cnpj) !== $cnpj) {
            throw new \InvalidArgumentException('Não é possível calcular o DV pois o CNPJ fornecido é inválido');
        }

        if ($cnpj === substr(self::CNPJ_ZERADO, 0, self::TAMANHO_SEM_DV)) {
            throw new \InvalidArgumentException('Não é possível calcular o DV pois o CNPJ fornecido é inválido');
        }

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

    private static function removeMascara(string $cnpj): string
    {
        return str_replace(['.', '/', '-'], '', $cnpj);
    }
}
