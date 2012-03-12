function eventSortableList(){
    $("#table-rss-news tbody").sortable({        
        start: function(event, ui){
            var $item = ui.item;
            $item.addClass("highlight");
        },
        stop: function(event, ui){            
            var $item = ui.item;
            $item.removeClass("highlight");
            updateIndexs($item);           
        }        
    });
    $( "#table-rss-news tbody" ).disableSelection();
}

function updateIndexs($item){
    var $table = $item.parents("table");
    var url = $item.children("td").children("a.delete").attr("href").replace("delete","update/index");
    var arrayIndex = new Array();    
    var arrayIdRss = new Array();
    $table.children("tbody").children("tr").each(function(){
        var idRss = $(this).children("td").children("a.delete").attr("id");
        arrayIndex.push($(this).index() + 1);
        arrayIdRss.push(idRss);        
    });
    sendUpdatedOrder(arrayIndex, arrayIdRss, url);
}

function sendUpdatedOrder(indexes, ids, url){    
    var request = AdminAjax();
    request.save(url, {"ids": ids, "indexes": indexes}, true);
}

function eventDelete(){
    $("a.delete").click(function(){
        if(confirm("Este registro será excluído do sistema. Clique em 'Ok' para continuar.")){
            var url = $(this).attr("href");
            var data = {"id" : this.id};
            if($(this).hasClass("publication")){
                data.type = $(this).attr("title");
            }
            var request = AdminAjax();
            request.doDelete(url, data);
        }
        return false;
    });
}

function eventInsert(){    
    $(".button-edit, .button-insert, .button-insert-analysis").click(function(){                        
        var $form = $(this).parents("form");
        var isValid = valid($form);        
        removeErrors($form);
        submitIfIsValid($form, isValid);
        return false;
    });
}

function eventInsertSpreadsheet(){
    //$(".button-insert-spreadsheet").click(function(){
       //console.log($(this).parents("form").html());
      // return false;
    //});
}

function textFieldCharLimits(){
    var limit = 0;
    $("input.charLimits").keyup(function(e){
        var totalChars = $(this).val().length;
        var maxLength = parseInt($(this).attr("maxlength"));
        if(totalChars < maxLength){           
            limit--;
            var thisid = "#"+this.id;
            //var current = parseInt($(thisid+"-char").val());
            $(thisid+"-char").text(limit);
        }
    }).focus(function(){        
        limit = Number($(this).attr("maxlength")) - $(this).val().length;
    });
}

var removeErrors = function($form){
    $("div.erro").empty();
    $("#"+$form.attr("id") + " input").removeClass("error").removeClass("errorDatacenter");
    $("#"+$form.attr("id") + " select").removeClass("error").removeClass("errorDatacenter");
}

var errorsMessages = function(type){
    var messages = new Array();
    messages["text"] = "Valor Inválido";
    if(type == "button-insert-paper"){
        messages["file"] = "Valor Inválido ou não é um Arquivo PDF";
    }else if(type == 'button-insert-spreadsheet'){
        messages["file"] = "Valor Inválido ou não é um Arquivo XLS. Se for uma planilha, verifique se está no formato .XLSX";
    }
    messages["select-one"] = "Selecione uma opção";
    return messages;
}

function submitIfIsValid($form, isValid){
    if(isValid.valid){
        var data = {};
        if($form.attr("title") == 'country'){
            data = {
                'id': $("#country-id").val(),
                'name':$('#name').val(),
                'type': $('#type_country').val()
            }
        }else{
            data = {
                'link': $("#link").val(),
                'title': $("#title").val(),
                'state': $("#state").val(), 
                'type_event': $("input[name=type_event]:checked").val()
            }            
        }        
        if($form.attr("id") != 'form-news'){            
            data.subarea = $("select#subarea").val();
        }
        if($form.attr("id") == 'form-video'){
            data.duration = $("#duration").val();
        }
        if($form.hasClass("analysis")){
            if($("#text").val() == ''){
                alert('O campo texto não deve estar vazio!');
                return false;
            }
            data.text = $("#text").val()
        }
        
        var request = AdminAjax();
        request.save($form.attr("action"), data);
    }else{
        var errorMessages = errorsMessages();
        $(isValid.inputs).each(function(i, item){
            $(item).addClass("error");
            $(item).next("div.erro").html(errorMessages[$(item).attr("type")]);
        });
    }
}

function listAreasToSelect(){
    if($("select#area").html() != null){        
        if(!$("select#area").hasClass("area_analysis")){
            var request = AdminAjax();        
            request.list_to_select("../admin/area", $("select#area"), {'no-must-online': true});
            eventChangeToArea();            
        }
    }
}

