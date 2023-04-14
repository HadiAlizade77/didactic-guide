function fnRemoveSpaces(sFldval)
{
	var sTemp=sFldval;
	var sNewval=sTemp;
	//remove spaces from the front
	for(var i=0;i<sTemp.length;i++)
	{
		if(sTemp.charAt(i)!=" ")
			break;
		else
			sNewval = sTemp.substring(i+1);
	}
	return sNewval;
}
function fnFixSpace(sFldval)
{
	var sTemp=sFldval;
	var sReversedString="";
	var sTemp1;

	//remove spaces from the front
	sNewval = fnRemoveSpaces(sTemp);

	// reverse n remove spaces from the front
	for(var i=sNewval.length-1;i>=0;i--)
		sReversedString = sReversedString + sNewval.charAt(i);
	sTemp1 = fnRemoveSpaces(sReversedString);
	//reverse again
	sReversedString="";
	for(var i=sTemp1.length-1;i>=0;i--)
		sReversedString = sReversedString + sTemp1.charAt(i);
	sNewval = sReversedString;
	return sNewval;
}

/*-------------------------------------------------------------------------
        This sub routine checks for the mandatory fields, 
		their data types and maximum length
        also validates valid email entered or not
        Return : True/False
        Input : objFrm ( form object name)
--------------------------------------------------------------------------*/
function ValidateForm(objFrm)
{
 var iConventionPos;
 var sChangedName;
 for( var i =0; i< objFrm.length;i++)
 {
///////////// Only for this site ends ////////
  if(objFrm[i].type=='text' || objFrm[i].type=='textarea' || 
     objFrm[i].type=='select-one' || objFrm[i].type=='select-multiple' || 
	 objFrm[i].type=='password' || objFrm[i].type=='file' || 
	 objFrm[i].type=='radio' || objFrm[i].type=='checkbox')
   {
	if(objFrm[i].type=='text' || objFrm[i].type=='textarea' || objFrm[i].type=='password')
    {
	 objFrm[i].value = fnFixSpace(objFrm[i].value);
    }
    var objDataTypeHolder = objFrm[i].name.substring(0,3);

	if(objFrm[i].type=='select-one' && objDataTypeHolder=="TR_")
	{
	 var test;
	 test='ok';
	 if((objFrm[i].name=='TR_end_month' || objFrm[i].name=='TR_end_year' ))
	 {
	  test='aa';
	 }
	 else if(objFrm[i].options[objFrm[i].selectedIndex].value=='' && test!='aa')
	 {
	  sChangedName = objFrm[i].name.substring(3);
	  sChangedName = getFormattedmsg(sChangedName)
	  alert("Please select "+ sChangedName +".");
	  objFrm[i].focus();
	  return false;
	  break;
	 }
	}
	if(objFrm[i].type=='select-multiple' &&	objDataTypeHolder=="TR_")
	{
	 if(objFrm[i].selectedIndex==-1)
	  {
	   sChangedName = objFrm[i].name.substring(3);
	   lengg=sChangedName.length;
	   sChangedName = sChangedName.substring(0,(lengg-2));
	   sChangedName = getFormattedmsg(sChangedName)
	   alert("Please select "+ sChangedName +".");
	   objFrm[i].focus();
	   return false;
	   break;
	  }
     }
  if((objDataTypeHolder=="TR_")&& (objFrm[i].value=='') && ! ((objFrm[i].name=='TR_end_month' || objFrm[i].name=='TR_end_year')))
	 {
	  sChangedName = objFrm[i].name.substring(3);
	  sChangedName = getFormattedmsg(sChangedName)
   alert("Please enter "+ sChangedName +".");
   {
	   objFrm[i].focus();
 	   objFrm[i].select();
   }
	  return false;
	  break;
  }
   if((objDataTypeHolder=="IN_" )&& 	(isNaN(objFrm[i].value) && objFrm[i].value!='' ))
	{
		sChangedName = objFrm[i].name.substring(3);
		sChangedName = getFormattedmsg(sChangedName)
		alert("Please enter numeric "+ sChangedName +".");
		objFrm[i].focus();
		objFrm[i].select();
		return false;
		break;
	}

	if((objDataTypeHolder=="IN_" )&& (objFrm[i].value<=0 && objFrm[i].value!=''))
			{
				sChangedName = objFrm[i].name.substring(3);
				sChangedName = getFormattedmsg(sChangedName)
				alert("Please enter valid "+ sChangedName +".");
				objFrm[i].focus();
				objFrm[i].select();
				return false;
				break;
			}

			if((objDataTypeHolder=="IN_" ) && 
				(objFrm[i].value.indexOf(".")!=-1))
			{
				sChangedName = objFrm[i].name.substring(3);
				sChangedName = getFormattedmsg(sChangedName)
				alert("Please enter valid "+ sChangedName +".");
				objFrm[i].focus();
				objFrm[i].select();
				return false;
				break;
			}
	  
	  	  
	  if(objFrm[i].name.substring(0,8)=="post_url")
	  {
	   if(objFrm[i].checked==true)
    {
     if(document.defineForm.url.value=='')
     {
      alert("please Enter the Apply URL.");
      document.defineForm.url.focus();
      document.defineForm.url.select();
      return false;
      break;
     }
     else if(document.defineForm.url.value!='')
     {
      var theurl=document.defineForm.url.value;
      var tomatch= /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/
      var tomatch1= /https:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/
      if (tomatch.test(theurl) || tomatch1.test(theurl))
      {
       return true;
      }
      else
      {
       alert("URL invalid. Try another.");
       return false; 
      }
     }
	   }
	  }
 	 }
	}
	return true;
}
function getFormattedmsg(sVal)
{
	while(sVal.indexOf("_")!=-1)
	{
		sVal = sVal.replace("_", " ")
	}
	return sVal;
}