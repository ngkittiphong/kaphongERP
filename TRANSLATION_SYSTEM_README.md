# Laravel Translation System Documentation

## Overview

This Laravel 11.40.0 project now includes a comprehensive translation system that supports multiple languages with a focus on Thai and English. The system is designed to be scalable, efficient, and follows Laravel best practices.

## Features

- ✅ Database-driven translations with proper normalization
- ✅ Efficient caching system for performance
- ✅ Language switcher UI component
- ✅ Middleware-based locale handling
- ✅ Session-based language persistence
- ✅ Fallback to English for missing translations
- ✅ Scalable design for future language additions

## Database Schema

### Translation Keys Table (`translation_keys`)
- `id` - Primary key
- `key` - Unique translation key identifier (e.g., 'sidebar.home')
- `group` - Translation group for organization (default: 'default')
- `description` - Description of what this translation is for
- `is_active` - Whether this translation key is active
- `created_at`, `updated_at` - Timestamps

### Translation Values Table (`translation_values`)
- `id` - Primary key
- `translation_key_id` - Foreign key to translation_keys
- `locale` - Language code (e.g., 'en', 'th')
- `value` - Translated text
- `is_active` - Whether this translation is active
- `created_at`, `updated_at` - Timestamps

## Architecture Components

### 1. Models
- **TranslationKey**: Manages translation keys with relationships
- **TranslationValue**: Manages translation values with locale support

### 2. Service Layer
- **TranslationService**: Handles all translation logic with caching
  - `get($key, $locale, $default)` - Get translation for key and locale
  - `getAllForLocale($locale)` - Get all translations for a locale
  - `getByGroup($group, $locale)` - Get translations by group
  - `set($key, $locale, $value, $group, $description)` - Add/update translation
  - `clearCache($key, $locale)` - Clear translation cache

### 3. Middleware
- **SetLocale**: Automatically sets application locale from session

### 4. Controller
- **LanguageController**: Handles language switching requests

### 5. Helper Function
- **`__t($key, $default, $locale)`**: Global helper for translations

## Usage Examples

### In Blade Templates
```blade
<!-- Basic usage with fallback -->
{{ __t('sidebar.home', 'Home') }}

<!-- With specific locale -->
{{ __t('common.save', 'Save', 'th') }}

<!-- In attributes -->
<button title="{{ __t('common.edit', 'Edit') }}">
    {{ __t('common.edit', 'Edit') }}
</button>
```

### In Controllers
```php
use App\Services\TranslationService;

class MyController extends Controller
{
    public function index(TranslationService $translationService)
    {
        $title = $translationService->get('page.title', app()->getLocale(), 'Default Title');
        return view('page', compact('title'));
    }
}
```

### Adding New Translations
```php
use App\Services\TranslationService;

$translationService = app(TranslationService::class);
$translationService->set('new.key', 'en', 'English Text', 'group_name', 'Description');
$translationService->set('new.key', 'th', 'ข้อความไทย', 'group_name', 'Description');
```

## Language Switcher

The language switcher is automatically included in the main layout and provides:
- Visual dropdown with current language
- Smooth switching with loading states
- Automatic page reload after language change
- Error handling for failed requests

### Location
- Component: `resources/views/components/language-switcher.blade.php`
- Included in: `resources/views/layout/master_layout.blade.php`

## Caching System

The translation system uses Laravel's cache system with:
- **Cache Key Pattern**: `translations:{locale}:{key}`
- **TTL**: 1 hour (3600 seconds)
- **Automatic Cache Clearing**: When translations are updated
- **Fallback Strategy**: English → Key → Default value

## Configuration

### Supported Locales
Currently configured for:
- `en` - English
- `th` - Thai

To add more locales:
1. Update `SetLocale` middleware
2. Update `LanguageController`
3. Add translations in seeder
4. Update language switcher component

### Session Configuration
The system uses Laravel's default session configuration. Language preference is stored in the session under the `locale` key.

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── LanguageController.php
│   └── Middleware/
│       └── SetLocale.php
├── Models/
│   ├── TranslationKey.php
│   └── TranslationValue.php
└── Services/
    └── TranslationService.php

database/
├── migrations/
│   ├── 2025_09_28_174844_create_translation_keys_table.php
│   └── 2025_09_28_174848_create_translation_values_table.php
└── seeders/
    └── TranslationSeeder.php

resources/views/
├── components/
│   └── language-switcher.blade.php
├── includes/
│   └── global_scripts.blade.php
└── sidebar/
    └── sidebar01.blade.php
```

## Installation & Setup

1. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

2. **Seed Sample Translations**:
   ```bash
   php artisan db:seed --class=TranslationSeeder
   ```

3. **Clear Cache** (if needed):
   ```bash
   php artisan cache:clear
   ```

## Best Practices

### 1. Translation Key Naming
- Use dot notation: `group.subgroup.key`
- Be descriptive: `sidebar.compose_new_mail` not `btn1`
- Use lowercase with underscores: `common.save_button`

### 2. Grouping Translations
- `common.*` - Common UI elements (buttons, labels)
- `sidebar.*` - Sidebar-specific translations
- `menu.*` - Menu items
- `form.*` - Form labels and messages
- `error.*` - Error messages

### 3. Performance Optimization
- Use caching (already implemented)
- Batch load translations for pages
- Consider lazy loading for large translation sets

### 4. Maintenance
- Regular cache clearing after translation updates
- Monitor translation completeness
- Use fallback values for missing translations

## Troubleshooting

### Common Issues

1. **Translations not showing**:
   - Check if middleware is registered in `Kernel.php`
   - Verify session is working
   - Clear cache: `php artisan cache:clear`

2. **Language switcher not working**:
   - Check JavaScript console for errors
   - Verify CSRF token is included
   - Ensure routes are registered

3. **Cache issues**:
   - Clear application cache
   - Check cache driver configuration
   - Verify cache permissions

### Debug Commands
```bash
# Check current locale
php artisan tinker
>>> session('locale')

# Clear translation cache
php artisan tinker
>>> app(App\Services\TranslationService::class)->clearCache()

# Test translation
php artisan tinker
>>> __t('sidebar.home', 'Home')
```

## Future Enhancements

1. **Admin Interface**: Web interface for managing translations
2. **Import/Export**: CSV/JSON import/export functionality
3. **Translation Validation**: Check for missing translations
4. **Pluralization**: Support for plural forms
5. **Context Support**: Context-aware translations
6. **Translation Memory**: Reuse similar translations

## Support

For issues or questions about the translation system:
1. Check this documentation
2. Review the code comments
3. Test with the provided seeder data
4. Use Laravel's debugging tools

---

**Created**: September 28, 2025  
**Version**: 1.0  
**Laravel Version**: 11.40.0
