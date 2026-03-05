<?
namespace KaizenNexus\Console;

abstract class Command
{
    protected array $options = [];

    protected function parseOptions(array $argv): void
    {
        foreach ($argv as $arg) {
            if (str_starts_with($arg, '--')) {
                [$key, $value] = array_pad(explode('=', substr($arg, 2), 2), 2, true);
                $this->options[$key] = $value;
            }
        }
    }

    protected function dir(): string
    {
        return $this->options['dir'] ?? 'admin';
    }

    abstract public function handle(array $argv): void;
}
