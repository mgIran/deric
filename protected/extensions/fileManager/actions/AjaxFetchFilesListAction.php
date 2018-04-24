<?php
//Copyright 2011, Marc Busqué Pérez
//
//This file is a part of Yii Sortable Model
//
//Yii Sortable Model is free software: you can redistribute it and/or modify
//it under the terms of the GNU Lesser General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU Lesser General Public License for more details.
//
//You should have received a copy of the GNU Lesser General Public License
//along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This action is triggered by SortableCGridView widget to store in the background the new records order.
 * @author Marc Busqué Pérez <marc@lamarciana.com>
 * @package Yii Sortable Model
 * @copyright Copyright &copy; 2012 Marc Busqué Pérez
 * @license LGPL
 * @since 1.0
 */

class AjaxFetchFilesListAction extends CAction
{
   public function run()
   {
      if(isset($_POST['path'])){
         $path = $_POST['path'];
         if(!is_dir($path)) @mkdir($path, 0777, true);
         $files = scandir($path, SCANDIR_SORT_ASCENDING);
         if($files){
            $items = [];
            foreach($files as $file)
               if(is_file($path . DIRECTORY_SEPARATOR . $file))
                  $items[] = '<div class="filemanager-item"  data-file-name="' . $file . '"><span class="item-title">' . $file . '</span><span class="ltr text-left pull-left item-size">' . Controller::fileSize($path . DIRECTORY_SEPARATOR . $file) . '</span></div>';
            // create output html
            $html = '<div class="filemanager-list-header"><span>نام فایل</span><span class="text-left pull-left">حجم فایل</span></div>';
            $html .= '<div class="filemanager-filter">';
            $html .= '<input type="text" class="filemanager-filter-text text-right" placeholder="جستجو کنید ...">';
            $html .= '</div>';
            $html .= '<div class="filemanager-list-section">';
            if($items)
               $html .= implode('', $items);
            else
               $html .= '<div class="filemanager-item filemanager-error">در مسیر موردنظر فایلی وجود ندارد.</div>';
            $html .= '</div>';
            echo $html;
         }else{
            $html = '<div class="filemanager-list-header"><span>نام فایل</span><span class="text-left pull-left">حجم فایل</span></div>';
            $html .= '<div class="filemanager-list-section">';
            $html .= '<div class="filemanager-item filemanager-error">در مسیر موردنظر فایلی وجود ندارد.</div>';
            $html .= '</div>';
            echo $html;
         }
         Yii::app()->end();
      }
   }
}