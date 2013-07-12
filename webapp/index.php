<!DOCTYPE html>
<html>
<head>
    <title>ANL Tower Data</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <script type="text/javascript" src="jquery-1.3.2.js"></script>   
    <script src="AquaGauge.js" type="text/javascript"></script>
    <script language="javascript" type="text/javascript">

function bindCity() {
     //disable child select list
  
     $("#Time").attr("disabled", true);
     //clear child select list's options
     $("#Time").html('');

     //querystring value is selected value of parent drop down list
     var qs = $("#Date").val();
     //if user selected a separator, show error
     if(qs == '') {
          alert('You cannot select this option. Please make a different selection.');
     }
     else {
          //show message indicating we're getting new values
          $("#Time").append(new Option('Getting Times ...'));
          //declare options array and populate
          var TimeOptions = new Array();
          $.get("helper.php?Date=" + qs, function(data) {
                    eval(data);
                    if(TimeOptions.length > 0) {
                         addOptions(TimeOptions);
                    }
               }
          );
     }
}

function addOptions(cl) {
     //enable child select and clear current child options
     $("#Time").removeAttr("disabled");
     $("#Time").html('');
     //repopulate child list with array from helper page
     var Time = document.getElementById('Time');
     for(var i = 0; i < cl.length; i++) {
          Time.options[i] = new Option(cl[i].text, cl[i].value);
     }
}


