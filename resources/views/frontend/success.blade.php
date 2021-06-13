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
                ကလေးများအတွက် ပညာရေးဝန်ဆောင်မှု၊ <br/>သီချင်းများ၊ ရုပ်ပြများ၊ ဥာဏ်စမ်းပဟေဠိများ <br/> ပါဝင်ပါသည်။
            </div>


            <div class="uk-flex uk-flex-center subs-div">
                <button class="uk-button" id="continue-btn">Continue</button>
            </div>

        </div>
    </div>
</main>
@endsection