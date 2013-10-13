<?php
/**
 * This sniff ensures a space after opening parens.
 *
 * @category PHP
 * @package PHP_CodeSniffer
 * @author Lance Erickson <lance@clockwork.net>
 **/

class Clockwork_Sniffs_SpaceAfterOpeningParenSniff implements PHP_CodeSniffer_Sniff {


    public function register ( ) {
        return array( T_OPEN_PARENTHESIS );
    }


    public function process  ( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

        $clean   =  true;
        $tokens  =  $phpcsFile->getTokens( );
        $next    =  $tokens[$stackPtr + 1];
        
        if ( $next['type'] != 'T_WHITESPACE' ) {
            $clean  =  false;
        }
        else if ( ! $next['content'] == ' ' || ! $next['content'] == $phpcsFile->eolChar ) {
            $clean  =  false;
        }

        if ( ! $clean ) {
            $phpcsFile->addError( "No space after opening paren", $stackPtr, '' );
        }


    }
}
