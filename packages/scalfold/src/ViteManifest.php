<?

namespace KaizenNexus\Scalfold;

class ViteManifest
{
    private array $manifest;
    private string $publicBaseUrl;

    public function __construct()
    {
        // filesystem path (read JSON)
        $manifestPath = dirname(__DIR__).'/html/kaizen-nexus/dist/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            throw new \RuntimeException('Vite manifest not found: ' . $manifestPath);
        }

        $this->manifest = json_decode(file_get_contents($manifestPath), true);

        
        $this->publicBaseUrl = '/kaizen-assets';
    }

    public function css(string $entry): ?string
    {

        return dirname(__DIR__). '/html/kaizen-nexus/dist/assets/style-CALGPmQ1.css';
        if (!isset($this->manifest[$entry])) {
            return null;
        }

        return $this->publicBaseUrl . '/'
            . $this->manifest[$entry]['file'];
    }
}
