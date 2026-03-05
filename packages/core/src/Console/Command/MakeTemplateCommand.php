<?

use KaizenNexus\Console\Command;

class MakeTemplateCommand extends Command
{
    public function handle(array $argv): void
    {
        $this->parseOptions($argv);

        $name = $argv[2] ?? null;
        if (!$name) {
            echo "Template name required\n";
            exit(1);
        }

        $dir = $this->dir();

        $target = "html/{$dir}/{$name}.tpl";
        $stub   = __DIR__ . '/stubs/template.tpl';

        if (file_exists($target)) {
            echo "Template already exists\n";
            exit(1);
        }

        copy($stub, $target);
        echo "✔ Template created: {$target}\n";
    }
}
