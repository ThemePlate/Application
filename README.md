# ThemePlate Application

## Usage

### `./vendor/bin/themeplate install`

### wp-config.php

```php
use ThemePlate\Application;
use ThemePlate\Application\Core;
use Env\Env;

require_once 'vendor/autoload.php';

Core::setup( __DIR__ ); // .env location

$application  = new Application();
$table_prefix = Env::get( 'DB_PREFIX' ) ?: 'wp_';

require_once ABSPATH . 'wp-settings.php';
```

### .env

```dotenv
## Relative to this .env file
# PUBLIC_ROOT='public'

## Relative to the PUBLIC_ROOT
# WP_CORE_DIR='wp'
# CONTENT_DIR='content'

WP_HOME='https://themeplate.local'

# DB_NAME='local'
# DB_USER='root'
# DB_PASSWORD='root'
# DB_HOST='localhost'
# DB_PREFIX='wp_'
# DB_CHARSET='utf8'
# DB_COLLATE=''

WP_DEBUG=true
# WP_DEBUG_LOG='/path/to/debug.log'

# WP_ENVIRONMENT_TYPE='local'
# WP_DEFAULT_THEME='themeplate'

# DISABLE_WP_CRON=true
# DISALLOW_FILE_MODS=false

# Authentication Unique Keys and Salts
AUTH_KEY='generateme'
SECURE_AUTH_KEY='generateme'
LOGGED_IN_KEY='generateme'
NONCE_KEY='generateme'
AUTH_SALT='generateme'
SECURE_AUTH_SALT='generateme'
LOGGED_IN_SALT='generateme'
NONCE_SALT='generateme'
```
