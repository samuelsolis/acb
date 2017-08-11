<?php

namespace Drupal\acb\Helper;
use Drupal\acb\Model\AcbBlockModelClass;

Class AcbHelper {
	

	static function get_renderized_block (array $blocks, $region, $theme) {
	  //limpiamos el nombre de los bloques
    foreach ($blocks as $block) {
      $block_delta[] = rtrim(explode(':', $block)[1],']');
    }
    //$block_delta = implode(',',$block_delta);
    $blocks = AcbBlockModelClass::get_block_filter_by_delta_theme  ($block_delta,$theme);
    $WADUS = '';
  
	}
	
	/**
	 * @return array
	 */
	static function get_enabled_theme_regions() {
		$list_theme = self::get_enabled_themes();
		$regions = self::get_regions($list_theme);
		return $regions;
	}
	
	/**
	 * @param array $list list of themes in the website.
	 *
	 * @return array
	 */
	static function get_enabled_themes(){
		$cache = cache_get('acb_enabled_themes');
		unset($cache->data);
		if (isset($cache->data)) {
			$data = $cache->data;
		}
		else {
			$list = list_themes();
			$data = array_filter($list, function ($theme) {
				if ($theme->status == 1) {
					return $theme;
				}
			});
			cache_set('acb_enabled_themes', $data);
		}
		return $data;
	}
	
	/**
	 * @param array $list_theme
	 *
	 * @return array
	 */
	static function get_regions(array $list_theme){
		$cache = cache_get('acb_theme_regions');
		unset($cache->data);
		if (isset($cache->data)) {
			$data = $cache->data;
		}
		else {
			$data = array_map(function ($theme) {
				return system_region_list($theme->name);
			}, $list_theme);
			cache_set('acb_theme_regions', $data);
		}
		return $data;
	}
	
	/**
	 * @param array $themes
	 * @param array $maps
	 *
	 * @return array
	 */
	static function clean_submited_values(array $themes, array $maps) {
		//TODO: check the code and look for a better way
		foreach ($maps as $theme => $regions) {
			foreach ($regions as $machine_name => $region) {
				//unset the add_item element.
				if (isset($themes[$theme][$machine_name]['add_item'])) {
					unset($themes[$theme][$machine_name]['add_item']);
				}
				
				//clean the empty and rebuild the result only to the bid.
				foreach ($themes[$theme][$machine_name] as $key => $result){
					if(empty($themes[$theme][$machine_name][$key])) {
						unset($themes[$theme][$machine_name][$key]);
					}else {
						$themes[$theme][$machine_name][$key] = $themes[$theme][$machine_name][$key];
					}
				}
			}
		}
		return $themes;
	}
	
	/**
	 * @param $string
	 * example of who the string will be: text[25]
	 *
	 * @return bool|string
	 */
	private static function clean_result_string($string) {
		return substr(explode('[',	$string)[1],0,-1);
	}
	
	/**
	 * Clean arrays with keys and empty values.
	 * @param array $array
	 *
	 * @return array
	 */
	static public function clean_array(array $array) {
		return array_filter($array, function ($item) {
			return empty($item) ? FALSE : TRUE;
		}, ARRAY_FILTER_USE_BOTH);
	}
 
}