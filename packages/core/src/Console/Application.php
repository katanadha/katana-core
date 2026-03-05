<?
namespace KaizenNexus\Core\Console;

use Symfony\Component\Console\Application as SymfonyApp;
use KaizenNexus\Console\Command\MakeModuleCommand;
use KaizenNexus\Console\Command\DevLinkCommand;

class Application extends SymfonyApp
{
    public function __construct()
    {
        parent::__construct('Kaizen Nexus', '1.0.0');

        $this->add(new MakeModuleCommand());
        $this->add(new DevLinkCommand());
    }
}
