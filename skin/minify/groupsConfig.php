<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
    // skin/minify/g=css
    'css'   => array(
                    '../home/css/styles.css'
                    ),
    'js'    => array(
					'../home/plugin/DataTables/js/jquery.dataTables.min.js',
                    '../home/js/script.js'
                    )
    'admin_css'   => array(
                    '../admin/css/styles.css'
                    ),
    'admin_js'    => array(
					'../admin/plugin/DataTables/js/jquery.dataTables.min.js',
                    '../admin/js/script.js'
                    )
);