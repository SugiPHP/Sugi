## Sugi ##

The second version of Sugi is using a different approach. It will be a micro framework using SugiPHP Components as well as some other components which are well known, well documented and are with decent quality.

### Sugi App ###

<?php

use SugiPHP\Sugi\App;

// Instantiate SugiPHP Application:
$app = new App();

// Or use Singleton pattern:
$app = App::getInstance();
