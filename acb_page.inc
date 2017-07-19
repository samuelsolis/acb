<?php

/**
 * @file acb_page.inc
 */
module_load_include('php', 'acb', 'src/Form/acb_layout_form');
module_load_include('php','acb','src/Helper/acb_load_block');

function acb_node_configure_block($type, $object) {
  $path = $type.'/'.$object->nid;
  return drupal_get_form('acb_layout_form',$path, $type);
    
}