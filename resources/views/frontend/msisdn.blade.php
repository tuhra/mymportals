@extends('frontend.layouts.master')
@section('content')
<main>
    <div>
        <div class="uk-container">
            <div class="uk-flex uk-flex-center">
                <div class="land-img">
                    <img src="{{ asset('web/images/kids_logo.png') }}"/>
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
                ကလေးများအတွက် ပညာရေးဝန်ဆောင်မှု၊ <br/>သီချင်းများ၊ ရုပ်ပြများ၊ ဥာဏ်စမ်းပဟေဠိများ <br/> ပါဝင်ပါသည်။
            </div>

            <form method="post" action="{{ url('msisdn') }}">
                <div class="uk-flex uk-flex-center">
                    {{ csrf_field() }}
                    <div class="input-group msisdn">
                        <span class="input-group-addon" id="basic-addon1">+959</span>
                        <input type="text" class="form-control" name="msisdn" placeholder="Enter Msisdn" aria-describedby="basic-addon1" maxlength="9">
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