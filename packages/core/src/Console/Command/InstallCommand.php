<?
namespace KaizenNexus\Core\Command;

use KaizenNexus\Core\Context\Context;
use RuntimeException;
use KaizenNexus\Core\Installer\ModuleInstaller;

class InstallCommand implements CommandInterface
{
    private string $package;

    public function __construct(
        private Context $context,
        array $argv
    ) {
        $this->package = $argv[2] ?? '';
        if ($this->package === '') {
            throw new RuntimeException('Package name required');
        }
    }

    public function execute(): int
    {
        $installer = new ModuleInstaller($this->context);

        $installer->install($this->package);

        echo "Installed: {$this->package}\n";
        return 0;
    }
}
