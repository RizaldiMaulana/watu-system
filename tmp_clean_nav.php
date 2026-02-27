<?php

$file = 'c:\laragon\www\watu-system\resources\views\layouts\navigation.blade.php';
$content = file_get_contents($file);

// Replace Tailwind arbitrary variables with CSS variables
$content = str_replace('bg-[var(--color-primary)]/5', 'bg-primary-5', $content);
$content = str_replace('shadow-[var(--color-primary)]/20', 'shadow-primary-20', $content);
$content = str_replace('bg-[var(--color-primary)]', 'bg-primary', $content);
$content = str_replace('text-[var(--color-primary)]', 'text-primary', $content);
$content = str_replace('hover:text-[var(--color-primary)]', 'hover:text-primary', $content);
$content = str_replace('group-hover:text-[var(--color-primary)]', 'group-hover:text-primary', $content);

// Change text size and layout of AP & AR to prevent wrapping issues, or use "Piutang & Hutang"
$content = str_replace("{{ __('Hutang & Piutang') }}", "{{ __('AP & AR') }}", $content);

// Remove overflow-hidden from the <nav> element to prevent toggle button from being cut off
// Wait, looking at line 5:
// class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-all duration-300 ease-in-out flex flex-col justify-between whitespace-nowrap overflow-hidden">
$content = str_replace('whitespace-nowrap overflow-hidden">', 'whitespace-nowrap z-[100]">', $content);


// Wait, "pengaturan sistem menjadi menu tersendiri"
// We need to move the Settings link OUT of the Data Master dropdown.
// Let's find the Settings block:
$settingsBlock = <<<HTML
                            <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('settings.*') ? 'text-primary font-bold bg-primary-5' : 'text-gray-500 hover:text-primary' }}">
                                <span class="text-xs">●</span>
                                <span class="font-medium text-sm">{{ __('Pengaturan Sistem') }}</span>
                            </x-nav-link>
HTML;

$content = str_replace($settingsBlock, "", $content);

// We want to add it as a standalone menu item above the User Profile block.
// Profile block starts with:    <!-- User Profile & Collapse Button -->
$standaloneSettings = <<<HTML
            <!-- Settings (Standalone) -->
            @if(in_array(Auth::user()->role, ['admin', 'owner']))
                <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('settings.*') ? 'bg-primary text-white shadow-md shadow-primary-20' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}"
                    x-bind:class="sidebarCollapsed ? 'justify-center' : ''">
                    <svg class="w-5 h-5 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">{{ __('Pengaturan Sistem') }}</span>
                </x-nav-link>
            @endif
            
        </div>
    </div>

    <!-- User Profile & Collapse Button -->
HTML;

$content = str_replace("        </div>\n    </div>\n\n    <!-- User Profile & Collapse Button -->", $standaloneSettings, $content);

file_put_contents($file, $content);
echo "Navigation updated successfully.\n";
