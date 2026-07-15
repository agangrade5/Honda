import { route } from 'ziggy-js';

jQuery(document).ready(function($)
{
    $(".multi-select").multiSelect({
        afterInit: function()
        {
            // Add alternative scrollbar to list
            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
        },
        afterSelect: function()
        {
            // Update scrollbar size
            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
        }
    });

    $(".selectboxit").selectBoxIt().on('open', function()
    {
        // Adding Custom Scrollbar
        $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
    });

    $('input[name="type"]').on('click', function(){
        if($('input[name="type"]:checked').val()=="c"){
            $(".next").hide();
            $("#EndSubmitForm").show();
            $("#data-loading").show();
            $("#survey-show").hide();
        }
        else if($('input[name="type"]:checked').val()=="s"){
            $(".next").show();
            $("#EndSubmitForm").hide();
            $("#data-loading").hide();
            $("#survey-show").show();
        }
        else if($('input[name="type"]:checked').val()=="y"){
            $(".next").hide();
            $("#EndSubmitForm").show();
            $("#data-loading").show();
            $("#survey-show").hide();
        }
    });

    $(".previous").on('click', function(){
        if($("div.tab-content").find(".active").attr("id")=="fwv-3"){
            $("#EndSubmitForm").hide();
        }
        $(".next").show();
    });

    $("#EndSubmitForm").on('click', function(e){
        e.preventDefault();
        var eventId = $("#eventID").val();
        var action = $('input[name="type"]:checked').val();
        var surveyId = $("#JumpStartSurveyEdit").val();

        var url = route('manage-data-management.export') + "?EventID=" + eventId;

        if (action == "c") {
            url += "&action=cust";
        } else if (action == "s") {
            url += "&action=survey&SurveyID=" + surveyId;
        } else if (action == "y") {
            url += "&action=honda";
        }

        window.open(url, "_blank");
    });

    $(".next").on('click', function(){
        if($("div.tab-content").find(".active").attr("id")=="fwv-2"){
            if($('input[name="type"]:checked').val()=="c"){
                $(".next").hide();
                $("#survey-show").hide();
                $("#data-loading").show();
            }
            else if($('input[name="type"]:checked').val()=="s"){
                $(".next").hide();
                $("#survey-show").show();
                $("#data-loading").hide();
                $("#EndSubmitForm").show();
            }
            else if($('input[name="type"]:checked').val()=="y"){
                $(".next").hide();
                $("#survey-show").hide();
                $("#data-loading").show();
            }
        }
    });
});
