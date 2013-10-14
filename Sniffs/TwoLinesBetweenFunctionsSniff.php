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


    /**
     * Checks for two lines either before function or before
     * a function's comment.
     **/
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
        $comment_index  =  $phpcsFile->findPrevious( T_DOC_COMMENT, $stackPtr );

        if ( $comment_index ) {
            // Does it end right above us?
            if ( $tokens[$comment_index]['line'] == $current_line - 1 ) {
                // Where does it begin?
                $comment_opener  =  $comment_index;
                $type  =  'T_DOC_COMMENT';
                while ( $type == 'T_DOC_COMMENT' ) {
                    $comment_opener--;
                    $type  =  $tokens[$comment_opener]['type'];
                }
                // We'll check from the first comment line. 
                $current_line  =  $tokens[$comment_opener + 1]['line'];
            }
        }

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
