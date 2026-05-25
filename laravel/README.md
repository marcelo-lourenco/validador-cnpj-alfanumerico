# Versão Laravel

Compatível com **Laravel 10+** (usa `ValidationRule` com `Closure`).

## Rule Class

Copie `CnpjAlfanumerico.php` para `app/Rules/`.

### Uso em FormRequest

```php
use App\Rules\CnpjAlfanumerico;

public function rules(): array
{
    return [
        'cnpj' => ['required', 'string', new CnpjAlfanumerico()],
    ];
}
```

### Uso em `validate()` direto

```php
use App\Rules\CnpjAlfanumerico;

$request->validate([
    'cnpj' => ['required', new CnpjAlfanumerico()],
]);
```

---

## Service Provider (regra como string)

Copie `CnpjAlfanumericoServiceProvider.php` para `app/Providers/` e registre em `config/app.php`:

```php
'providers' => [
    // ...
    App\Providers\CnpjAlfanumericoServiceProvider::class,
],
```

Após registrar, use a regra como string em qualquer lugar:

```php
$request->validate([
    'cnpj' => 'required|cnpj_alfanumerico',
]);
```

```php
Validator::make($data, [
    'cnpj' => 'required|cnpj_alfanumerico',
]);
```
