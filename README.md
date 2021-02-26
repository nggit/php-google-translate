# PHP Google Translate
A simple PHP library to translate texts using Google Translate. There is also a [free version](https://github.com/nggit/php-gtranslate-free) of Google Translate (no API key required).
## Install
```
composer require nggit/php-google-translate:dev-master
```
## Usage
```php
require __DIR__ . '/vendor/autoload.php';
use Nggit\Google\Translate;

$translate = new Translate(array('key' => 'your_api_key', 'lang' => array('de' => 'en'))); // translate from german to english
$translate->setText('Der schnelle Braune Fuchs springt über den faulen Hund');

echo $translate->process()->getResults();
```
Enjoy!
