# Versão em PHP

## PHP 7.4

### Validar um CNPJ

```php
require_once 'Cnpj.php';

CNPJ::isValid('12.ABC.345/01DE-35'); // true
CNPJ::isValid('12.ABC.345/01DE-99'); // false
CNPJ::isValid('00.000.000/0000-00'); // false — CNPJ zerado
```

### Calcular o DV

```php
require_once 'Cnpj.php';

CNPJ::calculaDV('12.ABC.345/01DE'); // "35"
CNPJ::calculaDV('000000000001');    // "91"
```

### Tratamento de exceção

```php
try {
    CNPJ::calculaDV('invalido!');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
}
```

---

## PHP 8.1

A versão 8.1 oferece a mesma interface, com uso de `declare(strict_types=1)`, `match`, `never` e `mixed`.

### Validar um CNPJ

```php
require_once 'Cnpj.php';

CNPJ::isValid('12.ABC.345/01DE-35'); // true
CNPJ::isValid('ABCDEFGHIJKL80');     // true
CNPJ::isValid('00000000000000');     // false — CNPJ zerado
```

### Calcular o DV

```php
require_once 'Cnpj.php';

CNPJ::calculaDV('12.ABC.345/01DE'); // "35"
CNPJ::calculaDV('ABCDEFGHIJKL');   // "80"
```

### Tratamento de exceção

```php
try {
    CNPJ::calculaDV('12ABc34501DE'); // lança exceção — letras minúsculas
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
}
```
