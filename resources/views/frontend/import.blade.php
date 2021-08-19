@extends('frontend.layouts.master')
@section('content')
<main>
    <div>
        <div class="uk-container">
            <div class="uk-flex uk-flex-center">
                <div class="land-img">
                    
                </div>
            </div>
            <div class="uk-flex rainbow">
                <div class="uk-width-1-6" style="background: #46afe1; width: 16.6%"></div>
                <div class="uk-width-1-6" style="background: #3d3c88; width: 16.6%"></div>
                <div class="uk-width-1-6" style="background: #eb4c9f; width: 16.6%"></div>
                <div class="uk-width-1-6" style="background: #eb5158; width: 16.6%"></div>
                <div class="uk-width-1-6" style="background: #f0a741; width: 16.6%"></div>
                <div class="uk-width-1-6" style="background: #24b54f; width: 16.6%"></div>
            </div>
            <!--<div class="uk-flex uk-flex-center text-ing">
              You can learn educational videos, songs, stories, puzzles and more in Smart Kids.
          </div>-->
            <div class="uk-flex uk-flex-center text-burmese">

            </div>

            <div style="width: 100%; text-align: center;">
                
            </div>


            <form method="post" action="{{ url('import') }}" enctype="multipart/form-data">
            	{{ csrf_field() }}
                <div class="uk-flex uk-flex-center">
                    <div class="input-group msisdn">
                        <input type="file" class="form-control" name="excel" >
                    </div>
                </div>
                <br>
                <div class="uk-flex uk-flex-center">
                    <div class="input-group msisdn">
                        <select name="service_id" class="form-control">
                            <option value="9500">Smart Kid</option>
                            <option value="9510">Myanmar Sport</option>
                            <option value="9520">Guess It</option>
                        </select>
                    </div>
                </div>
                <div class="uk-flex uk-flex-center subs-div">
                    <button class="uk-button" type="submit">Import</button>
                </div>
            </form>

        </div>
    </div>
</main>
@endsection




