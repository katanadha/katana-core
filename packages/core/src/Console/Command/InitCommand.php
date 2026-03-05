<?
namespace KaizenNexus\Core\Command;

use KaizenNexus\Core\Util\Filesystem;
use KaizenNexus\Core\Command\CommandInterface;
use KaizenNexus\Core\Context\Context;

class InitCommand implements CommandInterface
{
    public function __construct(private Context $context) {}

    public function execute(): int
    {
        // $base = $this->context->kaizenDir();

        // foreach (['enums','consts','traits','survey_package','point_package'] as $dir) {
        //     @mkdir($base . $dir, 0777, true);
        // }
        // $source = realpath(__DIR__ . "/../../../scalfold/");
        // echo 'SOURCE**'.  $source;
        // $destination = $this->context->kaizenDir();
        // Filesystem::copyRecursive($source, $destination);
        
        // echo "✔ Module  installed successfully\n";
        // echo "→ {$destination}\n";

        // $html_source =  realpath(__DIR__ . "/../../../scalfold")."/html";
        // echo 'html_SOURCE**'.  $html_source;
        // $html_destination = 'html/kaizen-nexus';
        // echo 'html_destination**'.  $html_destination;
        // Filesystem::copyRecursive($html_source, $html_destination);


    //     $command = "ln -sf "
    // . escapeshellarg($html_source)
    // . " "
    // . escapeshellarg($html_destination);

        // $command = "ln -sf " . escapeshellarg($source) . " " . escapeshellarg($destination);
        // $output = 'Symbolic Link';
        // $return_var = null;
        // echo 'Command >>'. $command;

        // Execute the command
        // exec($command, $output, $return_var);
        // if (is_link($html_destination) || file_exists($html_destination)) {
        //     unlink($html_destination);
        // }

        // if (!symlink($html_source, $html_destination)) {
        //     // throw new \RuntimeException('Failed to create symlink');
        // }

        

        // echo "Kaizen initialized at {$destination}\n";
        // return 0;
        $source = realpath(__DIR__ . '/../../../scalfold');
        $destination = $this->context->kaizenDir();

        /**
         * 1️⃣ Copy everything EXCEPT html/
         */
        Filesystem::copyRecursive(
            $source,
            $destination,
            ['html','node_modules','public']
        );
        symlink($source, $destination);

        echo "✔ Module files installed\n";

        /**
         * 2️⃣ Copy ONLY html/
         */
        $htmlSource = $source . '/html';
        $htmlDestination = 'html/kaizen-nexus';
        Filesystem::copyRecursive($htmlSource, $htmlDestination);
        symlink($htmlSource, $htmlDestination);


        echo "✔ Public assets installed\n";
        return 0;

    }
}
