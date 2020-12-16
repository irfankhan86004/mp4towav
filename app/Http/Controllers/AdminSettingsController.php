<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Category;
use App\Video;
use FFMpeg;
class AdminSettingsController extends Controller
{
    /*
    * Admin Setting listing
    */
    public function mp4towav()
    {
		
		

		$fileType = '.wav';
		$ffmpeg = FFMpeg\FFMpeg::create(array(
			'ffmpeg.binaries'  => 'D:\xampp\htdocs\PHP-FFMpeg\bin\ffmpeg.exe',
			'ffprobe.binaries' => 'D:\xampp\htdocs\PHP-FFMpeg\bin\ffprobe.exe'
		));
		//exit;
		
		$videos = Video::all();
		
		$i = 1;
		foreach ($videos as $video) {
			$categories = ltrim($video->categories, '|');
			$cats = explode('||', $categories);
			
			
			$fileName = 'big_buck_bunny_720p_d2mb.mp4';
			
			if (file_exists($fileName)) {
				$videoPath = $ffmpeg->open($fileName);
				
				$onlyName = pathinfo($fileName);
				
				//dump($onlyName);exit;
				foreach ($cats as $cat) {
					$cat = str_replace('|', '', $cat);
					
					$category = Category::find($cat);
					
					if (!empty($category)) {
						//$category->topID;
						$uploads_dir = 'uploads/'.$category->topID.'/'.$cat;
						
						//echo public_path().'/'.$uploads_dir.'/'.$onlyName['filename'].$fileType;exit;
						//echo !file_exists(public_path().'/'.$uploads_dir.'/'.$onlyName['filename'].$fileType);exit;
						if (file_exists(public_path().'/'.$uploads_dir.'/'.$onlyName['filename'].$fileType)) {
							
							$txt = $video->id.'__'.'__'.$cat.public_path().'/'.$uploads_dir.'/'.$onlyName['filename'].$fileType;
							$myfile = file_put_contents('data.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
						} else {
							if (file_exists($uploads_dir) == false) {
								mkdir($uploads_dir, 0777, true);
							}
							
							$videoPath->save(new FFMpeg\Format\Audio\Wav(), $uploads_dir.'/'.$onlyName['filename'].$fileType);
						}
					}
					//dump($category->topID);exit;
				}
			
				//dump($cat);exit;
				
			} else{
				$txt = $video->id.'__'.'__'.$fileName;
				$myfile = file_put_contents('file_not.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
			}
			
			if ($i == 5) {
				break;
			}
			
			$i++;
		}
    }


}
