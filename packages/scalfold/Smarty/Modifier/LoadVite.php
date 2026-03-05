<?
namespace KaizenNexus\Scalfold\Smarty\Modifier;

class LoadVite
{
    public static function execute($value)
    {
        return $value === 'admin';
    }
}
