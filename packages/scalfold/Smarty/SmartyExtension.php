<?

namespace KaizenNexus\Survey\Smarty;

use KaizenNexus\Scalfold\Smarty\Modifier\LoadVite;
use Smarty;

class SmartyExtension
{
    public static function register(Smarty $smarty): void
    {
        $smarty->registerPlugin(
        'modifier',
        'load_vite',
        [LoadVite::class, 'execute']
    );
    }
}
