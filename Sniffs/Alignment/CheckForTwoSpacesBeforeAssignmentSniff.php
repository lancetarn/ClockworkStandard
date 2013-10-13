<?php
/**
 * This sniff ensures a minimum of two spaces before and after assignment.
 *
 * @category PHP
 * @package PHP_CodeSniffer
 * @author Lance Erickson <lance@clockwork.net>
 **/

class Clockwork_Sniffs_Alignment_CheckForTwoSpacesBeforeAssignmentSniff implements PHP_CodeSniffer_Sniff{

    /** 
     * Looking for two or more spaces, no tabs.
     **/
    public $whitespace_regex  =  array(
        "before" => "/^  +$/",
        "after"  => "/^  $/",
    );

    public $warning_message  =  "Improper whitespace %s '%s' assignment.";

    public $token_stack;

    /**
     * Returns token types to sniff.
     *
     * @return array
     **/
    public function register( ) {
        return PHP_CodeSniffer_Tokens::$assignmentTokens;
    }


    /**
     * Examines surrounding tokens for matching whitespace. Warn if
     * less than two spaces are found.
     **/
    public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

        $tokens  =  $phpcsFile->getTokens( );

        $surrounding  =  array( );

        $surrounding['before']  =  $tokens[$stackPtr - 1];
        $surrounding['after']   =  $tokens[$stackPtr + 1];

        foreach( $surrounding as $key => $token ) {

            $match  =  preg_match( $this->whitespace_regex[$key], $token['content'] );
        
            if ( ! $match ) {
                $data  =  array( $key, $tokens[$stackPtr]['content'] );
                $phpcsFile->addWarning( $this->warning_message, $stackPtr, '', $data );
            }
        }
    }
}
