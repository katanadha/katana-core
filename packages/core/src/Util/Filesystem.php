<?php
namespace KaizenNexus\Core\Util;

class Filesystem
{
    public static function copyRecursiveOld(string $src, string $dst): void
    {
        if (!is_dir($src)) {
            return;
        }

        if (!is_dir($dst)) {
            mkdir($dst, 0777, true);
        }

        foreach (scandir($src) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $from = $src . '/' . $file;
            $to   = $dst . '/' . $file;

            if (is_dir($from)) {
                self::copyRecursive($from, $to);
            } else {
                copy($from, $to);
            }
        }
    }
    public static function copyRecursive(
        string $src,
        string $dst,
        array $exclude = []
    ): void {
        if (in_array(basename($src), $exclude, true)) {
            return;
        }

        if (is_dir($src)) {
            if (!is_dir($dst)) {
                mkdir($dst, 0775, true);
            }

            foreach (scandir($src) as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                self::copyRecursive(
                    $src . '/' . $file,
                    $dst . '/' . $file,
                    $exclude
                );
            }
        } else {
            copy($src, $dst);
        }
    }

}