function listStatesToSelect(){
    if($("select#state").html() != null){
        var request = AdminAjax();        
        request.list_to_select("../admin/state", $("select#state"), {'no-must-online': true});
    }
}

function listPublicationTypesToSelect(){
    if($("select#publicationType").html() != null){
        var request = AdminAjax();
        request.list_to_select("../admin/publicationTypes", $("select#publicationType"), {'no-must-online':true});
    }
}

function listValuesToDatacenterSelects(){
    //console.log($("#form-datacenter").attr("action"));
    var request = AdminAjax();
    var url = "../../datacenter/param";
    var data = {'id': null, 'no-must-online': true};
    listGroupsToDatacenter(request,url,data,$("select#groups"));
    listVarietiesToDatacenter(request, url, data,$("select#variety"));    
    listCoffeTypeToDatacenter(request, url, data,$("select#coffetype"));
    listDestiniesToDatacenter(request, url, data, $("select#destiny"));
    listOriginToDatacenter(request,url, data, $("select#origin"));
}
function listGroupsToDatacenter(request, url, data, $select){
    if($select.html() != null){
        data.type = 'Groups';
        request.list_to_select(url,$select,data);
        groupChange();
    }
}

function writeARadioBoxForCountriesIfGroupCannotHaveOriginAndDestinyAtTheSameTime(group){
    var comercioInternacional = 1;
    var $destiny = $("select#destiny");
    var $origin = $("select#origin");
    if(group != comercioInternacional){
        hideCountryDependingOnGroupSelected($destiny);
        hideCountryDependingOnGroupSelected($origin);
        $("div.country_radios").show();
    }else{
        showCountrySelect($destiny);
        showCountrySelect($origin);
        $("div.country_radios").hide();        
    }
}

var hideCountryDependingOnGroupSelected = function($countrySelect){
   $countrySelect.hide().attr("disabled","disabled").prev("label").hide().val(0);
}

var showCountrySelect = function($countrySelect){
    if(!$countrySelect.is(":visible")){
        $countrySelect.removeAttr("disabled").show().prev("label").show().val('');        
    }
}

function groupChange(){
    $("select#groups").live("change",function(){       
       if($(this).val() != ''){
           var idGroup = $(this).val();
           var request = AdminAjax();
           var data = {"id":idGroup,"type":"subgroup","no-must-online":true};
           request.list_to_select("../../datacenter/param", $("select#subgroups"), data);
           data.type = "font";
           request.list_to_select("../../datacenter/param", $("select#font"), data);
           writeARadioBoxForCountriesIfGroupCannotHaveOriginAndDestinyAtTheSameTime(idGroup); 
       }       
    });
}

function listVarietiesToDatacenter(request, url, data, $select){
    if($select.html() != null){
        data.type = "Variety";
        request.list_to_select(url,$select, data);
        $select.ajaxStop(function(){
            $(this).attr("disabled","disabled");
            $(this).append('<option value="none"></option>').val('none');
        });
    }
}
function listCoffeTypeToDatacenter(request,url,data,$select){
    if($select.html() != null){
        data.type = "CoffeType";
        request.list_to_select(url, $select, data);        
        $select.ajaxStop(function(){
            eventChangeToCoffeType();
        }); 
    }
}
function eventChangeToCoffeType(){
    $("#coffetype").live('change', function(){
        var option = $(this).children("option:selected").text();
        if(option == 'Verde'){
            $("#variety option[value='none']").remove();
            $("#variety").removeAttr("disabled").val('');
        }else{
            $("#variety").attr("disabled", "disabled");
            $("#variety").append('<option value="none"></option>').val('none');
        }
    });
}

function listDestiniesToDatacenter(request,url,data,$select){
    if($select.html() != null){
        data.type = 'destiny';
        request.list_to_select(url, $select, data);
        $select.ajaxStop(function(){
           eventDisableCountrySelect($(this)); 
        });
    }
}

var eventDisableCountrySelect = function($select){
    $select.change(function(){
        if(this.value != ''){
            if(this.id == 'origin'){
                $("#destiny").attr("disabled","disabled");
            }else if(this.id == 'destiny'){
                $("#origin").attr("disabled","disabled");
            }
        }else{
            if(this.id == 'origin'){
                $("#destiny").removeAttr("disabled");
            }else if(this.id == 'destiny'){
                $("#origin").removeAttr("disabled");
            }
        }
    });
}

