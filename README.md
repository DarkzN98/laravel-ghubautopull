# laravel-ghubautopull
**Author: Darkzn98 <darkzn98@gmail.com>**

# About
GHubAutoPull is a composer package for Laravel and Lumen. This package used for running `git pull` and `composer install`
on configured server using Github Webhook automatically.

# Requirements
- PHP >= 7.3.9
- Github Webhook
- Server SSH Key Saved to GitHub

# Usage
1. Add the package running this command 

```sh
composer require darkzn/ghubautopull
```

2. Add this package to service your app service provider so the route registered.  

For Laravel `config/app.php`:
```php
'providers' = >[
    // Other Providers
    Darkzn\Ghubautopull\GhubAutopullServiceProvider::class,
],
```

For Lumen `bootstrap/app.php`:
```php
$app->register(Darkzn\Ghubautopull\GhubAutopullServiceProvider::class);
```

3. Configure Github Webhook with the webhook url of `https://{YOUR_APP_URL}/hook.json`. Don't forget to set the Webhook Secret!
4. Configure the `.env` file by adding this code
```txt
# Webhook Secret
GHUB_WEBHOOK_SECRET=testwebhooklaravel
# Webhook Branch (Will exec git pull if github ref matched branch deploy variable)
GHUB_BRANCH_DEPLOY=master
```
5. Test the webhook.
