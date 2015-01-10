<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Dump a variable, wrapped in <pre> tags.
 * @param mixed $var The variable to dump.
 * @param string $label (optional) A label to prepend the dump with.  
 * @param boolean $echo (optional) Whether to echo the variable or return it
 * @global
 * @return mixed Return if $echo is passed as FALSE  
 * @author Joost van Veen
 */
function dump ($var, $label = 'DUMP', $echo = TRUE)
{
    // Store dump in variable 
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    
    // Add formatting
    $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
    $output = '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 10px;background-color:#eee">
                    <legend style="color:#0000FF;">&nbsp;&nbsp;' . $label . '&nbsp;&nbsp;</legend>
                    <div style="color:#0000FF;font-weight:normal;padding:4px 0 4px 0">' . $output . '</div>
                </fieldset>
    ';
    // Output
    if ($echo == TRUE) {
        echo $output;
    }
    else {
        return $output;
    }
}