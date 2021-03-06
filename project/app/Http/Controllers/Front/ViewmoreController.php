<?php

namespace App\Http\Controllers\front;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Counter;
use App\Models\Recentview;
use App\Models\Generalsetting;
use App\Models\favoriteItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Custom_page;
use App\Models\Socialsetting;
use Carbon\Carbon;
use Cartalyst\Stripe\Api\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;
use Validator;
use DateTime;

class ViewmoreController extends Controller
{
    public function featured_view_more(Request $request)
    {
      
        $skip=$request->input('skip');
        // echo $skip; exit;
        $featureds = Product::with('user')->where('featured','=',1)->where('user_id','!=',0)->where('status','=',1)->orderBy('id','desc')->offset($skip)->limit(8)->get();
        $html='';
        foreach($featureds as $featured){
            $html.='<div class="col-sm-3">';                          
            $html.='<div class="ec-spe-products featured-product-box">';                                                                
            $html.='<div class="artist-product-new">';
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<img src="'.asset('assets/images/products/'.$featured->photo).'">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href="'.url('').'/item/'.$featured->slug.'">'; 
            $html.=$featured->name;                                             
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">';
            $html.='Listed by '.$featured->user->shop_name.'('.$featured->user->name.')';
            $html.='</p>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                                $dt = $featured->created_at;
                                $date = date('m/d/Y h:i:s a', time());                                       
                                $date1=date_create($dt);
                                $date2=date_create($date);
                                $diff=date_diff($date1,$date2);
                                $days =$diff->format("%a days"); 
                                if($days == '0 days') {
                                    $datetime1 = new DateTime($date);
                                    $datetime2 = new DateTime($dt);
                                    $interval = $datetime1->diff($datetime2);
                                    $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                                }else{
                                    $html.=$days =$diff->format("%a days"); 
                                }
                                
            $html.='<p class="artist-p-size">'.getDollLength($featured->length, $featured->length_unit).'</p>';
                                
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';  
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($featured->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($featured->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>';            
            $favorities_count = DB::table('favorite_items')->where('product_id', $featured->id)->count();
            $html.='<p  onclick="addfev('.$featured->id.')"><a href="javascript:void(0)">Add to Favorities'.$favorities_count.'</a></p>';
                                                      
            $html.='</div>';
            $html.='<p class="ec-fs-pro-desc" id="'.$featured->id.'_favorite_msg"></p>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';                           
            $html.='</div>'; 
        }
        return response()->json($html);
    }

    /**
     * @developer: Neha kumari
     * Date:23/12/21
     * Description: Get view more Product Categorys kkk
     */
    public function productCate_view_more(Request $request,$slug)
    {
        $skip=$request->input('skip');
        $data =  DB::table('categories')->where('slug', $slug)->first();
        $category_id =  $data->id;   
        $category_list =  Product::with('user','category')
            ->where('category_id', $category_id)
            ->where('user_id','!=',0)
            ->where('status','=',1)
            ->orderBy('id','desc')
            ->offset($skip)->limit(8)->get(); 
                
         $html='';
         foreach($category_list as $list){
            $html.='<div class="col-sm-3">';                          
            $html.='<div class="ec-spe-products category-product-box">';                                                                
            $html.='<div class="artist-product-new">';
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<img src="'.asset('assets/images/products/'.$list->photo).'">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href="'.url('').'/item/'.$list->slug.'">'; 
            $html.=$list->name;                                             
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">';
            $html.='Listed by '.$list->user->shop_name.'('.$list->user->name.')';
            $html.='</p>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                                $dt = $list->created_at;
                                $date = date('m/d/Y h:i:s a', time());                                       
                                $date1=date_create($dt);
                                $date2=date_create($date);
                                $diff=date_diff($date1,$date2);
                                $days =$diff->format("%a days"); 
                                if($days == '0 days') {
                                    $datetime1 = new DateTime($date);
                                    $datetime2 = new DateTime($dt);
                                    $interval = $datetime1->diff($datetime2);
                                    $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                                }else{
                                    $html.=$days =$diff->format("%a days"); 
                                }
            $html.='<p class="artist-p-size">'.getDollLength($list->length, $list->length_unit).'</p>';
                                
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';  
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($list->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($list->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>'; 

            $favorities_count = DB::table('favorite_items')->where('product_id', $list->id)->count();
            $html.='<p  onclick="addfev('.$list->id.')"><a href="javascript:void(0)">Add to Favorities'.$favorities_count.'</a></p>';
            $html.='</div>';
            $html.='<p class="ec-fs-pro-desc" id="'.$list->id.'_favorite_msg"></p>'; 
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';                           
            $html.='</div>'; 
         }
         return response()->json($html);
    }

    /**
     * @developer: Neha kumari
     * Date:23/12/21
     * Description: Get view more Nursery Product 
     */
    public function nursery_view_more(Request $request) {
        $skip=$request->input('skip');  
        $nid=$request->input('nid'); 
         
	    $nurseries_view =  Product::with('user')->where('user_id',$nid)->where('status','=',1)->orderBy('id','desc')->offset($skip)->limit(4)->get();
        $html='';
        foreach($nurseries_view as $list){
            $html.='<div class="col-sm-3">';                          
            $html.='<div class="ec-spe-products nursery-product-box">';                                                                
            $html.='<div class="artist-product-new">';
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<img src="'.asset('assets/images/products/'.$list->photo).'">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href="'.url('').'/item/'.$list->slug.'">'; 
            $html.=$list->name;                                             
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">';
            $html.='Listed by '.$list->user->shop_name.'('.$list->user->name.')';
            $html.='</p>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                                $dt = $list->created_at;
                                $date = date('m/d/Y h:i:s a', time());                                       
                                $date1=date_create($dt);
                                $date2=date_create($date);
                                $diff=date_diff($date1,$date2);
                                $days =$diff->format("%a days"); 
                                if($days == '0 days') {
                                    $datetime1 = new DateTime($date);
                                    $datetime2 = new DateTime($dt);
                                    $interval = $datetime1->diff($datetime2);
                                    $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                                }else{
                                    $html.=$days =$diff->format("%a days"); 
                                }
            $html.='<p class="artist-p-size">'.getDollLength($list->length, $list->length_unit).'</p>';
                                
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';  
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($list->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($list->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>';  
            $favorities_count = DB::table('favorite_items')->where('product_id', $list->id)->count();
            $html.='<p  onclick="addfev('.$list->id.')"><a href="javascript:void(0)">Add to Favorities'.$favorities_count.'</a></p>';
                                                      
            $html.='</div>';
            $html.='<p class="ec-fs-pro-desc" id="'.$list->id.'_favorite_msg"></p>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';                           
            $html.='</div>'; 
         }
        return response()->json($html);
    }

    /**
     * @developer: Neha kumari
     * Date:23/12/21
     * Description: Get view more Nursery Product 
     */
    public function search_view_more(Request $request,$inputsearch) {
       
        $skip=$request->input('skip');
        $search_list =  Product::with('user')->where('name','like', "{$inputsearch}%")->where('user_id','!=',0)->where('status','=',1)->orderBy('id','desc')->offset($skip)->limit(4)->get();  
        $html='';
        foreach($search_list as $list){
            $html.='<div class="col-sm-3">';                          
            $html.='<div class="ec-spe-products search-product-box">';                                                                
            $html.='<div class="artist-product-new">';
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<img src="'.asset('assets/images/products/'.$list->photo).'">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href="'.url('').'/item/'.$list->slug.'">'; 
            $html.=$list->name;                                             
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">';
            $html.='Listed by '.$list->user->shop_name.'('.$list->user->name.')';
            $html.='</p>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                                $dt = $list->created_at;
                                $date = date('m/d/Y h:i:s a', time());                                       
                                $date1=date_create($dt);
                                $date2=date_create($date);
                                $diff=date_diff($date1,$date2);
                                $days =$diff->format("%a days"); 
                                if($days == '0 days') {
                                    $datetime1 = new DateTime($date);
                                    $datetime2 = new DateTime($dt);
                                    $interval = $datetime1->diff($datetime2);
                                    $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                                }else{
                                    $html.=$days =$diff->format("%a days"); 
                                }
                
            $html.='<p class="artist-p-size">'.getDollLength($list->length, $list->length_unit).'</p>';
                                
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';  
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($list->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($list->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>';  
            $favorities_count = DB::table('favorite_items')->where('product_id', $list->id)->count();
            $html.='<p  onclick="addfev('.$list->id.')"><a href="javascript:void(0)">Add to Favorities'.$favorities_count.'</a></p>';
                                           
            $html.='</div>';
            $html.='<p class="ec-fs-pro-desc" id="'.$list->id.'_favorite_msg"></p>'; 
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';                           
            $html.='</div>'; 
         }
        
        return response()->json($html);

    }

    /**
     * @developer: Neha kumari
     * Date:23/12/21
     * Description: Get view more Nursery Product 
     */
    public function fevertview_more(Request $request) {
        $userid = Auth::user()->id;
        $skip=$request->input('skip');
        $fevrt_list = favoriteItem::with('Product','user')->where('user_id',$userid)->orderBy('id','desc')
        ->offset($skip)->limit(6)->get();
        $html='';
        foreach($fevrt_list as $list){
            $html.='<div class="col-sm-4">';
            $html.='<div class="ec-spe-products1 fevrt-product-box">';
            $html.='<div class="artist-product-new">';  
            $html.='<img src="'.asset('assets/images/products/'.$list->product->photo).'">';
            $html.='</div>';
                foreach($list->product->galleries as $gal) {
            $html.='<div class="artist-product-new fevrt-productss">';
            $html.='<img src="'.asset('assets/images/galleries/'.$gal->photo).'">';
            $html.='</div>';
            
                }
            $html.='</div>';                             
            
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href=" '.url('').'/item/'.$list->product->slug.'" onclick="addrecent('.$list->product->id.')">';
            $html.=$list->product->name;                
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">Listed by '.$list->user->shop_name.'('.$list->user->name.')</p>';             
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                    $dt = $list->product->created_at;
                    $date = date('m/d/Y h:i:s a', time());                                       
                    $date1=date_create($dt);
                    $date2=date_create($date);
                    $diff=date_diff($date1,$date2);
                    $days =$diff->format("%a days"); 
                    if($days == '0 days') {
                        $datetime1 = new DateTime($date);
                        $datetime2 = new DateTime($dt);
                        $interval = $datetime1->diff($datetime2);
                        $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                    }else{
                        $html.=$days =$diff->format("%a days"); 
                    }
            $html.='</p>';
            $html.='<p class="artist-p-size">'.getDollLength($list->length, $list->length_unit).'</p>';
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($list->product->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($list->product->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>';
            $html.='</div>';
            $html.='</div>';           
            $html.='</div>';
            
      
         }
         
        
        return response()->json($html);

    }

   
     /**
     * @developer: Neha kumari
     * Date:23/12/21
     * Description: Get view more Nursery Product 
     */
    public function  recenttview_more(Request $request) {
        $userid = Auth::user()->id;
        $skip=$request->input('skip');
        $recentview_list = Recentview::with('Product','user')
        ->where('user_id',$userid)
        ->orderBy('id','desc')->offset($skip)->limit(6)->get();
        
        $html='';
        foreach($recentview_list as $list){
        $html.='<div class="col-sm-4">';
            $html.='<div class="ec-spe-products1 recent-product-box">';
            $html.='<div class="artist-product-new">';  
            $html.='<img src="'.asset('assets/images/products/'.$list->product->photo).'">';
            $html.='</div>';
                foreach($list->product->galleries as $gal) {
            $html.='<div class="artist-product-new">';
            $html.='<img src="'.asset('assets/images/galleries/'.$gal->photo).'">';
            $html.='</div>';
            
                }
            $html.='</div>';                             
            
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href=" '.url('').'/item/'.$list->product->slug.'" onclick="addrecent('.$list->product->id.')">';
            $html.=$list->product->name;                
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">Listed by '.$list->user->shop_name.'('.$list->user->name.')</p>';             
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                    $dt = $list->product->created_at;
                    $date = date('m/d/Y h:i:s a', time());                                       
                    $date1=date_create($dt);
                    $date2=date_create($date);
                    $diff=date_diff($date1,$date2);
                    $days =$diff->format("%a days"); 
                    if($days == '0 days') {
                        $datetime1 = new DateTime($date);
                        $datetime2 = new DateTime($dt);
                        $interval = $datetime1->diff($datetime2);
                        $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                    }else{
                        $html.=$days =$diff->format("%a days"); 
                    }
            $html.='</p>';
            $html.='<p class="artist-p-size">'.getDollLength($list->length, $list->length_unit).'</p>';
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($list->product->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($list->product->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>';
            $favorities_count = DB::table('favorite_items')->where('product_id', $list->product->id)->count();
            $html.='<p  onclick="addfev('.$list->product->id.')"><a href="javascript:void(0)">Add to Favorities';
            $html.= $favorities_count.'</a>';
            $html.=' </p>';
            $html.='</div>';
            $html.='<p class="ec-fs-pro-desc" id="'.$list->product->id.'_favorite_msg"></p>';                           
            $html.='</div>';           
            $html.='</div>';
        }       
        return response()->json($html);
    }
           
     /**
     * @developer: Neha kumari
     * Date:23/12/21
     * Description: Get view more Nursery Product 
     */
    public function  flloweditemview_more(Request $request) {
        $userid = Auth::user()->id;
        $skip=$request->input('skip');
        $v_ids = DB::table('vendor_followers')
        ->select('vendor_followers.user_id','vendor_followers.vendor_id')
        ->where('vendor_followers.user_id', $userid)
        ->get();
        foreach($v_ids as $ids) {
            $vendor_ids[] = $ids->vendor_id;
        } 
        $followitems = Product::with('user')->whereIn('user_id',$vendor_ids)->orderBy('id','desc')->offset($skip)->limit(6)->get();
        
        $html='';
        foreach($followitems as $list){
        $html.='<div class="col-sm-4">';
            $html.='<div class="ec-spe-products1 followed-product-box">';
            $html.='<div class="artist-product-new">';  
            $html.='<img src="'.asset('assets/images/products/'.$list->photo).'">';
            $html.='</div>';               
            $html.='</div>';                          
            $html.='<div class="ec-fs-pro-inner">';
            $html.='<h5 class="ec-fs-pro-title">';
            $html.='<a href=" '.url('').'/item/'.$list->slug.'" onclick="addrecent('.$list->id.')">';
            $html.=$list->name;                
            $html.='</a>';
            $html.='</h5>';
            $html.='<p class="ec-fs-pro-desc">Listed by '.$list->user->shop_name.'('.$list->user->name.')</p>';             
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="ec-fs-pro-desc-time">';
                    $dt = $list->created_at;
                    $date = date('m/d/Y h:i:s a', time());                                       
                    $date1=date_create($dt);
                    $date2=date_create($date);
                    $diff=date_diff($date1,$date2);
                    $days =$diff->format("%a days"); 
                    if($days == '0 days') {
                        $datetime1 = new DateTime($date);
                        $datetime2 = new DateTime($dt);
                        $interval = $datetime1->diff($datetime2);
                        $html.=$interval->format('%h')." Hours ".$interval->format('%i').' Minutes'; 
                    }else{
                        $html.=$days =$diff->format("%a days"); 
                    }
            $html.='</p>';
            $html.='<p class="artist-p-size">'.getDollLength($list->length, $list->length_unit).'</p>';
            $html.='</div>';
            $html.='<div class="w-100 d-flex justify-content-between">';
            $html.='<p class="time">';
            $html.=Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign;                                              
            $html.=Session::has('currency') ?   number_format($list->price * DB::table('currencies')->where('id','=',Session::get('currency'))->first()->value, 2)   : number_format($list->price * DB::table('currencies')->where('is_default','=',1)->first()->value, 2);
            $html.='</p>';
            $favorities_count = DB::table('favorite_items')->where('product_id', $list->id)->count();
            $html.='<p  onclick="addfev('.$list->id.')"><a href="javascript:void(0)">Add to Favorities';
            $html.= $favorities_count.'</a>';
            $html.=' </p>';
            $html.='</div>';
            $html.='<p class="ec-fs-pro-desc" id="'.$list->id.'_favorite_msg"></p>';                           
            $html.='</div>';           
            $html.='</div>';
        }       
        return response()->json($html);
    }

    public function comment_view_more(Request $request) {
        $skip=$request->input('skip');
        $user = Auth::user();        
        $forums = DB::table('vendor_forums')
		->join('users', 'vendor_forums.user_id', '=', 'users.id')
        ->join('countries', 'users.country_id', '=', 'countries.id')
		->where('vendor_forums.pid','=',0)
        ->where('users.is_vendor','=',1)
		->select('vendor_forums.*', 'users.name', 'users.photo', 'users.id AS userid','users.shop_name','countries.country_name')
        ->orderBy('id', 'DESC')
		->offset($skip)->limit(2)->get();
         $html='';      
        if(count($forums)>0){
            for($i=0;$i<count($forums); $i++){
                $child_forums = DB::table('vendor_forums')
                ->join('users', 'vendor_forums.user_id', '=', 'users.id')
                ->join('countries', 'users.country_id', '=', 'countries.id')
                ->where('users.is_vendor','=',1)
                ->where('vendor_forums.pid','=',$forums[$i]->id)
                ->select('vendor_forums.*', 'users.name', 'users.photo', 'users.id as userid','users.shop_name','countries.country_name')	
                ->get(); 

                $forums[$i]->comments=$child_forums;               
            }
        }
        foreach($forums as $forum){
            if($forum->photo!=''){
                $rootphoto = asset("assets/images/users/".$forum->photo);
            }else{
                $rootphoto = asset("images/products/avatar.webp");
            }
            $html.='<div class="post-comment-boxs">';            
            $html.='<div class="message bg-light row forum-product-box">';            
            $html.='<div class="col-md-2">';
            $html.='<img src="'.$rootphoto.'" alt="Avatar">';
            $html.='</div>';
            $html.='<div class="col-md-10"><h5>'.$forum->name.'</h5><h6>'.$forum->shop_name.'</h6><span>'.$forum->country_name.'</span>';
            $html.='<p>'.$forum->blog.'</p><p><span class="time-right pr-4">'.posted_time_calculation($forum->created).'</span>';
            $html.='<a onclick="bloglike('.$forum->pid.')" class="ecicon eci-thumbs-up" ></a>'.$forum->likes.'</p>';   
            if(count($forum->comments)>0) {
            foreach($forum->comments as $forumcomment) {
                if($forumcomment->photo!=''){
                    $childphoto = asset("assets/images/users/".$forumcomment->photo);
                }else{
                    $childphoto = asset("images/products/avatar.webp");
                }
           $html.='<div class="row message bg-darker">';
           $html.='<div class="col-md-2">';
           $html.=' <img src="'.$childphoto.'" alt="Avatar"></div> <div class="col-md-10"><h5>dfgg</h5>';
           $html.= '<h6>'.$forumcomment->name.'</h6> <span>'.$forumcomment->shop_name.'</span><p>'.$forumcomment->country_name.'</p>'.$forumcomment->blog.' <p>';
           $html.= '<span class="time-right pr-4">'.posted_time_calculation($forumcomment->created).'</span>';
           $html.= '<a  onclick="comentlike('.$forumcomment->id.')" class="ecicon eci-thumbs-up" ></a>'.$forumcomment->likes.'</p></div></div>';
            }
        }
        // <!-- =================chlide COMMENT=============================================== -->
        $html.='<div class="row message bg-darker">';
        $html.='<div class="col-md-2">';
        if($user->photo!=''){
            $user_photo = asset("assets/images/users/".$user->photo);
        }else{
            $user_photo = asset("images/products/avatar.webp");
        }
        $html.=' <img src="'.$user_photo.'" alt="vendor"> </div>';
        $html.= '<div class="col-md-10">';
        $html.= '<form action="'.route('vendor-storenewreplyforum').'" method="post">';   
        $html.= csrf_field();                                     
        $html.='<input type="hidden" name="pid" value="'.$forum->id.'">';                                   
        $html.= '<textarea class="form-control mb-4" placeholder="Write a Comment" name="forum" required=""></textarea>';                                      
        $html.='<button class="btn btn-success">Submit</button>';
        $html.='</form></div></div></div></div>';                                          
                                                       
    }
    
    return response()->json($html);
}       

      


}