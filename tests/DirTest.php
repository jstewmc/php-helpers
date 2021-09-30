<?php
/**
 * Tests for the Dir class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc\PhpHelpers <https://github.com/jstewmc/php-helpers>
 */

use Jstewmc\PhpHelpers\Dir;

/**
 * The Dir test class
 */
class DirTest extends \PHPUnit\Framework\TestCase
{
	/* !Protected members */

	/**
	 * @var  the path of the current working directory (aka, "cwd"); this directory
	 *     will be the parent directory of any directories created by tests
	 */
	protected $cwd;


	/* !Magic methods */

	/**
	 * Called before each test
	 *
	 * Sets the value of the current working directory (aka, "cwd").
	 */
	public function setUp(): void
	{
		$this->cwd = dirname(__FILE__);

		return;
	}


	/* !Providers */

	/**
	 * Provides non-string values
	 */
	public function provideNonStringValues()
	{
		return array(
			array(true),
			array(1),
			array(1.0),
			array(array()),
			array(new StdClass())
		);
	}

	/**
	 * Provides non-integer and not-false values
	 */
	public function provideNonIntegerAndNotFalseValues()
	{
		return array(
			array(true),
			array(1.0),
			array('foo'),
			array(array()),
			array(new StdClass())
		);
	}


	/* !abs2rel() */

	/**
	 * abs2rel() should throw a BadMethodCalLException if $absolute and $base are null
	 */
	public function testAbs2Rel_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Dir::abs2rel(null, null);