function go(gauge, value,target, direction, decimal, rounds) {
	if(typeof(direction) == 'undefined')
		direction = 1;
	if(typeof(decimal) == 'undefined')
		decimal = 0;
	if(typeof(rounds) == 'undefined')
		rounds = 0;

	gauge.refresh(value.toFixed(decimal));
	if(direction>0)
	{
		if(value>100)
		{
			direction = -direction;
			if(rounds<=0)
			{
				direction /= 3;
			}
			else
			{
				rounds--;
			}
		}
	}
	else
	{
		if(rounds<=0)
		{
			if(value<target)
			{
				gauge.refresh(target);
				return;
			}
		}
		else
		{
			if(value<0)
			{
				direction = -direction;
				rounds--;
			}
		}
	}
	value += direction;
	setTimeout(function(){go(gauge, value, target, direction, decimal, rounds);}, 3);
}



 function change(){
 myvar1 = document.getElementById('it1').value;
 document.getElementById('it2').innerHTML = myvar1;
 }
 function showGauge(){
            myvar1 = document.getElementById('it1').value; 
            myvar2 = document.getElementById('it2').value; 
            myvar3 = document.getElementById('it3').value; 
            myvar4 = document.getElementById('it4').value; 
            myvar5 = document.getElementById('it5').value;
	    myvar6 = document.getElementById('it6').value; 
            myvar7 = document.getElementById('it7').value; 
            myvar8 = document.getElementById('it8').value; 
            myvar9 = document.getElementById('it9').value; 
            myvar10 = document.getElementById('it10').value;
            myvar11 = document.getElementById('it11').value; 
            myvar12 = document.getElementById('it12').value; 
            myvar13 = document.getElementById('it13').value; 
            myvar14 = document.getElementById('it14').value; 
            label = ' - cm/s';
            myvar13 = myvar13 + label;
            myvar14 = myvar14 + label;
            var aGauge = new AquaGauge('cauge_00');
            aGauge.props.backgroundColor = 'transparent';
            aGauge.props.minValue = 0;
            aGauge.props.maxValue = 3600;
            aGauge.props.noOfDivisions = 10;
            aGauge.props.noOfSubDivisions = 4;
            aGauge.props.showMinorScaleValue = false;
            aGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            aGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            aGauge.props.dialTitle = 'Windspeed(60m)';
            aGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            aGauge.props.dialTitleTextColor = '#000'
            aGauge.props.dialSubTitle =  myvar13
            aGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            aGauge.props.dialSubTitleTextColor = '#666'
            aGauge.props.dialValueTextFont = '12px Arial Black'
            aGauge.props.dialValueTextColor = '#333'
            aGauge.props.majorDivisionColor = '#666'
            aGauge.props.minorDivisionColor = '#999'
            aGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            aGauge.props.dialScaleTextColor = '#333'
            aGauge.props.dialSubScaleFont = 'bold 8px Trebuchet MS'
            aGauge.props.dialSubScaleTextColor = '#666'
            aGauge.props.rangeSegments[0].start = '2520'
            aGauge.props.rangeSegments[0].end = '3600'
            aGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            aGauge.props.rangeSegments[1].start = '1080'
            aGauge.props.rangeSegments[1].end = '2520'
            aGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            aGauge.props.rangeSegments[2].start = '0'
            aGauge.props.rangeSegments[2].end = '1080'
            aGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            aGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            aGauge.props.rimWidth = '4'
//            aGauge.refresh(35.75);
			aGauge.refresh(myvar1);

            var bGauge = new AquaGauge('cauge_01');
            bGauge.props.backgroundColor = 'transparent'
            bGauge.props.minValue = 0;
            bGauge.props.maxValue = 30;
            bGauge.props.noOfDivisions = 6;
            bGauge.props.noOfSubDivisions = 3;
            bGauge.props.showMinorScaleValue = true;
            bGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            bGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            bGauge.props.dialTitle = 'Sigma Theta(60m)';
            bGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            bGauge.props.dialTitleTextColor = '#000'
            bGauge.props.dialSubTitle = 'Degrees';
            bGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            bGauge.props.dialSubTitleTextColor = '#666'
            bGauge.props.dialValueTextFont = '12px Arial Black'
            bGauge.props.dialValueTextColor = '#333'
            bGauge.props.majorDivisionColor = '#666'
            bGauge.props.minorDivisionColor = '#999'
            bGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            bGauge.props.dialScaleTextColor = '#333'
            bGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            bGauge.props.dialSubScaleTextColor = '#666'
            bGauge.props.showMinorScaleValue = 0;
            bGauge.props.showGlossiness = 1;
            bGauge.props.rangeSegments[0].start = '20'
            bGauge.props.rangeSegments[0].end = '30'
            bGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            bGauge.props.rangeSegments[1].start = '10'
            bGauge.props.rangeSegments[1].end = '20'
            bGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            bGauge.props.rangeSegments[2].start = '0'
            bGauge.props.rangeSegments[2].end = '10'
            bGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            bGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            bGauge.props.rimWidth = '4'
//            bGauge.refresh(24);
			bGauge.refresh(myvar2);

            var cGauge = new AquaGauge('cauge_02');
            cGauge.props.backgroundColor = 'transparent'
            cGauge.props.minValue = 0;
            cGauge.props.maxValue = 50;
            cGauge.props.noOfDivisions = 5;
            cGauge.props.noOfSubDivisions = 3;
            cGauge.props.showMinorScaleValue = true;
            cGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            cGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            cGauge.props.dialTitle = 'Temperature(60m)';
            cGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            cGauge.props.dialTitleTextColor = '#000'
            cGauge.props.dialSubTitle = 'Celsius';
            cGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            cGauge.props.dialSubTitleTextColor = '#666'
            cGauge.props.dialValueTextFont = '12px Arial Black'
            cGauge.props.dialValueTextColor = '#333'
            cGauge.props.majorDivisionColor = '#666'
            cGauge.props.minorDivisionColor = '#999'
            cGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            cGauge.props.dialScaleTextColor = '#333'
            cGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            cGauge.props.dialSubScaleTextColor = '#666'
            cGauge.props.showMinorScaleValue = 0;
            cGauge.props.showGlossiness = 1;
            cGauge.props.rangeSegments[0].start = '35'
            cGauge.props.rangeSegments[0].end = '50'
            cGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            cGauge.props.rangeSegments[1].start = '15'
            cGauge.props.rangeSegments[1].end = '35'
            cGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            cGauge.props.rangeSegments[2].start = '0'
            cGauge.props.rangeSegments[2].end = '15'
            cGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            cGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            cGauge.props.rimWidth = '4'
//            cGauge.refresh(12);
			cGauge.refresh(myvar3);

            var dGauge = new AquaGauge('cauge_03');
            dGauge.props.backgroundColor = 'transparent'
            dGauge.props.minValue = 0;
            dGauge.props.maxValue = 3600;
            dGauge.props.noOfDivisions = 10;
            dGauge.props.noOfSubDivisions = 4;
            dGauge.props.showMinorScaleValue = true;
            dGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            dGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            dGauge.props.dialTitle = 'Windspeed(10m)';
            dGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            dGauge.props.dialTitleTextColor = '#000'
            dGauge.props.dialSubTitle = myvar14
            dGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            dGauge.props.dialSubTitleTextColor = '#666'
            dGauge.props.dialValueTextFont = '12px Arial Black'
            dGauge.props.dialValueTextColor = '#333'
            dGauge.props.majorDivisionColor = '#666'
            dGauge.props.minorDivisionColor = '#999'
            dGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            dGauge.props.dialScaleTextColor = '#333'
            dGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            dGauge.props.dialSubScaleTextColor = '#666'
            dGauge.props.showMinorScaleValue = 0;
            dGauge.props.showGlossiness = 1;
            dGauge.props.rangeSegments[0].start = '2520'
            dGauge.props.rangeSegments[0].end = '3600'
            dGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            dGauge.props.rangeSegments[1].start = '1080'
            dGauge.props.rangeSegments[1].end = '2520'
            dGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            dGauge.props.rangeSegments[2].start = '0'
            dGauge.props.rangeSegments[2].end = '1080'
            dGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            dGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            dGauge.props.rimWidth = '4'
//            dGauge.refresh(22);
			dGauge.refresh(myvar4);

            var eGauge = new AquaGauge('cauge_04');
            eGauge.props.backgroundColor = 'transparent'
            eGauge.props.minValue = 0;
            eGauge.props.maxValue = 30;
            eGauge.props.noOfDivisions = 6;
            eGauge.props.noOfSubDivisions = 3;
            eGauge.props.showMinorScaleValue = true;
            eGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            eGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            eGauge.props.dialTitle = 'Sigma Theta(10m)';
            eGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            eGauge.props.dialTitleTextColor = '#000'
            eGauge.props.dialSubTitle = 'Degrees';
            eGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            eGauge.props.dialSubTitleTextColor = '#666'
            eGauge.props.dialValueTextFont = '12px Arial Black'
            eGauge.props.dialValueTextColor = '#333'
            eGauge.props.majorDivisionColor = '#666'
            eGauge.props.minorDivisionColor = '#999'
            eGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            eGauge.props.dialScaleTextColor = '#333'
            eGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            eGauge.props.dialSubScaleTextColor = '#666'
            eGauge.props.showMinorScaleValue = 0;
            eGauge.props.showGlossiness = 1;
            eGauge.props.rangeSegments[0].start = '20'
            eGauge.props.rangeSegments[0].end = '30'
            eGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            eGauge.props.rangeSegments[1].start = '10'
            eGauge.props.rangeSegments[1].end = '20'
            eGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            eGauge.props.rangeSegments[2].start = '0'
            eGauge.props.rangeSegments[2].end = '10'
            eGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            eGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            eGauge.props.rimWidth = '4'
//            eGauge.refresh(80);
			eGauge.refresh(myvar5);

            var fGauge = new AquaGauge('cauge_05');
            fGauge.props.backgroundColor = 'transparent'
            fGauge.props.minValue = 0;
            fGauge.props.maxValue = 50;
            fGauge.props.noOfDivisions = 5;
            fGauge.props.noOfSubDivisions = 3;
            fGauge.props.showMinorScaleValue = true;
            fGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            fGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            fGauge.props.dialTitle = 'Temperature(10m)';
            fGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            fGauge.props.dialTitleTextColor = '#000'
            fGauge.props.dialSubTitle = 'Celsius';
            fGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            fGauge.props.dialSubTitleTextColor = '#666'
            fGauge.props.dialValueTextFont = '12px Arial Black'
            fGauge.props.dialValueTextColor = '#333'
            fGauge.props.majorDivisionColor = '#666'
            fGauge.props.minorDivisionColor = '#999'
            fGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            fGauge.props.dialScaleTextColor = '#333'
            fGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            fGauge.props.dialSubScaleTextColor = '#666'
            fGauge.props.showMinorScaleValue = 0;
            fGauge.props.showGlossiness = 1;
            fGauge.props.rangeSegments[0].start = '35'
            fGauge.props.rangeSegments[0].end = '50'
            fGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            fGauge.props.rangeSegments[1].start = '15'
            fGauge.props.rangeSegments[1].end = '35'
            fGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            fGauge.props.rangeSegments[2].start = '0'
            fGauge.props.rangeSegments[2].end = '15'
            fGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            fGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            fGauge.props.rimWidth = '4'
//            fGauge.refresh(76);
			fGauge.refresh(myvar6);

            var gGauge = new AquaGauge('cauge_06');
            gGauge.props.backgroundColor = 'transparent'
            gGauge.props.minValue = 0;
            gGauge.props.maxValue = 100;
            gGauge.props.noOfDivisions = 5;
            gGauge.props.noOfSubDivisions = 4;
            gGauge.props.showMinorScaleValue = true;
            gGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            gGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            gGauge.props.dialTitle = 'Relative Humidity';
            gGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            gGauge.props.dialTitleTextColor = '#000'
            gGauge.props.dialSubTitle = '%';
            gGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            gGauge.props.dialSubTitleTextColor = '#666'
            gGauge.props.dialValueTextFont = '12px Arial Black'
            gGauge.props.dialValueTextColor = '#333'
            gGauge.props.majorDivisionColor = '#666'
            gGauge.props.minorDivisionColor = '#999'
            gGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            gGauge.props.dialScaleTextColor = '#333'
            gGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            gGauge.props.dialSubScaleTextColor = '#666'
            gGauge.props.showMinorScaleValue = 0;
            gGauge.props.showGlossiness = 1;
            gGauge.props.rangeSegments[0].start = '0'
            gGauge.props.rangeSegments[0].end = '33'
            gGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            gGauge.props.rangeSegments[1].start = '33'
            gGauge.props.rangeSegments[1].end = '66'
            gGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            gGauge.props.rangeSegments[2].start = '66'
            gGauge.props.rangeSegments[2].end = '100'
            gGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            gGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            gGauge.props.rimWidth = '4'
//            gGauge.refresh(15);
			gGauge.refresh(myvar7);

            var hGauge = new AquaGauge('cauge_07');
            hGauge.props.backgroundColor = 'transparent'
            hGauge.props.minValue = 0;
            hGauge.props.maxValue = 1200;
            hGauge.props.noOfDivisions = 5;
            hGauge.props.noOfSubDivisions = 4;
            hGauge.props.showMinorScaleValue = true;
            hGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            hGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            hGauge.props.dialTitle = 'Barometric Pres.';
            hGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            hGauge.props.dialTitleTextColor = '#000'
            hGauge.props.dialSubTitle = 'kPa';
            hGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            hGauge.props.dialSubTitleTextColor = '#666'
            hGauge.props.dialValueTextFont = '12px Arial Black'
            hGauge.props.dialValueTextColor = '#333'
            hGauge.props.majorDivisionColor = '#666'
            hGauge.props.minorDivisionColor = '#999'
            hGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            hGauge.props.dialScaleTextColor = '#333'
            hGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            hGauge.props.dialSubScaleTextColor = '#666'
            hGauge.props.showMinorScaleValue = 0;
            hGauge.props.showGlossiness = 1;
            hGauge.props.rangeSegments[0].start = '900'
            hGauge.props.rangeSegments[0].end = '1200'
            hGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            hGauge.props.rangeSegments[1].start = '300'
            hGauge.props.rangeSegments[1].end = '900'
            hGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            hGauge.props.rangeSegments[2].start = '0'
            hGauge.props.rangeSegments[2].end = '300'
            hGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            hGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            hGauge.props.rimWidth = '4'
//            hGauge.refresh(35);
			hGauge.refresh(myvar8);

            var iGauge = new AquaGauge('cauge_08');
            iGauge.props.backgroundColor = 'transparent'
            iGauge.props.minValue = -5;
            iGauge.props.maxValue = 1000;
            iGauge.props.noOfDivisions = 5;
            iGauge.props.noOfSubDivisions = 5;
            iGauge.props.showMinorScaleValue = true;
            iGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            iGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            iGauge.props.dialTitle = 'Solar Irradiation';
            iGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            iGauge.props.dialTitleTextColor = '#000'
            iGauge.props.dialSubTitle = 'W/m**2';
            iGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            iGauge.props.dialSubTitleTextColor = '#666'
            iGauge.props.dialValueTextFont = '12px Arial Black'
            iGauge.props.dialValueTextColor = '#333'
            iGauge.props.majorDivisionColor = '#666'
            iGauge.props.minorDivisionColor = '#999'
            iGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            iGauge.props.dialScaleTextColor = '#333'
            iGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            iGauge.props.dialSubScaleTextColor = '#666'
            iGauge.props.showMinorScaleValue = 0;
            iGauge.props.showGlossiness = 1;
            iGauge.props.rangeSegments[0].start = '660'
            iGauge.props.rangeSegments[0].end = '1000'
            iGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            iGauge.props.rangeSegments[1].start = '330'
            iGauge.props.rangeSegments[1].end = '660'
            iGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            iGauge.props.rangeSegments[2].start = '0'
            iGauge.props.rangeSegments[2].end = '330'
            iGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            iGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            iGauge.props.rimWidth = '4'
//            iGauge.refresh(22);8
			iGauge.refresh(myvar9); 
			
			 var jGauge = new AquaGauge('cauge_09');
            jGauge.props.backgroundColor = 'transparent'
            jGauge.props.minValue = -50;
            jGauge.props.maxValue = 1000;
            jGauge.props.noOfDivisions = 5;
            jGauge.props.noOfSubDivisions = 4;
            jGauge.props.showMinorScaleValue = true;
            jGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            jGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            jGauge.props.dialTitle = 'Net Radiation';
            jGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            jGauge.props.dialTitleTextColor = '#000'
            jGauge.props.dialSubTitle = 'W/m**2';
            jGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            jGauge.props.dialSubTitleTextColor = '#666'
            jGauge.props.dialValueTextFont = '12px Arial Black'
            jGauge.props.dialValueTextColor = '#333'
            jGauge.props.majorDivisionColor = '#666'
            jGauge.props.minorDivisionColor = '#999'
            jGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            jGauge.props.dialScaleTextColor = '#333'
            jGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            jGauge.props.dialSubScaleTextColor = '#666'
            jGauge.props.showMinorScaleValue = 0;
            jGauge.props.showGlossiness = 1;
            jGauge.props.rangeSegments[0].start = '660'
            jGauge.props.rangeSegments[0].end = '1000'
            jGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            jGauge.props.rangeSegments[1].start = '330'
            jGauge.props.rangeSegments[1].end = '660'
            jGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            jGauge.props.rangeSegments[2].start = '-50'
            jGauge.props.rangeSegments[2].end = '330'
            jGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            jGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            jGauge.props.rimWidth = '4'
//            iGauge.refresh(22);8
			jGauge.refresh(myvar10); 
			
			var kGauge = new AquaGauge('cauge_10');
            kGauge.props.backgroundColor = 'transparent'
            kGauge.props.minValue = -2;
            kGauge.props.maxValue = 6;
            kGauge.props.noOfDivisions = 4;
            kGauge.props.noOfSubDivisions = 4;
            kGauge.props.showMinorScaleValue = true;
            kGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            kGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            kGauge.props.dialTitle = 'Stability';
            kGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            kGauge.props.dialTitleTextColor = '#000'
            kGauge.props.dialSubTitle = '';
            kGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            kGauge.props.dialSubTitleTextColor = '#666'
            kGauge.props.dialValueTextFont = '12px Arial Black'
            kGauge.props.dialValueTextColor = '#333'
            kGauge.props.majorDivisionColor = '#666'
            kGauge.props.minorDivisionColor = '#999'
            kGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            kGauge.props.dialScaleTextColor = '#333'
            kGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            kGauge.props.dialSubScaleTextColor = '#666'
            kGauge.props.showMinorScaleValue = 0;
            kGauge.props.showGlossiness = 1;
            kGauge.props.rangeSegments[0].start = '4'
            kGauge.props.rangeSegments[0].end = '6'
            kGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            kGauge.props.rangeSegments[1].start = '1'
            kGauge.props.rangeSegments[1].end = '4'
            kGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            kGauge.props.rangeSegments[2].start = '-2'
            kGauge.props.rangeSegments[2].end = '1'
            kGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            kGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            kGauge.props.rimWidth = '4'
//            iGauge.refresh(22);8
			kGauge.refresh(myvar11); 
			
					var lGauge = new AquaGauge('cauge_11');
            lGauge.props.backgroundColor = 'transparent'
            lGauge.props.minValue = 0;
            lGauge.props.maxValue = 100;
            lGauge.props.noOfDivisions = 5;
            lGauge.props.noOfSubDivisions = 4;
            lGauge.props.showMinorScaleValue = true;
            lGauge.props.dialColor = 'rgba(210, 210, 210, 1)'
            lGauge.props.dialGradientColor = 'rgba(255, 255, 255, 0.5)'
            lGauge.props.dialTitle = 'Wet Bulb Temp.';
            lGauge.props.dialTitleTextFont = 'bold 9px Trebuchet MS'
            lGauge.props.dialTitleTextColor = '#000'
            lGauge.props.dialSubTitle = 'Celsius';
            lGauge.props.dialSubTitleTextFont = '9px Trebuchet MS'
            lGauge.props.dialSubTitleTextColor = '#666'
            lGauge.props.dialValueTextFont = '12px Arial Black'
            lGauge.props.dialValueTextColor = '#333'
            lGauge.props.majorDivisionColor = '#666'
            lGauge.props.minorDivisionColor = '#999'
            lGauge.props.dialScaleFont = 'bold 8px Trebuchet MS'
            lGauge.props.dialScaleTextColor = '#333'
            lGauge.props.dialSubScaleFont = 'bold 0px Trebuchet MS'
            lGauge.props.dialSubScaleTextColor = '#666'
            lGauge.props.showMinorScaleValue = 0;
            lGauge.props.showGlossiness = 1;
            lGauge.props.rangeSegments[0].start = '0'
            lGauge.props.rangeSegments[0].end = '33'
            lGauge.props.rangeSegments[0].color = 'rgba(220, 57, 18, 1)'
            lGauge.props.rangeSegments[1].start = '33'
            lGauge.props.rangeSegments[1].end = '66'
            lGauge.props.rangeSegments[1].color = 'rgba(255, 153, 0, 1)'
            lGauge.props.rangeSegments[2].start = '66'
            lGauge.props.rangeSegments[2].end = '100'
            lGauge.props.rangeSegments[2].color = 'rgba(16, 150, 24, 1)'
            lGauge.props.rimColor = 'rgba(230, 230, 230, 1)'
            lGauge.props.rimWidth = '4'
//            iGauge.refresh(22);8
			lGauge.refresh(myvar12);

//setTimeout(function() { go(bGauge, 0,22); }, 0);
//setTimeout(function() { go(cGauge, 0,12); }, 4000);
//setTimeout(function() { go(dGauge, 0,22); }, 8000);
//setTimeout(function() { go(eGauge, 0,80); }, 12000);
//setTimeout(function() { go(fGauge, 0,76); }, 16000);
//setTimeout(function() { go(gGauge, 0,15); }, 20000);
//setTimeout(function() { go(hGauge, 0,35); }, 24000);
//setTimeout(function() { go(iGauge, 0,22); }, 28000);
//setTimeout(function() { go(aGauge, 0, 35.75, 1, 2, 4); }, 32000);

}

