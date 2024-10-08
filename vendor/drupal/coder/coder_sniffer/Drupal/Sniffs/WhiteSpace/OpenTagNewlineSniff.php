<?php
/**
 * \Drupal\Sniffs\WhiteSpace\OpenTagNewlineSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

namespace Drupal\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks that there is exactly one newline after the PHP open tag.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class OpenTagNewlineSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [T_OPEN_TAG];

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The PHP_CodeSniffer file where the
     *                                               token was found.
     * @param int                         $stackPtr  The position in the PHP_CodeSniffer
     *                                               file's token stack where the token
     *                                               was found.
     *
     * @return int End of the stack to skip the rest of the file.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Only check the very first PHP open tag in a file, ignore any others.
        if ($stackPtr !== 0) {
            return ($phpcsFile->numTokens + 1);
        }

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

        // If there is no further content in this file ignore it.
        if ($next === false) {
            return ($phpcsFile->numTokens + 1);
        }

        if ($tokens[$next]['line'] === 3) {
            return ($phpcsFile->numTokens + 1);
        }

        $error = 'The PHP open tag must be followed by exactly one blank line';
        $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'BlankLine');

        if ($fix === true) {
            $phpcsFile->fixer->beginChangeset();

            if ($tokens[$next]['line'] === 1) {
                $phpcsFile->fixer->addNewline($stackPtr);
                $phpcsFile->fixer->addNewline($stackPtr);
            } else if ($tokens[$next]['line'] === 2) {
                $phpcsFile->fixer->addNewline($stackPtr);
            } else {
                for ($i = ($stackPtr + 1); $i < $next; $i++) {
                    if ($tokens[$i]['line'] > 2) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }
                }
            }

            $phpcsFile->fixer->endChangeset();
        }

        return ($phpcsFile->numTokens + 1);

    }//end process()


}//end class
