<?php

namespace App\Core;

class Menu2
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
                $menu .= '<li style="width: 100%;">';
                $menu .= '<a href="'.$item->url.'" class="ripple">';
                $menu .= '<i class="'.$item->icon.'"></i>&nbsp;&nbsp;&nbsp;';
                $menu .= $item->text;
                $menu .= '<span class="fa fa-chevron-down""></span>';
                $menu .= '</a>';
               /* $menu .= '<li style="width: 100%;">';
                $menu .= '<a data-toggle="tab" href="'.$item->url.'" class="ripple">';
                $menu .= '<i class="'.$item->icon.'"></i>&nbsp;&nbsp;&nbsp;';
                $menu .= $item->text;*/
                if (isset($item->subMenu)) {
                    if (!empty($item->subMenu)) {
                        $menu .= '<ul class="nav child_menu" style="display: block;">';
                        foreach ($item->subMenu as $items) {
                            $menu .= '<li style="width: 100%;">';
                            $menu .= '<a data-toggle="tab" href="' . $items->url . '" class="ripple">';
                            $menu .= '<i class="' . $items->icon . '"></i>&nbsp;&nbsp;&nbsp;';
                            $menu .= $items->text;
                            if (isset($items->subMenu)) {
                                if (!empty($items->subMenu)) {
                                    $menu .= '<span class="fa fa-chevron-down""></span>';
                                }
                            }
                            $menu .= '</a>';
                            if (isset($items->subMenu)) {
                                if (!empty($items->subMenu)) {
                                    $menu .= '<ul class="nav child_menu" style="display: block;">';
                                    foreach ($items->subMenu as $value) {
                                        $menu .= '<li style="width: 100%;">';
                                        $menu .= '<a data-toggle="tab" href="' . $value->url . '" class="ripple">';
                                        $menu .= '<i class="' . $value->icon . '"></i>&nbsp;&nbsp;&nbsp;';
                                        $menu .= $value->text;
                                        $menu .= '</a>';
                                        $menu .= '</li>';
                                    }
                                    $menu .= '</ul>';
                                }
                            }
                            $menu .= '</li>';
                        }
                        $menu .= '</ul>';
                    }
                }
                /*if (isset($item->subMenu)) {
                    if (!empty($item->subMenu)) {
                        $menu .= '<span class="fa fa-chevron-down""></span>';
                    }
                }
                $menu .= '</a>';
                if (isset($item->subMenu)) {
                    if (!empty($item->subMenu)) {
                        $menu .= '<ul class="nav child_menu" style="display: block;">';
                        $menu .= self::itemsMenu($item->subMenu);
                        $menu .= '</ul>';
                    }

                }*/
                $menu .= '</li>';
            }
        }

        return $menu;
    }
    public static function buildMenu()
    {
        $menus = menu2();
        return self::itemsMenu($menus);
    }

}