function listOriginToDatacenter(request,url,data,$select){
    if($select.html() != null){
        data.type = 'origin';
        request.list_to_select(url, $select, data);
        $select.ajaxStop(function(){
            eventDisableCountrySelect($(this));
        });
    }
}
function eventChangeToArea(){
    $("select#area").live("change", function(){
        if($(this).val() != '' && !$(this).hasClass("area_analysis")){
            var request = AdminAjax();
            request.list_to_select("../admin/subarea", $("select#subarea"), {'area': $(this).val(), 'no-must-online': true});
        }else{
            $("select#subarea")
                .attr("disabled","disabled")
                .html("<option value=''>Selecione uma área</option>");
        }
    });
}

function dataDatacenter(){  
    var comercioInternacional = 1;
    var data = {
        "subgroup":$("#subgroups").val(),
        "font":$("#font").val(),
        "coffetype":$("#coffetype").val(),
        "variety":$("#variety").val()   
    };    
    data.typeCountry = $("input[name=country_group]:checked").val();    
    
    if($("#destiny").is(":visible") && $("#destiny").attr("disabled") == undefined){
        data.destiny = $("#destiny").val();
    }else{
        data.destiny = -1;
        if($("#groups").val() == comercioInternacional) data.typeCountry = 'destiny';
    }
    
    if($("#origin").is(":visible") && $("#origin").attr("disabled") == undefined){
        data.origin = $("#origin").val();
    }else{
        data.origin = -1;
        if($("#groups").val() == comercioInternacional) data.typeCountry = 'origin';
    }
        
    return data;
}

function dataPublication(){
    var data = {
        "title": $("#title").val(),
        "subarea":$("#subarea").val(),
        'state': $("#state").val(), 
        'year': $("#publication-year").val(),
        'publication_type': $("#publicationType").val(),
        'type_event': $("input[name=type_event]:checked").val()                
    };
    return data;
}

function getData(type){
    var data = null;
    if(type == "button-insert-paper"){
        data = dataPublication();
    }else if(type == 'button-insert-spreadsheet'){
        data = dataDatacenter();
    }
    return data;
}

function eventInsertDataWithFile(){    
    $(".button-insert-paper, .button-insert-spreadsheet").click(function(){                
        var $form = $(this).parents("form");
        removeErrors($form);
        var validResponse = valid($form);
        if(validResponse.valid){            
            var data = getData($(this).attr("class"));
            var request = AdminAjax();
            if($("div.country_radios").is(":visible")){
                if(data.typeCountry == undefined){
                    alert('Selecione o grupo de países a que os dados da planilha pertencem');
                    return false;
                }
            }
            request.saveWithFile($form, data);
        }else{
            var insertClass = $(this).attr("class");
            var inputsErrors = errorsMessages($(this).attr("class"));
            $(validResponse.inputs).each(function(i,item){                
                $(item).addClass("error");
                if(insertClass == "button-insert-spreadsheet"){                    
                    $(item).removeClass("error")
                    $(item).addClass("errorDatacenter");
                }                    
                var type = $(item).attr("type").toString();
                if($(item).next("div.erro").html() != null)
                    $(item).next("div.erro").html(inputsErrors[type]);
            });
            return false;
        }
        return false;
    });
}

function valid($form){    
    var id = "#"+$form.attr("id");
    var isvalid = true;
    var invalid_inputs = new Array();
    var formAction = $form.attr("action");    
    $(id + " input, select").each(function(i, obj){
        if($(obj).is(":visible") && $(obj).attr("disabled") == undefined && obj.value == ''){
            isvalid = isvalid && false;
            invalid_inputs.push("#"+obj.id);
        }else{
            if(obj.type == "file"){
                var file = $(this).val();
                var indexA = file.length-4;
                var indexB = file.length;
                var toVerify = file.substring(indexA, indexB).replace(".","");
                if(!canInsertThisFileExtension(toVerify, formAction)){
                    isvalid = isvalid && false;
                    invalid_inputs.push("#"+obj.id);
                }
            }
        }
    });
    return {
        "valid": isvalid,
        "inputs": invalid_inputs
    }
}

var canInsertThisFileExtension = function(extension, serviceAction){
    //modelo do link http://{root}/admin/datacenter/insert
    var serviceString = serviceAction.split("/admin/");
    serviceString = serviceString[1];    
    switch(serviceString){
        case 'datacenter/insert':
            return insertFileToDatacenter(extension);
        break;
        case 'paper/insert':
            return insertFileToPublication(extension);
        break;
    }
}

var insertFileToPublication = function(extension){
    var fileExtensionsAllowed = new Array("pdf");
    return (fileExtensionsAllowed.indexOf(extension, 0) != -1);    
}

var insertFileToDatacenter = function(extension){
    var fileExtensionsAllowed = new Array("xls");
    return (fileExtensionsAllowed.indexOf(extension, 0) != -1);
}