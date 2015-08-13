function update_objects(modelFilename, fieldName)
{
    //retrives information from a php-generated xml
    var url = 'app.php?c=Request&a=getGroupModelsMDXML&mg_id='+document.getElementById('model_group_id').value;

    var hreq = null;
    if(window.XMLHttpRequest){//firefox, chrome,...
       hreq = new XMLHttpRequest();
    } else {
       hreq = new ActiveXObject("Microsoft.XMLHTTP");//IE
    }

    if (typeof fieldName === "undefined") {
        fieldName = "modelId";
    }

    hreq.onreadystatechange = function(){changeObjectsList(hreq, modelFilename, fieldName); };
    hreq.open("GET", url, true); //true=asynchronous
    hreq.send(null);
}

function changeObjectsList(hreq, modelFilename, fieldName)
{
    var text="<select name='"+fieldName+"' id='"+fieldName+"' onchange='change_thumb('"+fieldName+"');update_model_info();'>";

    // If the request is finished
    if(hreq.readyState === 4)
    {
        var models=hreq.responseXML.getElementsByTagName("model");

        for(i=0; i<models.length; i++)
        {
            var object=models[i];
            var id=object.getElementsByTagName("id")[0].childNodes[0].nodeValue;
            var name=object.getElementsByTagName("name")[0].childNodes[0].nodeValue;
            text+="<option value='"+id+"'";
            if(modelFilename == name) {
                text+= " selected=\"selected\"";
            }
            text+=">"+name+"</option>";
        }
    }

    text+="</select>";

    document.getElementById('form_objects').innerHTML = text;
    change_thumb(fieldName);
}

function update_model_info(path)
{
    //retrives information from a php-generated xml
    var url = 'app.php?c=Request&a=getModelInfoXML&mo_id='+document.getElementById('modelId').value;

    var hreq = null;
    if(window.XMLHttpRequest){//firefox, chrome,...
       hreq = new XMLHttpRequest();
    } else {
       hreq = new ActiveXObject("Microsoft.XMLHTTP");//IE
    }

    hreq.onreadystatechange = function(){changeModelInfo(hreq,path); };
    hreq.open("GET", url, true); //true=asynchronous
    hreq.send(null);
}

function changeModelInfo(hreq, path)
{
    if(hreq.readyState === 4) //checks that the request is finished
    {
        var object=hreq.responseXML;
        var name=object.getElementsByTagName("name")[0].childNodes[0].nodeValue;
        var notesNode = object.getElementsByTagName("notes")[0].childNodes;
        var notes;
        if (notesNode.length>0) {
            notes=object.getElementsByTagName("notes")[0].childNodes[0].nodeValue;
        } else {
            notes = "";
        }
        var au_id=object.getElementsByTagName("author")[0].childNodes[0].nodeValue;
        
        document.getElementById('mo_name').value = name;
        document.getElementById('notes').value = notes;
        document.getElementById('mo_author').value = au_id;
    }
}

function change_thumb(modelIdFieldName) {
    if (typeof modelIdFieldName === "undefined") {
        modelIdFieldName = "modelId";
    }
    
    document.getElementById('form_objects_thumb').src = "app.php?c=Models&a=thumbnail&id="+document.getElementById(modelIdFieldName).value;  
}

function update_map(long_id, lat_id) {
    var longitude = document.getElementById(long_id).value;
    var latitude = document.getElementById(lat_id).value;

    if(longitude!=="" && latitude!=="")
        document.getElementById('map').data = "http://mapserver.flightgear.org/popmap/?zoom=13&lat="+latitude+"&lon="+longitude;
}


function update_country() {
    var longitude = document.getElementById('longitude').value;
    var latitude = document.getElementById('latitude').value;
    
    if (longitude!=="" && latitude!=="") {
        //retrieves information from a php-generated xml
        var url = '/app.php?c=Request&amp;a=getCountryCodeAtXML&lg='+longitude+"&lt="+latitude;

        var hreq = null;
        if(window.XMLHttpRequest){//firefox, chrome,...
           hreq = new XMLHttpRequest();
        } else {
           hreq = new ActiveXObject("Microsoft.XMLHTTP");//IE
        }

        hreq.onreadystatechange = function(){update_country_aux(hreq); };
        hreq.open("GET", url, true); //true=asynchronous
        hreq.send(null);
    }
}

function update_country_aux(hreq)
{
    if (hreq.readyState == 4) //checks that the request is finished       
    {
        var country=hreq.responseXML.getElementsByTagName("country")[0].childNodes[0].nodeValue;

        var ddl = document.getElementById('ob_country');
        
        for (var i = 0; i < ddl.options.length; i++)
        {
            if (ddl.options[i].value == country)
            {
                if (ddl.selectedIndex != i) {
                    ddl.selectedIndex = i;
                    ddl.onchange();
                }
                break;
            }
        }
    }
}
