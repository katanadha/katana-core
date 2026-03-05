<?

use KaizenNexus\Console\Command;

class MakePageCommand extends Command
{
    public function handle(array $argv): void
    {
        $this->parseOptions($argv);

        $name = ucfirst($argv[2] ?? '');
        $dir  = $this->dir();

        if (!$name) {
            echo "Page name required\n";
            exit(1);
        }

        // PHP
        $pageClass = "LC_Page_{$name}";
        $phpPath   = "data/class/pages/{$dir}/{$pageClass}.php";

        // Template
        $tplPath   = "html/{$dir}/{$name}.tpl";

        // generate
        file_put_contents($phpPath, str_replace('{{class}}', $pageClass, file_get_contents(__DIR__.'/stubs/page.php')));
        file_put_contents($tplPath, file_get_contents(__DIR__.'/stubs/page.tpl'));

        echo "✔ Page created ({$dir})\n";
    }
}
