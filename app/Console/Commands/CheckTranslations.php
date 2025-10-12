<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class CheckTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:check
                            {--paths=app,resources,routes : Comma-separated list of paths to scan for translation keys}
                            {--seeder=database/seeders/TranslationSeeder.php : Seeder file that must contain the keys}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure every __t() translation key used in the codebase exists in the TranslationSeeder';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pathsOption = $this->option('paths') ?? '';
        $paths = array_filter(array_map('trim', explode(',', $pathsOption)));

        if (empty($paths)) {
            $this->error('No paths provided to scan.');
            return self::FAILURE;
        }

        $scanPaths = [];
        foreach ($paths as $path) {
            $fullPath = base_path($path);
            if (!is_dir($fullPath)) {
                $this->warn("Skipping missing path: {$path}");
                continue;
            }
            $scanPaths[] = $fullPath;
        }

        if (empty($scanPaths)) {
            $this->error('None of the provided paths exist.');
            return self::FAILURE;
        }

        $finder = new Finder();
        $finder->files()
            ->in($scanPaths)
            ->name('*.php')
            ->name('*.blade.php');

        $translationKeys = [];
        $pattern = '/__t\s*\(\s*[\'"]([^\'"]+)[\'"]/u';

        foreach ($finder as $file) {
            $contents = $file->getContents();

            if (preg_match_all($pattern, $contents, $matches)) {
                foreach ($matches[1] as $key) {
                    // Skip obviously dynamic keys that we cannot resolve statically
                    if (str_contains($key, '$') || str_contains($key, '{')) {
                        continue;
                    }
                    $translationKeys[$key] = true;
                }
            }
        }

        ksort($translationKeys);
        $this->info('Discovered ' . count($translationKeys) . ' unique translation keys in the codebase.');

        $seederPath = base_path($this->option('seeder'));
        if (!is_file($seederPath)) {
            $this->error("Seeder file not found: {$seederPath}");
            return self::FAILURE;
        }

        $seederContents = file_get_contents($seederPath);

        $missingKeys = [];
        foreach (array_keys($translationKeys) as $key) {
            if (!$this->keyExistsInSeeder($seederContents, $key)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            $this->error('The following translation keys are missing from the seeder:');
            foreach ($missingKeys as $key) {
                $this->line(" - {$key}");
            }
            return self::FAILURE;
        }

        $this->info('All translation keys are present in the seeder file.');
        return self::SUCCESS;
    }

    /**
     * Determine if a translation key exists within the seeder contents.
     */
    private function keyExistsInSeeder(string $seederContents, string $key): bool
    {
        $escaped = preg_quote($key, '/');
        $pattern = "/['\"]{$escaped}['\"]\s*=>/u";
        return (bool) preg_match($pattern, $seederContents);
    }
}
