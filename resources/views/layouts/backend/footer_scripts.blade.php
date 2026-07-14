<!-- Imported styles on this page -->
<link rel="stylesheet" href={{ asset("assets/js/datatables/dataTables.bootstrap.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/daterangepicker/daterangepicker-bs3.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/bootstrap-datetimepicker.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/select2/select2.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/select2/select2-bootstrap.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/multiselect/css/multi-select.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/wysihtml5/src/bootstrap-wysihtml5.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/uikit/vendor/codemirror/codemirror.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/uikit/uikit.css")}}>
<link rel="stylesheet" href={{ asset("assets/js/uikit/css/addons/uikit.almost-flat.addons.min.css")}}>
<!-- Bottom Scripts -->
{!! returnScriptWithNonce(asset("assets/js/bootstrap.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/TweenMax.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/resizeable.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/joinable.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/xenon-api.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/xenon-toggles.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/datatables/js/jquery.dataTables.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/moment.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/wysihtml5/lib/js/wysihtml5-0.3.0.js")) !!}
<!-- Imported scripts on this page -->
{!! returnScriptWithNonce(asset("assets/js/xenon-widgets.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/devexpress-web-14.1/js/globalize.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/devexpress-web-14.1/js/dx.chartjs.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/toastr/toastr.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/datatables/dataTables.bootstrap.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/datatables/yadcf/jquery.dataTables.yadcf.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/datatables/tabletools/dataTables.tableTools.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/select2/select2.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/jquery-ui/jquery-ui.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/selectboxit/jquery.selectBoxIt.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/multiselect/js/jquery.multi-select.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/daterangepicker/daterangepicker.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/datepicker/bootstrap-datepicker.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/datepicker/bootstrap-datetimepicker.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/inputmask/jquery.inputmask.bundle.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/formwizard/jquery.bootstrap.wizard.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/jquery-validate/jquery.validate.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/ckeditor/ckeditor.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/ckeditor/adapters/jquery.js")) !!}
{{-- {!! returnScriptWithNonce(asset("assets/js/rwd-table/js/rwd-table.min.js")) !!} --}}
<!-- JavaScripts initializations and stuff -->
{!! returnScriptWithNonce(asset("assets/js/xenon-custom.js")) !!}

<script>
$(document).ready(function() {
    $(document).on('click', '#btn-logout', function(event) {
        event.preventDefault();
        var form = document.getElementById('logout-form');
        if (form) {
            form.submit();
        }
    });
});
</script>
