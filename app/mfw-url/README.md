# mfw-url

## Description

The `mfw-url` module provides helper functions to generate absolute paths within the application without relying on hardcoded routes.

Thanks to this module, if you ever decide to move your app into a subdirectory like `/mfw-app/` (for example, in a shared hosting environment), **all routes will continue to work automatically** without having to change anything in your code.

## Available Functions

### `mfw_url(string $path): string`

Generates a complete URL relative to the application’s base path.

**Example:**

```php
mfw_url('/login'); // => /mfw-app/login (if the app is in /mfw-app)
```