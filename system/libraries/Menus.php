<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Chào m?ng các b?n d?n v?i di?n dàn codeigniter.vn
 * Chúng tôi r?t mong nh?n du?c nhi?u s? dóng góp t? c?ng d?ng codeigniter d? xây d?ng.
 * Ngu?i th?c hi?n: tinhphaistc
 * Y!H & Skype: tinhphaistc
 * Email: tinhphaistc@gmail.com
 * Ch?nh s?a: darkearl
 * Email: ttrung.david@gmail.com
 */
class Menus {
    //config variable
    var $table			    = 'category';
    var $Parent_ID          = 'Parent_ID';
    var $ID                 = 'ID';
    var $Title              = 'Title';
    var $Order_by           = 'Position';
    //config style
    var $cls_first_item   ='first';
    var $cls_list_item    ='list';
    var $cls_end_item   ='end';

    public function __construct($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		log_message('debug', "menus class Initialized");
	}

    function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}
    function query_menu($id = 0)
    {
        //call database
        $CI =& get_instance();
        $ID = $this->ID;
        $parent_id =$this->Parent_ID;
        if($id)
        {
            $CI->db->where($this->Parent_ID,$id);
        }
        if($this->Order_by != ''){
            $CI->db->order_by($this->Order_by);
        }
        $result =   $CI->db->get($this->table);
        $menu = array();
        //tra ve array std oject
        $results    =   $result->result();
        //tra ve array not std oject
        $results_array  =   $result->result_array();
        for ($i = 0;count($results) > $i; $i++)
        {
            $menu['items'][$results[$i]->$ID] = $results_array[$i];
            $menu['parents'][$results[$i]->$parent_id][] = $results[$i]->$ID;
        }
        return $menu;
    }
    function _build_menu($parent, $menu,$display=''){
        $html = "";

        if (isset($menu['parents'][$parent])){
            $html .= "<ul class='sList listCategory' $display>";
            $display = 'style="display: none"';
            foreach ($menu['parents'][$parent] as $itemId){
                if(!isset($menu['parents'][$itemId])){
                    $html .= "<li id=item-".$menu['items'][$itemId][$this->ID].">";
                    $html .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    $html .= "<a href=".base_url('admin.php/category/create/'.$menu['items'][$itemId][$this->ID]).">".$menu['items'][$itemId][$this->Title]."</a>\n</li> \n";
                }else{
                    $html .= "<li id=item-".$menu['items'][$itemId][$this->ID].">";
                    $html .= '<a href="javascript:void(0)" class="collapsed">[+]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $html .= "<a href=".base_url('admin.php/category/create/'.$menu['items'][$itemId][$this->ID]).">".$menu['items'][$itemId][$this->Title]."</a>\n";
                    $html .= $this->_build_menu($itemId, $menu,$display);
                    $html .= "</li> \n";
                }
            }
        $html .= "</ul> \n";
        }
        return $html;
    }
    function _build_menu2($menu,$id,$count=0,$parent=0){
        $background_colors = array('#E3EFFD','#F2F2F2','#FFFFFF', '#FFFFCC');
        $html = "";

        if (isset($menu['parents'][$parent])){
            if($parent == 0){
                $html .= '<table id="select_cat" cellpadding=0 cellspacing=0>';
                $html .= '<tr style="background:'.$background_colors[$count].'"><td>';
                $html .= '<input type="radio" name="rd_cat" style="margin-right:25px" value="0" checked=checked/>';
                $html .= '<span class="cat_title">Root level</span>';
            }else{
                $html .= '<table cellpadding=0 cellspacing=0 style="display:none">';
            }

            foreach ($menu['parents'][$parent] as $itemId){
                if($itemId != $id){
                    $html .= '<tr style="background:'.$background_colors[$count].'"><td>';
                    $html .= '<input type="radio" name="rd_cat" style="margin-right:'.(25*$count).'px" value="'.$itemId.'" />';
                    if(!isset($menu['parents'][$itemId])){
                        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        $html .= "<span class=cat_title>".$menu['items'][$itemId][$this->Title]."</span>\n";
                    }else{
                        $html .= '&nbsp;&nbsp;<a href="javascript:void(0)" class="collapsed" >[+]</a>&nbsp;&nbsp;';
                        $html .= "<span class=cat_title>".$menu['items'][$itemId][$this->Title]."</span>\n";
                        $html .= $this->_build_menu2($menu,$id,$count+1,$itemId);
                    }
                    $html .= '</td></tr>';
                }

            }
        $html .= "</table> \n";
        }
        return $html;
    }
    function _create_map($parent=0, $menu){
        $html = "";
        if (isset($menu['parents'][$parent])){
            foreach ($menu['parents'][$parent] as $itemId){
                $html .= $this->_create_map($itemId, $menu).'-'.$itemId;
            }
        }
        return $html;
    }

    function get_map($id){
        $output =$this->_create_map($id,$this->query_menu(0));
        //string to array
        $output = explode('-',$output);
        //remove empty elements
        $output =array_filter($output);
        //reset key array
        $output=array_values($output);
        //add current id
        $output[]=strval($id);
        return $output;
    }

    function show_menu ($parent = 0)
    {
        echo $this->_build_menu($parent, $this->query_menu(0));
    }

    function show_menu2 ($id)
    {
        echo $this->_build_menu2($this->query_menu(0),$id);
    }
}
// END Menus Class
/* End of file Menus.php */
/* Location: ./application/libraries/Menus.php */