$.ajax({
        url : 'index2.php',
        type : 'POST',
        data : data,
        dataType : 'json',
        success : function(result) {
           alert(result['ajax']); // "Hello world!" alerted
           //console.log(result['windspeed60m'])
	  // var windspeed60m = $windspeed60m['windspeed60m'];
	  // alert($windspeed60m['windspeed60m'])
           // The value of your php $row['adverts'] will be displayed
        },
        error : function () {
           alert("error");
       }
     })

 

    </script>


</head>
<center>
<body style="background-color:black;">
<img src='anltop.jpg'>
<form id="form1" name="form1" method="post" onsubmit="sbutton()" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
    <label style="color:white;">
        Select a Date:&nbsp;
        <select id="Date" name="Date" onchange="bindCity()">
<?php
//error if cannot connect to db server
if(!$link = mysql_connect('localhost', 'anluser', 'tdata97')) {
     echo "<option>Cannot connect to database server</option>\n";
}
//error if cannot select database
elseif(!mysql_select_db('ANLTower1')) {
     echo "<option>Cannot select database</option>\n";
}
else {
     //error if problem parsing query
     if(!$rs = mysql_query("SELECT DISTINCT Date FROM ANL4")) {
          echo "<option>Error getting values from database</option>\n";
     }
     //error if query returns no records
     elseif(mysql_num_rows($rs) == 0) {
          echo "<option>No records found</option>\n";
     }
     else {
          //ouput records
          $i = 0;
          while($row = mysql_fetch_array($rs)) {
               //create separator every five records
               if($i % 5 == 0) {
                    echo "<option value=\"\">--------------------</option>\n";
               }
               echo "<option value=\"$row[Date]\">$row[Date]</option>\n";
               $i++;
          }
     }
}
?>
        </select>
    </label>
    <label style="color:white;">
        Select a Time:&nbsp;
        <select id="Time" name="Time" disabled="disabled">
            <option>Showing Times ...</option>
        </select>
    </label>
    <input id="submitform" name="submitform" type="submit" value="Submit" />
    <input type="button" value="Show Gauges" onclick="showGauge()"/>

      
