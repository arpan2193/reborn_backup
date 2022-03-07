@extends('layouts.admin')

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Add Slider') }} <a class="add-btn"
                            href="{{ route('admin-sl-index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Home Page Settings') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-sl-index') }}">{{ __('Sliders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-sl-create') }}">{{ __('Add Slider') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-sl-create') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @include('includes.admin.form-both')
                                
                                @php $all_lang = DB::table('languages')->get(); @endphp
                                <select name="lang_id" id="languages">
                                    <option selected>Select Language</option>
                                    @foreach($all_lang as $lang)
                                    <option value="{{$lang->id}}">{{$lang->language}}</option>
                                    @endforeach
                                  </select>                            
                                {{-- Title Section --}}

                                <div class="panel panel-default slider-panel">
                                    <div class="panel-heading text-center">
                                        <h3>{{ __('Title') }}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label" for="title_text">{{ __('Text') }}*</label>

                                                <textarea class="form-control" name="title_text" id="title_text" rows="5"
                                                    placeholder="{{ __('Enter Title Text') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Title Section Ends --}}


                                {{-- Details Section --}}

                                <div class="panel panel-default slider-panel">
                                    <div class="panel-heading text-center">
                                        <h3>{{ __('Description') }}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="control-label"
                                                    for="details_text">{{ __('Text') }}*</label>

                                                <textarea class="form-control" name="details_text" id="details_text"
                                                    rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Title Section Ends --}}


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="img-upload full-width-img">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ asset('assets/admin/images/upload.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="photo" class="img-upload" id="image-upload">
                                            </div>
                                            <p class="text">
                                                {{ __('Prefered Size: (1920x800) or Square Sized Image') }}</p>
                                        </div>

                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Link') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="link"
                                            placeholder="{{ __('Link') }}" required="" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn"
                                            type="submit">{{ __('Create Slider') }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
