<?php

namespace Jstewmc\PhpHelpers;

/**
 * The directory (aka, "dir") class
 */
class Dir
{
    /**
     * Returns a relative path (aka, "rel") from an absolute path (aka, "abs")
     *
     * For example:
     *
     *   Dir::abs2rel('/foo/bar/baz', '/foo/bar');  // returns "baz"
     *
     * @param   string  $absolute  the abosolute path (e.g., 'C:\path\to\folder')
     * @param   string  $base      the relative base (e.g., 'C:\path\to')
     *
     * @return  string  the relative path (e.g., 'folder') or false on failure
     */
    public static function abs2rel(string $absolute, string $base): string
    {
        // remove trailing slashes and explode absolute path
        $absolute = rtrim($absolute, DIRECTORY_SEPARATOR);
        $absolute = explode(DIRECTORY_SEPARATOR, $absolute);

        // remove trailing slashes and explode base path
        $base = rtrim($base, DIRECTORY_SEPARATOR);
        $base = explode(DIRECTORY_SEPARATOR, $base);

        // get the difference between the two
        $diff = array_diff($absolute, $base);

        return implode(DIRECTORY_SEPARATOR, $diff);
    }

    /**
     * Copies files or directory to the filesystem
     *
     * PHP's native copy() function only copies files, not directories. I will
     * recursively copy a directory and all of its files and sub-directories.
     *
     * If the $destination exists, I will overwrite any existing files with the
     * corresponding file in the $source directory.
     *
     * If $destination does not exist, and $mode is set to false I will throw an
     * InvalidArgumentException. If $mode is an integer (or omitted), I attempt
     * to create the destination directory. I will recursively create destination
     * directories as needed.
     *
     * To copy a file, use PHP's native copy() method.
     *
     * @param  string  $source       the source directory path
     * @param  string  $destination  the destination directory path
     * @param  int     $mode         the mode of the destination directory as an
     *    octal number with a leading zero (ignored on Windows) (optional; if
     *    omitted, defaults to 0777, the widest possible access) (set to false to
     *    throw an exception if the destination directory does not exist)
     *
     * @return  bool  true if successful
     *
     * @throws  \InvalidArgumentException  if $mode is not an integer or false
     * @throws  \InvalidArgumentException  if $source does not exist or is not readable
     * @throws  \InvalidArgumentException  if $destination does not exist or is not writeable
     *
     * @see  http://stackoverflow.com/a/2050909  Felix King's answer to "Copy entire
     *    contents of a directory to another using php" on StackOverflow
     */
    public static function copy(string $source, string $destination, $mode = 0777): bool
    {
        $isSuccess = false;

        // if $mode is neither an integer nor false, short-circuit
        if (!is_integer($mode) && $mode !== false) {
            throw new \InvalidArgumentException(
                "mode should be an octal integer or false"
            );
        }

        // if $source does not exist or is not readable, short-circuit
        if (!is_dir($source) || !is_readable($source)) {
            throw new \InvalidArgumentException(
                "source should be an existing, readable directory"
            );
        }

        // if $destination does not exist and we're allowed to create it
        if (! file_exists($destination) && is_integer($mode)) {
            mkdir($destination, $mode, true);
        }

        // if $destination directory does not exist or is not writable, short-circuit
        if (!is_dir($destination) || !is_writable($destination)) {
            throw new \InvalidArgumentException(
                "destination should be an existing, writable directory " .
                    "(or mode should be an integer)"
            );
        }

        // let's get started
        $isSuccess = false;

        // open the source directory
        $sourceDir = opendir($source);

        // loop through the entities in the source directory
        $entity = readdir($sourceDir);
        while ($entity !== false) {
            // if not the special entities "." and ".."
            if ($entity != '.' && $entity != '..') {
                // if the file is a dir
                if (is_dir($source.DIRECTORY_SEPARATOR.$entity)) {
                    // recursively copy the dir
                    $isSuccess = self::copy(
                        $source.DIRECTORY_SEPARATOR.$entity,
                        $destination.DIRECTORY_SEPARATOR.$entity,
                        $mode
                    );
                } else {
                    // otherwise, just copy the file
                    $isSuccess = copy(
                        $source.DIRECTORY_SEPARATOR.$entity,
                        $destination.DIRECTORY_SEPARATOR.$entity
                    );
                }
                // if an error occurs, stop
                if (! $isSuccess) {
                    break;
                }
            } else {
                // there was nothing to remove
                // set $isSuccess to true in case the directory is empty
                // if it's not empty, $isSuccess will be overwritten on the next iteration
                $isSuccess = true;
            }
            // advance to the next file
            $entity = readdir($sourceDir);
        }

        // close the source directory
        closedir($sourceDir);

        return $isSuccess;
    }

    /**
     * Deletes a non-empty directory and its sub-directories
     *
     * PHP's native rmdir() function requires the directory to be empty. I'll
     * recursively delete a directory's files and sub-directories. BE CAREFUL!
     * Use the $container argument to be safe.
     *
     * @param  string  $directory  the path of the directory to remove
     * @param  string  $container  an ancestor directory of $directory
     *
     * @return  bool  true if success
     *
     * @throws  \InvalidArgumentException  if $directory does not exist or is not writeable
     * @throws  \InvalidArgumentException  if $directory is not contained in $container
     *
     * @see  http://stackoverflow.com/a/11614201  donald123's answer to "Remove all
     *    files, folders, and their subfolders with php" on StackOverflow
     * @see  http://us1.php.net/rmdir  rmdir() man page
     *
     */
    public static function remove(string $directory, string $container): bool
    {
        // if $directory does not exist or is not writable, short-circuit
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new \InvalidArgumentException(
                "directory should exist and be writable"
            );
        }

        // if $directory is not in $container, short-circuit
        if (!Str::startsWith($directory, $container)) {
            throw new \InvalidArgumentException(
                "directory should be within container"
            );
        }

        // otherwise, let's get started
        $isSuccess = false;

        // open the directory
        $dir = opendir($directory);

        // read the first entity
        $entity = readdir($dir);

        // loop through the directory's entities
        while ($entity !== false) {
            // if the entity is not the special chars "." and ".."
            if ($entity != '.' && $entity != '..') {
                // if the entity is a sub-directory
                if (is_dir($directory.DIRECTORY_SEPARATOR.$entity)) {
                    // clear and delete the sub-directory
                    $isSuccess = self::remove(
                        $directory.DIRECTORY_SEPARATOR.$entity,
                        $container
                    );
                } else {
                    // otheriwse, the entity is a file; delete it
                    $isSuccess = unlink($directory.DIRECTORY_SEPARATOR.$entity);
                }
                // if an error occurs, stop
                if (! $isSuccess) {
                    break;
                }
            } else {
                // there was nothing to remove
                // set $isSuccess true in case the directory is empty
                // if it's not empty, $isSuccess will be overwritten anyway
                $isSuccess = true;
            }
            // advance to the next entity
            $entity = readdir($dir);
        }

        // close and the resource and remove the final entity
        closedir($dir);
        $isSuccess = rmdir($directory.DIRECTORY_SEPARATOR.$entity);

        return $isSuccess;
    }
}
