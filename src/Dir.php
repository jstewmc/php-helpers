<?php
/**
 * The file for the directory class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc\PhpHelpers <https://github.com/jstewmc/php-helpers>
 *
 */

namespace Jstewmc\PhpHelpers;

/**
 * The dir (aka, "directory") class
 *
 * @since  0.1.0
 */
class Dir
{
	/** 
	 * Alias for Dir::remove() method
	 *
	 * @since  0.1.0
	 * @see    \Jstewmc\PhpHelpers\Dir::remove()
	 */
	public function rm($directory, $container)
	{
		return self::rm($directory, $container);
	}
	
	/**
	 * Deletes a non-empty directory and its sub-directories
	 *
	 * PHP's native rmdir() function requires the directory to be empty. I'll 
	 * recursively delete a directory's files and sub-directories. BE CAREFUL!
	 * Use the $container argument to be safe.
	 *
	 * @since   0.1.0
	 * @param   string  $directory  the path of the directory to remove
	 * @param   string  $container  an ancestor directory of $directory
	 * @return  bool  true if success
	 * @throws  \BadMethodCallException    if $directory or $container is null
	 * @throws  \InvalidArgumentException  if $directory is not a string
	 * @throws  \InvalidArgumentException  if $container is not a string
	 * @throws  \InvalidArgumentException  if $directory is not a valid directory path
	 * @throws  \InvalidArgumentException  if $directory is not writeable
	 * @throws  \InvalidArgumentException  if $directory is not contained in $container
	 * @see     <http://stackoverflow.com/questions/11613840> (donald123's answer)
	 * @see     <http://us1.php.net/rmdir>
	 *
	 */
	public static function remove($directory, $container)
	{
		$isSuccess = false; 

		// if $directory and $container are given
		if ($directory !== null && $container !== null) {
			// if $directory is a string
			if (is_string($directory)) {
				// if $container is a string
				if (is_string($container)) {					
					// if the $directory argument is a dir
					if (is_dir($directory)) {
						// if $directory is writable
						if (is_writable($directory)) {
							// if the $directory is in the $container
							if (\Jstewmc\PhpHelpers\Str::startsWith($directory, $container)) {
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
									__METHOD__."() expects parameter two, container, to contain the directory"
								);
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__."() expects parameter one, directory, to be a writable directory"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects parameter one, directory, to be a valid directory"
						);
					}					
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects the second parameter, container, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects the first parameter, directory, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two string parameters, directory and container"
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
	 * @since   0.1.0
	 * @param   string  $source       the source directory path
	 * @param   string  $destination  the destination directory path
	 * @param   int     $mode         the mode of the destination directory as an
	 *     octal number with a leading zero (ignored on Windows) (optional; if 
	 *     omitted, defaults to 0777, the widest possible access)
	 * @return  bool    true if successful
	 * @see     <http://stackoverflow.com/questions/2050859> (Felix Kling, 1/12/10)
	 *
	 */
	public static function copy($source, $destination, $mode = 0777)
	{
		$isSuccess = false;

		// if $source and $destination are given
		if ($source !== null && $destination !== null) {
			// if $source is a string
			if (is_string($source)) {
				// if $destination is a string
				if (is_string($destination)) {
					// if $mode is an integer
					if (is_integer($mode)) {
						// if the $source is a dir
						if (is_dir($source)) {
							// if the $source dir is readable
							if (is_readable($source)) {
								// if the $destination dir does not exist
								if ( ! file_exists($destination)) {
									mkdir($destination, $mode, true);
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
									__METHOD__."() expects parameter one, source, to be a readable directory"
								);
							}
						} elseif (is_file($source)) {
							// if $destination is a file name 
							if (is_file($destination)) {
								// use PHP's native copy() function
								$isSuccess = copy($source, $destination);
							} else {
								throw new \InvalidArgumentException(
									__METHOD__."() expects parameter two, destination, to be a file name when ".
										"parameter one, source, is a file name"
								);
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__."() expects parameter one to be a valid directory or file name"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects parameter three, mode, to be an integer"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, destination, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, source, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two or three parameters: source, destination, and mode"
			);
		}

		return $isSuccess;
	}
	
	/** 
	 * Alias for Dir::copy() method
	 *
	 * @since  0.1.0
	 * @see    \Jstewmc\PhpHelpers\Dir::copy()
	 */
	public function cp($source, $destination, $mode = 0777)
	{
		return self::cp($source, $destination, $mode);
	}

	/**
	 * Returns a relative path from an absolute path
	 *
	 * @since   0.1.0
	 * @param   string  $absolute  the abosolute path (e.g., 'C:\path\to\folder')
	 * @param   string  $base      the relative base (e.g., 'C:\path\to')
	 * @return  string  the relative path (e.g., 'folder')
	 * @throws  \BadMethodCallException    if $absolute or $base is null
	 * @throws  \InvalidArgumentException  if $absolute is not a string
	 * @throws  \InvalidArgumentException  if $base is not a string
	 */
	public static function abs2rel($absolute, $base)
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
