<?php

declare(strict_types=1);

require_once __DIR__ . '/Cnpj.php';

try {
    assertEquals('91', CNPJ::calculaDV('000000000001'));
    assertEquals('35', CNPJ::calculaDV('12.ABC.345/01DE'));

    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV(''));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV("'!@#\$%&*-_=+^~"));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV('$0123456789A'));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV('012345?6789A'));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV('0123456789A#'));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV('12ABc34501DE'));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV('00000000000'));
    assertThrows(\InvalidArgumentException::class, fn() => CNPJ::calculaDV('00000000000191'));

    assertTrue(CNPJ::isValid('12.ABC.345/01DE-35'));
    assertTrue(CNPJ::isValid('90.021.382/0001-22'));
    assertTrue(CNPJ::isValid('90.024.778/0001-23'));
    assertTrue(CNPJ::isValid('90.025.108/0001-21'));
    assertTrue(CNPJ::isValid('90.025.255/0001-00'));
    assertTrue(CNPJ::isValid('90.024.420/0001-09'));
    assertTrue(CNPJ::isValid('90.024.781/0001-47'));
    assertTrue(CNPJ::isValid('04.740.714/0001-97'));
    assertTrue(CNPJ::isValid('44.108.058/0001-29'));
    assertTrue(CNPJ::isValid('90.024.780/0001-00'));
    assertTrue(CNPJ::isValid('90.024.779/0001-78'));
    assertTrue(CNPJ::isValid('00000000000191'));
    assertTrue(CNPJ::isValid('ABCDEFGHIJKL80'));

    assertFalse(CNPJ::isValid(''));
    assertFalse(CNPJ::isValid("'!@#\$%&*-_=+^~"));
    assertFalse(CNPJ::isValid('$0123456789ABC'));
    assertFalse(CNPJ::isValid('0123456?789ABC'));
    assertFalse(CNPJ::isValid('0123456789ABC#'));
    assertFalse(CNPJ::isValid('12.ABc.345/01DE-35'));
    assertFalse(CNPJ::isValid('0000000000019'));
    assertFalse(CNPJ::isValid('000000000001911'));
    assertFalse(CNPJ::isValid('0000000000019L'));
    assertFalse(CNPJ::isValid('000000000001P1'));
    assertFalse(CNPJ::isValid('00000000000192'));
    assertFalse(CNPJ::isValid('ABCDEFGHIJKL81'));
    assertFalse(CNPJ::isValid('00000000000000'));
    assertFalse(CNPJ::isValid('00.000.000/0000-00'));

    echo 'All tests passed successfully!' . PHP_EOL;
} catch (\Exception $e) {
    echo 'Some of the tests failed.' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}

function assertTrue(bool $value): void
{
    assertEquals(true, $value);
}

function assertFalse(bool $value): void
{
    assertEquals(false, $value);
}

function assertEquals(mixed $expected, mixed $actual): void
{
    if ($expected !== $actual) {
        throw new \Exception("Assertion error: expected [{$expected}] but was [{$actual}].");
    }
}

function assertThrows(string $expectedClass, callable $fn): void
{
    try {
        $fn();
    } catch (\Throwable $e) {
        if ($e instanceof $expectedClass) {
            return;
        }
        throw new \Exception("Assertion error: expected [{$expectedClass}] but got [" . get_class($e) . "].");
    }
    throw new \Exception("Assertion error: expected [{$expectedClass}] to be thrown but nothing was thrown.");
}