		return;
	}

	/**
	 * abs2rel() should throw an InvalidArgumentException if $absolute is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testAbs2Rel_throwsInvalidArgumentException_ifAbsoluteIsNotAString($absolute)
	{
		$this->setExpectedException('InvalidArgumentException');
		Dir::abs2rel($absolute, 'foo');

		return;
	}

	/**
	 * abs2rel() should throw an InvalidArgumentException if $base is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testAbs2Rel_throwsInvalidArgumentException_ifBaseIsNotAString($base)
	{
		$this->setExpectedException('InvalidArgumentException');
		Dir::abs2rel('foo', $base);

		return;
	}

	/**
	 * abs2rel() should return $absolute if $base is empty
	 */
	public function testAbs2Rel_returnsAbsolute_ifBaseIsEmpty()
	{
		$input = 'path/to/foo';

		return $this->assertEquals(Dir::abs2rel($input, ''), $input);
	}

	/**
	 * abs2rel() should return $absolute if $base does not match
	 */
	public function testAbs2Rel_returnsAbsolute_ifBaseDoesNotMatch()
	{
		$input = 'path/to/foo';

		return $this->assertEquals(Dir::abs2rel($input, 'bar'), $input);
	}

	/**
	 * abs2rel() should return relative path
	 */
	public function testAbs2Rel_returnsRelative_ifBaseDoesMatch()
	{
		$input    = 'path/to/foo';
		$actual   = Dir::abs2rel($input, 'path/to');
		$expected = 'foo';

		return $this->assertEquals($actual, $expected);
	}


	/* !copy() */

	/**
	 * copy() should throw a BadMethodCallException if $source and $destination is null
	 */
	public function testCopy_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Dir::copy(null, null);

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $source is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testCopy_throwsInvalidArgumentException_ifSourceIsNotAString($source)
	{
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'foo';

		$this->setExpectedException('InvalidArgumentException');
		Dir::copy($source, $destination);

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $destination is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testCopy_throwsInvalidArgumentException_ifDestinationIsNotAString($destination)
	{
		$source = $this->cwd.DIRECTORY_SEPARATOR.'foo';

		$this->setExpectedException('InvalidArgumentException');
		Dir::copy($source, $destination);

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $mode is not an integer or
	 *     not false
	 *
	 * @dataProvider  provideNonIntegerAndNotFalseValues
	 */
	public function testCopy_throwsInvalidArgumentException_ifModeIsNotIntegerAndNotFalse($mode)
	{
		$source      = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'bar';

		$this->setExpectedException('InvalidArgumentException');
		Dir::copy($source, $destination, $mode);

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $source does not exist
	 */
	public function testCopy_throwsInvalidArgumentException_ifSourceDNE()
	{
		$source      = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'bar';

		$this->setExpectedException('InvalidArgumentException');
		Dir::copy($source, $destination);

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $source is not a directory
	 */
	public function testCopy_throwsInvalidArgumentException_ifSourceIsNotADirectory()
	{
		$source      = $this->cwd.DIRECTORY_SEPARATOR.'foo.txt';
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'bar.txt';

		// create the file
		file_put_contents($source, 'hello world');
		$this->assertTrue(file_exists($source));

		// try to copy the file
		// the method should throw an InvalidArgumentException
		// catch it, clean up, and re-throw it
		//
		$this->setExpectedException('InvalidArgumentException');
		try {
			Dir::copy($source, $destination);
		} catch (Exception $e) {
			unlink($source);
			throw $e;
		}

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $destination does not exist
	 *     and $mode is false
	 */
	public function testCopy_throwsInvalidArgumentException_ifDestinationDNEAndModeIsFalse()
	{
		$source      = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'bar';

		// create the source directory
		mkdir($source);
		$this->assertTrue(is_dir($source));

		// try to copy the directory with mode set to false
		// the method will throw an InvalidArgumentException
		// catch it, remove the source directory, and re-throw it
		//
		$this->setExpectedException('InvalidArgumentException');
		try {
			Dir::copy($source, $destination, false);
		} catch (Exception $e) {
			rmdir($source);
			throw $e;
		}

		return;
	}

	/**
	 * copy() should throw an InvalidArgumentException if $destination exists, and is
	 *    not a directory
	 */
	public function testCopy_throwsInvalidArgumentException_ifDestinationIsNotADirectory()
	{
		$source      = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'bar.txt';

		// create the source directory
		mkdir($source);
		$this->assertTrue(is_dir($source));

		// create the destination as a file
		file_put_contents($destination, 'hello world');
		$this->assertTrue(file_exists($destination));

		// try to copy
		// the method should throw an InvalidArgumentException
		// catch the exception, clean up, and re-throw it
		//
		$this->setExpectedException('InvalidArgumentException');
		try {
			Dir::copy($source, $destination);
		} catch (Exception $e) {
			unlink($destination);
			rmdir($source);
			throw $e;
		}
	}

	/**
	 * copy() should copy empty directory
	 */
	public function testCopy_returnsTrue_ifDirectoryIsEmpty()
	{
		// create the source directory
		$source = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		mkdir($source);
		$this->assertTrue(is_dir($source));

		// create the destination directory
		$destination = $this->cwd.DIRECTORY_SEPARATOR.'bar';
		mkdir($destination);
		$this->assertTrue(is_dir($destination));

		// copy source to destination
		$this->assertTrue(Dir::copy($source, $destination));

		// remove the source and destination directories
		rmdir($source);
		rmdir($destination);

		return;
	}

	/**
	 * copy() should copy a non-empty directory
	 */
	public function testCopy_returnsTrue_ifDirectoryIsNotEmpty()
	{
		// create the source "<cwd>/foo" directory
		$foo = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		mkdir($foo);
		$this->assertTrue(is_dir($foo));
		// create the "<cwd>/foo/bar" directory
		$bar1 = $foo.DIRECTORY_SEPARATOR.'bar';
		mkdir($bar1);
		$this->assertTrue(is_dir($bar1));
		// create the "<cwd>/foo/bar/baz" directory
		$baz1 = $bar1.DIRECTORY_SEPARATOR.'baz';
		mkdir($baz1);
		$this->assertTrue(is_dir($baz1));
		// create a file
		$qux1 = $baz1.DIRECTORY_SEPARATOR.'qux.txt';
		file_put_contents($qux1, 'hello world');
		$this->assertTrue(is_file($qux1));

		// create the destination "<cwd>/qux" directory
		$quux = $this->cwd.DIRECTORY_SEPARATOR.'quux';
		mkdir($quux);
		$this->assertTrue(is_dir($quux));

		// copy "<cwd>/foo" to "<cwd>/quux"
		$this->assertTrue(Dir::copy($foo, $quux));

		// set the paths of quux's sub-directories
		$bar2 = $quux.DIRECTORY_SEPARATOR.'bar';
		$baz2 = $bar2.DIRECTORY_SEPARATOR.'baz';
		$qux2 = $baz2.DIRECTORY_SEPARATOR.'qux.txt';

		// test to be sure the old files still exist
		$this->assertTrue(is_dir($foo));
		$this->assertTrue(is_dir($bar1));
		$this->assertTrue(is_dir($baz1));
		$this->assertTrue(is_file($qux1));

		// test to be sure the new files exist
		$this->assertTrue(is_dir($quux));
		$this->assertTrue(is_dir($bar2));
		$this->assertTrue(is_dir($baz2));
		$this->assertTrue(is_file($qux2));

		// delete old files
		unlink($qux1);
		rmdir($baz1);
		rmdir($bar1);
		rmdir($foo);

		// delete new files
		unlink($qux2);
		rmdir($baz2);
		rmdir($bar2);
		rmdir($quux);

		return;
	}


	/* !remove() */

	/**
	 * remove() should throw a BadMethodCallException if $directory or $container is null
	 */
	public function testRemove_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Dir::remove(null, null);
	}

	/**
	 * remove() should throw an InvalidArgumentException if $directory is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testRemove_throwsInvalidArgumentException_ifDirectoryIsNotAString($directory)
	{
		$container = $this->cwd.DIRECTORY_SEPARATOR.'foo';

		$this->setExpectedException('InvalidArgumentException');
		Dir::remove($directory, $container);

		return;
	}

	/**
	 * remove() should throw an InvalidArgumentException if $container is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testRemove_throwsInvalidArgumentException_ifContainerIsNotAString($container)
	{
		$directory = $this->cwd.DIRECTORY_SEPARATOR.'foo';

		$this->setExpectedException('InvalidArgumentException');
		Dir::remove($directory, $container);

		return;
	}

	/**
	 * remove() should throw an InvalidArgumentException if $directory is not an existing
	 *     directory
	 */
	public function testRemove_throwsInvalidArgumentException_ifDirectoryDoesNotExist()
	{
		$directory = $this->cwd.DIRECTORY_SEPARATOR.'foo';

		$this->setExpectedException('InvalidArgumentException');
		Dir::remove($directory, $this->cwd);

		return;
	}

	/**
	 * remove() should throw an InvalidArgumentException if $directory is not in $container
	 */
	public function testRemove_throwsInvalidArgumentException_ifDirectoryIsNotContained()
	{
		// create a directory
		$directory = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		mkdir($directory);
		$this->assertTrue(is_dir($directory));

		// set the directory's container
		$container = $this->cwd.DIRECTORY_SEPARATOR.'bar';

		// try to remove the directory
		// the method should throw an InvalidArgumentException
		// catch it, clean up, and re-throw it
		//
		$this->setExpectedException('InvalidArgumentException');
		try {
			Dir::remove($directory, $container);
		} catch (Exception $e) {
			rmdir($directory);
			throw $e;
		}

		return;
	}

	/**
	 * remove() should return true if the directory is empty
	 */
	public function testRemove_returnTrue_ifDirectoryIsEmpty()
	{
		// create a directory
		$directory = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		mkdir($directory);
		$this->assertTrue(is_dir($directory));

		// remove the directory
		$this->assertTrue(Dir::remove($directory, dirname(__FILE__)));

		// test if the directory is gone
		$this->assertFalse(is_dir($directory));

		// if something goes wrong, be sure to clean up
		if (is_dir($directory)) {
			rmdir($directory);
		}

		return;
	}

	/**
	 * remove() should return true if the directory is not empty
	 */
	public function testRemove_returnTrue_ifDirectoryIsNotEmpty()
	{
		// create a "<cwd>/foo" directory
		$foo = $this->cwd.DIRECTORY_SEPARATOR.'foo';
		mkdir($foo);
		$this->assertTrue(is_dir($foo));

		// create a "<cwd>/foo/bar" directory
		$bar = $foo.DIRECTORY_SEPARATOR.'bar';
		mkdir($bar);
		$this->assertTrue(is_dir($bar));

		// create a "<cwd>/foo/bar/baz" directory
		$baz = $bar.DIRECTORY_SEPARATOR.'baz';
		mkdir($baz);
		$this->assertTrue(is_dir($baz));

		// create a "<cwd>/foo/bar/baz/qux.txt" file
		$qux = $baz.DIRECTORY_SEPARATOR.'qux.txt';
		file_put_contents($qux, 'hello world');
		$this->assertTrue(file_exists($qux));

		// remove the "foo" directory
		$this->assertTrue(Dir::remove($foo, $this->cwd));

		// check to be sure the directories and files are gone
		$this->assertFalse(file_exists($qux));
		$this->assertFalse(is_dir($baz));
		$this->assertFalse(is_dir($bar));
		$this->assertFalse(is_dir($foo));

		// if the old files still exist, remove them
		if (file_exists($qux)) {
			unlink($qux);
		}

		if (is_dir($baz)) {
			rmdir($baz);
		}

		if (is_dir($bar)) {
			rmdir($bar);
		}

		if (is_dir($foo)) {
			rmdir($foo);
		}

		return;
	}
}
