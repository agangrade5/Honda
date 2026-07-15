<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="With Honda, you’ll receive early access to demos, new product information and much more!">
    <title>Honda Registration</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" sizes="16x16" type="image/png">
    <link rel="stylesheet" href="{{ asset('register-assets/css/materialize.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('register-assets/css/materialize-stepper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('register-assets/css/style.css') }}">

    {!! returnScriptWithNonce(asset('register-assets/js/jquery.min.js')) !!}
    {!! returnScriptWithNonce(asset('register-assets/js/materialize.min.js')) !!}
    {!! returnScriptWithNonce(asset('register-assets/js/jquery.validate.min.js')) !!}
    {!! returnScriptWithNonce(asset('register-assets/js/additional-methods.js')) !!}
    {!! returnScriptWithNonce(asset('register-assets/js/materialize-stepper.min.js')) !!}

    <script type="text/javascript">
        var dynamic_survey_html_1 = {!! json_encode($strHtml1) !!};
        var dynamic_survey_html_2 = {!! json_encode($strHtml2) !!};
        var dynamic_survey_html_3 = {!! json_encode($strHtml3) !!};
        var dynamic_survey_html_4 = {!! json_encode($strHtml4) !!};
        var dynamic_survey_html_5 = {!! json_encode($strHtml5) !!};
    </script>
</head>
<body>

<div class="section main-section">
    <div id="loadingImage" style="display: none;">
        <div class="loadingImageinner">
            <img src="{{ asset('register-assets/images/loading_spinner.svg') }}" alt="honda">
        </div>
    </div>

    @if(empty($showError) && $preregisterHTML)
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-content steps-content">
                        <form method="POST" name="SubmitFrm" id="SubmitFrm" action="{{ route('register.index') }}">
                            @csrf
                            <input type="hidden" name="eventid" id="eventid" value="{{ $eventid }}">
                            <input type="hidden" name="totalQuestion" id="totalQuestion" value="{{ $totalQuestion }}">
                            <input type="hidden" name="currentDate" id="currentDate" value="{{ date('Y-m-d H:i:s') }}">
                            <input type="hidden" name="questionIDS" id="questionIDS" value="{{ $questionIDS }}">
                            <input type="hidden" name="surveyid" id="surveyid" value="{{ $registrationsurveyid }}">
                            <input type="hidden" name="isCheckedAll" id="isCheckedAll" value="{{ $isCheckedAll }}">

                            {!! $preregisterHTML->htmlcontent !!}

                            <div class="container container-border" style="margin-top: -28px !important;">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-content steps-content">
                                                <ul data-method="GET" class="stepper horizontal">
                                                    <li class="step active">
                                                        <div class="step-title waves-effect waves-dark"><span>Quantity</span></div>
                                                        <div class="step-content">
                                                            {!! $preregisterHTML->quantityform !!}
                                                        </div>
                                                    </li>
                                                    <li class="step">
                                                        <div class="step-title waves-effect waves-dark" id="ticketInfoBtn"><span>Info</span></div>
                                                        <div class="step-content">
                                                            <span id="singleFrm"></span>
                                                            <span id="addNewTicketsDynamic"></span>
                                                            {!! $preregisterHTML->infoform !!}
                                                        </div>
                                                    </li>
                                                    <li class="step">
                                                        <div class="step-title waves-effect waves-dark" id="ConfirmDivId"><span>Complete</span></div>
                                                        <div class="step-content">
                                                            {!! str_replace(
                                                                ['string_actual_link', 'http://honda.kickstartuser.com/register/images/success.png'],
                                                                [request()->fullUrl(), asset('register-assets/images/success.png')],
                                                                $preregisterHTML->completeform
                                                            ) !!}
                                                            <div class="step-actions"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container showErrorMessage">
            {{ $showError ?: 'This is not a valid event for honda member registration' }}
        </div>
        <div style="width:100%;text-align: center;font-size: 14px;color: #fff;">
            <span>
                <a target="_blank" href="#" style="text-decoration:underline;color: #fff;">Terms &amp; Conditions</a>
            </span>
            <span>
                &nbsp; | &nbsp;
            </span>
            <span>
                <a target="_blank" href="#" style="text-decoration:underline;color: #fff;">Privacy Policy</a>
            </span>
        </div>
    @endif
</div>

@vite(['resources/js/backend/auth/register.js'])

<style type="text/css">
#SubmitFrm {
    padding-top: 10.0rem !important;
    position: relative !important;
}
.step-title {
    pointer-events: none;
}
#loadingImage {
    width: 100%;
    background: #fff;
    height: 100%;
    position: fixed;
    z-index: 999999999;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    text-align: center;
    padding-top: 50px;
}
#loadingImage .loadingImageinner {
    width: 100%;
    height: 100%;
    align-items: center;
    -webkit-align-items: center;
    -moz-align-items: center;
    -ms-align-items: center;
    justify-content: center;
    -webkit-justify-content: center;
    -moz-justify-content: center;
    -ms-justify-content: center;
    -webkit-display: flex !important;
    -moz-display: flex !important;
    -ms-display: flex !important;
    display: flex !important;
}
.showErrorMessage {
    border: 1.5px solid #717171;
    border-top: 0;
    border-radius: 4px;
    margin-bottom: 25px !important;
    padding: 50px 30px;
    text-align: center;
    margin-top: -30px;
    color: red;
    font-weight: 400;
}
</style>

{!! returnScriptWithNonce(asset('register-assets/js/jquery.inputmask.bundle.js')) !!}


</body>
</html>
