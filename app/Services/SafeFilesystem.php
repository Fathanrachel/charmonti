<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;

class SafeFilesystem extends BaseFilesystem
{
    /**
     * Replace the contents of the file, safely handling Windows file locking.
     *
     * @param  string  $path
     * @param  string  $content
     * @param  int|null  $mode
     * @return void
     */
    public function replace($path, $content, $mode = null)
    {
        // On Windows OS, atomic rename() can fail with 'Access is denied (code: 5)' 
        // due to anti-virus or background file-indexing locks on .tmp files.
        if (str_starts_with(PHP_OS, 'WIN')) {
            // Direct write prevents the temporary file rename lock conflict
            $this->put($path, $content);
            if ($mode !== null) {
                @chmod($path, $mode);
            }
            return;
        }

        parent::replace($path, $content, $mode);
    }
}
