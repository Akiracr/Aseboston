<?php
/**
 * \Drupal\Sniffs\NamingConventions\ValidClassNameSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

namespace Drupal\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * \Drupal\Sniffs\NamingConventions\ValidClassNameSniff.
 *
 * Ensures class and interface names start with a capital letter
 * and do not use _ separators.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class ValidClassNameSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            T_CLASS,
            T_INTERFACE,
        ];

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The current file being processed.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $className = $phpcsFile->findNext(T_STRING, $stackPtr);
        $name      = trim($tokens[$className]['content']);
        $errorData = [ucfirst($tokens[$stackPtr]['content'])];

        // Make sure the first letter is a capital.
        if (preg_match('|^[A-Z]|', $name) === 0) {
            $error = '%s name must begin with a capital letter';
            $phpcsFile->addError($error, $stackPtr, 'StartWithCapital', $errorData);
        }

        // Search for underscores.
        if (strpos($name, '_') !== false) {
            $error = '%s name must use UpperCamel naming without underscores';
            $phpcsFile->addError($error, $stackPtr, 'NoUnderscores', $errorData);
        }

    }//end process()


}//end class
