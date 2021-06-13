<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h1>loading</h1>

	<input type="hidden" id="check-tran-url" value="{{ url('checkTran') }}">
	<input type="hidden" id="msisdn" value="{{ $data['MSISDN'] }}">
	<input type="hidden" id="tranid" value="{{ $data['transID'] }}">
	<script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
	<script>
	    $(function(){
	        $(document).ready(function () {
	            setTimeout(function () {
	                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	                var url = $('#check-tran-url').val();
	                var msisdn = $('#msisdn').val();
	                var tranid = $('#tranid').val();
	                $.ajax({
	                    url: url + "?msisdn=" + msisdn + "&tranid=" + tranid,
	                    type: 'GET',
	                    dataType: 'JSON',
	                    success: function (data) {
							if(data['status'] === true) {
								window.location.href = data['url'];
							}
							window.location.href = data['url'];
	                    }
	                });
	            }, 3000)
	        })
	    })
	</script>
</body>
</html>