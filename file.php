<?php
/**
 * A file utility class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @license    WTFPL <http://www.wtfpl.net>
 * @package    Jsc/php-helpers
 * @since      July 2014
 *
 */

namespace Jsc;

class File
{
	/**
	 * Deletes a non-empty directory and its sub-directories
	 *
	 * PHP's native rmdir() function requires the directory to be empty. I'll 
	 * recursively delete a directory's files and sub-directories. 
	 *
	 * WARNING! I will recursively delete a non-empty directory! Use me carefully!
	 * Use the $container argument to be safe.
	 *
	 * @static
	 * @access  public
	 * @see     http://stackoverflow.com/questions/11613840 (donald123's answer)
	 * @see     http://us1.php.net/rmdir
	 * @param   $directory  str   the path of the directory to clear
	 * @param   $container  str   the container (e.g., a parent directory); the
	 *                            $directory must start with this path
	 * @return              bool  true if success
	 *
	 */
	public static function rmdir($directory, $container)
	{
		$isSuccess = false; 

		// if the $directory is in the container
		if (\SC\str::startsWith($directory, $container)) {
			// if the $directory argument is a dir
			if (is_dir($directory)) {
				// if $directory is writable
				if (is_writable($directory)) {
					// open the directory
					$dir = opendir($directory);
					// read the first entity
					$entity = readdir($dir);
					// loop through the dir's entities
					while ($entity !== false) {
						// if the entity is not the special chars "." and ".."
						if ($entity != '.' && $entity != '..') {
							// if the entity is a sub-directory
							if (is_dir($directory.DIRECTORY_SEPARATOR.$entity)) {
								// clear and delete the sub-directory
								$isSuccess = self::rmdir(
									$directory.DIRECTORY_SEPARATOR.$entity, 
									$container
								);
							} else {
								// otheriwse, the entity is a file; delete it
								$isSuccess = unlink($directory.DIRECTORY_SEPARATOR.$entity);
							}
							// if an error occurs, stop
							if ( ! $isSuccess) {
								break;
							}
						} else {
							// there was nothing to remove
							// set $isSuccess true in case the directory is empty
							// if it's not empty, $isSuccess will be overwritten anyway
							//
							$isSuccess = true;
						}
						// advance to the next entity
						$entity = readdir($dir);
					}
					// close and remove the directory
					closedir($dir);
					$isSuccess = rmdir($directory.DIRECTORY_SEPARATOR.$entity);
				} else {
					throw new \InvalidArgumentException(
						"rmdir() expects parameter one to be a writable directory; ".
							"'$directory' is not writable"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					"rmdir() expects parameter one to be a valid directory, ".
						"given '$directory'"
				);
			}
		} else {
			throw new \InvalidArgumentException(
				"rmdir() expects parameter one to be a directory within the ".
					"container; '$directory' is not within '$container'"
			);
		}

		return $isSuccess;
	}

	/**
	 * Copies files or directory to the filesystem
	 *
	 * PHP's native copy() function only copies files, not directories. I will
	 * handle both because I'm awesome.
	 *
	 * @static
	 * @access  public
	 * @see     http://stackoverflow.com/questions/2050859 (Felix Kling, 1/12/10)
	 * @throws  InvalidArgumentException  if $source and $destination are not 
	 *                                    valid dir or file names; if $soure
	 *                                    does not exist; if $source is not
	 *                                    readable
	 * @param   $source       str   the source directory path
	 * @param   $destination  str   the destination directory path
	 * @param   $mode         str   the mode of the destination directory as an
	 *                              octal number with a leading zero (ignored
	 *                              on Windows) (optional; if omitted, defaults
	 *                              to '0777', the widest possible access)
	 * @return                bool
	 *
	 */
	public static function copy($source, $destination, $mode = 0777)
	{
		$isSuccess = false;

		// if the $source is a dir
		if (is_dir($source)) {
			// if the $source dir is readable
			if (is_readable($source)) {
				// if the $destination dir does not exist
				if ( ! file_exists($destination)) {
					mkdir($destination, $mode);
				}
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
						if ( ! $isSuccess) {
							break;
						}
					} else {
						// there was nothing to remove
						// set $isSuccess to true in case the directory is empty
						// if it's not empty, $isSuccess will be overwritten on the next iteration
						//
						$isSuccess = true;
					}
					// advance to the next file
					$entity = readdir($sourceDir);
				}
				// close the source directory
				closedir($sourceDir);
			} else {
				throw new \InvalidArgumentException(
					"copy() expects parameter one to be a readable directory; ".
						"'$source' is not readable"
				);
			}
		} else {
			// if $source is a file name
			if (is_file($source)) {
				// if $destination is a file name 
				if (is_file($destination)) {
					// use PHP's native copy() function
					$isSuccess = copy($source, $destination);
				} else {
					throw new \InvalidArgumentException(
						"copy() expects parameter two to be a file name when ".
							"parameter one is a file name; '$source' and ".
							"'$destination' given"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					"copy() expects parameter one to be a valid directory or ".
						"file name; '$source' given"
				);
			}
		}

		return $isSuccess;
	}

	/**
	 * Returns a relative path from an absolute path
	 *
	 * @access  public
	 * @param   $absolute  str  the abosolute path (e.g., 'C:\path\to\folder')
	 * @param   $base      str  the relative base (e.g., 'C:\path\to')
	 * @return             str  the relative path (e.g., 'folder')
	 *
	 */
	public function getRelativePathFromAbsolutePath($absolute, $base)
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
}
