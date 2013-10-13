<?php
/**
 * This sniff ensures that tabs are only used as leading space.
 *
 * @category PHP
 * @package PHP_CodeSniffer
 * @author Lance Erickson <lance@clockwork.net>
 **/

class Clockwork_Sniffs_LeadingTabsOnlySniff implements PHP_CodeSniffer_Sniff {


    public function register ( ) {
        return array( T_WHITESPACE );
    }


    public function process ( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

        $tokens  =  $phpcsFile->getTokens( );

        // Error if token contains tab and prior token is not newline.
        if ( strpos( $tokens[$stackPtr]['content'], "\t" ) !== false ) {

            if ( $tokens[$stackPtr - 1]['content'] !== $phpcsFile->eolChar ) {
                $phpcsFile->addError( "Tab found in non-leading whitespace.", $stackPtr, '' );
            }
        }
    }
    protected function show_whitespace( $string ) {
            $badcontent  =  str_replace( "    ", "[tab]", $string );
            $badcontent  =  str_replace( " ", "[space]", $badcontent );
            $badcontent  =  str_replace( "\n", "[nl]", $badcontent );

            return $badcontent;
    }
}