</form>
	
<?php
$con = mysql_connect('localhost', 'anluser', 'tdata97');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("ANLTower1", $con);

$sql = "SELECT DISTINCT * FROM ANL4 WHERE Date=\"{$_POST['Date']}\" AND Time=\"{$_POST['Time']}\"";


$result = mysql_query($sql);

$wspeed .= ' cm/s';
$st .= ' deg';
$temp .= ' C';
$rh .=  ' %';
$rad .= ' W/m';
$bar .= ' kPa';
$sq .= "<sup>2</sup>";


echo "<table border='1' class='top'>
<tr>
<th class='top'>Date</th>
<th class='top'>Time</th>
<th class='top'>Wind speed (60m)</th>
<th class='top'>Wind Direction (60m)</th>
<th class='top'>Sigma theta (60m)</th>
<th class='top'>Temperature (60m)</th>
<th class='top'>Windspeed (10m)</th>
<th class='top'>Wind Direction (10m)</th>
</tr>";

while($row = mysql_fetch_array($result))
  {

  $wind =  $row['Windspeed_60m_cms'];
  $sig60m = $row['Sigma_Theta_60m'];
  $temp60m = $row['Temperature_60m_C'];
  $wind10m = $row['Windspeed_10m_cms'];
  $windd60m = $row['Windspeed_60m_dir'];
  $windd10m = $row['Wind_Direction_10m'];
  $stability = $row['Stability'];



  echo "<tr>";
  echo "<td class='top'>" . $row['Date'] . "</td>";
  echo "<td class='top'>" . $row['Time'] . "</td>";
  echo "<td class='top'>" . $row['Windspeed_60m_cms'] .$wspeed . "</td>";
  echo "<td class='top'>" . $row['Windspeed_60m_dir'] . "</td>";
  echo "<td class='top'>" . $row['Sigma_Theta_60m'] .$st .  "</td>";
  echo "<td class='top'>" . $row['Temperature_60m_C'] .$temp . "</td>";
  echo "<td class='top'>" . $row['Windspeed_10m_cms'] .$wspeed .  "</td>";
  echo "<td class='top'>" . $row['Wind_Direction_10m'] . "</td>";
  

  echo "</tr>";
}

