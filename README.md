# ThemePlate Application

## Usage

### wp-config.php
```php
use ThemePlate\Application;
use Env\Env;

require_once __DIR__ . '/vendor/autoload.php';

$application  = new Application( __DIR__ );
$table_prefix = Env::get( 'DB_PREFIX' ) ?: 'wp_';

require_once ABSPATH . 'wp-settings.php';
```

### .env
```dotenv
WP_HOME='https://themeplate.local'
# WP_ROOT_DIR='/wp'
# CONTENT_DIR='/content'

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
WP_DEFAULT_THEME='themeplate'

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
