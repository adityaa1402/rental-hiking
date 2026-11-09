<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ImageSetupController extends Controller
{
    public function copyImages()
    {
        $sourceDir = __DIR__ . '/../../storage/app/public/barang';
        $destDir = __DIR__ . '/../../public/assets/images';

        // Create destination directory if not exists
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $files = glob($sourceDir . '/product-*.png');
        $copied = 0;
        $results = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $dest = $destDir . '/' . $filename;
            if (copy($file, $dest)) {
                $copied++;
                $results[] = "✓ " . $filename;
            } else {
                $results[] = "✗ Failed: " . $filename;
            }
        }

        return view('admin.image-setup', [
            'copied' => $copied,
            'results' => $results,
            'step' => 'copied'
        ]);
    }

    public function seedDatabase()
    {
        try {
            Artisan::call('db:seed', ['--class' => 'BarangGallerySeeder']);
            $output = Artisan::output();

            return view('admin.image-setup', [
                'output' => $output,
                'step' => 'seeded',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return view('admin.image-setup', [
                'error' => $e->getMessage(),
                'step' => 'seeded',
                'success' => false
            ]);
        }
    }

    public function status()
    {
        $destDir = __DIR__ . '/../../public/assets/images';
        $images = glob($destDir . '/product-*.png');
        $imageCount = count($images);

        return response()->json([
            'status' => 'ok',
            'images_copied' => $imageCount,
            'total_expected' => 16,
            'images' => array_map('basename', $images)
        ]);
    }
}
