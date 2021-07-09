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
                  <input type="radio" id="smartkid" name="service" value="9500">
                  <label for="male">Smart Kid</label><br>
            </div>
            <div class="uk-flex uk-flex-center text-burmese">
                  <input type="radio" id="mmsport" name="service" value="9510">
                  <label for="female">MM Sport</label>
            </div>
            <div class="uk-flex uk-flex-center subs-div">
                <button class="uk-button" id="choose-btn">Go to Home</button>
            </div>

        </div>
    </div>
</main>
<script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript">
    $('input:radio[name="service"]').change(
        function(){
            if ($(this).is(':checked')) {
                var checked = $(this).val();
                console.log(typeof(checked));
                if (checked === "9500") {
                    var url = "{{ url('/') }}";
                    window.location.href = url + "?service_type=SMARTKID&service_id=9500";
                } else if (checked === "9510") {
                    var url = "{{ url('/') }}";
                    window.location.href = url + "?service_type=MMSPORT&service_id=9510";                    
                }
            }
        });
</script>
@endsection