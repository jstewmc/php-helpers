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
	 * @param   string  $absolute  the abosolute path (e.g., 'C:\path\to\folder')
	 * @param   string  $base      the relative base (e.g., 'C:\path\to')
	 *
	 * @return  string  the relative path (e.g., 'folder') or false on failure
	 *
	 * @throws  \BadMethodCallException    if $absolute or $base is null
	 * @throws  \InvalidArgumentException  if $absolute is not a string
	 * @throws  \InvalidArgumentException  if $base is not a string
	 */
	public static function abs2rel($absolute, $base)
	{
		$rel = false;

		if ($absolute !== null && $base !== null) {
			if (is_string($absolute)) {
				if (is_string($base)) {
					// remove trailing slashes and explode absolute path
					$absolute = rtrim($absolute, DIRECTORY_SEPARATOR);
					$absolute = explode(DIRECTORY_SEPARATOR, $absolute);

					// remove trailing slashes and explode base path
					$base = rtrim($base, DIRECTORY_SEPARATOR);
					$base = explode(DIRECTORY_SEPARATOR, $base);

					// get the difference between the two
					$diff = array_diff($absolute, $base);

					// implode it yar
					$rel = implode(DIRECTORY_SEPARATOR, $diff);
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, base path, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, absolute path, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two string parameters: absolute path and base path"
			);
		}

		return $rel;
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
	 * @throws  \BadMethodCallException    if $source, $destination, or $mode is null
	 * @throws  \InvalidArgumentException  if $source is not a string
	 * @throws  \InvalidArgumentException  if $destination is not a string
	 * @throws  \InvalidArgumentException  if $mode is not an integer or false
	 * @throws  \InvalidArgumentException  if $source does not exist or is not a directory
	 * @throws  \InvalidArgumentException  if $source is not readable
	 * @throws  \InvalidArgumentException  if $destination does not exist or it could not
	 *    be created successfully
	 * @throws  \InvalidArgumentException  if $destination is not writeable
	 *
	 * @see  http://stackoverflow.com/a/2050909  Felix King's answer to "Copy entire
	 *    contents of a directory to another using php" on StackOverflow
	 */
	public static function copy($source, $destination, $mode = 0777)
	{
		$isSuccess = false;

		if ($source !== null && $destination !== null && $mode !== null) {
			if (is_string($source)) {
				if (is_string($destination)) {
					// if $mode is an integer or false
					if (is_integer($mode) || $mode === false) {
						// if the source directory exists and is a directory
						if (is_dir($source)) {
							// if the source directory is readable
							if (is_readable($source)) {
								// if the destination directory does not exist and we're ok to create it
								if ( ! file_exists($destination) && is_integer($mode)) {
									mkdir($destination, $mode, true);
								}
								// if the destination directory exists and is a directory
								if (is_dir($destination)) {
									// if the destination directory is writable
									if (is_writable($destination)) {
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
											__METHOD__."() expects parameter two, destination, to be a writable directory"
										);
									}
								} else {
									throw new \InvalidArgumentException(
										__METHOD__."() expects parameter two, destination, to be an existing directory ".
											"(or it expects parameter three, mode, to be an integer)"
									);
								}
							} else {
								throw new \InvalidArgumentException(
									__METHOD__."() expects parameter one, source, to be a readable directory"
								);
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__."() expects parameter one, source, to be an existing directory"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects parameter three, mode, to be an integer or false"
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
	 * @see  \Jstewmc\PhpHelpers\Dir::copy()
	 */
	public function cp($source, $destination, $mode = 0777)
	{
		return self::cp($source, $destination, $mode);
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
	 * @throws  \BadMethodCallException    if $directory or $container is null
	 * @throws  \InvalidArgumentException  if $directory is not a string
	 * @throws  \InvalidArgumentException  if $container is not a string
	 * @throws  \InvalidArgumentException  if $directory is not a valid directory path
	 * @throws  \InvalidArgumentException  if $directory is not writeable
	 * @throws  \InvalidArgumentException  if $directory is not contained in $container
	 *
	 * @see  http://stackoverflow.com/a/11614201  donald123's answer to "Remove all
	 *    files, folders, and their subfolders with php" on StackOverflow
	 * @see  http://us1.php.net/rmdir  rmdir() man page
	 *
	 */
	public static function remove($directory, $container)
	{
		$isSuccess = false;

		if ($directory !== null && $container !== null) {
			if (is_string($directory)) {
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
											$isSuccess = self::remove(
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
	 * Alias for Dir::remove() method
	 *
	 * @see  \Jstewmc\PhpHelpers\Dir::remove()
	 */
	public function rm($directory, $container)
	{
		return self::rm($directory, $container);
	}
}
