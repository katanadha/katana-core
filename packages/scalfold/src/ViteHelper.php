<?
namespace KaizenNexus\Scalfold;
class ViteHelper
{
    public static function asset(string $entry): array
    {
        $manifest = dirname(__DIR__) . '/dist/.vite/manifest.json';
        $data = json_decode(file_get_contents($manifest), true);
        var_dump($manifest[$entry]);
        return [
            'assets/css/tailwind.cs' => array_map(
                fn($c) => __DIR__ . 'dist/assets/' . $c,
                $data[$entry]['css'] ?? []
            ),
        ];
    }
}
