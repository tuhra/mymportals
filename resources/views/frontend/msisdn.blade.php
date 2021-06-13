@extends('frontend.layouts.master')
@section('content')
<main>
    <div>
        <div class="uk-container">
            <div class="uk-flex uk-flex-center">
                <div class="land-img">
                    <img src="{{ asset($config['logo']) }}"/>
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
                {!! $config['landing'] !!}
            </div>

            <div style="width: 100%; text-align: center;">
                <img style="width: 320px; height: auto; border-radius: 10px;" src="{{ asset($config['image']) }}"> <br><br>
            </div>

            <div style="margin: 0; box-sizing: border-box; border-width: 3px 0px 3px 0px; border-color: #58c7d8; border-style: solid; padding: 6px; margin-top: 9px; font-size: 17px; color: black; textalign: center; padding-top: 15px; line-height: 2em; text-align: center;">
                    {{$config['landing_price']}}
                </div>
            <br><br>

            <form method="post" action="{{ url('msisdn') }}">
                <div class="uk-flex uk-flex-center">
                    {{ csrf_field() }}
                    <div class="input-group msisdn">
                        <span class="input-group-addon" id="basic-addon1">+959</span>
                        <input type="text" class="form-control" name="msisdn" placeholder="Enter Msisdn" aria-describedby="basic-addon1" maxlength="9" required="">
                    </div>
                </div>
                <div class="uk-flex uk-flex-center subs-div">
                    <button class="uk-button" id="subscribe-btn">စာရင်းသွင်းမည်</button>
                </div>
            </form>

        </div>
    </div>
</main>
@endsection