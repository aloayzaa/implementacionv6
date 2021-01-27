<?php

namespace App\Core;

class Menu1
{
    /**
     * Build menu
     *
     * @param array $menus Menu items
     * @return null|string
     */


    public static function itemsMenu($menus)
    {
        $menu = null;

        foreach ($menus as $item) {
            if (empty($item->permission) || in_array($item->permission, \Session::get('permissions'))) {
                $menu .= '<li>';
                $menu .= sprintf('<a href="%s" class="ripple">', !is_null($item->route) ? route($item->route) : '#');
                $menu .= sprintf('<i class="%s"></i>&nbsp;&nbsp;&nbsp;', $item->icon);
                $menu .= sprintf('%s', $item->text);

                if (isset($item->subMenu)) {
                    if (!empty($item->subMenu)) {

                        $menu .= '<span class="fa fa-chevron-down""></span>';
                        //$menu .= '<i class="material-icons">face</i>';
                    }
                }
                $menu .= '</a>';
                if (isset($item->subMenu)) {
                    if (!empty($item->subMenu)) {
                        $menu .= '<ul class="nav child_menu" style="display: block;">';
                        $menu .= self::itemsMenu($item->subMenu);
                        $menu .= '</ul>';
                    }
                    $menu .= '</li>';

                }
            }
        }

        return $menu;
    }


    public static function buildMenu()
    {
        $menus = menu();
        /* if (\Session::get('permission') == 1)
         {*/
        return self::itemsMenu($menus);
        /* }else{
             return self::itemsM($menus);
         }*/
    }

}