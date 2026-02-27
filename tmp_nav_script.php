<?php

$file = 'c:\laragon\www\watu-system\resources\views\layouts\navigation.blade.php';
$content = file_get_contents($file);

// Replace Tailwind Arbitrary Values with CSS Variables
$content = str_replace('bg-[#5f674d]', 'bg-[var(--color-primary)]', $content);
$content = str_replace('text-[#5f674d]', 'text-[var(--color-primary)]', $content);
$content = str_replace('shadow-[#5f674d]', 'shadow-[var(--color-primary)]', $content);
$content = str_replace('hover:text-[#5f674d]', 'hover:text-[var(--color-primary)]', $content);
$content = str_replace('group-hover:text-[#5f674d]', 'group-hover:text-[var(--color-primary)]', $content);

// Update Logo
$content = preg_replace(
    "/<img src=\"\{\{ asset\('images\/LOGO Produk.png'\) \}\}\".*?>/",
    "<img src=\"{{ asset(setting('logo_navigation', 'images/LOGO Produk.png')) }}\" alt=\"Logo\" class=\"h-8 w-auto\">",
    $content
);

// Update Company Name (optional enhancement for sidebar)
$content = preg_replace(
    '/>\s*WATU\s*<span.*?SYSTEM<\/span>\s*</s',
    '>{{ explode(" ", setting("company_name", "WATU SYSTEM"))[0] ?? "WATU" }} <span class="text-[var(--color-primary)]">{{ (explode(" ", setting("company_name", "WATU SYSTEM"))[1] ?? "SYSTEM") . " " . (explode(" ", setting("company_name", "WATU SYSTEM"))[2] ?? "") }}</span><',
    $content
);

file_put_contents($file, $content);
echo "Done replacing navigation";
