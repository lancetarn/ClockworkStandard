<?php
/**
 * This sniff ensures a minimum of two spaces lines between function calls.
 *
 * @category PHP
 * @package PHP_CodeSniffer
 * @author Lance Erickson <lance@clockwork.net>
 **/

class Clockwork_Sniffs_TwoLinesBetweenFunctionsSniff implements PHP_CodeSniffer_Sniff {


    public function register ( ) {
        return array( T_FUNCTION );
    }


    public function process ( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

        $clean  =  true;

        $tokens        =  $phpcsFile->getTokens( );
        $current_line  =  $tokens[$stackPtr]['line'];

        // Is this an inline function? 
        $last_assignment  =  $phpcsFile->findPrevious( PHP_CodeSniffer_Tokens::$assignmentTokens, $stackPtr );

        if ( $last_assignment && $tokens[$last_assignment]['line'] == $current_line ) {
            // All bets are off
            return;
        }

        // Do we have a comment? 

        // Are the previous two lines empty?
        $lines  =  range( $current_line - 2, $current_line - 1 );

        foreach ( $lines as $line ) {
            if ( ! $this->is_line_empty( $tokens, $line, $phpcsFile->eolChar ) ) {
                $clean  =  false;
            }
        }

        if ( ! $clean ) {
            $phpcsFile->addError( "Two empty lines required before function.", $stackPtr, '' );

        }
    }


    protected function is_line_empty( $tokens, $line, $eol_char ) {

        foreach ( $tokens as $token ) {
            if ( $line == $token['line'] && $eol_char != $token['content'] ) {
                return false;
            }
        }

        return true;
    }
}