echo "</table>";

$result = mysql_query($sql);

echo "<table border='1'>

<tr>
<th>Sigma theta (10m)</th>
<th>Temperature (10m)</th>
<th>Relative Humidity</th>
<th>Barometric Pressure</th>
<th>Solar Irradiation</th>
<th>Net Radiation</th>
<th class='top'>Stability</th>
<th>Wet Bulb Globe Temp.</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  $sig10m = $row['Sigma_Theta_10m'];
  $temp10m = $row['Temperature_10m_C'];
  $humid = $row['Relative_Humidity'];
  $baro = $row['Barometric_Pressure_kpa'];
  $solir = $row['Solar_Irradiation'];
  $netrad = $row['Net_Radiation'];
  $wetbulb = $row['Wet_Bulb_Temp_Cels'];
 
  echo "<tr>";  
  echo "<td>" . $row['Sigma_Theta_10m'] .$st . "</td>";
  echo "<td>" . $row['Temperature_10m_C'] .$temp . "</td>";
  echo "<td>" . $row['Relative_Humidity'] .$rh .  "</td>";
  echo "<td>" . $row['Barometric_Pressure_kpa'] .$bar .  "</td>";
  echo "<td>" . $row['Solar_Irradiation'] .$rad . $sq .  "</td>";
  echo "<td>" . $row['Net_Radiation'] .$rad . $sq .  "</td>";
  echo "<td>" . $row['Stability'] . "</td>";
  echo "<td>" . $row['Wet_Bulb_Temp_Cels'] .$temp . "</td>";
  echo "</tr>";
  }

