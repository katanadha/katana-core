<?
namespace KaizenNexus\Core\Installer;

use KaizenNexus\Core\Context\Context;
use KaizenNexus\Core\Util\Filesystem;

class ModuleInstaller
{
    public function __construct(private Context $context) {}

    public function install($package): void
    {
        // $destination = $this->context->moduleDir() . 'kaizen_survey';
        // $source = realpath(__DIR__ . "/../../../{$package}/src/eccube");

        
        // Filesystem::copyRecursive($source, $destination);

        $src = $package->basePath() . '/' . $package;
        $dst = $this->context->moduleDir() . $package;
        Filesystem::copyRecursive($src, $dst);
    }
}
