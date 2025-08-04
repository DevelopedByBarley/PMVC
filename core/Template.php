<?php

namespace Core;

class Template
{
  private static $compiledPath;

  public static function init()
  {
    self::$compiledPath = base_path('storage/compiled');
    if (!file_exists(self::$compiledPath)) {
      mkdir(self::$compiledPath, 0755, true);
    }
  }

  public static function compile($template)
  {
    // Változók kiírása
    $template = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>', $template);
    $template = preg_replace('/\{\!\!\s*(.+?)\s*\!\!\}/', '<?= $1 ?>', $template);

    // php kódok
    $template = preg_replace('/\{\{\s*php\s*(.+?)\s*\}\}/', '<?php $1 ?>', $template);
    $template = preg_replace('/\{\{\s*php\s*\}\}/', '<?php ?>', $template);
    $template = preg_replace('/\{\{\s*php\s*\/\s*\}\}/', '<?php ?>', $template);

    // Feltételek
    $template = preg_replace('/@if\s*\(\s*(.+?)\s*\)/', '<?php if ($1): ?>', $template);
    $template = preg_replace('/@elseif\s*\(\s*(.+?)\s*\)/', '<?php elseif ($1): ?>', $template);
    $template = preg_replace('/@else/', '<?php else: ?>', $template);
    $template = preg_replace('/@endif/', '<?php endif; ?>', $template);

    // Ciklusok
    $template = preg_replace('/@foreach\s*\(\s*(.+?)\s*\)/', '<?php foreach ($1): ?>', $template);
    $template = preg_replace('/@endforeach/', '<?php endforeach; ?>', $template);

    $template = preg_replace('/@for\s*\(\s*(.+?)\s*\)/', '<?php for ($1): ?>', $template);
    $template = preg_replace('/@endfor/', '<?php endfor; ?>', $template);

    // Auth helpers - JAVÍTVA paraméterekkel
    // @auth vagy @auth('guard_name')
    $template = preg_replace('/@auth\s*\(\s*(.+?)\s*\)/', '<?php if (session($1)): ?>', $template);
    $template = preg_replace('/@auth(?!\s*\()/', '<?php if (session("user")): ?>', $template);
    $template = preg_replace('/@endauth/', '<?php endif; ?>', $template);
    
    // @guest vagy @guest('guard_name')
    $template = preg_replace('/@guest\s*\(\s*(.+?)\s*\)/', '<?php if (!session($1)): ?>', $template);
    $template = preg_replace('/@guest(?!\s*\()/', '<?php if (!session("user")): ?>', $template);
    $template = preg_replace('/@endguest/', '<?php endif; ?>', $template);

    // Helpers
    $template = preg_replace('/@csrf/', '<?php csrf(); ?>', $template);
    $template = preg_replace('/@asset\s*\(\s*(.+?)\s*\)/', '<?= public_file($1) ?>', $template);
    $template = preg_replace('/@lang\s*\(\s*(.+?)\s*\)/', '<?= lang($1) ?>', $template);
    $template = preg_replace('/@paginate\s*\(\s*(.+?)\s*\)/', '<?php paginate($1, false); ?>', $template);
    $template = preg_replace('/@method\s*\(\s*[\'"](.+?)[\'"]\s*\)/', '<input type="hidden" name="_method" value="$1">', $template);

    // Include
    $template = preg_replace('/@include\s*\(\s*(.+?)\s*\)/', '<?= view($1) ?>', $template);

    // Comments
    $template = preg_replace('/\{\{--\s*(.+?)\s*--\}\}/s', '', $template);

    return $template;
  }

  public static function render($path, $data = [])
  {
    $templatePath = base_path("resources/views/{$path}.blade.php");
    $compiledFile = self::$compiledPath . '/' . md5($path) . '.php';

    // Cache ellenőrzés
    if (!file_exists($compiledFile) || filemtime($templatePath) > filemtime($compiledFile)) {
      $template = file_get_contents($templatePath);
      $compiled = self::compile($template);
      file_put_contents($compiledFile, $compiled);
    }

    extract($data);
    ob_start();
    include $compiledFile;
    return ob_get_clean();
  }

  function __(string $key, array $replace = []): string
  {
    return lang($key);
  }
}

// Ideiglenes - töröld a cache-t
function clearTemplateCache() {
    $compiledPath = base_path('storage/compiled');
    if (is_dir($compiledPath)) {
        $files = glob($compiledPath . '/*');
        foreach($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

// Hívd meg egyszer:
clearTemplateCache();