echo "</table>";

mysql_close($con);

?>
<style>
#gauges{background-image: url('gbg.jpg');
-moz-border-radius: 30px;
border-radius: 30px;
width:60%;
height:auto;

}
</style>
<div id="gauges">

<br/>
<canvas id="cauge_00" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_01" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_02" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_03" width="170%" height="170%">Browser not supported.</canvas>
<br/>
<canvas id="cauge_04" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_05" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_06" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_07" width="170%" height="170%">Browser not supported.</canvas>
<br/>
<canvas id="cauge_08" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_09" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_10" width="170%" height="170%">Browser not supported.</canvas>
<canvas id="cauge_11" width="170%" height="170%">Browser not supported.</canvas>
<br/>
</div>
<p id = "wind"></p>
<?php
$yes = "yes";
?>
<input type="hidden" id = "it1" onclick="change()" value="<?php echo($wind); ?>">
<input type="hidden" id = "it3" onclick="change()" value="<?php echo($temp60m); ?>">
<input type="hidden" id = "it4" onclick="change()" value="<?php echo($wind10m); ?>">
<input type="hidden" id = "it13" onclick="change()" value="<?php echo($windd60m); ?>">
<input type="hidden" id = "it14" onclick="change()" value="<?php echo($windd10m); ?>">
<input type="hidden" id = "it2" onclick="change()" value="<?php echo($sig60m); ?>">

<input type="hidden" id = "it5" onclick="change()" value="<?php echo($sig10m); ?>">
<input type="hidden" id = "it6" onclick="change()" value="<?php echo($temp10m); ?>">
<input type="hidden" id = "it7" onclick="change()" value="<?php echo($humid); ?>">
<input type="hidden" id = "it8" onclick="change()" value="<?php echo($baro); ?>">
<input type="hidden" id = "it9" onclick="change()" value="<?php echo($solir); ?>">
<input type="hidden" id = "it10" onclick="change()" value="<?php echo($netrad); ?>">
<input type="hidden" id = "it11" onclick="change()" value="<?php echo($stability); ?>">
<input type="hidden" id = "it12" onclick="change()" value="<?php echo($wetbulb); ?>">


<p id = "it2"></p>
</body>
</center>
</html>
