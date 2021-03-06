<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Calendar;
use PixelPenguin\Admin\Models\CalendarGallery;

use Auth;

use Cloudder;

class CalendarsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.calendars.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.calendars.edit', ['calendarId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.calendars.create');
	}
	
	public function destroy($id)
    {
        $page = Calendar::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$calendar = Calendar::whereId($id)->first();
		}
		else{
			$calendar = new Calendar();
		}
		
		$calendar->name = $input['name'];
		//$calendar->position = $input['position'];
		$calendar->detail = $input['detail'];
		$calendar->active = $input['active'];
		$calendar->whole_day = $input['whole_day'];
		$calendar->date_from = $input['date_from'];
		$calendar->date_to = $input['date_to'];
		$calendar->user_id = Auth::user()->id;
		
		$cleanTags = array();
		
		/*
		if(isset($input['tags'])){
			foreach($input['tags'] as $tag){
				$cleanTags[] = $tag['value'];
			}	
		}
		*/
		
		//$calendar->tags = implode(',', $cleanTags);
		
		
		$calendar->save();
		
		$calendar->categories()->detach();
		
		foreach($input['calendar_category'] as $category){
			$calendar->categories()->attach($category['id']);
		}
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $calendar;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Calendar::findOrFail($input['id']);
		
		if($page['active'] == true)
		{
			$page->active = false;
		}
		else
		{
			$page->active = true;
		}
		
		$page->save();

		$response = array();
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateImage(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_calendar_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Calendar::whereId($input['calendar_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function uploadGallery(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	Cloudder::upload($image_name->getRealPath(),  env('CLOUDINARY_BASE_FOLDER_PATH').'app_calendar_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new CalendarGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->calendar_id = $input['calendar_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function deleteGallery($id){
		//dd($id);
		
		$gallery = CalendarGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function galleryOrder(Request $request, $pageId){
		$input = $request->all();
		
		//dd($input);
		$count = 0;
		
		foreach($input['calendarGallery'] as $gallery){
			$galleryEntry = CalendarGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
}
