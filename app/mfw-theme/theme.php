<?php
// mfw-theme/theme.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-menu/view.php';

/**
 * Generates the complete HTML header of the page, including the title,
 * default styles, optional module-specific styles and scripts, and the main menu.
 *
 * @param string $title   The browser tab title (default: "MFW App").
 * @param array  $assets  Associative array with optional keys:
 *                        - 'css': array of additional CSS file paths for the module.
 *                        - 'js' : array of additional JS file paths for the module.
 *
 * @return string The generated HTML for the header (up to the beginning of the <body> tag).
 */
function mfw_theme_header(string $title = 'MFW App', array $assets = []): string
{
    $menu = mfw_menu_render();

    $default_css = [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
        mfw_url('/mfw-theme/theme.css'),
        mfw_url('/mfw-menu/style.css'),
    ];

    $module_css = $assets['css'] ?? [];
    $module_js = $assets['js'] ?? [];

    $default_css_html = '';
    foreach ($default_css as $href) {
        $default_css_html .= '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES) . '">' . PHP_EOL;
    }

    $module_css_html = '';
    foreach ($module_css as $href) {
        $module_css_html .= '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES) . '">' . PHP_EOL;
    }

    $module_js_html = '';
    foreach ($module_js as $js) {
        if (is_array($js)) {
            $src = htmlspecialchars($js['src'] ?? '', ENT_QUOTES);
            $attrs = $js['attrs'] ?? '';
            
            if (is_array($attrs)) {
                $attr_str = '';
                foreach ($attrs as $key => $value) {
                    if (is_bool($value)) {
                        if ($value) {
                            $attr_str .= ' ' . htmlspecialchars($key, ENT_QUOTES);
                        }
                    } else {
                        $attr_str .= ' ' . htmlspecialchars($key, ENT_QUOTES) . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
                    }
                }
            } else {
                $attr_str = ' ' . $attrs; // legacy string support
            }
    
        } else {
            $src = htmlspecialchars($js, ENT_QUOTES);
            $attr_str = '';
        }
    
        $module_js_html .= "<script src=\"$src\"$attr_str></script>" . PHP_EOL;
    }
    

    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{$title}</title>
    {$default_css_html}
    {$module_css_html}
    {$module_js_html}
</head>
<body>
    {$menu}
HTML;
}


/**
 * Generates the HTML footer with common scripts
 * 
 * @return string HTML footer markup
 */
function mfw_theme_footer(): string
{
    return <<<HTML
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}
